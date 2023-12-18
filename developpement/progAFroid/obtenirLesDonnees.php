<?php

#CONNEXION A LA BASE DE DONNEES
    $bdd= "gvernis_cms"; // Base de données 
    $host= "lakartxela.iutbayonne.univ-pau.fr";
    $user= "gvernis_cms"; // Utilisateur 
    $pass= "gvernis_cms"; // mp

    $link=mysqli_connect($host,$user,$pass,$bdd) or die( "Impossible de se connecter à la base de données");

#RECUPERATION DES DONNEES
    #Les 3 categories preferees de l'utilisateur
        $pseudonyme= 'Sparky';

        $query = "SELECT C.nomCategorie 
        FROM Rist_Categorie C
        JOIN Rist_Preferer P ON C.nomCategorie=P.nomCategorie
        JOIN Rist_Utilisateur U ON P.pseudonyme=U.pseudonyme
        WHERE P.pseudonyme='$pseudonyme'";

        $result= mysqli_query($link,$query);
        $categorie1 = $categorie2 = $categorie3 = "";

        // Boucle à travers les résultats
        $i = 1;
        while ($donnees = mysqli_fetch_assoc($result)) {
            ${"categorie" . $i} = $donnees["nomCategorie"];
            $i++;
        }

        // Afficher les résultats
        echo "<p>Utilisateur : $pseudonyme</p>";
        echo "<p>Catégorie 1 : $categorie1</p>";
        echo "<p>Catégorie 2 : $categorie2</p>";
        echo "<p>Catégorie 3 : $categorie3</p>";

    #

?>