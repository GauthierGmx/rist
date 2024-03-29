<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Les Recommandations</title>

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
include 'CalculDistance.php';

// pour trier les activités il faut faire usort($activites, 'comparerScore');
function comparerScore($a, $b) {
    return $a->score - $b->score;
}


// Récupérez la chaîne sérialisée à partir de la requête GET
$serializedUtilisateur = urldecode($_GET['utilisateur']);

// Désérialisez la chaîne pour obtenir l'objet utilisateur
$utilisateur = unserialize($serializedUtilisateur);

// Affichez les informations de l'utilisateur
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



//CONNEXION A LA BASE DE DONNEES
$bdd= "gvernis_cms"; // Base de données 
$host= "localhost";
$user= "gvernis_cms"; // Utilisateur 
$pass= "gvernis_cms"; // mp

$link=mysqli_connect($host,$user,$pass,$bdd) or die( "Impossible de se connecter à la base de données");

$query = "SELECT * FROM rist_activite";

$result= mysqli_query($link,$query);

//array pour avoir un ordre avec les activités
$activites = array();

//Creation des instances de classe Activite
while ($donnees = mysqli_fetch_assoc($result)) {
    //on prend toutes les données des activitées
    $id = $donnees['idActivite'];
    $titre = $donnees['titre'];
    $description = $donnees['description'];
    $prix = $donnees['prix'];
    $nbPersonMax = $donnees['nbPersonneMaxi'];
    $dateLim = $donnees['dateLimiteInscription'];
    $dateRdv = $donnees['dateRdv'];
    $adress = $donnees['adresse'];
    $coord = $donnees['coordGPS'];
    $pseudo = $donnees['pseudonyme'];

    //on créer une instance de classe pour chaque activités (parce que ça serait dommage de pas s'en servir)
    ${"activite" . $id} = new Activite($id,$titre,$description,$prix,$nbPersonMax,$dateLim,$dateRdv,$coord,$pseudo);
    //on ajoute les activités à un array
    array_push($activites, ${"activite" . $id});


    //attribuer score (prix)
    $scorePrix = $utilisateur->getBudget()  /  (${"activite" . $id}->getPrix() * 3 + 200) + 1;
    ${"activite" . $id}->setScore($scorePrix);
}

?>
</body>

<h3>Veuillez distribuez la priorité sur les différentes critères pour un total de 100%

<span class='info-icon'>
<img src='info-icon.png' alt='Info' width='20' height='20'>
<span class='info-text'>Plus la priorité est grande pour un critère, plus il sera pris en compte dans le calcul de votre liste de recommandation</span>
</span>
</h3>

<form method="post" action="recupDonnees.php" onsubmit="return verifierTotal()";>
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

<?php
//affichage des activités
foreach ($activites as $activite) {
    echo $activite->getId();
    //les \t c'est pour faire des tab
    echo " Score = " . $activite->getScore();
    echo "<BR>";
}


?>

<h3>Vos recommandations :</h3>

<a href="pageUtilisateurs.php">&lsaquo; Retourner aux utilisateurs</a>
</html>