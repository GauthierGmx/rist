<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Les recommandations</title>

    <style>
        .info-icon {
            position: relative;
            display: inline-block;
        }

        .info-icon:hover .info-text {
            display: block;
        }

        .info-text {
            font-size:15px;
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
    </style>
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
    WHERE A.dateLimiteInscription <= '2024-12-10'                   # Date à changer plus tard
      AND (SELECT COUNT(*) FROM Rist_Participer P WHERE P.idActivite = A.idActivite) <= A.nbPersonneMaxi;"; 
    $result= mysqli_query($link,$query);
    
    // Boucle à travers les résultats
    $i=0;
    while ($donnees = mysqli_fetch_assoc($result)) {
        ${"activite" . $i} = new Activite();
        ${"activite" . $i} -> setId($donnees["idActivite"]);
        echo "<h4>Activité ";                                   //C'est juste pour l'affichage de toutes les activités donc à supprimer
        echo ${'activite' . $i} -> getId();                                   //C'est juste pour l'affichage de toutes les activités donc à supprimer
        echo "</h4>";                                   //C'est juste pour l'affichage de toutes les activités donc à supprimer
        ${"activite" . $i} -> setTitre($donnees["titre"]);
        echo ${'activite' . $i} -> getTitre();                                   //C'est juste pour l'affichage de toutes les activités donc à supprimer
        echo "<br>";                                   //C'est juste pour l'affichage de toutes les activités donc à supprimer
        ${"activite" . $i} -> setDescription($donnees["description"]);
        echo ${'activite' . $i} -> getDescription();                                   //C'est juste pour l'affichage de toutes les activités donc à supprimer
        echo "<br>";                                   //C'est juste pour l'affichage de toutes les activités donc à supprimer
        ${"activite" . $i} -> setPrix($donnees["prix"]/$donnees["nbPersonneMaxi"]);
        echo ${'activite' . $i} -> getPrix() . " €/personne";                                   //C'est juste pour l'affichage de toutes les activités donc à supprimer
        echo "<br>";                                   //C'est juste pour l'affichage de toutes les activités donc à supprimer
        ${"activite" . $i} -> setNbPersonneMaxi($donnees["nbPersonneMaxi"]);
        echo ${'activite' . $i} -> getNbPersonneMaxi() . " personnes maximum";                                   //C'est juste pour l'affichage de toutes les activités donc à supprimer
        echo "<br>";                                   //C'est juste pour l'affichage de toutes les activités donc à supprimer
        ${"activite" . $i} -> setDateLimiteInscription($donnees["dateLimiteInscription"]);
        echo "Date de limite d'inscription : " . ${'activite' . $i} -> getDateLimiteInscription();                                   //C'est juste pour l'affichage de toutes les activités donc à supprimer
        echo "<br>";                                   //C'est juste pour l'affichage de toutes les activités donc à supprimer
        ${"activite" . $i} -> setDateRdv($donnees["dateRdv"]);
        echo "Date de rendez-vous : " . ${'activite' . $i} -> getDateRdv();                                   //C'est juste pour l'affichage de toutes les activités donc à supprimer
        echo "<br>";                                   //C'est juste pour l'affichage de toutes les activités donc à supprimer
        ${"activite" . $i} -> setAdresse($donnees["adresse"]);
        echo ${'activite' . $i} -> getAdresse();                                   //C'est juste pour l'affichage de toutes les activités donc à supprimer
        echo "<br>";                                   //C'est juste pour l'affichage de toutes les activités donc à supprimer
        ${"activite" . $i} -> setCoordGPS($donnees["coordGPS"]);
        echo ${'activite' . $i} -> getCoordGPS();                                   //C'est juste pour l'affichage de toutes les activités donc à supprimer
        echo "<br>";                                   //C'est juste pour l'affichage de toutes les activités donc à supprimer
        ${"activite" . $i} -> setOrganisateur($donnees["pseudonyme"]);
        echo "Organisateur : " . ${'activite' . $i} -> getOrganisateur();                                   //C'est juste pour l'affichage de toutes les activités donc à supprimer
        echo "<br>";                                   //C'est juste pour l'affichage de toutes les activités donc à supprimer
        ${"activite" . $i} -> setCategories(recupererCategories($link,$i,${"activite" . $i}->getId()));
        var_dump(${'activite' . $i} -> getCategories());                                    //C'est juste pour l'affichage de toutes les activités donc à supprimer 
        $i+=1;
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
</body>

<h3>Veuillez distribuez la priorité sur les différentes critères pour un total de 100%

<span class='info-icon'>
<img src='info-icon.png' alt='Info' width='20' height='20'>
<span class='info-text'>Plus la priorité est grande pour un critère, plus il sera pris en compte dans le calcul de votre liste de recommandation</span>
</span>
</h3>

<form method="post" action="recommandations.php" onsubmit="return verifierTotal()";>
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
recupererInfosPrincipalesActivite($link);
#appels des fonctions de calcul de score
#affichage des 3 activités recommandées
?>

<br>
<a href="index.php">&lsaquo; Retourner aux utilisateurs</a>
</html>