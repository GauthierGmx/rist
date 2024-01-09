<?php

include 'classActivite.php';
include 'classUtilisateur.php';







function Haversine( $uneActivite, $unUtilisateur){

    // Recuperation des données
    $latitudeUtilisateur = $unUtilisateur[0];
    $longitudeUtilisateur = $unUtilisateur[1];
    $latitudeActivite = $uneActivite[0];
    $longitudeActivite = $uneActivite[1];

    //Calcul des differences
    $differenceLatitude = $latitudeActivite - $latitudeUtilisateur;
    $differenceLongitude = $longitudeActivite - $longitudeUtilisateur;

    // utiliser la formule haversine pour calculer le distance
    $distanceHaversine = sin($differenceLatitude/2)**2 + cos($latitudeUtilisateur) * cos($latitudeActivite) * sin($differenceLongitude/2);

    // calcul de l'angle central
    $angle = 2 * atan2(sqrt($distanceHaversine),sqrt(1 - $distanceHaversine));

    //rayon moyen de la Terre
    $rayonTerre = 6371;

    //Calcul final

    $DistanceFinal = $rayonTerre * $angle;

    return $DistanceFinal;
}




echo "La distance entre l'activité et l'utilisateur est : " . $result . "km";