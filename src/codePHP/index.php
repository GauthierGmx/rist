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
            color: #000;
            text-decoration: none;
            font-weight: bold;
        }

        .btn-warning {
            --bs-btn-color: #000;
            --bs-btn-bg: #ffc107;
            --bs-btn-border-color: #ffc107;
            --bs-btn-hover-color: #000;
            --bs-btn-hover-bg: #ffe127;
            --bs-btn-hover-border-color: #ffc720;
            --bs-btn-focus-shadow-rgb: 217,164,6;
            --bs-btn-active-color: #000;
            --bs-btn-active-bg: #ffcd39;
            --bs-btn-active-border-color: #ffc720;
            --bs-btn-active-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
            --bs-btn-disabled-color: #000;
            --bs-btn-disabled-bg: #ffc107;
            --bs-btn-disabled-border-color: #ffc107;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        #INCLUDE DES CLASSES
        include 'classesPHP/classUtilisateur.php';
        include 'classesPHP/classActivite.php';
        include 'fonctions.php';

        #CONNEXION A LA BASE DE DONNEES
        $bdd = "gvernis_cms"; // Base de données 
        $host = "lakartxela.iutbayonne.univ-pau.fr";
        $user = "gvernis_cms"; // Utilisateur 
        $pass = "gvernis_cms"; // mp

        $link = mysqli_connect($host, $user, $pass, $bdd) or die("Impossible de se connecter à la base de données");        

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
        ${"utilisateur" . $i}->setCategories(array_unique(${"utilisateur" . $i}->getCategories()));
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