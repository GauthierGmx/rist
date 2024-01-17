<?php
/**
 * @class Activite
 * @brief Classe représentant une activité.
 * @author groupe 23
 */
class Activite {
    /**
     * @var int $id Identifiant de l'activité.
     */
    private $id;

    /**
     * @var string $titre Titre de l'activité.
     */
    private $titre;

    /**
     * @var string $description Description de l'activité.
     */
    private $description;

    /**
     * @var float $prix Prix de l'activité, arrondi à 2 chiffres après la virgule.
     */
    private $prix;

    /**
     * @var int $nbPersonneMaxi Nombre maximal de personnes pour l'activité.
     */
    private $nbPersonneMaxi;

    /**
     * @var string $dateLimiteInscription Date limite d'inscription à l'activité (YYYY-MM-DD).
     */
    private $dateLimiteInscription;

    /**
     * @var string $dateRdv Date du rendez-vous de l'activité (YYYY-MM-DD).
     */
    private $dateRdv;

    /**
     * @var string $adresse Adresse de l'activité.
     */
    private $adresse;

    /**
     * @var array $coordGPS Coordonnées GPS de l'activité sous la forme [X, Y].
     */
    private $coordGPS;

    /**
     * @var string $organisateur Pseudonyme de l'organisateur de l'activité.
     */
    private $organisateur;

    /**
     * @var array $categories Catégories de l'activité.
     */
    private $categories;

    /**
     * @var int $score Score de l'activité.
     */
    private $score;

    /**
     * @var float $distance Distance associée à l'activité.
     */
    private $distance;

    /**
     * @brief Constructeur de la classe Activite.
     * @param int $id Identifiant de l'activité.
     * @param string $titre Titre de l'activité.
     * @param string $description Description de l'activité.
     * @param float $prix Prix de l'activité.
     * @param int $nbPersonneMaxi Nombre maximal de personnes pour l'activité.
     * @param string $dateLimiteInscription Date limite d'inscription à l'activité (YYYY-MM-DD).
     * @param string $dateRdv Date du rendez-vous de l'activité (YYYY-MM-DD).
     * @param string $adresse Adresse de l'activité.
     * @param string $coordGPS Coordonnées GPS de l'activité sous la forme "X, Y".
     * @param string $organisateur Pseudonyme de l'organisateur de l'activité.
     * @param array $categories Catégories de l'activité.
     * @param int $score Score de l'activité.
     */
    public function __construct($id = 0, $titre = "", $description = "", $prix = 0, $nbPersonneMaxi = 0, $dateLimiteInscription = "2000-01-01", $dateRdv = "2000-01-01", $adresse = "", $coordGPS = "0,0", $organisateur = "", $categories = array(), $score = 1) {
        // Initialisation des attributs de l'objet.
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
     * Getter pour l'attribut $id.
     * @return int L'identifiant de l'activité.
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Setter pour l'attribut $id.
     * @param int $id Nouvel identifiant de l'activité.
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * Getter pour l'attribut $titre.
     * @return string Le titre de l'activité.
     */
    public function getTitre() {
        return $this->titre;
    }

    /**
     * Setter pour l'attribut $titre.
     * @param string $titre Nouveau titre de l'activité.
     */
    public function setTitre($titre) {
        $this->titre = $titre;
    }

    /**
     * Getter pour l'attribut $description.
     * @return string La description de l'activité.
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Setter pour l'attribut $description.
     * @param string $description Nouvelle description de l'activité.
     */
    public function setDescription($description) {
        $this->description = $description;
    }

    /**
     * Getter pour l'attribut $prix.
     * @return float Le prix de l'activité.
     */
    public function getPrix() {
        return $this->prix;
    }

    /**
     * Setter pour l'attribut $prix.
     * @param float $prix Nouveau prix de l'activité.
     */
    public function setPrix($prix) {
        $this->prix = $prix;
    }

    /**
     * Getter pour l'attribut $nbPersonneMaxi.
     * @return int Le nombre maximal de personnes pour l'activité.
     */
    public function getNbPersonneMaxi() {
        return $this->nbPersonneMaxi;
    }

    /**
     * Setter pour l'attribut $nbPersonneMaxi.
     * @param int $nbPersonneMaxi Nouveau nombre maximal de personnes pour l'activité.
     */
    public function setNbPersonneMaxi($nbPersonneMaxi) {
        $this->nbPersonneMaxi = $nbPersonneMaxi;
    }

    /**
     * Getter pour l'attribut $dateLimiteInscription.
     * @return string La date limite d'inscription à l'activité (format YYYY-MM-DD).
     */
    public function getDateLimiteInscription() {
        return $this->dateLimiteInscription;
    }

    /**
     * Setter pour l'attribut $dateLimiteInscription.
     * @param string $dateLimiteInscription Nouvelle date limite d'inscription à l'activité (format YYYY-MM-DD).
     */
    public function setDateLimiteInscription($dateLimiteInscription) {
        $this->dateLimiteInscription = $dateLimiteInscription;
    }

    /**
     * Getter pour l'attribut $dateRdv.
     * @return string La date du rendez-vous de l'activité (format YYYY-MM-DD).
     */
    public function getDateRdv() {
        return $this->dateRdv;
    }

    /**
     * Setter pour l'attribut $dateRdv.
     * @param string $dateRdv Nouvelle date du rendez-vous de l'activité (format YYYY-MM-DD).
     */
    public function setDateRdv($dateRdv) {
        $this->dateRdv = $dateRdv;
    }

    /**
     * Getter pour l'attribut $adresse.
     * @return string L'adresse de l'activité.
     */
    public function getAdresse() {
        return $this->adresse;
    }

    /**
     * Setter pour l'attribut $adresse.
     * @param string $adresse Nouvelle adresse de l'activité.
     */
    public function setAdresse($adresse) {
        $this->adresse = $adresse;
    }

    /**
     * Getter pour l'attribut $coordGPS.
     * @return array Les coordonnées GPS de l'activité sous la forme [X, Y].
     */
    public function getCoordGPS() {
        return $this->coordGPS;
    }

    /**
     * Setter pour l'attribut $coordGPS.
     * @param string $chaineCoord Nouvelles coordonnées GPS de l'activité sous la forme "X, Y".
     */
    public function setCoordGPS($chaineCoord) {
        $coordGPSParts = explode(', ', $chaineCoord);
        $this->coordGPS = [$coordGPSParts[0], $coordGPSParts[1]];
    }

    /**
     * Getter pour l'attribut $organisateur.
     * @return string Le pseudonyme de l'organisateur de l'activité.
     */
    public function getOrganisateur() {
        return $this->organisateur;
    }

    /**
     * Setter pour l'attribut $organisateur.
     * @param string $organisateur Nouveau pseudonyme de l'organisateur de l'activité.
     */
    public function setOrganisateur($organisateur) {
        $this->organisateur = $organisateur;
    }

    /**
     * Getter pour l'attribut $categories.
     * @return array Les catégories de l'activité.
     */
    public function getCategories() {
        return $this->categories;
    }

    /**
     * Setter pour l'attribut $categories.
     * @param array $categories Nouvelles catégories de l'activité.
     */
    public function setCategories($categories) {
        $this->categories = $categories;
    }

    /**
     * Getter pour l'attribut $score.
     * @return int Le score de l'activité.
     */
    public function getScore() {
        return $this->score;
    }

    /**
     * Setter pour l'attribut $score.
     * @param int $score Nouveau score de l'activité.
     */
    public function setScore($score) {
        $this->score = $score;
    }

    /**
     * Getter pour l'attribut $distance.
     * @return float La distance associée à l'activité.
     */
    public function getDistance() {
        return $this->distance;
    }

    /**
     * Setter pour l'attribut $distance.
     * @param float $distance Nouvelle distance associée à l'activité.
     */
    public function setDistance($distance) {
        $this->distance = $distance;
    }

    /**
     * @brief Affiche les détails de l'activité.
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