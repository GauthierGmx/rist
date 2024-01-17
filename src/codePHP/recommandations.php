<!DOCTYPE html>
<html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Les recommandations</title>

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

        <style>
            body {
                background-color: #eaf7ff;
                color: #333;
                padding-top: 50px;
            }

            .container {
                margin-bottom: 50px;
            }

            h3 {
                color: #3498db;
            }

            label {
                color: #f39c12;
            }

            input[type="number"] {
                background-color: #eeffff;
                border: 1px solid #00cccc;
            }

            ul {
                list-style-type: none;
                padding: 0;
            }

            li {
                margin-bottom: 5px;
            }

            .info-icon {
                position: relative;
                display: inline-block;
            }

            .info-icon:hover .info-text {
                display: block;
            }

            .info-text {
                font-size: 15px;
                display: none;
                position: absolute;
                top: 20px;
                left: 0;
                background-color: #f9f9f9;
                padding: 10px;
                border: 1px solid #ccc;
                border-radius: 5px;
                z-index: 1;
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

            /* Ajout de styles pour la grille Bootstrap */
            .activite-box {
                margin-bottom: 20px;
            }

            .user-info-box {
                border: 1px solid #3498db;
                padding: 10px;
                margin-bottom: 20px;
                border-radius: 10px;
                background-color: #ffffff;
            }

            .user-info-box p {
                margin: 0; /* Supprime la marge par défaut des paragraphes */
            }

            .user-info-box ul {
                list-style-type: none;
                padding: 0;
            }

            .user-info-box ul li {
                margin-bottom: 5px;
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
            font-weight: bold;
        }
        </style>
    </head>
    <script>
        function verifierTotal() {
            var prctGeographie = parseInt(document.getElementsByName('prctGeographie')[0].value) || 0;
            var prctPrix = parseInt(document.getElementsByName('prctPrix')[0].value) || 0;
            var prctCategories = parseInt(document.getElementsByName('prctCategories')[0].value) || 0;

            var total = prctGeographie + prctPrix + prctCategories;

            if (total !== 100) {
                alert("La somme des pourcentages doit être égale à 100. Actuellement, c'est " + total + "\nVeuillez corriger, merci.");
                return false; // Empêche l'envoi du formulaire
            }
            return true;
        }
    </script>
    <body>
        <?php // Section PHP liée aux données et aux calculs
                #INCLUDE DES CLASSES
                include './classesPHP/classUtilisateur.php';
                include './classesPHP/classActivite.php';
                include 'fonctions.php';

                #RECUPERATION DES DONNEES DE L'UTILISATEUR
                // Récupérez la chaîne sérialisée à partir de la requête GET
                $serializedUtilisateur = urldecode($_GET['utilisateur']);

                // Désérialisez la chaîne pour obtenir l'objet utilisateur
                $utilisateur = unserialize($serializedUtilisateur);

                #CONNEXION A LA BASE DE DONNEES
                $bdd= "gvernis_cms"; // Base de données 
                $host= "lakartxela.iutbayonne.univ-pau.fr";
                $user= "gvernis_cms"; // Utilisateur 
                $pass= "gvernis_cms"; // mp

                $link=mysqli_connect($host,$user,$pass,$bdd) or die( "Impossible de se connecter à la base de données");

                //Vérifier si on doit prendre en compte les catégories les plus récurrentes
                $chaud = false;

                $query = "SELECT Ca.nomCategorie, COUNT(Ca.nomCategorie) 
                FROM Rist_Categorie Ca
                JOIN Rist_Correspondre Co ON Ca.nomCategorie = Co.nomCategorie
                JOIN Rist_Activite A ON Co.idActivite = A.idActivite
                JOIN Rist_Participer P ON A.idActivite = P.idActivite
                WHERE P.pseudonyme = '".$utilisateur->getPseudonyme()."'
                AND A.dateRdv<DATE('2024-12-19')                            # Date à changer plus tard
                GROUP BY Ca.nomCategorie
                ORDER BY COUNT(Ca.nomCategorie) DESC, Ca.nomCategorie ASC
                LIMIT 3";

                $result= mysqli_query($link,$query);
                if(mysqli_num_rows($result) > 2){
                    $chaud = true;

                    $categoriesRecurrentes = array();
                    while ($donnees = mysqli_fetch_assoc($result)){
                        array_push($categoriesRecurrentes,$donnees['nomCategorie']);
                    }
                }


        ?>

        

        <div class="container">
            <h3>Informations de l'utilisateur</h3>

            <div class="user-info-box">
                <?php
                    // Section PHP pour l'affichage des informations de l'utilisateur...
                    echo "<p><strong>Pseudonyme :</strong> " . utf8_encode($utilisateur->getPseudonyme()) . "</p>";
                    echo "<p><strong>Nombre d'activités :</strong> " . utf8_encode($utilisateur->getNbActivite()) . "</p>";
                    echo "<p><strong>Coordonnées GPS x :</strong> " . $utilisateur->getCoordGPS()[0] . "</p>";
                    echo "<p><strong>Coordonnées GPS y :</strong> " . $utilisateur->getCoordGPS()[1] . "</p>";
                    echo "<p><strong>Budget :</strong> " . $utilisateur->getBudget() . "</p>";

                    echo "<p><strong>Categories préférées :</strong></p>";
                    echo "<ul>";
                    foreach ($utilisateur->getCategories() as $category) {
                        echo "<li>$category</li>";
                    }
                    echo "</ul>";

                    echo "<p><strong>Positions géographiques des activités de son historique :</strong></p>";
                    echo "<ul>";
                    foreach ($utilisateur->getGPSHistorique() as $GPS) {
                        echo "<li>$GPS[0], $GPS[1]</li>";
                    }
                    echo "</ul>";
                ?>
            </div>

            <h3>Veuillez distribuez la priorité sur les différentes critères pour un total de 100%
                <span class='info-icon'>
                    <img src='info-icon.png' alt='Info' width='20' height='20'>
                    <span class='info-text'>Plus la priorité est grande pour un critère, plus il sera pris en compte dans le calcul de votre liste de recommandation</span>
                </span>
            </h3>

            <?php
            // Gestion du formulaire pour les poids
            echo '<form method="post" action="recommandations.php?utilisateur=' . urlencode($serializedUtilisateur) . '" onsubmit="return verifierTotal();">';
            $prctGeographie = isset($_POST['prctGeographie']) ? intval($_POST['prctGeographie']) : 50;
            $prctPrix = isset($_POST['prctPrix']) ? intval($_POST['prctPrix']) : 30;
            $prctCategories = isset($_POST['prctCategories']) ? intval($_POST['prctCategories']) : 20;
            ?>

            <label>Zone géographique :
                <span class='info-icon'>
                    <img src='info-icon.png' alt='Info' width='20' height='20'>
                    <span class='info-text'>Détermine si la distance entre vous et les activités recommandées est importante</span>
                </span>
            </label>

            <?php
            echo "<input value=$prctGeographie type='number' name='prctGeographie' placeholder='50' min='0' max='100' required>";
            ?>

            <label>Prix :
                <span class='info-icon'>
                    <img src='info-icon.png' alt='Info' width='20' height='20'>
                    <span class='info-text'>Détermine si le prix des activités recommandées est important par rapport à votre budget</span>
                </span>
            </label>

            <?php
            echo "<input value=$prctPrix type='number' name='prctPrix' placeholder='30' min='0' max='100' required>";
            ?>

            <label>Intérêts :
                <span class='info-icon'>
                    <img src='info-icon.png' alt='Info' width='20' height='20'>
                    <span class='info-text'>Détermine si vos intérêts sont importants par rapport aux catégories des activités recommandées</span>
                </span>
            </label>

            <?php
            echo "<input value=$prctCategories type='number' name='prctCategories' placeholder='20' min='0' max='100' required>";
            ?>

            <input class='btn btn-warning' type="submit" value="Rafraîchir">
            </form>

            <ul class="liste">
            <div class="d-flex flex-nowrap overflow-auto">
                <?php
                // Appels des fonctions de calcul de score et affichage des 3 activités recommandées
                $activites = recupererInfosPrincipalesActivite($link);

                // Calcul de la distance moyenne entre l'utilisateur et les activités de son historique
                $listeDist = array();

                foreach ($utilisateur->getGPSHistorique() as $GPS) {
                    $result = haversineDistance($utilisateur->getCoordGPS()[0], $utilisateur->getCoordGPS()[1], $GPS[0], $GPS[1]);
                    $listeDist[] = $result;
                }

                $somme = array_sum($listeDist);
                $nombreElements = count($listeDist);

                if ($nombreElements > 0) {
                    $moyenne = $somme / $nombreElements;

                } else {
                    echo "Le tableau est vide, impossible de calculer la moyenne.";
                }

                foreach ($activites as $index => $activite) {
                    $distanceUtilisateurActivite = haversineDistance($utilisateur->getCoordGPS()[0], $utilisateur->getCoordGPS()[1], $activite->getCoordGPS()[0], $activite->getCoordGPS()[1]);
                    $activite->setDistance($distanceUtilisateurActivite);

                    $scoreDistance = calculScoreDistance($distanceUtilisateurActivite, $moyenne, $prctGeographie);
                    $nouveauScore = $activite->getScore() + $scoreDistance;
                    $activite->setScore($nouveauScore);

                    $scorePrix = calculScorePrix($activite->getPrix(), $utilisateur->getBudget(), $prctPrix);
                    $nouveauScore = $activite->getScore() + $scorePrix;
                    $activite->setScore($nouveauScore);

                    $scoreCategorie = calculScoreCategorie($activite->getCategories(), $utilisateur->getCategories(), $prctCategories);
                    $nouveauScore = $activite->getScore() + $scoreCategorie;
                    $activite->setScore($nouveauScore);

                    /*if ($chaud) {
                        $scoreCategorieRecurrente = calculScoreCategorie($activite->getCategories(), $categoriesRecurrentes, $prctCategories);
                        $nouveauScore = $activite->getScore() + $scoreCategorieRecurrente;
                        $activite->setScore($nouveauScore);
                    }*/
                }

                tri($activites);
                
                foreach ($activites as $index => $activite) {
                    echo "<div class='col-md-4 flex-shrink-0'>";
                    echo "<div class='card'>";
                    echo "<div class='card-body'>";
                    echo "<h5 class='card-title'>" . utf8_encode($activite->getTitre()) . "</h5>";
                    echo "<p class='card-text'>" . utf8_encode($activite->getDescription()) . "</p>";
                    echo "<p class='card-text'>Prix : " . round($activite->getPrix(), 2) . " €</p>";
                    echo "<p class='card-text'>Distance : " . round($activite->getDistance(),2) . " km</p>";
                    echo "<p class='card-text'>Score : " . round($activite->getScore(),3) . 
                         "     (score distance : ". round(calculScoreDistance(haversineDistance($utilisateur->getCoordGPS()[0], $utilisateur->getCoordGPS()[1], $activite->getCoordGPS()[0], $activite->getCoordGPS()[1]), $moyenne, $prctGeographie),3) . 
                         ", score prix : ".round(calculScorePrix($activite->getPrix(), $utilisateur->getBudget(), $prctPrix),3) .
                         ", score catégorie : ". round(calculScoreCategorie($activite->getCategories(), $utilisateur->getCategories(), $prctCategories),3) . ")</p>";
                    echo "<p class='card-text'>Catégorie : ";
                    foreach($activite->getCategories() as $categorie){
                        echo utf8_encode($categorie) .", ";
                    }
                    echo "</p>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    /*
                    echo "<p id='" . $activite->getScore() . "'>";
                    echo $activite->toString();
                    echo "</p>";
                    */
                }
                ?>
                </div>
            </ul>
            <br>
            <a class='btn btn-warning' href="index.php">&lsaquo; Retourner aux utilisateurs</a>

        </div>

        <!-- Bootstrap JS and Popper.js -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
