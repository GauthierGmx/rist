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
$host= "localhost";
$user= "gvernis_cms"; // Utilisateur 
$pass= "gvernis_cms"; // mp

$link=mysqli_connect($host,$user,$pass,$bdd) or die( "Impossible de se connecter à la base de données");

#FONCTIONS DE RECUPERATION DES DONNEES
function recupererCategories($link,$i,$id){
    $query = "SELECT C.nomCategorie
    FROM rist_activite A
    JOIN rist_correspondre C ON A.idActivite = C.idActivite
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
        $activite->setCategories(recupererCategories($link,$i,$activite->getId()));

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

<form method="post" action="recommandations.php" onsubmit="return verifierTotal();">
    <label>Zone géographique :
    <span class='info-icon'>
    <img src='info-icon.png' alt='Info' width='20' height='20'>
    <span class='info-text'>Détermine si la distance entre vous et les activités recommandées est importante</span>
    </span>
    </label>
    <input type='number' name='prctGeographie' placeholder='50' min="0" max="100" required>
    <label>Prix :
    <span class='info-icon'>
    <img src='info-icon.png' alt='Info' width='20' height='20'>
    <span class='info-text'>Détermine si le prix des activités recommandées est important par rapport à votre budget</span>
    </span>
    </label>
    <input type='number' name='prctPrix' placeholder='30' min="0" max="100" required>
    <label>Intérêts :
    <span class='info-icon'>
    <img src='info-icon.png' alt='Info' width='20' height='20'>
    <span class='info-text'>Détermine si vos intérêts sont importants par rapport aux catégories des activités recommandées</span>
    </span>
    </label>
    <input type='number' name='prctCategories' placeholder='20' min="0" max="100" required>
    <input type="submit" value="Rafraîchir">
</form>

<h3>Vos recommandations :</h3>

<?php
#appels des fonctions de calcul de score
#affichage des 3 activités recommandées
$activites = recupererInfosPrincipalesActivite($link);

echo "<h3>Vos recommandations :</h3>";
echo "<ul>";
foreach ($activites as $index => $activite) {   //parcours de toutes les activites

    //Calcul de la distance entre l'activité et l'utilisateur 


    $distanceUtilisateurActivite = haversineDistance($utilisateur->getCoordGPS()[0], $utilisateur->getCoordGPS()[1], $activite->getCoordGPS()[0], $activite->getCoordGPS()[1]);
    $listeDistanceHistorique

    $moyenneDistance = 

    

    scoreDistance = distMoyenne/(distanceUtilisateurActivite * (1-poids))
    echo $distanceUtilisateurActivite;
    echo "</br>";
    


}
echo "</ul>";

?>

<br>
<a href="index.php">&lsaquo; Retourner aux utilisateurs</a>

<!-- Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>