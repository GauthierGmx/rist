<?php
#FONCTIONS DE RECUPERATION DES DONNEES
function recupererCategories($link,$id){
    $query = "SELECT C.nomCategorie
    FROM Rist_Activite A
    JOIN Rist_Correspondre C ON A.idActivite = C.idActivite
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
    WHERE A.dateLimiteInscription <= '2024-12-10'
    AND (SELECT COUNT(*) FROM Rist_Participer P WHERE P.idActivite = A.idActivite) <= A.nbPersonneMaxi;";
    $result= mysqli_query($link,$query);
    
    $activites = array(); // Tableau pour stocker les instances d'Activite

    // Boucle à travers les résultats
    $i=0;
    while ($donnees = mysqli_fetch_assoc($result)) {
        $activite = new Activite();
        $activite->setId($donnees["idActivite"]);
        $activite->setTitre($donnees["titre"]);
        $activite->setDescription($donnees["description"]);
        $activite->setPrix($donnees["prix"]/$donnees["nbPersonneMaxi"]);
        $activite->setNbPersonneMaxi($donnees["nbPersonneMaxi"]);
        $activite->setDateLimiteInscription($donnees["dateLimiteInscription"]);
        $activite->setDateRdv($donnees["dateRdv"]);
        $activite->setAdresse($donnees["adresse"]);
        $activite->setCoordGPS($donnees["coordGPS"]);
        $activite->setOrganisateur($donnees["pseudonyme"]);
        $activite->setCategories(recupererCategories($link,$activite->getId()));

        $activites[$i] = $activite; // Ajouter l'activité au tableau avec l'index $i
        $i+=1;
    }

    return $activites;
}

function haversineDistance($lat1, $lon1, $lat2, $lon2) {
    // Rayon moyen de la Terre en kilomètres
    $earthRadius = 6371;

    // Conversion des degrés en radians
    $lat1 = deg2rad($lat1);
    $lon1 = deg2rad($lon1);
    $lat2 = deg2rad($lat2);
    $lon2 = deg2rad($lon2);

    // Calcul des variations de latitude et de longitude
    $latDelta = $lat2 - $lat1;
    $lonDelta = $lon2 - $lon1;

    // Formule de la distance haversine
    $a = sin($latDelta / 2) * sin($latDelta / 2) + cos($lat1) * cos($lat2) * sin($lonDelta / 2) * sin($lonDelta / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    // Distance en kilomètres
    $distance = $earthRadius * $c;

    return $distance;
}

//calcul du score de distance via la formule :
//scoreDistance = distMoyenne/(distanceUtilisateurActivite * (1-poids))
function calculScoreDistance($distanceUtilisateurActivite, $moyenne,$prctGeo) {

    // Calculer le score de distance
    $scoreDistance = $moyenne / $distanceUtilisateurActivite;

    return $scoreDistance * ($prctGeo / 100);
}

function calculScorePrix($prix, $budget, $prctPrix) {
    // Vérifie si le prix est différent de zéro pour éviter la division par zéro
    if ($prix != 0) {
        // Calculer le score de prix
        $scorePrix = $budget / $prix;
        return $scorePrix * ($prctPrix / 100);
    } else {
        // Retourner un score par défaut (ou gérer le cas de prix égal à zéro selon vos besoins)
        return 0;
    }
}

function categorieSimilaire($categorie1, $categorie2){
    $bdd= "gvernis_cms"; // Base de données 
    $host= "lakartxela.iutbayonne.univ-pau.fr";
    $user= "gvernis_cms"; // Utilisateur 
    $pass= "gvernis_cms"; // mp

    $link=mysqli_connect($host,$user,$pass,$bdd) or die( "Impossible de se connecter à la base de données");
    $query = "SELECT DISTINCT e1.nomEnsemble
                FROM Rist_Ensemble e1
                JOIN Rist_Ensemble e2 ON e1.nomEnsemble = e2.nomEnsemble
                JOIN Rist_Appartenir a1 ON e1.nomEnsemble = a1.nomEnsemble
                JOIN Rist_Appartenir a2 ON e2.nomEnsemble = a2.nomEnsemble
                JOIN Rist_Categorie c1 ON a1.nomCategorie = c1.nomCategorie
                JOIN Rist_Categorie c2 ON a2.nomCategorie = c2.nomCategorie
                WHERE c1.nomCategorie = '".$categorie1."' AND c2.nomCategorie = '".$categorie2."';";
    
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

function tri(&$activites) {
    $n = count($activites);

    for ($i = 0; $i < $n - 1; $i++) {
        for ($j = 0; $j < $n - $i - 1; $j++) {
            // Utilisez la méthode getScore() pour comparer les objets
            if ($activites[$j]->getScore() < $activites[$j + 1]->getScore()) {
                // Échangez les éléments s'ils sont dans le mauvais ordre
                $temp = $activites[$j];
                $activites[$j] = $activites[$j + 1];
                $activites[$j + 1] = $temp;
            }
        }
    }
}
?>