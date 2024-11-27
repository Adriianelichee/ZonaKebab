<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../MIautocargador.php';
require_once '../vendor/autoload.php';

header("Content-Type: application/json; charset=UTF-8");

$conexion = Conexion::getConection();
$repoOrders = new RepoOrders($conexion);
$repoUsers = new RepoUser($conexion);

$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? $_GET['id'] : null;

switch($method) {
    case 'GET':
        if ($id) {
            $order = $repoOrders->getById($id);
            if ($order) {
                echo json_encode($order);
            } else {
                http_response_code(404);
                echo json_encode(["message" => "Pedido no encontrado"]);
            }
        } else {
            $orders = $repoOrders->getAll();
            echo json_encode($orders);
        }
        break;

        case 'POST':
            $data = json_decode(file_get_contents("php://input"), true);
            error_log("Datos recibidos: " . print_r($data, true));
        
            // Verificar que todos los campos necesarios estén presentes
            $requiredFields = ['datetime', 'state', 'totalPrice', 'userID', 'orderLines'];
            $missingFields = array_diff($requiredFields, array_keys($data));
        
            if (!empty($missingFields)) {
                http_response_code(400);
                echo json_encode(["message" => "Datos incompletos. Faltan los siguientes campos: " . implode(', ', $missingFields)]);
                break;
            }
        
            $user = $repoUsers->getById($data['userID']);
            if (!$user) {
                http_response_code(404);
                echo json_encode(["message" => "Usuario no encontrado"]);
                break;
            }
        
            try {
        
                $order = new Order(
                    null,
                    $data['datetime'],
                    $data['state'],
                    $data['totalPrice'],
                    $data['userID'],
                    json_encode($user->getAddresses()[0]), // Asumiendo que usamos la primera dirección del usuario
                    json_encode($data['orderLines'])
                );
        
                $newOrder = $repoOrders->create($order);
                if (!$newOrder) {
                    throw new Exception("Error al crear el pedido");
                }
        
                $orderId = $newOrder->getIdOrder();
        
                // Crear las líneas de pedido
                $repoOrderLines = new RepoOrderLine($conexion);
        
                foreach ($data['orderLines'] as $lineData) {
                    $linePrice = floatval($lineData['price']);
                
                    $orderLine = new OrderLine(
                        null,  // orderLineID es null para nuevas líneas de pedido
                        json_encode($lineData['kebabs']),  // stringJson
                        intval($lineData['quantity']),     // quantity
                        $linePrice,                        // price
                        $orderId                           // orderID
                    );
                
                    // Añadir los kebabs a la línea de pedido
                    foreach ($lineData['kebabs'] as $kebabData) {
                        // Crear el array de ingredientes
                        $ingredients = [];
                        if (isset($kebabData['ingredients']) && is_array($kebabData['ingredients'])) {
                            foreach ($kebabData['ingredients'] as $ingredientData) {
                                $ingredient = new Ingredient(
                                    $ingredientData['id'],
                                    $ingredientData['name'],
                                    floatval($ingredientData['price'])  // Asegurarse de que el precio es un número
                                );
                                $ingredients[] = $ingredient;
                            }
                        }
                
                        // Verificar si 'price' está presente, si no, usar un valor predeterminado o calcular
                        $kebabPrice = isset($kebabData['price']) ? floatval($kebabData['price']) : 0;
                
                        // Crear el kebab con los 4 argumentos esperados
                        $kebab = new Kebab($kebabData['id'], $kebabData['name'], $kebabPrice, $ingredients);
                        
                        $orderLine->addKebab($kebab);
                    }
                
                    $newOrderLine = $repoOrderLines->create($orderLine);
                    if (!$newOrderLine) {
                        throw new Exception("Error al crear la línea de pedido");
                    }

                    
                }
        
                http_response_code(201);
                echo json_encode(["message" => "Pedido creado con éxito", "orderId" => $orderId]);
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode(["message" => "Error al procesar el pedido: " . $e->getMessage()]);
            }
            break;

            case 'PUT':
                error_log("Entrando en el caso PUT");
                $data = json_decode(file_get_contents("php://input"), true);
                error_log("Datos recibidos: " . print_r($data, true));
            
                if (isset($data['action']) && $data['action'] === 'updateState' && 
                    isset($data['orderId']) && isset($data['newState'])) {
                    
                    error_log("Actualizando estado del pedido");
                    $orderId = $data['orderId'];
                    $newState = $data['newState'];
                    
                    $existingOrder = $repoOrders->getById($orderId);
                    
                    if ($existingOrder) {
                        error_log("Pedido encontrado, actualizando estado");
                        $existingOrder->setState($newState);
                        
                        $updatedOrder = $repoOrders->update($orderId, $existingOrder);
                        if ($updatedOrder) {
                            error_log("Estado actualizado con éxito");
                            echo json_encode([
                                "success" => true,
                                "message" => "Estado del pedido actualizado con éxito"
                            ]);
                        } else {
                            error_log("Error al actualizar el estado");
                            echo json_encode([
                                "success" => false,
                                "message" => "Error al actualizar el estado del pedido"
                            ]);
                        }
                    } else {
                        error_log("Pedido no encontrado");
                        echo json_encode([
                            "success" => false,
                            "message" => "Pedido no encontrado"
                        ]);
                    }
                } else {
                    // Mantener el código existente para otras actualizaciones
                    if (isset($_GET['id'])) {
                        $id = $_GET['id'];
                        $existingOrder = $repoOrders->getById($id);
                        
                        if ($existingOrder) {
                            if (isset($data['state'])) {
                                $existingOrder->setState($data['state']);
                            }
                            
                            $updatedOrder = $repoOrders->update($id, $existingOrder);
                            if ($updatedOrder) {
                                echo json_encode($updatedOrder);
                            } else {
                                http_response_code(500);
                                echo json_encode(["message" => "Error al actualizar el pedido"]);
                            }
                        } else {
                            http_response_code(404);
                            echo json_encode(["message" => "Pedido no encontrado"]);
                        }
                    } else {
                        http_response_code(400);
                        echo json_encode(["message" => "ID de pedido no proporcionado"]);
                    }
                }
                break;

    case 'DELETE':
        if ($id) {
            if ($repoOrders->delete($id)) {
                http_response_code(204);
            } else {
                http_response_code(500);
                echo json_encode(["message" => "Error al eliminar el pedido"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "ID de pedido no proporcionado"]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["message" => "Método no permitido"]);
        break;
}