<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Utilisateurs</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #eaf7ff;
            color: #333;
        }

        .container {
            margin-top: 50px;
        }

        .user-info {
            border: 1px solid #3498db;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 10px;
            background-color: #ffffff;
        }

        .user-info a {
            color: #f39c12;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        #INCLUDE DES CLASSES
        include 'classUtilisateur.php';
        include 'classActivite.php';

        #CONNEXION A LA BASE DE DONNEES
        $bdd = "gvernis_cms"; // Base de données 
        $host = "localhost";
        $user = "gvernis_cms"; // Utilisateur 
        $pass = "gvernis_cms"; // mp

        $link = mysqli_connect($host, $user, $pass, $bdd) or die("Impossible de se connecter à la base de données");

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
        
        function recupererGPSHistorique($link,$pseudonyme){
            echo "<br>";
        
            $query = "SELECT A.coordGPS
            FROM Rist_Activite A
            JOIN rist_participer P ON A.idActivite = P.idActivite
            WHERE P.pseudonyme = 'Sparky' AND A.dateRdv>DATE('2023-12-22')      # Date à changer plus tard
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

        #INITIALISATION DES DONNEES
        $NB_UTILISATEURS = 3;
        $utilisateurs = array(); // Initialisez un tableau vide

        // Ajoutez des pseudonymes à la liste
        $utilisateurs[] = 'PatPat';
        $utilisateurs[] = 'Laulau64100';
        $utilisateurs[] = 'Sparky';

        #RECUPERATION DES DONNEES
        for ($i = 0; $i < $NB_UTILISATEURS; $i++) {
            $pseudonyme = $utilisateurs[$i];
            echo "<div class='user-info'>";
            echo "<p>Utilisateur: $pseudonyme</p>";
            ${"utilisateur" . $i} = new Utilisateur();
            ${"utilisateur" . $i}->setPseudonyme($pseudonyme);

            $query = "SELECT COUNT(idActivite) as nbActivite
        FROM Rist_Participer
        WHERE pseudonyme='$pseudonyme'";
        $result= mysqli_query($link,$query);
                        
        // Boucle à travers les résultats
        while ($donnees = mysqli_fetch_assoc($result)) {
            $nbActivite = $donnees["nbActivite"];
        }
        ${"utilisateur" . $i}->SetNbActivite($nbActivite);
        if($nbActivite < 2){
            printf("Nouvel utilisateur");
            echo "<br>";
            ${"utilisateur" . $i}->setCoordGPS(recupererCoordGPS($link,$pseudonyme));
            ${"utilisateur" . $i}->setbudget(recupererBudgetSaisi($link,$pseudonyme));
            ${"utilisateur" . $i}->setCategories(recupererCategoriesPref($link,$pseudonyme));
        }
        else{
            printf("Utilisateur habitué");
            echo "<br>";
            ${"utilisateur" . $i}->setCoordGPS(recupererCoordGPS($link,$pseudonyme));
            ${"utilisateur" . $i}->setBudget(recupererBudgetMoyen($link,$pseudonyme));
            ${"utilisateur" . $i}->setCategories(recupererCategoriesHist($link,$pseudonyme));
        }
        ${"utilisateur" . $i}->setGPSHistorique(recupererGPSHistorique($link,$pseudonyme));
        $serializedUser = urlencode(serialize(${"utilisateur" . $i}));
            echo "<a href='recommandations.php?utilisateur=$serializedUser' class='btn btn-warning'>Voir sa page de recommandation</a>";
            echo "</div>";
        }
        ?>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>