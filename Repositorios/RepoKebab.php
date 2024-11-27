<?php

class RepoKebab implements RepoCrud{
    private $conexion;

    public function __construct($conexion){
        $this->conexion = $conexion;
    }
    public function create($kebab) {
        $this->conexion->beginTransaction();
        try {
            // Insertar el kebab
            $stmt = $this->conexion->prepare("INSERT INTO kebab (name, photo, basePrice) VALUES (:name, :photo, :basePrice)");
            $nombre = $kebab->getName();
            $foto = $kebab->getPhoto();
            $basePrice = $kebab->getBasePrice();
            $result = $stmt->execute(["name" => $nombre, "photo" => $foto, "basePrice" => $basePrice]);
    
            if (!$result) {
                throw new Exception("Error al insertar el kebab");
            }

    
            $kebabId = $this->conexion->lastInsertId();
            $kebab->setIdKebab($kebabId);
    
            // Insertar los ingredientes del kebab
            $stmt = $this->conexion->prepare("INSERT INTO ingredientsKebab (Ingredients_idIngredients, Kebab_idKebab) VALUES (:Ingredients_idIngredients, :Kebab_idKebab)");
            $ingredients = $kebab->getIngredients();
            foreach ($ingredients as $ingredient) {
                $result = $stmt->execute(["Ingredients_idIngredients" => $ingredient->getIdIngredient(), "Kebab_idKebab" => $kebabId]);
                if (!$result) {
                    throw new Exception("Error al insertar ingrediente para el kebab");
                }
            }
    
            $this->conexion->commit();
            return true;
        } catch (Exception $e) {
            $this->conexion->rollBack();
            error_log("Error al crear kebab: " . $e->getMessage());
            return ["success" => false, "error" => $e->getMessage()];
        }
    }
    public function update($id, $kebab) {
        $this->conexion->beginTransaction();
    
        try {
            // Logging para depuración
            error_log("Actualizando kebab con ID: " . $id);
            error_log("Nombre del kebab: " . $kebab->getName());
            error_log("Precio base: " . $kebab->getBasePrice());
            error_log("Foto: " . $kebab->getPhoto());
    
            // Actualizar la información básica del kebab
            $stmt = $this->conexion->prepare("UPDATE kebab SET name = :name, basePrice = :basePrice, photo = :photo WHERE idKebab = :id");
            $result = $stmt->execute([
                "id" => $id,
                "name" => $kebab->getName(),
                "basePrice" => $kebab->getBasePrice(),
                "photo" => $kebab->getPhoto()
            ]);
    
            if (!$result) {
                throw new Exception("Error al actualizar la información básica del kebab");
            }
    
            // Eliminar los ingredientes existentes
            $stmt = $this->conexion->prepare("DELETE FROM ingredientsKebab WHERE Kebab_idKebab = :kebab_id");
            $result = $stmt->execute(["kebab_id" => $id]);
    
            if (!$result) {
                throw new Exception("Error al eliminar los ingredientes existentes del kebab");
            }
    
            // Insertar los nuevos ingredientes
            $stmt = $this->conexion->prepare("INSERT INTO ingredientsKebab (Ingredients_idIngredients, Kebab_idKebab) VALUES (:ingredient_id, :kebab_id)");
            
            $ingredients = $kebab->getIngredients();
            error_log("Número de ingredientes a insertar: " . count($ingredients));
    
            foreach ($ingredients as $ingredient) {
                error_log("Insertando ingrediente: " . $ingredient->getName() . " (ID: " . $ingredient->getIdIngredient() . ")");
                
                $result = $stmt->execute([
                    "kebab_id" => $id,
                    "ingredient_id" => $ingredient->getIdIngredient()
                ]);
    
                if (!$result) {
                    throw new Exception("Error al insertar el ingrediente " . $ingredient->getName());
                }
            }
    
            $this->conexion->commit();
            error_log("Actualización del kebab completada con éxito");
            return true;
        } catch (Exception $e) {
            $this->conexion->rollBack();
            error_log("Error en RepoKebab::update: " . $e->getMessage());
            return false;
        }
    }

    public function delete($id) {
        try {
            $this->conexion->beginTransaction();
    
            // Eliminar los ingredientes asociados al kebab
            $stmtIngredients = $this->conexion->prepare("DELETE FROM ingredientsKebab WHERE Kebab_idKebab = :id");
            $resultIngredients = $stmtIngredients->execute(["id" => $id]);
            
            if (!$resultIngredients) {
                throw new Exception("Error al eliminar los ingredientes del kebab");
            }
    
            // Eliminar el kebab
            $stmtKebab = $this->conexion->prepare("DELETE FROM kebab WHERE idKebab = :id");
            $resultKebab = $stmtKebab->execute(["id" => $id]);
            
            if (!$resultKebab) {
                throw new Exception("Error al eliminar el kebab");
            }
    
            $this->conexion->commit();
            return ["success" => true, "message" => "Kebab y sus ingredientes eliminados correctamente"];
        } catch (Exception $e) {
            $this->conexion->rollBack();
            error_log("Error en RepoKebab::delete: " . $e->getMessage());
            return ["success" => false, "error" => $e->getMessage()];
        }
    }

    public function getById($id) {
        // Obtener información básica del kebab
        $stmt = $this->conexion->prepare("SELECT * FROM kebab WHERE idKebab = :id");
        $stmt->execute(["id" => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$result) {
            echo "Kebab no encontrado";
            return null;
        }
        
        // Crear instancia de Kebab con los datos obtenidos
        $kebab = new Kebab($result['idKebab'], $result['name'], $result['basePrice'], $result['photo']);
        
        // Obtener los ingredientes del kebab
        $stmt = $this->conexion->prepare("SELECT i.* FROM ingredients i JOIN ingredientsKebab ik ON i.idIngredients = ik.Ingredients_idIngredients WHERE ik.Kebab_idKebab = :kebab_id");
        $stmt->execute(["kebab_id" => $id]);
        $ingredients = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Añadir los ingredientes al kebab
        foreach ($ingredients as $ingredientData) {
            $ingredient = new Ingredient(
                $ingredientData['name'],
                $ingredientData['price']
            );
            $ingredient->setIdIngredient($ingredientData['idIngredients']);
            if (isset($ingredientData['photo'])) {
                $ingredient->setPhoto($ingredientData['photo']);
            }
            $kebab->addIngredient($ingredient);
        }
        
        return $kebab;
    }

    public function getAll() {
        $kebabs = [];
    
        // Obtener todos los kebabs
        $stmt = $this->conexion->prepare("SELECT * FROM kebab");
        $stmt->execute();
        $kebabsData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        foreach ($kebabsData as $kebabData) {
            // Crear instancia de Kebab
            $kebab = new Kebab($kebabData['idKebab'], $kebabData['name'], $kebabData['basePrice'], $kebabData['photo']);    
            // Obtener los ingredientes para este kebab
            $stmtIngredients = $this->conexion->prepare("SELECT i.* FROM ingredients i JOIN ingredientsKebab ik ON i.idIngredients = ik.Ingredients_idIngredients WHERE ik.Kebab_idKebab = :kebab_id");
            $stmtIngredients->execute(['kebab_id' => $kebabData['idKebab']]);
            $ingredientsData = $stmtIngredients->fetchAll(PDO::FETCH_ASSOC);
    
            // Añadir ingredientes al kebab
            foreach ($ingredientsData as $ingredientData) {
                $ingredient = new Ingredient(
                    $ingredientData['name'],
                    $ingredientData['price']
                );
                $ingredient->setIdIngredient($ingredientData['idIngredients']);
                if (isset($ingredientData['photo'])) {
                    $ingredient->setPhoto($ingredientData['photo']);
                }
                $kebab->addIngredient($ingredient);
            }
    
    
            // Añadir el kebab completo al array de kebabs
            $kebabs[] = $kebab;
        }
    
        return $kebabs;
    }

    public function find($criterio) {
        $kebabs = [];
    
        // Preparar la consulta SQL para buscar kebabs por nombre
        $stmt = $this->conexion->prepare("SELECT * FROM kebab WHERE name LIKE :criterio");
        $stmt->execute(['criterio' => '%' . $criterio . '%']);
        $kebabsData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        foreach ($kebabsData as $kebabData) {
            // Crear instancia de Kebab
            $kebab = new Kebab($kebabData['idKebab'], $kebabData['name'], $kebabData['basePrice'], $kebabData['photo']);
    
            // Obtener los ingredientes para este kebab
            $stmtIngredients = $this->conexion->prepare("SELECT i.* FROM ingredients i JOIN ingredientsKebab ik ON i.idIngredients = ik.Ingredients_idIngredients WHERE ik.Kebab_idKebab = :kebab_id");
            $stmtIngredients->execute(['kebab_id' => $kebabData['idKebab']]);
            $ingredientsData = $stmtIngredients->fetchAll(PDO::FETCH_ASSOC);
    
            // Añadir ingredientes al kebab
            foreach ($ingredientsData as $ingredientData) {
                error_log("Ingredient data: " . print_r($ingredientData, true));

                $ingredient = new Ingredient(
                    $ingredientData['name'],
                    $ingredientData['price']
                );
                $ingredient->setIdIngredient($ingredientData['idIngredients']);
                if (isset($ingredientData['photo'])) {
                    $ingredient->setPhoto($ingredientData['photo']);
                }
                $kebab->addIngredient($ingredient);
            }
    
    
            // Añadir el kebab completo al array de kebabs
            $kebabs[] = $kebab;
        }
        return $kebabs;
    }

    public function count() {
        // Implementación del método count
        $stmt = $this->conexion->prepare("SELECT COUNT(*) AS total FROM kebab");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
        return 0; // Devuelve 0 si no hay kebabs en la base de datos
    }
}
