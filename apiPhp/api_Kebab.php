<?php
require_once '../MIautocargador.php';
require_once '../vendor/autoload.php';

header("Content-Type: application/json; charset=UTF-8");

$conexion = Conexion::getConection();
$repoKebab = new RepoKebab($conexion);

$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? $_GET['id'] : null;

switch($method) {
    case 'GET':
        $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
    
        if ($id) {
            $kebab = $repoKebab->getById($id);
            if ($kebab) {
                echo json_encode([
                    'id' => $kebab->getIdKebab(),
                    'name' => $kebab->getName(),
                    'photo' => $kebab->getPhoto(),
                    'basePrice' => $kebab->getBasePrice(),
                    'ingredients' => array_map(function($ingredient) {
                        return [
                            'id' => $ingredient->getIdIngredient(),
                            'name' => $ingredient->getName(),
                            'price' => $ingredient->getPrice(),
                            'photo' => $ingredient->getPhoto()
                        ];
                    }, $kebab->getIngredients())
                ]);
            } else {
                echo json_encode(["message" => "Kebab no encontrado"]);
            }
        } else {
            $kebabs = $searchTerm ? $repoKebab->find($searchTerm) : $repoKebab->getAll();
            $jsonArray = array_map(function($kebab) {
                return [
                    'id' => $kebab->getIdKebab(),
                    'name' => $kebab->getName(),
                    'photo' => $kebab->getPhoto(),
                    'basePrice' => $kebab->getBasePrice(),
                    'ingredients' => array_map(function($ingredient) {
                        return [
                            'id' => $ingredient->getIdIngredient(),
                            'name' => $ingredient->getName(),
                            'price' => $ingredient->getPrice(),
                            'photo' => $ingredient->getPhoto()
                        ];
                    }, $kebab->getIngredients())
                ];
            }, $kebabs);
            echo json_encode($jsonArray);
        }
        break;

        case 'POST':
            // Obtener el cuerpo de la solicitud JSON
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
        
            // Logging para depuración
            error_log("Received POST data: " . print_r($data, true));
        
            $name = $data['kebab-title'] ?? null;
            $basePrice = $data['kebab-price'] ?? null;
            $photo = $data['photo'] ?? null;
            $ingredients = $data['ingredients'] ?? [];
        
            if ($name && $basePrice && $photo) {
                try {
                    // Convertir el precio a float
                    $basePrice = floatval($basePrice);
        
                    // Eliminar el prefijo base64 de la foto
                    $photo = preg_replace('/^data:image\/\w+;base64,/', '', $photo);
        
                    // Crear un nuevo objeto Kebab
                    $kebab = new Kebab(null, $name, $basePrice, $photo);
        
                    // Añadir ingredientes
                    $repoIngredient = new RepoIng($conexion);
                    if (!empty($ingredients)) {
                        foreach ($ingredients as $ingredientId) {
                            $ingredientId = intval($ingredientId);
                            error_log("Ingredient ID: " . $ingredientId);
                            $ingredient = $repoIngredient->getById($ingredientId);
                            if ($ingredient) {
                                $kebab->addIngredient($ingredient);
                            } else {
                                error_log("Ingrediente con ID $ingredientId no encontrado.");
                            }
                        }
                    }
        
                    $result = $repoKebab->create($kebab);
                    if ($result) {
                        echo json_encode([
                            "message" => "Kebab creado", 
                            "success" => true,
                            "id" => $kebab->getIdKebab()
                        ]);
                    } else {
                        echo json_encode([
                            "error" => "Error al crear el kebab", 
                            "success" => false
                        ]);
                    }
                } catch (Exception $e) {
                    error_log("Error creating kebab: " . $e->getMessage());
                    echo json_encode([
                        "error" => "Error interno al crear el kebab: " . $e->getMessage(), 
                        "success" => false
                    ]);
                }
            } else {
                echo json_encode([
                    "error" => "Datos erróneos", 
                    "success" => false
                ]);
            }
            break;

            case 'PUT':
                error_log("PUT request received. ID: " . $id);
                error_log("Request body: " . file_get_contents("php://input"));
                if ($id) {
                    $data = json_decode(file_get_contents("php://input"), true);
                    error_log("Decoded data: " . print_r($data, true));
            
                    $kebab = $repoKebab->getById($id);
            
                    if (!$kebab) {
                        http_response_code(404);
                        echo json_encode(["error" => "Kebab no encontrado", "success" => false]);
                        break;
                    }
            
                    // Actualizar los campos del kebab
                    if (isset($data['name'])) {
                        $kebab->setName($data['name']);
                    }
                    if (isset($data['basePrice'])) {
                        $kebab->setBasePrice($data['basePrice']);
                    }
                    if (isset($data['photo'])) {
                        $kebab->setPhoto($data['photo']);
                    }
            
                    // Actualizar ingredientes
                    if (isset($data['ingredients'])) {
                        $ingredientIds = json_decode($data['ingredients'], true);
                        error_log("Ingredientes recibidos: " . print_r($ingredientIds, true));
            
                        $kebab->clearIngredients();
                        $repoIngredient = new RepoIng($conexion);
                        foreach ($ingredientIds as $ingredientId) {
                            $ingredient = $repoIngredient->getById($ingredientId);
                            if ($ingredient) {
                                $kebab->addIngredient($ingredient);
                                error_log("Ingrediente añadido al kebab: " . $ingredient->getName());
                            } else {
                                error_log("Ingrediente no encontrado: " . $ingredientId);
                            }
                        }
                        error_log("Total de ingredientes añadidos: " . count($kebab->getIngredients()));
                    }
            
                    try {
                        $result = $repoKebab->update($id, $kebab);
                        if ($result) {
                            error_log("Kebab actualizado con éxito. ID: " . $kebab->getIdKebab());
                            echo json_encode([
                                "message" => "Kebab actualizado con éxito",
                                "success" => true,
                                "kebab" => [
                                    "id" => $kebab->getIdKebab(),
                                    "name" => $kebab->getName(),
                                    "basePrice" => $kebab->getBasePrice(),
                                    "photo" => $kebab->getPhoto(),
                                    "ingredients" => array_map(function($ing) {
                                        return [
                                            "id" => $ing->getIdIngredient(),
                                            "name" => $ing->getName()
                                        ];
                                    }, $kebab->getIngredients())
                                ]
                            ]);
                        } else {
                            error_log("Error al actualizar el kebab. ID: " . $id);
                            http_response_code(500);
                            echo json_encode(["error" => "Error al actualizar el kebab", "success" => false]);
                        }
                    } catch (Exception $e) {
                        error_log("Excepción al actualizar el kebab: " . $e->getMessage());
                        http_response_code(500);
                        echo json_encode(["error" => "Error interno al actualizar el kebab: " . $e->getMessage(), "success" => false]);
                    }
                } else {
                    error_log("No ID provided in PUT request");
                    http_response_code(400);
                    echo json_encode(["error" => "Se requiere ID para actualizar", "success" => false]);
                }
                break;

       case 'DELETE':
            if ($id) {
                try {
                    error_log("Intentando eliminar kebab con ID: " . $id);
                    $result = $repoKebab->delete($id);
                    error_log("Resultado de la eliminación: " . ($result ? "true" : "false"));
                    if ($result) {
                        http_response_code(200);
                        echo json_encode(["message" => "Kebab eliminado con éxito", "success" => true]);
                    } else {
                        http_response_code(404);
                        echo json_encode(["error" => "No se pudo eliminar el kebab. Es posible que no exista.", "success" => false]);
                    }
                } catch (Exception $e) {
                    error_log("Excepción al eliminar kebab: " . $e->getMessage());
                    http_response_code(500);
                    echo json_encode(["error" => "Error interno al eliminar el kebab: " . $e->getMessage(), "success" => false]);
                }
            } else {
                http_response_code(400);
                echo json_encode(["error" => "Se requiere ID para eliminar", "success" => false]);
            }
            break;

    default:
        http_response_code(404);
        echo json_encode(["error" => "Metodo no soportado"]);
        break;
}