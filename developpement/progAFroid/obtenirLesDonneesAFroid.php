<?php

#CONNEXION A LA BASE DE DONNEES
    $bdd= "gvernis_cms"; // Base de données 
    $host= "lakartxela.iutbayonne.univ-pau.fr";
    $user= "gvernis_cms"; // Utilisateur 
    $pass= "gvernis_cms"; // mp

    $link=mysqli_connect($host,$user,$pass,$bdd) or die( "Impossible de se connecter à la base de données");

#RECUPERATION DES DONNEES
    $pseudonyme= 'Sparky';
    echo "<p>Utilisateur : $pseudonyme</p>";
    #La zone géographique de l'utilisateur
        echo "<br>";
        $query = "SELECT coordGPS
        FROM Rist_Utilisateur
        WHERE pseudonyme='$pseudonyme'";
        $result= mysqli_query($link,$query);
                
        // Boucle à travers les résultats
        while ($donnees = mysqli_fetch_assoc($result)) {
            $coordGPS = $donnees["coordGPS"];
        }

        // Division des coordonnées GPS x et y séparées par une virgule
        $coordGPS = explode(',',$coordGPS);

        // Afficher les résultats
        echo "<p>Coordonnées GPS x : $coordGPS[0]</p>"; // X
        echo "<p>Coordonnées GPS y : $coordGPS[1]</p>"; // Y

    #Le budget de l'utilisateur
        echo "<br>";
        $query = "SELECT budget
        FROM Rist_Utilisateur
        WHERE pseudonyme='$pseudonyme'";
        $result= mysqli_query($link,$query);
                
        // Boucle à travers les résultats
        while ($donnees = mysqli_fetch_assoc($result)) {
            $budgetSaisi = $donnees["budget"];
        }

        // Afficher les résultats
        echo "<p>Budget saisi : $budgetSaisi</p>";
        
    #Les 3 categories preferees de l'utilisateur
    echo "<br>";
    $query = "SELECT C.nomCategorie 
        FROM Rist_Categorie C
        JOIN Rist_Preferer P ON C.nomCategorie=P.nomCategorie
        JOIN Rist_Utilisateur U ON P.pseudonyme=U.pseudonyme
        WHERE P.pseudonyme='$pseudonyme'";
    
    $result = mysqli_query($link, $query);
    
    $categories = array();
    
    // Fetch categories and store them in an array
    while ($donnees = mysqli_fetch_assoc($result)) {
        $categories[] = $donnees["nomCategorie"];
    }
    
    // Display the preferred categories
    for ($i = 1; $i <= count($categories); $i++) {
        echo "<p>Catégorie préférée $i : {$categories[$i - 1]}</p>";
    }

?>