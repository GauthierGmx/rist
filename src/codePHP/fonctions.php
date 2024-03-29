<?php
#FONCTIONS DE RECUPERATION DES DONNEES

/**
 * @brief retourne un array list contenant les catégories d'une activité
 *
 * cette fonction prend en paramêtre une connexion à une bdd et un id d'activité
 *
 * @param mysqli_object $link le liens vers la base de données .
 * @param int $id l'id de l'activité.
 *
 * @return array la liste des catégories de l'activité .
 *
 *
 * @warning Assurez-vous que les entrées sont des id/connexions valides pour obtenir un résultat correct.
 *
 */
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

/**
 * @brief retourne un array d'objets activités qui correspond à toutes les activités disponibles 
 * 
 * cette fonction prend en parametre un lien de connexion vers une base de données 
 * 
 * @param mysqli_object $link le liens vers la base de données 
 * 
 * @return array la liste des activités disponibles 
 * 
 * @warning Assurez-vous que le liens de connexion est valide 
 */
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

/**
 * @brief retourne une distance à partir de deux couple de coordonnées 
 * 
 * cette fonction prend en parametre les coordonnées x et y de deux points différents 
 * 
 * @param float $lat1 latitude du point 1
 * 
 * @param float $lon1 longitude du point 1
 * 
 * @param float $lat2 latitude du point 2
 * 
 * @param float $lon2 longitude du point 2
 * 
 * @return float $distance la distance entre les deux points en km.
 * 
 * @warning Assurez-vous que le liens de connexion est valide 
 */
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



/**
 * @brief calcul le score de la distance 
 * 
 * Prend en parametre une distance, une distance moyenne et un pourcentage 
 * 
 * @param float $distanceUtilisateurActivite distance utilisateur - activité
 * 
 * @param float $moyenne distance moyenne à laquelle l'utilisateur se déplace
 * 
 * @param int  $prctGeo pourcentage ququel l'utilisateur veut que son score de distance corresponde dans sa note finale.
 * 
 * @return float $scoreDistance le score de distance de l'activité
 * 
 */
function calculScoreDistance($distanceUtilisateurActivite, $moyenne,$prctGeo) {

    // Calculer le score de distance
    $scoreDistance = $moyenne / $distanceUtilisateurActivite;

    return $scoreDistance * ($prctGeo / 100);
}


/**
 * @brief calcul le score du prix
 * 
 * Prend en parametre un prix, une distance moyenne et un pourcentage 
 * 
 * @param float $prix prix de l'activité
 * 
 * @param float $budget prix moyen que l'utilisateur dépense 
 * 
 * @param int  $prctPrix pourcentage auquel l'utilisateur veut que son score de prix corresponde dans sa note finale.
 * 
 * @return float  le score de prix de l'activité
 * 
 */
function calculScorePrix($prix, $budget, $prctPrix) {
    // Vérifie si le prix est différent de zéro pour éviter la division par zéro
    if ($prix != 0) {
        // Calculer le score de prix
        $scorePrix = $budget / $prix;
        return $scorePrix * ($prctPrix / 100);
    } else {
        // Retourner un score par défaut (ou gérer le cas de prix égal à zéro selon vos besoins)
        return 5 * ($prctPrix / 100);
    }
}

/**
 * @brief retourne vrai si deux catégories ont un ensemble commun, faux sinon
 * 
 * @param string $categorie1 première catégorie
 * 
 * @param string $categorie2 deuxième catégorie
 * 
 * @return Boolean  true si les catégories ont des ensembles communs, faux sinon 
 * 
 */
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


/**
 * @brief retourne vrai si deux catégories ont un ensemble commun, faux sinon
 * 
 * @param array $listeCategorieActivite la liste des categories de l'activité
 * 
 * @param array $listeCategorieUtilisateur la liste des activités récurrentes dans l'historiue de l'utilisateur
 * 
 * @param int $prctCategories pourcentage auquel l'utilisateur veut que son score de catégories corresponde dans sa note finale.
 *  
 * 
 */
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


/**
 * @brief trie l'array list de catégorie en fonction du score 
 * 
 * @param array $activites liste des activités à trier 
 */
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


/**
 * @brief retourne les coordonnées d'un utilisateur
 * 
 * @param mysql_object $link lien vers  la base de données 
 * 
 * @param string $pseudonyme pseudo de l'utilisateur
 * 
 * @return float $coordGPS les coordonnées de l'utilisateur
 */
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

/**
 * @brief retourne le budget saisi par l'utilisateur lors de sa première connexion 
 * 
 * @param mysql_object $link lien vers  la base de données 
 * 
 * @param string $pseudonyme pseudo de l'utilisateur
 * 
 * @return int le budget saisi par l'utilisateur
 */
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


/**
 * @brief retourne le budget moyen de l'utilisateur
 * 
 * @param mysql_object $link lien vers  la base de données 
 * 
 * @param string $pseudonyme pseudo de l'utilisateur
 * 
 * @return int le budget moyen de l'utilisateur l'utilisateur
 */
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
            AND A.dateRdv < DATE('2024-12-19')                                  # Date à changer plus tard
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

/**
 * @brief retourne les catégories préférés de l'utilisateurs
 * 
 * @param mysql_object $link lien vers  la base de données 
 * 
 * @param string $pseudonyme pseudo de l'utilisateur
 * 
 * @return array $categoriesPref les catégories préférées de l'utilisateur
 */
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

    // Boucle à travers les résultats
    while ($donnees = mysqli_fetch_assoc($result)) {
        $categoriesPref[] = $donnees["nomCategorie"];
    }

    echo "<ul>";
    for ($i = 1; $i <= count($categoriesPref); $i++) {
        echo "<li>Catégorie préférée $i : {$categoriesPref[$i - 1]}</li>";
    }
    echo "</ul>";
    
    return $categoriesPref;
}


/**
 * @brief retourne les catégories de l'historique de l'utilisateur
 * 
 * @param mysql_object $link lien vers  la base de données 
 * 
 * @param string $pseudonyme pseudo de l'utilisateur
 * 
 * @return array les catégories présentes dans l'historique de l'utilisateur
 */
function recupererCategoriesHist($link,$pseudonyme){
    #Les 3 categories les plus récurrentes dans l'historique de l'utilisateur
    // Récupérer d'abord les catégories préférées
    $categoriesPref = recupererCategoriesPref($link, $pseudonyme);
    
    $query = "SELECT Ca.nomCategorie, COUNT(Ca.nomCategorie) 
        FROM Rist_Categorie Ca
        JOIN Rist_Correspondre Co ON Ca.nomCategorie = Co.nomCategorie
        JOIN Rist_Activite A ON Co.idActivite = A.idActivite
        JOIN Rist_Participer P ON A.idActivite = P.idActivite
        WHERE P.pseudonyme = '$pseudonyme'
        AND A.dateRdv<DATE('2024-12-19')                            # Date à changer plus tard
        GROUP BY Ca.nomCategorie
        ORDER BY COUNT(Ca.nomCategorie) DESC, Ca.nomCategorie ASC
        LIMIT 3";
    
    $result = mysqli_query($link, $query);
    
    $categoriesHist = array();
    
    // Boucle à travers les résultats
    while ($donnees = mysqli_fetch_assoc($result)) {
        $categoriesHist[] = $donnees["nomCategorie"];
    }
    
    echo "<ul>";
    for ($i = 1; $i <= count($categoriesHist); $i++) {
        echo "<li>Catégorie récurrente $i : {$categoriesHist[$i - 1]}</li>";
    }
    echo "</ul>";
    $categoriesHist = array_merge($categoriesPref, $categoriesHist); // Fusionner avec les catégories préférées
    return $categoriesHist;
}


/**
 * @brief retourne les coordonnées gps des activités dans l'historique de l'utilisateur
 * 
 * @param mysql_object $link lien vers  la base de données 
 * 
 * @param string $pseudonyme pseudo de l'utilisateur
 * 
 * @return array $GPSHistorique
 */
function recupererGPSHistorique($link,$pseudonyme){
    echo "<br>";

    $query = "SELECT A.coordGPS
    FROM Rist_Activite A
    JOIN Rist_Participer P ON A.idActivite = P.idActivite
    WHERE P.pseudonyme = '$pseudonyme' AND A.dateRdv>DATE('2023-12-22')      # Date à changer plus tard
    ORDER BY DATE(A.dateRdv) DESC, A.idActivite ASC 
    LIMIT 10;";
    
    $result = mysqli_query($link, $query);

    $GPSHistorique = array();
    
    // Boucle à travers les résultats
    while ($donnees = mysqli_fetch_assoc($result)) {
        $GPSHistorique[] = explode(',',$donnees["coordGPS"]);
    }

    echo "<ul>";
    for ($i = 1; $i <= count($GPSHistorique); $i++) {
        echo "<li>Activité $i de son historique : {$GPSHistorique[$i - 1][0]}, {$GPSHistorique[$i - 1][1]}</li>";
    }
    echo "</ul>";

    return $GPSHistorique;
}
/**
 * @file fonctions.php
 */
?>