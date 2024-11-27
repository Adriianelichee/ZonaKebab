<?php

require_once '../MIautocargador.php';
require_once '../vendor/autoload.php';

header("Content-Type: application/json; charset=UTF-8");


$conexion = Conexion::getConection();
$repoUser = new RepoUser($conexion);

$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? $_GET['id'] : null;
$action = isset($_GET['action']) ? $_GET['action'] : null;
$actionPost = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : null);

switch($method) {
    case 'GET':
        if ($id) {
            $user = $repoUser->getById($id);
            if ($user) {
                echo json_encode([
                    "id" => $user->getUserID(),
                    "username" => $user->getUsername(),
                    "email" => $user->getEmail(),
                    "phone" => $user->getPhone(),
                    "wallet" => $user->getMonedero(),
                    // Incluye otros campos necesarios
                ]);
            } else {
                http_response_code(404);
                echo json_encode(["message" => "Usuario no encontrado."]);
            }
        }
        break;
    
        case 'POST':
            $data = json_decode(file_get_contents("php://input"));
            error_log(print_r($data, true));
            $actionPost = isset($data->action) ? $data->action : null;
        
            if ($actionPost === 'addToCart') {
                if (!empty($data->userId) && !empty($data->product)) {
                    $user = $repoUser->getById($data->userId);
                    if ($user) {
                        $cart = $user->getCart();
                        $cart[] = $data->product;
                        $user->setCart($cart);
                        if ($repoUser->update($data->userId, $user)) {
                            http_response_code(200);
                            echo json_encode(array("message" => "Producto añadido al carrito."));
                        } else {
                            http_response_code(500);
                            echo json_encode(array("message" => "No se pudo añadir el producto al carrito."));
                        }
                    } else {
                        http_response_code(404);
                        echo json_encode(array("message" => "Usuario no encontrado."));
                    }
                } else {
                    http_response_code(400);
                    echo json_encode(array("message" => "Datos incompletos."));
                }
            } elseif ($actionPost === 'removeFromCart') {
                if (!empty($data->userId) && isset($data->productIndex)) {
                    $user = $repoUser->getById($data->userId);
                    if ($user) {
                        $cart = $user->getCart();
                        if (isset($cart[$data->productIndex])) {
                            unset($cart[$data->productIndex]);
                            $cart = array_values($cart);  // Reindexar el array
                            $user->setCart($cart);
                            if ($repoUser->update($data->userId, $user)) {
                                http_response_code(200);
                                echo json_encode(array("message" => "Producto eliminado del carrito."));
                            } else {
                                http_response_code(500);
                                echo json_encode(array("message" => "No se pudo eliminar el producto del carrito."));
                            }
                        } else {
                            http_response_code(404);
                            echo json_encode(array("message" => "Producto no encontrado en el carrito."));
                        }
                    } else {
                        http_response_code(404);
                        echo json_encode(array("message" => "Usuario no encontrado."));
                    }
                } else {
                    http_response_code(400);
                    echo json_encode(array("message" => "Datos incompletos."));
                }
            } elseif ($actionPost === 'login') {
                error_log("Login action detected");
                $rawData = file_get_contents("php://input");
                error_log("Raw data received: " . $rawData);
                
                $data = json_decode($rawData);
                error_log("Decoded data: " . print_r($data, true));
                
                if (empty($data->email) || empty($data->password)) {
                    error_log("Email or password missing. Email: " . ($data->email ?? 'not present') . ", Password: " . (empty($data->password) ? 'empty' : 'present'));
                    http_response_code(400);
                    echo json_encode(array("success" => false, "message" => "Email and password are required"));
                    return;
                }
            
                // Intentar obtener el usuario por email
                $user = $repoUser->getUserByEmail($data->email);
                
                if ($user) {
                    // Verificar la contraseña
                    if (password_verify($data->password, $user->getPassword())) {
                        http_response_code(200);
                        echo json_encode(array(
                            "success" => true,
                            "message" => "Login exitoso",
                            "user" => array(
                                "id" => $user->getUserID(),
                                "username" => $user->getUsername(),
                                "email" => $user->getEmail(),
                                "phone" => $user->getPhone()
                            )
                        ));
                    } else {
                        http_response_code(401);
                        echo json_encode(array("success" => false, "message" => "Contraseña incorrecta"));
                    }
                } else {
                    http_response_code(404);
                    echo json_encode(array("success" => false, "message" => "Usuario no encontrado"));
                }
            } elseif ($actionPost === 'updateWallet') {
                $data = json_decode(file_get_contents("php://input"));
                if (!empty($data->userId) && isset($data->amount)) {
                    $result = $repoUser->updateWallet($data->userId, $data->amount);
                    if ($result['success']) {
                        http_response_code(200);
                        echo json_encode(array(
                            "success" => true,
                            "message" => "Monedero actualizado con éxito.",
                            "newWallet" => $result['newWallet']
                        ));
                    } else {
                        http_response_code(500);
                        echo json_encode(array(
                            "success" => false,
                            "message" => $result['message']
                        ));
                    }
                } else {
                    http_response_code(400);
                    echo json_encode(array(
                        "success" => false,
                        "message" => "Datos incompletos."
                    ));
                }
            } else {
                // Código existente para crear un nuevo usuario
                if (!empty($data->username) && !empty($data->password) && !empty($data->email)) {
                    $user = new User(null, $data->username, $data->password, $data->rol ?? 'user', $data->email, $data->phone);
                    if (isset($data->addresses) && is_array($data->addresses)) {
                        foreach ($data->addresses as $addr) {
                            $address = new Address(null, $addr->roadName, $addr->roadNumber);
                            $user->addAddress($address);
                        }
                    }
                    
                    if ($repoUser->create($user)) {
                        $newUserId = $repoUser->getLastInsertId();
                        http_response_code(201);
                        echo json_encode(array(
                            "message" => "Usuario creado con éxito.",
                            "user" => array("id" => $newUserId)
                        ));
                    } else {
                        http_response_code(503);
                        echo json_encode(array("message" => "No se pudo crear el usuario."));
                    }
                } else {
                    http_response_code(400);
                    echo json_encode(array("message" => "Datos incompletos."));
                }
            }
            break;
    
    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        if(!empty($data->id) && !empty($data->username) && !empty($data->password) && !empty($data->email)) {
            $user = new User($data->id, $data->username, $data->password, $data->rol ?? 'user', $data->email, $data->phone);
            if(isset($data->addresses) && is_array($data->addresses)) {
                foreach($data->addresses as $addr) {
                    $address = new Address($addr->id ?? null, $addr->roadName, $addr->roadNumber);
                    $user->addAddress($address);
                }
            }
            if($repoUser->update($data->id, $user)) {
                http_response_code(200);
                echo json_encode(array("message" => "Usuario actualizado con éxito."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "No se pudo actualizar el usuario."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Datos incompletos."));
        }
        break;
    
    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        if(!empty($data->id)) {
            if($repoUser->delete($data->id)) {
                http_response_code(200);
                echo json_encode(array("message" => "Usuario eliminado con éxito."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "No se pudo eliminar el usuario."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "ID de usuario no proporcionado."));
        }
        break;
    
    default:
        http_response_code(405);
        echo json_encode(array("message" => "Método no permitido."));
        break;
    }