<?php
class Utilisateur {
    // Attributs
    private $pseudonyme; // String
    private $nbActivite; // Int
    private $coordGPS;   // Array : [0] X ; [1] Y
    private $budget;     // Int arrondi à 2 chiffres après la virgule
    private $categories; // Array : [0] catégorie 1 ; [1] catégorie 2 ; [2] catégorie 3 ; ...

    // Constructeur
    public function __construct($pseudonyme='',$nbActivite=0,$coordGPS='',$budget=0,$categories=array()){
        $this->pseudonyme = $pseudonyme;
        $this->nbActivite = $nbActivite;
        $this->coordGPS = $coordGPS;
        $this->budget = $budget;
        $this->categories = $categories;
    }
    // Get & Set
    public function getPseudonyme()
    {
        return $this->pseudonyme;
    }
    public function getNbActivite()
    {
        return $this->nbActivite;
    }
    public function getCoordGPS()
    {
        return $this->coordGPS;
    }
    public function getBudget()
    {
        return $this->budget;
    }
    public function getCategories()
    {
        return $this->categories;
    }

    public function setPseudonyme($pseudonyme)
    {
        $this->pseudonyme = $pseudonyme;
    }
    public function setNbActivite($nbActivite)
    {
        $this->nbActivite = $nbActivite;
    }
    public function setCoordGPS($coordGPS)
    {
        $this->coordGPS = $coordGPS;
    }
    public function setBudget($budget)
    {
        $this->budget = $budget;
    }
    public function setCategories($categories)
    {
        $this->categories = $categories;
    }
}
?>
