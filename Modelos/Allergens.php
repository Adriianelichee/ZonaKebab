<?php
class Allergens {
    public $idAllergens;
    public $name;
    public $photo;


    //Constructor
    public function __construct($idAllergens, $name, $photo) {
        $this->idAllergens = $idAllergens;
        $this->name = $name;
        $this->photo = $photo;
    }

   //Getters and Setters
    public function getIdAllergens() {
        return $this->idAllergens;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function getPhoto() {
        return $this->photo;
    }
    
    public function setPhoto($photo) {
        $this->photo = $photo;
    }
    public function setName($name){
        $this->name = $name;
    }
    public function setId($idAllergens){
        $this->idAllergens = $idAllergens;
    }
    
}