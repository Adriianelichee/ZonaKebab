<?php

class Ingredient {
    private $idIngredient;
    private $name;
    private $photo;
    private $price;
    private $kebabs = [];
    private $allergens = [];

    //Constructor
    public function __construct($name, $price) {
        $this->name = $name;
        $this->price = $price;
    }

    
    //Getters and setters
    public function getAllergens() {
        return $this->allergens;
    }
    
    public function addAllergen($allergen) {
        $this->allergens[] = $allergen;
    }
    public function getIdIngredient() {
        return $this->idIngredient;
    }
    
    public function getKebabs() {
        return $this->kebabs;
    }
    
    public function addKebab($kebab) {
        $this->kebabs[] = $kebab;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function getPrice() {
        return $this->price;
    }
    
    public function setIdIngredient($idIngredient) {
        $this->idIngredient = $idIngredient;
    }
    
    public function setName($name) {
        $this->name = $name;
    }
    
    public function setPrice($price) {
        $this->price = $price;
    }
    public function getPhoto() {
        return $this->photo;
    }

    public function setPhoto($photo) {
        $this->photo = $photo;
    }

    public function toJson() {
        $data = ['idIngredient' => $this->idIngredient, 'name' => $this->name, 'price' => $this->price, 'photo' => $this->photo, 'allergens' => [], 'kebabs' => []];
    
    
        return json_encode($data);
    }

}