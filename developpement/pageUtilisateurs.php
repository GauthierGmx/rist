<?php

#CONNEXION A LA BASE DE DONNEES
    $bdd= "gvernis_cms"; // Base de données 
    $host= "lakartxela.iutbayonne.univ-pau.fr";
    $user= "gvernis_cms"; // Utilisateur 
    $pass= "gvernis_cms"; // mp

    $link=mysqli_connect($host,$user,$pass,$bdd) or die( "Impossible de se connecter à la base de données");

#FONCTIONS DE RECUPERATION DES DONNEES
function recupererCoordGPS($link,$pseudonyme){
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

    return $coordGPS;
}

function  recupererBudgetSaisi($link,$pseudonyme){
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
    return $budgetSaisi;
}

function recupererBudgetMoyen($link,$pseudonyme){
    #Le budget moyen de l'utilisateur
    echo "<br>";
        $query = "SELECT AVG(prix_moyen) AS moyennePrix
        FROM (
            SELECT A.idActivite, A.prix/COUNT(P.idActivite) AS prix_moyen
            FROM Rist_Participer P
            JOIN Rist_Activite A ON P.idActivite = A.idActivite
            WHERE A.idActivite IN (SELECT idActivite 
                                  FROM Rist_Participer
                                  WHERE pseudonyme = '$pseudonyme')
            AND A.dateRdv < DATE('2024-12-19')
            GROUP BY A.idActivite
        ) AS subquery";
        $result= mysqli_query($link,$query);
                
        // Boucle à travers les résultats
        while ($donnees = mysqli_fetch_assoc($result)) {
            $budgetMoyen = round($donnees["moyennePrix"], 2);
        }

        // Afficher les résultats
        echo "<p>Budget moyen : $budgetMoyen</p>";
        return $budgetMoyen;
}

function recupererCategoriesPref($link,$pseudonyme){
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
        $categoriesPref[] = $donnees["nomCategorie"];
    }

    // Display the preferred categories
    for ($i = 1; $i <= count($categoriesPref); $i++) {
        echo "<p>Catégorie préférée $i : {$categoriesPref[$i - 1]}</p>";
    }
    return $categoriesPref;
}

function recupererCategoriesHist($link,$pseudonyme){
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
    
    $result = mysqli_query($link, $query);
    
    $categoriesHist = array();
    
    // Fetch categories and store them in an array
    while ($donnees = mysqli_fetch_assoc($result)) {
        $categoriesHist[] = $donnees["nomCategorie"];
    }
    
    // Display the recurrent categories
    for ($i = 1; $i <= count($categoriesHist); $i++) {
        echo "<p>Catégorie récurrente $i : {$categoriesHist[$i - 1]}</p>";
    }
    return $categoriesHist;
}


#RECUPERATION DES DONNEES
$NB_UTILISATEURS = 3;
$utilisateurs = array(); // Initialisez un tableau vide

// Ajoutez des pseudonymes à la liste
$utilisateurs[] = 'PatPat';
$utilisateurs[] = 'Laulau64100';
$utilisateurs[] = 'Sparky';

    for ($i=0; $i < $NB_UTILISATEURS; $i++) {
        $pseudonyme  = $utilisateurs[$i];
        echo "<p>Utilisateur : $pseudonyme</p>";

        $query = "SELECT COUNT(idActivite) as nbActivite
        FROM Rist_Participer
        WHERE pseudonyme='$pseudonyme'";
        $result= mysqli_query($link,$query);
                        
        // Boucle à travers les résultats
        while ($donnees = mysqli_fetch_assoc($result)) {
            $nbActivite = $donnees["nbActivite"];
        }

        if($nbActivite < 2){
            printf("Nouvel utilisateur avec une participation à $nbActivite activité");
            echo "<br>";
            recupererCoordGPS($link,$pseudonyme);
            recupererBudgetSaisi($link,$pseudonyme);
            recupererCategoriesPref($link,$pseudonyme);
        }
        else{
            printf("Utilisateur habitué avec une participation à $nbActivite activités");
            echo "<br>";
            recupererCoordGPS($link,$pseudonyme);
            recupererBudgetMoyen($link,$pseudonyme);
            recupererCategoriesHist($link,$pseudonyme);
        }
        echo "<a href='recommandations.php?pseudonyme=$pseudonyme'><button>Voir sa page de recommandation</button></a>";
        echo "<br>";
        echo "------------------------------------------------";
    }

?>