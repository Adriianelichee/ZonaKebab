<?php
require_once '../MIautocargador.php';
require_once '../vendor/autoload.php';

header("Content-Type: application/json; charset=UTF-8");

$conexion = Conexion::getConection();
$repoIng = new RepoIng($conexion);

$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? $_GET['id'] : null;

switch($method) {
    case 'GET':
        if ($id) {
            $ingredient = $repoIng->getById($id);
            if ($ingredient) {
                echo $ingredient->toJson();
            } else {
                echo json_encode(["message" => "Ingrediente no encontrado"]);
            }
        } else {
            $ingredients = $repoIng->getAll();
            $jsonArray = [];
            foreach ($ingredients as $ingredient) {
                $jsonArray[] = json_decode($ingredient->toJson());
                
            }
            echo json_encode($jsonArray);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['name']) && isset($data['price'])) {
            $ingredient = new Ingredient($data['name'], $data['price']);
            if (isset($data['photo'])) {
                $ingredient->setPhoto($data['photo']);
            }
            if (isset($data['allergens']) && is_array($data['allergens'])) {
                foreach ($data['allergens'] as $allergenId) {
                    $ingredient->addAllergen($allergenId);
                }
            }
            $result = $repoIng->create($ingredient);
            echo json_encode(["message" => "Ingredient created", "success" => $result]);
        } else {
            echo json_encode(["error" => "Invalid data"]);
        }
        break;

    case 'PUT':
        if ($id) {
            $data = json_decode(file_get_contents("php://input"), true);
            if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
                echo json_encode(["success" => false, "message" => "Invalid JSON data"]);
                break;
            }
    
            if (isset($data['name']) && isset($data['price'])) {
                $ingredient = new Ingredient($data['name'], $data['price']);
                $ingredient->setIdIngredient($id);  // Asegúrate de establecer el ID
    
                if (isset($data['photo'])) {
                    $ingredient->setPhoto($data['photo']);
                }
    
                if (isset($data['allergens'])) {
                    $allergens = json_decode($data['allergens'], true);
                    foreach ($allergens as $allergenId) {
                        $ingredient->addAllergen($allergenId);
                    }
                }
    
                $result = $repoIng->update($id, $ingredient);
                
                if ($result) {
                    echo json_encode(["success" => true, "message" => "Ingredient updated successfully"]);
                } else {
                    echo json_encode(["success" => false, "message" => "Failed to update ingredient"]);
                }
            } else {
                echo json_encode(["success" => false, "message" => "Name and price are required"]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "ID is required"]);
        }
        break;

    case 'DELETE':
        if ($id) {
            try {
                // Primero, obtenemos el ingrediente para devolver su información si se elimina con éxito
                $ingredient = $repoIng->getById($id);
                if (!$ingredient) {
                    echo json_encode([
                        "success" => false,
                        "message" => "Ingrediente no encontrado"
                    ]);
                    break;
                }

                // Llamamos al método delete que ya maneja la eliminación de relaciones
                $result = $repoIng->delete($id);

                if ($result) {
                    echo json_encode([
                        "success" => true,
                        "message" => "Ingrediente eliminado correctamente",
                        "deletedIngredient" => json_decode($ingredient->toJson())
                    ]);
                } else {
                    echo json_encode([
                        "success" => false,
                        "message" => "Error al eliminar el ingrediente"
                    ]);
                }
            } catch (Exception $e) {
                echo json_encode([
                    "success" => false,
                    "message" => "Error al eliminar el ingrediente: " . $e->getMessage()
                ]);
            }
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Se requiere un ID para eliminar"
            ]);
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(["error" => "Method not allowed"]);
        break;
}