<?php
require_once 'MIautocargador.php';
require_once 'vendor/autoload.php';
// Incluye aquí otros controladores que necesites

// Obtén la ruta de la URL
$request_uri = $_SERVER['REQUEST_URI'];


// Enrutamiento básico
if (strpos($request_uri, '/kebab-detail/') === 0) {
    // Extraer el ID del kebab de la URL
    $parts = explode('/', $request_uri);
    $kebabId = end($parts);
    
    $controller = new KebabDetailsController();
    $controller->index($kebabId);
} else {
    switch ($request_uri) {
    case '/':
        $controller = new HomeController();
        $controller->index();
        break;
    
    case '/dashboard/add-kebab':
        $controller = new AddKebabController();
        $controller->index();
        break;

    case '/dashboard/edit-kebab':
        $controller = new EditKebabController();
        $controller->index();
        break;

    case '/dashboard/delete-kebab':
        $controller = new DeleteKebabController();
        $controller->index();
        break;
     case '/dashboard/add-ingredient':
        $controller = new AddIngredientController();
        $controller->index();
        break;

    case '/dashboard/edit-ingredient':
        $controller = new EditIngredientController();
        $controller->index();
        break;

    case '/dashboard/delete-ingredient':
        $controller = new DeleteIngredientController();
        $controller->index();
        break;
    case '/carrito':
        $controller = new CartController();
        $controller->index();
        break;
    case '/kebab-create':
        $controller = new KebabDetailsNewController();
        $controller->index();
        break;

    case '/carta-kebab':
        $controller = new CartaKebabController();
        $controller->index();
        break;

    case '/info-alergenos':
        $controller = new InfoAlergenosController();
        $controller->index();
        break;
    case '/contacto':
        $controller = new ContactoController();
        $controller->index();
        break;

    case '/dashboard/see-orders':
        $controller = new SeeOrdersController();
        $controller->index();
        break;

    case '/admin-panel':

    default:
        // Manejo de 404 Not Found
        header("HTTP/1.0 404 Not Found");
        echo "404 Not Found";
        break;
    }
}