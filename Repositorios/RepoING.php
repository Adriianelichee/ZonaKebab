<?php

class RepoIng implements RepoCrud{
    private $conexion;
    
    public function __construct($conexion){
        $this->conexion = $conexion;
    }

    //METODOS CRUD
    public function create($ingrediente) {
        $name = $ingrediente->getName();
        $price = $ingrediente->getPrice();
        $photo = $ingrediente->getPhoto();
        $allergens = $ingrediente->getAllergens();
    
        $stmt = $this->conexion->prepare("SELECT * FROM ingredients WHERE name = :name");
        $stmt->execute(["name" => $name]);
    
        if ($stmt->rowCount() > 0) {
            return ["success" => false, "message" => "El ingrediente ya existe"];
        } else {
            $this->conexion->beginTransaction();
    
            try {
                if (isset($photo) && !empty($photo)) {
                    $stmt = $this->conexion->prepare("INSERT INTO ingredients (name, price, photo) VALUES (:name, :price, :photo)");
                    $stmt->execute(["name" => $name, "price" => $price, "photo" => $photo]);
                } else {
                    $stmt = $this->conexion->prepare("INSERT INTO ingredients (name, price) VALUES (:name, :price)");
                    $stmt->execute(["name" => $name, "price" => $price]);
                }
    
                $ingredientId = $this->conexion->lastInsertId();
    
                if (!empty($allergens)) {
                    $stmtAllergen = $this->conexion->prepare("INSERT INTO ingredientsallergens (Ingredients_idIngredients, Allergens_idAlergenos) VALUES (:Ingredients_idIngredients, :Allergens_idAlergenos)");
                    foreach ($allergens as $allergenId) {
                        $stmtAllergen->execute([
                            "Ingredients_idIngredients" => $ingredientId, 
                            "Allergens_idAlergenos" => $allergenId
                        ]);
                    }
                }
    
                $this->conexion->commit();
                return ["success" => true, "message" => "Ingrediente añadido con éxito", "id" => $ingredientId];
            } catch (Exception $e) {
                $this->conexion->rollBack();
                return ["success" => false, "message" => "Error al crear el ingrediente: " . $e->getMessage()];
            }
        }
    }

    public function update($id, $ingrediente) {
        $this->conexion->beginTransaction();
    
        try {
            $stmt = $this->conexion->prepare("UPDATE ingredients SET name = :name, price = :price, photo = :photo WHERE idIngredients = :id");
            $result = $stmt->execute([
                "name" => $ingrediente->getName(),
                "price" => $ingrediente->getPrice(),
                "photo" => $ingrediente->getPhoto(),
                "id" => $id
            ]);
    
            if (!$result) {
                throw new Exception("No se pudo actualizar el ingrediente");
            }
    
            // Actualizar alérgenos
            $stmt = $this->conexion->prepare("DELETE FROM ingredientsallergens WHERE Ingredients_idIngredients = :id");
            $stmt->execute(["id" => $id]);
    
            $allergens = $ingrediente->getAllergens();
            if (!empty($allergens)) {
                $stmtAllergen = $this->conexion->prepare("INSERT INTO ingredientsallergens (Ingredients_idIngredients, Allergens_idAlergenos) VALUES (:ingredientId, :allergenId)");
                foreach ($allergens as $allergenId) {
                    $stmtAllergen->execute([
                        "ingredientId" => $id,
                        "allergenId" => $allergenId
                    ]);
                }
            }
    
            $this->conexion->commit();
            return ["success" => true, "message" => "Ingrediente actualizado correctamente"];
        } catch (Exception $e) {
            $this->conexion->rollBack();
            return ["success" => false, "message" => "Error al actualizar el ingrediente: " . $e->getMessage()];
        }
    }

    public function updatePrice($name, $ingrediente) {
        // Implementación del método update
        $name = $ingrediente->getName();
        $price = $ingrediente->getPrice();

        $stmt = $this->conexion->prepare("UPDATE ingredients SET price = :price WHERE name = :name");
        $result = $stmt->execute(["price" => $price, "name" => $name]);

        if ($result) {
            echo "Ingrediente actualizado correctamente";
            return true;
        } else {
            echo "No se ha podido actualizar el ingrediente";
            return false;
        }
    }
    public function updateName($name, $ingrediente) {
        // Implementación del método update
        $nameNew = $ingrediente->getName();

        $stmt = $this->conexion->prepare("UPDATE ingredients SET name = :name WHERE name = :name");
        $result = $stmt->execute(["name" => $nameNew, "name" => $name]);

        if ($result) {
            echo "Ingrediente actualizado correctamente";
            return true;
        } else {
            echo "No se ha podido actualizar el ingrediente";
            return false;
        }
    }
    public function updatePhoto($name, $ingrediente) {
        // Implementación del método update
        $photo = $ingrediente->getPhoto();

        $stmt = $this->conexion->prepare("UPDATE ingredients SET photo = :photo WHERE name = :name");
        $result = $stmt->execute(["photo" => $photo, "name" => $name]);

        if ($result) {
            echo "Ingrediente actualizado correctamente";
            return true;
        } else {
            echo "No se ha podido actualizar el ingrediente";
            return false;
        }
    }
    
    public function delete($id) {
        $this->conexion->beginTransaction();
    
        try {
            // Primero, eliminamos las relaciones en la tabla intermedia
            $stmt = $this->conexion->prepare("DELETE FROM ingredientsallergens WHERE Ingredients_idIngredients = :id");
            $stmt->execute(["id" => $id]);
    
            // Luego, eliminamos el ingrediente
            $stmt = $this->conexion->prepare("DELETE FROM ingredients WHERE idIngredients = :id");
            $result = $stmt->execute(["id" => $id]);
    
            if (!$result) {
                throw new Exception("No se pudo eliminar el ingrediente");
            }
    
            $this->conexion->commit();
            return ["success" => true, "message" => "Ingrediente eliminado correctamente"];
        } catch (Exception $e) {
            $this->conexion->rollBack();
            return ["success" => false, "message" => "Error al eliminar el ingrediente: " . $e->getMessage()];
        }
    }
    
    public function getById($id) {
        $stmt = $this->conexion->prepare("SELECT * FROM ingredients WHERE idIngredients = :id");
        $stmt->execute(["id" => $id]);
    
        if ($stmt->rowCount() > 0) {
            $fila = $stmt->fetch(PDO::FETCH_ASSOC);
            $ingredient = new Ingredient($fila['name'], $fila['price']);
            $ingredient->setIdIngredient($fila['idIngredients']);
            if (isset($fila['photo'])) {
                $ingredient->setPhoto($fila['photo']);
            }
            return $ingredient;
        } else {
            return null;
        }    
    }
    
    public function getAll() {
        $stmt = $this->conexion->prepare("SELECT * FROM ingredients");
        $stmt->execute();
        
        $ingredientes = [];
    
        if ($stmt->rowCount() > 0) {
            while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Create the Ingredient object with the correct order of parameters
                $ingrediente = new Ingredient($fila['name'], $fila['price']);
                if (isset($fila['photo'])) {
                    $ingrediente->setPhoto($fila['photo']);
                }
                if (isset($fila['idIngredients'])) {
                    $ingrediente->setIdIngredient($fila['idIngredients']);
                }
                $ingredientes[] = $ingrediente;
            }
            return $ingredientes;
        } else {
            return array();  // No hay ingredientes en la base de datos
        }
    }
    
    public function find($criterio) {
        // Implementación del método find
        $stmt = $this->conexion->prepare("SELECT * FROM ingredients WHERE name LIKE :criterio");
        $stmt->execute(["criterio" => $criterio]);

        $ingredientes = [];

        if ($stmt->rowCount() > 0) {
            while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $ingredientes[] = new Ingredient($fila['name'], $fila['price']);
            }
            return $ingredientes;
        } else {
            return array();  // No hay ingredientes que coincidan con el criterio
            echo "No hay ingredientes que coincidan con el criterio";
        }
    }
    
    public function count() {
        // Implementación del método count
        $stmt = $this->conexion->prepare("SELECT COUNT(*) AS total FROM ingredients");
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
