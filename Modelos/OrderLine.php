<?php

class OrderLine {
    private $orderLineID;
    private $stringJson;
    private $quantity;
    private $price;
    private $orderID;
    private $kebabs = [];

    // Constructor
    public function __construct($orderLineID, $stringJson, $quantity, $price, $orderID) {
        $this->orderLineID = $orderLineID;
        $this->stringJson = $stringJson;
        $this->quantity = $quantity;
        $this->price = $price;
        $this->orderID = $orderID;
    }
    
    // GETTERS AND SETTERS
    public function getOrderLineID() {
        return $this->orderLineID;
    }
    
    public function setOrderLineID($orderLineID) {
        $this->orderLineID = $orderLineID;
    }
    
    public function getStringJson() {
        return $this->stringJson;
    }
    
    public function setStringJson($stringJson) {
        $this->stringJson = $stringJson;
    }
    
    public function getQuantity() {
        return $this->quantity;
    }
    
    public function setQuantity($quantity) {
        $this->quantity = $quantity;
    }
    
    public function getPrice() {
        return $this->price;
    }
    
    public function setPrice($price) {
        $this->price = $price;
    }
    
    public function getOrderID() {
        return $this->orderID;
    }
    
    public function setOrderID($orderID) {
        $this->orderID = $orderID;
    }
    
    public function getKebabs() {
        return $this->kebabs;
    }
    
    public function addKebab(Kebab $kebab) {
        $this->kebabs[] = $kebab;
    }
}