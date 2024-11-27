<?php
require_once '../MIautocargador.php';
require_once '../vendor/autoload.php';

header("Content-Type: application/json; charset=UTF-8");

$conexion = Conexion::getConection();
$repoAllerg = new RepoAllerg($conexion);

$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? $_GET['id'] : null;

switch($method) {
    case 'GET':
        if ($id) {
            $allergen = $repoAllerg->getById($id);
            if ($allergen) {
                echo json_encode($allergen);
            } else {
                http_response_code(404);
                echo json_encode(["message" => "Alérgeno no encontrado"]);
            }
        } else {
            $allergens = $repoAllerg->getAll();
            echo json_encode($allergens);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->name) && !empty($data->photo)) {
            $newAllerg = new Allergens($data->idAllergens,$data->name, $data->photo);
            if ($repoAllerg->create($newAllerg)) {
                http_response_code(201);
                echo json_encode(["message" => "Alérgeno creado"]);
            } else {
                http_response_code(503);
                echo json_encode(["message" => "No se pudo crear el alérgeno"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Datos incompletos"]);
        }
        break;

    case 'PUT':
        if ($id) {
            $data = json_decode(file_get_contents("php://input"));
            if (!empty($data->name) && !empty($data->photo)) {
                $allergen = new Allergens($data->idAllergens,$data->name, $data->photo);
                if ($repoAllerg->update($id,$allergen)) {
                    echo json_encode(["message" => "Alérgeno actualizado"]);
                } else {
                    http_response_code(503);
                    echo json_encode(["message" => "No se pudo actualizar el alérgeno"]);
                }
            } else {
                http_response_code(400);
                echo json_encode(["message" => "Datos incompletos"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "ID de alérgeno no proporcionado"]);
        }
        break;

    case 'DELETE':
        if ($id) {
            if ($repoAllerg->delete($id)) {
                echo json_encode(["message" => "Alérgeno eliminado"]);
            } else {
                http_response_code(503);
                echo json_encode(["message" => "No se pudo eliminar el alérgeno"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "ID de alérgeno no proporcionado"]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["message" => "Método no permitido"]);
        break;
}