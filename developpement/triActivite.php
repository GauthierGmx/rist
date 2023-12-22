<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php

    include 'classes/classActivite.php'
    //création d'activitees pour le test
    $activite1 = new Activite();

    $activite1->setTitre("salut");
    $activite1->setDescription("Ici on dit bonjour");
    $activite1->setScore(5);

    $activite2 = new Activite();

    $activite2->setTitre("ski");
    $activite2->setDescription(" vivement février");
    $activite2->setScore(2);

    $activite3 = new Activite();

    $activite3->setTitre("Rocket League");
    $activite3->setDescription("Ici on speed flick");
    $activite3->setScore(3);

    $activite4 = new Activite();

    $activite4->setTitre("dead cell");
    $activite4->setDescription("You've been desecrated");
    $activite4->setScore(7);

    //creation d'un array
    $listePasTrie = array($activite1,$activite2,$activite3,$activite4);

    //fonction de tri
    function triArrayActivite($arrayActivite){
        for($i=0; $i<3; $i++) {
            for($j=0; $j<(3-$i); $j++) {
                if ($arrayActivite[$j]->getScore() < $arrayActivite[$j+1]->getScore()) {
                    $temp = $arrayActivite[$j+1];
                    $arrayActivite[$j+1] = $arrayActivite[$j];
                    $arrayActivite[$j] = $temp;
                }
            }
        }
        return $arrayActivite; 
    }

    //affichage des arrays
    for($i = 0; $i < 4; $i++){
        $titre = $listePasTrie[$i]->getTitre();
        $description = $listePasTrie[$i]->getDescription();
        $score = $listePasTrie[$i]->getScore();
        echo("<div style='border:dotted'><p>$titre <BR> $description <BR> $score</p></div>");
    }

?>
</body>
</html>
