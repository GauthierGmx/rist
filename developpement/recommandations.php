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
