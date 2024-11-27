<?php
class Address {
    public $idAddress;
    public $roadName;
    public $roadNumber;
    public $json;

    // Constructor
    public function __construct($idAddress,$roadName, $roadNumber) {
        $this->idAddress = $idAddress;
        $this->roadName = $roadName;
        $this->roadNumber = $roadNumber;
    }
    
    // Getters
    public function getId() {
        return $this->idAddress;
    }
    
    public function getRoadName() {
        return $this->roadName;
    }
    
    public function getRoadNumber() {
        return $this->roadNumber;
    }
    
    public function getJson() {
        return $this->json;
    }

    // Setters
    public function setId($id) {
        $this->idAddress = $id;
        $this->updateJson();
    }

    public function setRoadName($roadName) {
        $this->roadName = $roadName;
        $this->updateJson();
    }
    
    public function setRoadNumber($roadNumber) {
        $this->roadNumber = $roadNumber;
        $this->updateJson();
    }

    // Método privado para actualizar el JSON
    private function updateJson() {
        $this->json = json_encode([
            'id' => $this->idAddress,
            'roadName' => $this->roadName,
            'roadNumber' => $this->roadNumber,
        ]);
    }
}