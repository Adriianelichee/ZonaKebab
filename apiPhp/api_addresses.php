<?php

require_once '../MIautocargador.php';
require_once '../vendor/autoload.php';

header("Content-Type: application/json; charset=UTF-8");

try {
    $conexion = Conexion::getConection();
    $repoAddress = new RepoDirec($conexion);

    $method = $_SERVER['REQUEST_METHOD'];
    $id = isset($_GET['idAddress']) ? $_GET['idAddress'] : null;
    $userId = isset($_GET['user_Id']) ? $_GET['user_Id'] : null;
    
    switch($method) {
        case 'GET':
            if ($id) {
                // Obtener una dirección específica por ID
                $address = $repoAddress->getById($id);
                if ($address) {
                    echo json_encode($address);
                } else {
                    throw new Exception("Dirección no encontrada.", 404);
                }
            } elseif ($userId) {
                // Obtener todas las direcciones de un usuario específico
                $addresses = $repoAddress->getAddressesByUserId($userId);
                if ($addresses) {
                    echo json_encode($addresses);
                } else {
                    echo json_encode([]);
                }
            } else {
                // Si no se proporciona ni id ni userId, devolver todas las direcciones
                $addresses = $repoAddress->getAll();
                echo json_encode($addresses);
            }
            break;

        case 'POST':
            $data = json_decode(file_get_contents("php://input"));
            if (!empty($data->roadName) && !empty($data->roadNumber) && !empty($data->userId)) {
                $address = new Address(null, $data->roadName, $data->roadNumber);
                if ($repoAddress->createAddressUser($address, $data->userId)) {
                    http_response_code(201);
                    echo json_encode(array("message" => "Dirección creada con éxito."));
                } else {
                    throw new Exception("No se pudo crear la dirección.", 503);
                }
            } else {
                throw new Exception("Datos incompletos.", 400);
            }
            break;

        case 'PUT':
            $data = json_decode(file_get_contents("php://input"));
            if (!empty($data->id) && !empty($data->roadName) && !empty($data->roadNumber)) {
                $address = new Address($data->id, $data->roadName, $data->roadNumber);
                if ($repoAddress->update($data->id, $address)) {
                    echo json_encode(array("message" => "Dirección actualizada con éxito."));
                } else {
                    throw new Exception("No se pudo actualizar la dirección.", 503);
                }
            } else {
                throw new Exception("Datos incompletos.", 400);
            }
            break;

        case 'DELETE':
            if ($id) {
                if ($repoAddress->delete($id)) {
                    echo json_encode(array("message" => "Dirección eliminada con éxito."));
                } else {
                    throw new Exception("No se pudo eliminar la dirección.", 503);
                }
            } else {
                throw new Exception("ID de dirección no proporcionado.", 400);
            }
            break;

        default:
            throw new Exception("Método no permitido.", 405);
    }
} catch (Exception $e) {
    echo json_encode(array(
        "message" => $e->getMessage(),
        "file" => $e->getFile(),
        "line" => $e->getLine()
    ));
    // Log the error
    error_log("Error in api_addresses.php: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());
}