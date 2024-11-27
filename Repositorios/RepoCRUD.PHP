<?php

//TERMINADO
interface RepoCrud{
    // Create
    public function create($obj);

    // Read
    public function getById($id);
    public function getAll();

    // Update
    public function update($id, $obj);

    // Delete
    public function delete($id);

    public function find($criterio);
    public function count();
    
}