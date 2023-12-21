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
            margin-left: 5px;
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
</head>
<body>
<?php

class Utilisateur {
    // Attributs
    public $pseudonyme; // String
    public $nbActivite; // Int
    public $coordGPS;   // Array : [0] X ; [1] Y
    public $budget;     // Int arrondi à 2 chiffres après la virgule
    public $categories; // Array : [0] catégorie 1 ; [1] catégorie 2 ; [2] catégorie 3

    // Constructeur
    public function __construct($pseudonyme,$nbActivite,$coordGPS,$budget,$categories){
        $this->pseudonyme = $pseudonyme;
        $this->nbActivite = $nbActivite;
        $this->coordGPS = $coordGPS;
        $this->budget = $budget;
        $this->categories = $categories;
    }
}

// Récupérez la chaîne sérialisée à partir de la requête GET
$serializedUtilisateur = urldecode($_GET['utilisateur']);

// Désérialisez la chaîne pour obtenir l'objet utilisateur
$utilisateur = unserialize($serializedUtilisateur);

// Affichez les informations de l'utilisateur
echo "<h3>Informations de l'utilisateur</h3>";
echo "<p>Pseudonyme : " . $utilisateur->pseudonyme . "</p>";
echo "<p>Nombre d'activités : " . $utilisateur->nbActivite . "</p>";
echo "<p>Coordonnées GPS x : " . $utilisateur->coordGPS[0] . "</p>";
echo "<p>Coordonnées GPS y : " . $utilisateur->coordGPS[1] . "</p>";
echo "<p>Budget : " . $utilisateur->budget . "</p>";

echo "<p>Categories préférées :</p>";
echo "<ul>";
foreach ($utilisateur->categories as $category) {
    echo "<li>$category</li>";
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
<h3>Vos recommandations :</h3>

<a href="pageUtilisateurs.php">&lsaquo; Retourner aux utilisateurs</a>
</html>