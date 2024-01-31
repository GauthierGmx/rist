<?php

/**
 * @file classActivite.php
 * @brief Définition de la classe Activite.
 * @details La classe Activite représente une activité avec différents attributs tels que l'identificateur, le titre, la description, etc.
 * @author Groupe 23
 */

class Activite {


    /**
     * @brief Identificateur de l'activité.
     * @var int
     */
    private $id;

    /**
     * @brief Titre de l'activité.
     * @var string
     */
    private $titre;

    /**
     * @brief Description de l'activité.
     * @var string
     */
    private $description;

    /**
     * @brief Prix de l'activité (arrondi à 2 chiffres après la virgule).
     * @var float
     */
    private $prix;

    /**
     * @brief Nombre maximal de personnes pour l'activité.
     * @var int
     */
    private $nbPersonneMaxi;

    /**
     * @brief Date limite d'inscription à l'activité au format YYYY-MM-DD.
     * @var string
     */
    private $dateLimiteInscription;

    /**
     * @brief Date du rendez-vous de l'activité au format YYYY-MM-DD.
     * @var string
     */
    private $dateRdv;

    /**
     * @brief Adresse de l'activité.
     * @var string
     */
    private $adresse;

    /**
     * @brief Coordonnées GPS de l'activité, représentées par un tableau [X, Y].
     * @var array
     */
    private $coordGPS;

    /**
     * @brief Pseudonyme de l'organisateur de l'activité.
     * @var string
     */
    private $organisateur;

    /**
     * @brief Catégories auxquelles l'activité appartient, représentées par un tableau.
     * @var array
     */
    private $categories;

    /**
     * @brief Score de l'activité.
     * @var int
     */
    private $score;

    /**
     * @brief Distance de l'activité.
     * @var int
     */
    private $distance;

    /**
     * @brief Constructeur de la classe Activite.
     * @param int $id Identificateur de l'activité.
     * @param string $titre Titre de l'activité.
     * @param string $description Description de l'activité.
     * @param float $prix Prix de l'activité.
     * @param int $nbPersonneMaxi Nombre maximal de personnes.
     * @param string $dateLimiteInscription Date limite d'inscription.
     * @param string $dateRdv Date du rendez-vous.
     * @param string $adresse Adresse de l'activité.
     * @param string $coordGPS Coordonnées GPS de l'activité.
     * @param string $organisateur Pseudonyme de l'organisateur.
     * @param array $categories Catégories de l'activité.
     * @param int $score Score de l'activité.
     */
    public function __construct($id = 0, $titre = "", $description = "", $prix = 0, $nbPersonneMaxi = 0, $dateLimiteInscription = "2000-01-01", $dateRdv = "2000-01-01", $adresse = "", $coordGPS = "0,0", $organisateur = "", $categories = array(), $score = 1) {
        $this->id = $id;
        $this->titre = $titre;
        $this->description = $description;
        $this->prix = $prix;
        $this->nbPersonneMaxi = $nbPersonneMaxi;
        $this->dateLimiteInscription = $dateLimiteInscription;
        $this->dateRdv = $dateRdv;
        $this->adresse = $adresse;
        $this->coordGPS = $coordGPS;
        $this->organisateur = $organisateur;
        $this->categories = $categories;
        $this->score = $score;
        $this->distance = 0;
    }

    /**
     * @brief Getter pour l'identificateur de l'activité.
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @brief Getter pour le titre de l'activité.
     * @return string
     */
    public function getTitre() {
        return $this->titre;
    }

    /**
     * @brief Getter pour la description de l'activité.
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @brief Getter pour le prix de l'activité.
     * @return float
     */
    public function getPrix() {
        return $this->prix;
    }

    /**
     * @brief Getter pour le nombre maximal de personnes pour l'activité.
     * @return int
     */
    public function getNbPersonneMaxi() {
        return $this->nbPersonneMaxi;
    }

    /**
     * @brief Getter pour la date limite d'inscription à l'activité.
     * @return string
     */
    public function getDateLimiteInscription() {
        return $this->dateLimiteInscription;
    }

    /**
     * @brief Getter pour la date du rendez-vous de l'activité.
     * @return string
     */
    public function getDateRdv() {
        return $this->dateRdv;
    }

    /**
     * @brief Getter pour l'adresse de l'activité.
     * @return string
     */
    public function getAdresse() {
        return $this->adresse;
    }

    /**
     * @brief Getter pour les coordonnées GPS de l'activité.
     * @return array
     */
    public function getCoordGPS() {
        return $this->coordGPS;
    }

    /**
     * @brief Getter pour le pseudonyme de l'organisateur de l'activité.
     * @return string
     */
    public function getOrganisateur() {
        return $this->organisateur;
    }

    /**
     * @brief Getter pour les catégories de l'activité.
     * @return array
     */
    public function getCategories() {
        return $this->categories;
    }

    /**
     * @brief Getter pour le score de l'activité.
     * @return int
     */
    public function getScore() {
        return $this->score;
    }

    

    /**
     * @brief Méthode pour afficher les informations de l'activité.
     */
    public function toString() {
        echo "Titre: " . $this->getTitre() . "<br>";
        echo "Description: " . $this->getDescription() . "<br>";
        echo "Prix: " . $this->getPrix() . "<br>";
        echo "Nombre de personnes maximum: " . $this->getNbPersonneMaxi() . "<br>";
        echo "Date limite d'inscription: " . $this->getDateLimiteInscription() . "<br>";
        echo "Date de rendez-vous: " . $this->getDateRdv() . "<br>";
        echo "Adresse: " . $this->getAdresse() . "<br>";
        //echo "Coordonnées GPS: " . $this->getCoordGPS() . "<br>";
        echo "Organisateur: " . $this->getOrganisateur() . "<br>";
        echo "Score: " . $this->getScore() . "<br>";
        echo "Distance: " . $this->getDistance() . "<br>";
    }
}
?>
