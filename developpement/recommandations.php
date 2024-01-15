<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Les recommandations</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #eaf7ff;
            color: #333;
            padding-top: 50px;
        }

        .container {
            margin-bottom: 50px;
        }

        h3 {
            color: #3498db;
        }

        label {
            color: #f39c12;
        }

        input[type="number"] {
            background-color: #ffff99;
            border: 1px solid #cccc00;
        }

        input[type="submit"] {
            background-color: #33cc33;
            color: #fff;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin-bottom: 5px;
        }

        .info-icon {
            position: relative;
            display: inline-block;
        }

        .info-icon:hover .info-text {
            display: block;
        }

        .info-text {
            font-size: 15px;
            display: none;
            position: absolute;
            top: 20px;
            left: 0;
            background-color: #f9f9f9;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            z-index: 1;
        }

        .user-info {
            border: 1px solid #3498db;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 10px;
            background-color: #ffffff;
        }

        .user-info a {
            color: #f39c12;
            text-decoration: none;
            font-weight: bold;
        }
    </style>

    <!-- Bootstrap JS and Popper.js -->
    <script src="chemin/vers/popper.min.js"></script>
    <script src="chemin/vers/bootstrap.min.js"></script>


    <script>
        function verifierTotal() {
            var prctGeographie = parseInt(document.getElementsByName('prctGeographie')[0].value) || 0;
            var prctPrix = parseInt(document.getElementsByName('prctPrix')[0].value) || 0;
            var prctCategories = parseInt(document.getElementsByName('prctCategories')[0].value) || 0;

            var total = prctGeographie + prctPrix + prctCategories;

            if (total !== 100) {
                alert("La somme des pourcentages doit être égale à 100. Actuellement, c'est " + total + "\nVeuillez corriger, merci.");
                return false; // Empêche l'envoi du formulaire
            }

            return true; // Permet l'envoi du formulaire
        }
    </script>

    
</head>
<body>

<?php
#INCLUDE DES CLASSES
include 'classUtilisateur.php';
include 'classActivite.php';

#RECUPERATION DES DONNEES DE L'UTILISATEUR
// Récupérez la chaîne sérialisée à partir de la requête GET
$serializedUtilisateur = urldecode($_GET['utilisateur']);

// Désérialisez la chaîne pour obtenir l'objet utilisateur
$utilisateur = unserialize($serializedUtilisateur);

#CONNEXION A LA BASE DE DONNEES
$bdd= "gvernis_cms"; // Base de données 
$host= "lakartxela.iutbayonne.univ-pau.fr";
$user= "gvernis_cms"; // Utilisateur 
$pass= "gvernis_cms"; // mp

$link=mysqli_connect($host,$user,$pass,$bdd) or die( "Impossible de se connecter à la base de données");

#FONCTIONS DE RECUPERATION DES DONNEES
function recupererCategories($link,$id){
    $query = "SELECT C.nomCategorie
    FROM Rist_Activite A
    JOIN Rist_Correspondre C ON A.idActivite = C.idActivite
    WHERE A.idActivite = $id;";
    $result= mysqli_query($link,$query);
    
    $categories = array();

    // Boucle à travers les résultats
    while ($donnees = mysqli_fetch_assoc($result)) {
        $categories[] = $donnees["nomCategorie"];
    }

    return $categories;
}

function recupererInfosPrincipalesActivite($link){
    $query = "SELECT A.*
    FROM Rist_Activite A
    WHERE A.dateLimiteInscription <= '2024-12-10'
      AND (SELECT COUNT(*) FROM Rist_Participer P WHERE P.idActivite = A.idActivite) <= A.nbPersonneMaxi;";
    $result= mysqli_query($link,$query);
    
    $activites = array(); // Tableau pour stocker les instances d'Activite

    // Boucle à travers les résultats
    $i=0;
    while ($donnees = mysqli_fetch_assoc($result)) {
        $activite = new Activite();
        $activite->setId($donnees["idActivite"]);
        $activite->setTitre($donnees["titre"]);
        $activite->setDescription($donnees["description"]);
        $activite->setPrix($donnees["prix"]/$donnees["nbPersonneMaxi"]);
        $activite->setNbPersonneMaxi($donnees["nbPersonneMaxi"]);
        $activite->setDateLimiteInscription($donnees["dateLimiteInscription"]);
        $activite->setDateRdv($donnees["dateRdv"]);
        $activite->setAdresse($donnees["adresse"]);
        $activite->setCoordGPS($donnees["coordGPS"]);
        $activite->setOrganisateur($donnees["pseudonyme"]);
        $activite->setCategories(recupererCategories($link,$activite->getId()));

        $activites[$i] = $activite; // Ajouter l'activité au tableau avec l'index $i
        $i+=1;
    }

    return $activites;
}

function haversineDistance($lat1, $lon1, $lat2, $lon2) {
    // Rayon moyen de la Terre en kilomètres
    $earthRadius = 6371;

    // Conversion des degrés en radians
    $lat1 = deg2rad($lat1);
    $lon1 = deg2rad($lon1);
    $lat2 = deg2rad($lat2);
    $lon2 = deg2rad($lon2);

    // Calcul des variations de latitude et de longitude
    $latDelta = $lat2 - $lat1;
    $lonDelta = $lon2 - $lon1;

    // Formule de la distance haversine
    $a = sin($latDelta / 2) * sin($latDelta / 2) + cos($lat1) * cos($lat2) * sin($lonDelta / 2) * sin($lonDelta / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    // Distance en kilomètres
    $distance = $earthRadius * $c;

    return $distance;
}

//calcul du score de distance via la formule :
//scoreDistance = distMoyenne/(distanceUtilisateurActivite * (1-poids))
function calculScoreDistance($distanceUtilisateurActivite, $moyenne,$prctGeo) {

    // Calculer le score de distance
    $scoreDistance = $moyenne / $distanceUtilisateurActivite;

    return $scoreDistance * ($prctGeo / 100);
}

function calculScorePrix($prix, $budget, $prctPrix) {
    // Vérifie si le prix est différent de zéro pour éviter la division par zéro
    if ($prix != 0) {
        // Calculer le score de prix
        $scorePrix = $budget / $prix;
        return $scorePrix * ($prctPrix / 100);
    } else {
        // Retourner un score par défaut (ou gérer le cas de prix égal à zéro selon vos besoins)
        return 0;
    }
}

function categorieSimilaire($categorie1, $categorie2){
    $bdd= "gvernis_cms"; // Base de données 
    $host= "lakartxela.iutbayonne.univ-pau.fr";
    $user= "gvernis_cms"; // Utilisateur 
    $pass= "gvernis_cms"; // mp

    $link=mysqli_connect($host,$user,$pass,$bdd) or die( "Impossible de se connecter à la base de données");
    $query = "SELECT DISTINCT e1.nomEnsemble
                FROM Rist_Ensemble e1
                JOIN Rist_Ensemble e2 ON e1.nomEnsemble = e2.nomEnsemble
                JOIN Rist_Appartenir a1 ON e1.nomEnsemble = a1.nomEnsemble
                JOIN Rist_Appartenir a2 ON e2.nomEnsemble = a2.nomEnsemble
                JOIN Rist_Categorie c1 ON a1.nomCategorie = c1.nomCategorie
                JOIN Rist_Categorie c2 ON a2.nomCategorie = c2.nomCategorie
                WHERE c1.nomCategorie = '".$categorie1."' AND c2.nomCategorie = '".$categorie2."';";
    
    $result= mysqli_query($link,$query);

    // Vérification si la requête n'est pas vide
    if ($result && $result->num_rows > 0) {
        //La requête n'est pas vide, retourne true.
        return true;
    } else {
        //La requête est vide, retourne false.
        return false;
    }
}

function calculScoreCategorie($listeCategorieActivite, $listeCategorieUtilisateur, $prctCategories){
    $score = 0;
    foreach($listeCategorieActivite as $categorieActivite){
        foreach($listeCategorieUtilisateur as $categorieUtilisateur){
            if ($categorieActivite == $categorieUtilisateur) {
                $score++;
            }
            else {
                if (categorieSimilaire($categorieActivite, $categorieUtilisateur)) {
                    $score = $score + 0.5;
                }
            }
        }
    }
    
    
    return $score * ($prctCategories/100);
}

function tri(&$activites) {
    $n = count($activites);

    for ($i = 0; $i < $n - 1; $i++) {
        for ($j = 0; $j < $n - $i - 1; $j++) {
            // Utilisez la méthode getScore() pour comparer les objets
            if ($activites[$j]->getScore() < $activites[$j + 1]->getScore()) {
                // Échangez les éléments s'ils sont dans le mauvais ordre
                $temp = $activites[$j];
                $activites[$j] = $activites[$j + 1];
                $activites[$j + 1] = $temp;
            }
        }
    }
}

//Vérifier si on doit prendre en compte les catégories les plus récurrentes
$chaud = false;

$query = "SELECT Ca.nomCategorie, COUNT(Ca.nomCategorie) 
FROM Rist_Categorie Ca
JOIN Rist_Correspondre Co ON Ca.nomCategorie = Co.nomCategorie
JOIN Rist_Activite A ON Co.idActivite = A.idActivite
JOIN Rist_Participer P ON A.idActivite = P.idActivite
WHERE P.pseudonyme = '".$utilisateur->getPseudonyme()."'
AND A.dateRdv<DATE('2024-12-19')                            # Date à changer plus tard
GROUP BY Ca.nomCategorie
ORDER BY COUNT(Ca.nomCategorie) DESC, Ca.nomCategorie ASC
LIMIT 3";

$result= mysqli_query($link,$query);
if(mysqli_num_rows($result) > 2){
    $chaud = true;

    $categoriesRecurrentes = array();
    while ($donnees = mysqli_fetch_assoc($result)){
        array_push($categoriesRecurrentes,$donnees['nomCategorie']);
    }
}


#AFFICHAGE DES INFORMATIONS
echo "<h3>Informations de l'utilisateur</h3>";
echo "<p>Pseudonyme : " . $utilisateur->getPseudonyme() . "</p>";
echo "<p>Nombre d'activités : " . $utilisateur->getNbActivite() . "</p>";
echo "<p>Coordonnées GPS x : " . $utilisateur->getCoordGPS()[0] . "</p>";
echo "<p>Coordonnées GPS y : " . $utilisateur->getCoordGPS()[1] . "</p>";
echo "<p>Budget : " . $utilisateur->getBudget() . "</p>";

echo "<p>Categories préférées :</p>";
echo "<ul>";
foreach ($utilisateur->getCategories() as $category) {
    echo "<li>$category</li>";
}
echo "</ul>";

echo "<p>Positions géographiques des activités de son historique :</p>";
echo "<ul>";
foreach ($utilisateur->getGPSHistorique() as $GPS) {
    echo "<li>$GPS[0], $GPS[1]</li>";
}
echo "</ul>";

?>

<h3>Veuillez distribuez la priorité sur les différentes critères pour un total de 100%

<span class='info-icon'>
<img src='info-icon.png' alt='Info' width='20' height='20'>
<span class='info-text'>Plus la priorité est grande pour un critère, plus il sera pris en compte dans le calcul de votre liste de recommandation</span>
</span>
</h3>

<?php
echo '<form method="post" action="recommandations.php?utilisateur='.urlencode($serializedUtilisateur).'" onsubmit="return verifierTotal();">';
$prctGeographie = isset($_POST['prctGeographie']) ? intval($_POST['prctGeographie']) : 50;
$prctPrix = isset($_POST['prctPrix']) ? intval($_POST['prctPrix']) : 30;
$prctCategories = isset($_POST['prctCategories']) ? intval($_POST['prctCategories']) : 20;
?>
<label>Zone géographique :
    <span class='info-icon'>
    <img src='info-icon.png' alt='Info' width='20' height='20'>
    <span class='info-text'>Détermine si la distance entre vous et les activités recommandées est importante</span>
    </span>
    </label>
    <?php
    echo "<input value=$prctGeographie type='number' name='prctGeographie' placeholder='50' min='0' max='100' required>";
    ?>
    <label>Prix :
    <span class='info-icon'>
    <img src='info-icon.png' alt='Info' width='20' height='20'>
    <span class='info-text'>Détermine si le prix des activités recommandées est important par rapport à votre budget</span>
    </span>
    </label>
    <?php
    echo "<input value=$prctPrix type='number' name='prctPrix' placeholder='30' min='0' max='100' required>";
    ?>
    <label>Intérêts :
    <span class='info-icon'>
    <img src='info-icon.png' alt='Info' width='20' height='20'>
    <span class='info-text'>Détermine si vos intérêts sont importants par rapport aux catégories des activités recommandées</span>
    </span>
    </label>
    <?php
    echo "<input value=$prctCategories type='number' name='prctCategories' placeholder='20' min='0' max='100' required>";
    ?>
    <input type="submit" value="Rafraîchir">
</form>

<h3>Vos recommandations :</h3>
<ul class="liste">
<?php
#appels des fonctions de calcul de score
#affichage des 3 activités recommandées
$activites = recupererInfosPrincipalesActivite($link);



//Calcul de la distance moyenne entre l'utilisateur et les activiés de son historique 
//création d'une liste pour contenir les résultats 
$listeDist = array();

//calcul de la distance de toutes les activites de son historique 
foreach ($utilisateur->getGPSHistorique() as $GPS) {
    $result = haversineDistance($utilisateur->getCoordGPS()[0], $utilisateur->getCoordGPS()[1],$GPS[0],$GPS[1]);
    $listeDist[] = $result;

}

// Calculer la somme des valeurs
$somme = array_sum($listeDist);

// Calculer le nombre d'éléments dans le tableau
$nombreElements = count($listeDist);

// Calculer la moyenne
if ($nombreElements > 0) {
    $moyenne = $somme / $nombreElements;
} 
else {
    echo "Le tableau est vide, impossible de calculer la moyenne.";
}

foreach ($activites as $index => $activite) {   //parcours de toutes les activites

    //calcul du score de distance
    $distanceUtilisateurActivite = haversineDistance($utilisateur->getCoordGPS()[0], $utilisateur->getCoordGPS()[1], $activite->getCoordGPS()[0], $activite->getCoordGPS()[1]);
    $activite->setDistance($distanceUtilisateurActivite);


    $scoreDistance = calculScoreDistance($distanceUtilisateurActivite,$moyenne,$prctGeographie);
    $nouveauScore = $activite->getScore() + $scoreDistance;
    $activite->setScore($nouveauScore);

    //calcul du score de prix
    $scorePrix = calculScorePrix($activite->getPrix(), $utilisateur->getBudget(), $prctPrix);
    $nouveauScore = $activite->getScore() + $scorePrix;
    $activite->setScore($nouveauScore);

    $scoreCategorie = calculScoreCategorie($activite->getCategories(),$utilisateur->getCategories(),$prctCategories);
    $nouveauScore = $activite->getScore() + $scoreCategorie;
    $activite->setScore($nouveauScore);

    //calcul pour categories récurrentes
    if ($chaud) {
        $scoreCategorieRecurrente = calculScoreCategorie($activite->getCategories(),$categoriesRecurrentes,$prctCategories);
        $nouveauScore = $activite->getScore() + $scoreCategorieRecurrente;
        $activite->setScore($nouveauScore);
    }

}

tri($activites);

foreach($activites as $index => $activite){
    echo "<p id='".$activite->getScore()."'>";
    echo $activite->toString();
    echo "</p>"; 
}
?>

</ul>
<br>
<a href="index.php">&lsaquo; Retourner aux utilisateurs</a>

<!-- Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
</script>
</body>
</html>