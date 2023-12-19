<?php
//FAIRE CALCUL NB PERSONNES POUR BUDGET


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

    #Le budget saisi de l'utilisateur
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
    
    #Le budget moyen de l'utilisateur
    echo "<br>";
        $query = "SELECT AVG(A.prix) as prix
        FROM Rist_Activite A
        JOIN Rist_Participer P ON A.idActivite = P.idActivite
        WHERE P.pseudonyme = '$pseudonyme'
        AND A.dateRdv<DATE('2024-12-19')";
        $result= mysqli_query($link,$query);
                
        // Boucle à travers les résultats
        while ($donnees = mysqli_fetch_assoc($result)) {
            $budgetMoyen = round($donnees["prix"], 2);
        }

        // Afficher les résultats
        echo "<p>Budget saisi : $budgetMoyen</p>";
    
    #Les 3 categories preferees de l'utilisateur
        echo "<br>";
        $query = "SELECT C.nomCategorie 
        FROM Rist_Categorie C
        JOIN Rist_Preferer P ON C.nomCategorie=P.nomCategorie
        JOIN Rist_Utilisateur U ON P.pseudonyme=U.pseudonyme
        WHERE P.pseudonyme='$pseudonyme'";

        $result= mysqli_query($link,$query);
        $categoriePref1 = $categoriePref2 = $categoriePref3 = "";

        // Boucle à travers les résultats
        $i = 1;
        while ($donnees = mysqli_fetch_assoc($result)) {
            ${"categoriePref" . $i} = $donnees["nomCategorie"];
            $i++;
        }

        // Afficher les résultats
        echo "<p>Catégorie préférée 1 : $categoriePref1</p>";
        echo "<p>Catégorie préférée 2 : $categoriePref2</p>";
        echo "<p>Catégorie préférée 3 : $categoriePref3</p>";
        
    #Les 3 categories les plus récurrentes dans l'historique de l'utilisateur
        echo "<br>";
        $query = "SELECT Ca.nomCategorie, COUNT(Ca.nomCategorie) 
        FROM Rist_Categorie Ca
        JOIN Rist_Correspondre Co ON Ca.nomCategorie = Co.nomCategorie
        JOIN Rist_Activite A ON Co.idActivite = A.idActivite
        JOIN Rist_Participer P ON A.idActivite = P.idActivite
        WHERE P.pseudonyme = '$pseudonyme'
        AND A.dateRdv<DATE('2024-12-19')
        GROUP BY Ca.nomCategorie
        ORDER BY COUNT(Ca.nomCategorie) DESC, Ca.nomCategorie ASC
        LIMIT 3";

        $result= mysqli_query($link,$query);
        $categorieHist1 = $categorieHist2 = $categorieHist3 = "";

        // Boucle à travers les résultats
        $i = 1;
        while ($donnees = mysqli_fetch_assoc($result)) {
            ${"categorieHist" . $i} = $donnees["nomCategorie"];
            $i++;
        }

        // Afficher les résultats
        echo "<p>Catégorie récurrente 1 : $categorieHist1</p>";
        echo "<p>Catégorie récurrente 2 : $categorieHist2</p>";
        echo "<p>Catégorie récurrente 3 : $categorieHist3</p>";

?>