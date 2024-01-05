<?php class Activite {
    // Attributs
    private $id; // Int
    private $titre; // String
    private $description;   // String
    private $prix;     // Int arrondi à 2 chiffres après la virgule
    private $nbPersonneMaxi; // Int
    private $dateLimiteInscription; // Date YYYY-MM-DD
    private $dateRdv; // Date YYYY-MM-DD
    private $adresse; // String
    private $coordGPS; // Array : [0] X ; [1] Y
    private $organisateur; // String du pseudonyme de l'organisateur
    private $categories; // Array : [0] catégorie 1 ; [1] catégorie 2 ; [2] catégorie 3 ; ...
    private $score; // Int 

    // Constructeur
    public function __construct($id=0,$titre="",$description="",$prix=0,$nbPersonneMaxi=0,$dateLimiteInscription="2000-01-01",$dateRdv="2000-01-01",$adresse="",$coordGPS="0,0",$organisateur="",$categories=array(),$score=1){
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
    }

    // Get & Set
    public function getId()
    {
        return $this->id;
    }
    public function getTitre()
    {
        return $this->titre;
    }
    public function getDescription()
    {
        return $this->description;
    }
    public function getPrix()
    {
        return $this->prix;
    }
    public function getNbPersonneMaxi()
    {
        return $this->nbPersonneMaxi;
    }
    public function getDateLimiteInscription()
    {
        return $this->dateLimiteInscription;
    }
    public function getDateRdv()
    {
        return $this->dateRdv;
    }
    public function getAdresse()
    {
        return $this->adresse;
    }
    public function getCoordGPS()
    {
        return $this->coordGPS;
    }
    public function getOrganisateur()
    {
        return $this->organisateur;
    }
    public function getCategories()
    {
        return $this->categories;
    }
    public function getScore()
    {
        return $this->score;
    }

    public function setId($id)
    {
        $this->id = $id;
    }
    public function setTitre($titre)
    {
        $this->titre = $titre;
    }
    public function setDescription($description)
    {
        $this->description = $description;
    }
    public function setPrix($prix)
    {
        $this->prix = $prix;
    }
    public function setNbPersonneMaxi($nbPersonneMaxi)
    {
        $this->nbPersonneMaxi = $nbPersonneMaxi;
    }
    public function setDateLimiteInscription($dateLimiteInscription)
    {
        $this->dateLimiteInscription = $dateLimiteInscription;
    }
    public function setDateRdv($dateRdv)
    {
        $this->dateRdv = $dateRdv;
    }
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    }
    public function setCoordGPS($chaineCoord)
    {
        $coordGPSParts = explode(', ', $chaineCoord);
        $this->coordGPS = [$coordGPSParts[0], $coordGPSParts[1]];
    }
    public function setOrganisateur($organisateur)
    {
        $this->organisateur = $organisateur;
    }
    public function setCategories($categories)
    {
        $this->categories = $categories;
    }
    public function setScore($score)
    {
        $this->score = $score;
    }


    public function toString()
    {
        echo "Titre: " . $this->getTitre() . "<br>";
        echo "Description: " . $this->getDescription() . "<br>";
        echo "Prix: " . $this->getPrix() . "<br>";
        echo "Nombre de personnes maximum: " . $this->getNbPersonneMaxi() . "<br>";
        echo "Date limite d'inscription: " . $this->getDateLimiteInscription() . "<br>";
        echo "Date de rendez-vous: " . $this->getDateRdv() . "<br>";
        echo "Adresse: " . $this->getAdresse() . "<br>";
        echo "Coordonnées GPS: " . $this->getCoordGPS() . "<br>";
        echo "Organisateur: " . $this->getOrganisateur() . "<br>";
        echo "Catégories: " . implode(", ", $this->getCategories()) . "<br>";
        echo "Score: " . $this->getScore() . "<br>";
    }


}
?>