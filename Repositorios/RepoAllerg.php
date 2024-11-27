<?php

class RepoAllerg implements RepoCrud{
    private $conexion;

    public function __construct($conexion){
        $this->conexion = $conexion;
    }
    public function create($allergen) {
        // Implementación del método create
        $name = $allergen->getName();
        $photo = $allergen->getPhoto();
        $stmt = $this->conexion->prepare("SELECT * FROM allergens WHERE name = :name");
        $stmt->execute(["name"=>$name]);

        if ($stmt->rowCount() > 0) {
            echo "El tipo de alergeno ya existe";
            return false;
        } else {
            $stmt = $this->conexion->prepare("INSERT INTO allergens (name, photo) VALUES (:name, :photo)");
            return $stmt->execute(["name" => $name, "photo" => $photo]);
        }
        
    }

    public function update($name, $allergen) {
        // Implementación del método update
        $nameNew = $allergen->getName();
        $photoNew = $allergen->getPhoto();

        $stmt = $this->conexion->prepare("UPDATE allergens SET name = :name, photo = :photo WHERE name = :name");
        $result = $stmt->execute(["name" => $nameNew, "photo" => $photoNew, "name" => $name]);
        
        if ($result) {
            echo "Tipo de alergeno actualizado correctamente";
            return true;
        } else{
            echo "No se ha podido actualizar el tipo de alergeno";
            return false;
        }
    }
    public function updateName($name, $allergen) {
        // Implementación del método update
        $nameNew = $allergen->getName();
        
        $stmt = $this->conexion->prepare("UPDATE allergens SET name = :name WHERE name = :name");
        $result = $stmt->execute(["name" => $nameNew, "name" => $name]);
        
        if ($result) {
            echo "Tipo de alergeno actualizado correctamente";
            return true;
        } else {
            echo "No se ha podido actualizar el tipo de alergeno";
            return false;
        }
    }
    public function updatePhoto($name, $allergen) {
        // Implementación del método update
        $photo = $allergen->getPhoto();

        $stmt = $this->conexion->prepare("UPDATE allergens SET photo = :photo WHERE name = :name");
        $result = $stmt->execute(["photo" => $photo, "name" => $name]);

        if ($result) {
            echo "Ingrediente actualizado correctamente";
            return true;
        } else {
            echo "No se ha podido actualizar el ingrediente";
            return false;
        }
    }

    public function delete($name) {
        // Implementación del método delete
        $stmt = $this->conexion->prepare("DELETE FROM allergens WHERE name = :name");
        $result = $stmt->execute(["name" => $name]);
        
        if ($result) {
            echo "Tipo de alergeno eliminado correctamente";
            return true;
        } else {
            echo "No se ha podido eliminar el tipo de alergeno";
            return false;
        }
    }

    public function getById($name) {
        $stmt = $this->conexion->prepare("SELECT * FROM allergens WHERE name = :name");
        $stmt->execute(["name" => $name]);

        if ($stmt->rowCount() > 0) {
            $fila = $stmt->fetch(PDO::FETCH_ASSOC);
            return new Ingredient($fila['name'], $fila['photo']);
        } else{
            return null;
        }
        // Implementación del método getById
    }

    public function getAll() {
        // Implementación del método getAll
        $stmt = $this->conexion->prepare("SELECT * FROM allergens");
        $stmt->execute();
        
        $allergens = [];

        if ($stmt->rowCount() > 0) {
            while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $allergens[] = new Allergens($fila['idAllergens'],$fila['name'], $fila['photo']);
            }
            return $allergens;
        } else {
            return array();  
            echo "No hay alergenos en la base de datos";
        }
    }

    public function find($criterio) {
        // Implementación del método find
        $stmt = $this->conexion->prepare("SELECT * FROM allergens WHERE name LIKE :criterio");
        $stmt->execute(["criterio" => $criterio]);

        $allergens = [];

        if ($stmt->rowCount() > 0) {
            while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $allergens[] = new Allergens($fila['idAllergens'],$fila['name'], $fila['photo']);
            }
            return $allergens;
        } else {
            return array();  // No hay ingredientes que coincidan con el criterio
            echo "No hay alergenos que coincidan con el criterio";
        }
    }

    public function count() {
        // Implementación del método count
        $stmt = $this->conexion->prepare("SELECT COUNT(*) AS total FROM allergens");
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $fila = $stmt->fetch(PDO::FETCH_ASSOC);
            return $fila['total'];
        } else {
            return 0;  // No hay ingredientes en la base de datos
            echo "No hay ingredientes en la base de datos";
        }
    }
}
