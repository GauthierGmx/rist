<?php
//obtention de toutes les activitées déjà faites
$requeteHistorique = "Select * from activite join historique on historique.idActivite = activite.id where activite.userId = $idUser";

$Historique = mysqli_query($link, $requeteNbHistorique);


$distMax = 0;
while($uneActivite = mysqli_fetch_assoc($Historique)){
    $distance = api($uneActivite['coordonnees'],$user['coordonnees']);
    //
    if ($distMax < $distance) {
        $distMax = $distance;
    }
}

?>