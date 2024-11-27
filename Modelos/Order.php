<?php

class Order {
    private $idOrder;
    private $datetime;
    private $state;
    private $totalPrice;
    private $userID;
    private $orderLines; 

    public function __construct($idOrder, $datetime, $state, $totalPrice, $userID) {
        $this->idOrder = $idOrder;
        $this->datetime = $datetime;
        $this->state = $state;
        $this->totalPrice = $totalPrice;
        $this->userID = $userID;
        $this->orderLines = [];
    }

    //GETTERS AND SETTERS

    public function addOrderLine($orderLine) {
        $this->orderLines[] = $orderLine;
    }

    public function getOrderLines() {
        return $this->orderLines;
    }
    
    public function getIdOrder() {
        return $this->idOrder;
    }

    
    public function setIdOrder($idOrder) {
        $this->idOrder = $idOrder;
    }
    
    public function getDatetime() {
        return $this->datetime;
    }
    
    public function setDatetime($datetime) {
        $this->datetime = $datetime;
    }
    
    public function getState() {
        return $this->state;
    }
    
    public function setState($state) {
        $this->state = $state;
    }
    
    public function getTotalPrice() {
        return $this->totalPrice;
    }
    
    public function setTotalPrice($totalPrice) {
        $this->totalPrice = $totalPrice;
    }
    
    public function getUserID() {
        return $this->userID;
    }
    
    public function setUserID($userID) {
        $this->userID = $userID;
    }

}