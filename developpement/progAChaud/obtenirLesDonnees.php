<?php

#CONNEXION A LA BASE DE DONNEES
    $bdd= "gvernis_cms"; // Base de données 
    $host= "lakartxela.iutbayonne.univ-pau.fr";
    $user= "gvernis_cms"; // Utilisateur 
    $pass= "gvernis_cms"; // mp

    $link=mysqli_connect($host,$user,$pass,$bdd) or die( "Impossible de se connecter à la base de données");

#RECUPERATION DES DONNEES
    $pseudonyme= 'Sparky';
    #la zone géographique
        

    #le budget moyen de l'utilisateur
        // Execution requete SQL
        $query = "SELECT AVG(A.prix/A.nbPersonneMaxi) AS prixMoyen
        FROM Rist_Activite A
        JOIN Rist_Participer P ON A.idActivite = P.idActivite
        WHERE P.pseudonyme = '$pseudonyme'";

        $result= mysqli_query($link,$query);
        
        // Boucle à travers les résultats
        while ($donnees = mysqli_fetch_assoc($result)) {
            $prixMoyen = number_format($donnees["prixMoyen"], 2);
        }

        // Afficher les résultats
        echo "<p>Prix moyen : $prixMoyen</p>";
    
    #Les 3 categories preferees de l'utilisateur
        // Execution requete SQL
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


?>