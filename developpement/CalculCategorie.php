<?php



function categorieSimilaire($categorie1, $categorie2){
    $bdd= "gvernis_cms"; // Base de données 
    $host= "localhost";
    $user= "gvernis_cms"; // Utilisateur 
    $pass= "gvernis_cms"; // mp

    $link=mysqli_connect($host,$user,$pass,$bdd) or die( "Impossible de se connecter à la base de données");

    $query = "SELECT DISTINCT e1.nomEnsemble
              FROM rist_ensemble e1
              JOIN rist_ensemble e2 ON e1.nomEnsemble = e2.nomEnsemble
              JOIN rist_appartenir a1 ON e1.nomEnsemble = a1.nomEnsemble
              JOIN rist_appartenir a2 ON e2.nomEnsemble = a2.nomEnsemble
              JOIN rist_categorie c1 ON a1.nomCategorie = c1.nomCategorie
              JOIN rist_categorie c2 ON a2.nomCategorie = c2.nomCategorie
              WHERE c1.nomCategorie = '".$categorie1."'
              AND c2.nomCategorie = '".$categorie2."';";
    
    $result= mysqli_query($link,$query);

    // Vérification si la requête n'est pas vide
    if ($result && $result->num_rows > 0) {
        //La requête n'est pas vide, retourne true.
        return true;
    } else {
        //La requête est vide, retourne false.
        return false;
    }
}

function calculScoreCategorie($listeCategorieActivite, $listeCategorieUtilisateur, $prctCategories){
    $score = 0;
    foreach($listeCategorieActivite as $categorieActivite){
        foreach($listeCategorieUtilisateur as $categorieUtilisateur){
            if ($categorieActivite == $categorieUtilisateur) {
                $score++;
            }
            else {
                if (categorieSimilaire($categorieActivite, $categorieUtilisateur)) {
                    $score = $score + 0.5;
                }
            }
        }
    }
    
    
    return $score * ($prctCategories/100);
}
