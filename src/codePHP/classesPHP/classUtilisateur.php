<?php
/**
 * @class Utilisateur
 * @brief Classe représentant un utilisateur.
 * @author groupe 23
 */
class Utilisateur {
    /**
     * @var string $pseudonyme Pseudonyme de l'utilisateur.
     */
    private $pseudonyme;

    /**
     * @var int $nbActivite Nombre d'activités associées à l'utilisateur.
     */
    private $nbActivite;

    /**
     * @var array $coordGPS Coordonnées GPS de l'utilisateur sous la forme [X, Y].
     */
    private $coordGPS;

    /**
     * @var float $budget Budget de l'utilisateur, arrondi à 2 chiffres après la virgule.
     */
    private $budget;

    /**
     * @var array $categories Catégories préférentielles de l'utilisateur.
     */
    private $categories;

    /**
     * @var array $GPSHistorique Historique des coordonnées GPS de l'utilisateur sous la forme d'un tableau d'arrays [X, Y].
     */
    private $GPSHistorique;

    /**
     * @brief Constructeur de la classe Utilisateur.
     * @param string $pseudonyme Pseudonyme de l'utilisateur.
     * @param int $nbActivite Nombre d'activités associées à l'utilisateur.
     * @param array $coordGPS Coordonnées GPS de l'utilisateur sous la forme [X, Y].
     * @param float $budget Budget de l'utilisateur.
     * @param array $categories Catégories préférentielles de l'utilisateur.
     * @param array $GPSHistorique Historique des coordonnées GPS de l'utilisateur.
     */
    public function __construct($pseudonyme = '', $nbActivite = 0, $coordGPS = '', $budget = 0, $categories = array(), $GPSHistorique = array()) {
        // Initialisation des attributs de l'objet.
        $this->pseudonyme = $pseudonyme;
        $this->nbActivite = $nbActivite;
        $this->coordGPS = $coordGPS;
        $this->budget = $budget;
        $this->categories = $categories;
        $this->GPSHistorique = $GPSHistorique;
    }

    /**
     * Getter pour l'attribut $pseudonyme.
     * @return string Le pseudonyme de l'utilisateur.
     */
    public function getPseudonyme() {
        return $this->pseudonyme;
    }

    /**
     * Setter pour l'attribut $pseudonyme.
     * @param string $pseudonyme Nouveau pseudonyme de l'utilisateur.
     */
    public function setPseudonyme($pseudonyme) {
        $this->pseudonyme = $pseudonyme;
    }

    /**
     * Getter pour l'attribut $nbActivite.
     * @return int Le nombre d'activités associées à l'utilisateur.
     */
    public function getNbActivite() {
        return $this->nbActivite;
    }

    /**
     * Setter pour l'attribut $nbActivite.
     * @param int $nbActivite Nouveau nombre d'activités associées à l'utilisateur.
     */
    public function setNbActivite($nbActivite) {
        $this->nbActivite = $nbActivite;
    }

    /**
     * Getter pour l'attribut $coordGPS.
     * @return array Les coordonnées GPS de l'utilisateur sous la forme [X, Y].
     */
    public function getCoordGPS() {
        return $this->coordGPS;
    }

    /**
     * Setter pour l'attribut $coordGPS.
     * @param array $coordGPS Nouvelles coordonnées GPS de l'utilisateur sous la forme [X, Y].
     */
    public function setCoordGPS($coordGPS) {
        $this->coordGPS = $coordGPS;
    }

    /**
     * Getter pour l'attribut $budget.
     * @return float Le budget de l'utilisateur.
     */
    public function getBudget() {
        return $this->budget;
    }

    /**
     * Setter pour l'attribut $budget.
     * @param float $budget Nouveau budget de l'utilisateur.
     */
    public function setBudget($budget) {
        $this->budget = $budget;
    }

    /**
     * Getter pour l'attribut $categories.
     * @return array Les catégories préférentielles de l'utilisateur.
     */
    public function getCategories() {
        return $this->categories;
    }

    /**
     * Setter pour l'attribut $categories.
     * @param array $categories Nouvelles catégories préférentielles de l'utilisateur.
     */
    public function setCategories($categories) {
        $this->categories = $categories;
    }

    /**
     * Getter pour l'attribut $GPSHistorique.
     * @return array L'historique des coordonnées GPS de l'utilisateur.
     */
    public function getGPSHistorique() {
        return $this->GPSHistorique;
    }

    /**
     * Setter pour l'attribut $GPSHistorique.
     * @param array $GPSHistorique Nouvel historique des coordonnées GPS de l'utilisateur.
     */
    public function setGPSHistorique($GPSHistorique) {
        $this->GPSHistorique = $GPSHistorique;
    }
}
?>
