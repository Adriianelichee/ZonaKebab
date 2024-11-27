<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Modelos/User.php';
require_once __DIR__ . '/../Repositorios/RepoUser.php';

use League\Plates\Engine;

class SeeOrdersController {
    protected $templates;
    protected $repoUser;
    protected $repoOrders;

    public function __construct() {
        // Usar __DIR__ para obtener la ruta absoluta del directorio actual
        $basePath = dirname(__DIR__);
        $templatePath = $basePath . DIRECTORY_SEPARATOR . 'Vista' . DIRECTORY_SEPARATOR . 'Plantillas';
        $this->templates = new Engine($templatePath);

        // Inicializar conexión y repositorios
        $conexion = Conexion::getConection();
        $this->repoOrders = new RepoOrders($conexion);
    }    

    /**
     * Renderiza la página "SeeOrders" con una lista de pedidos.
     *
     * Este método obtiene todos los pedidos de la base de datos utilizando RepoOrders,
     * luego renderiza la plantilla 'SeeOrders' con los datos de los pedidos.
     *
     * @return void Este método no devuelve un valor, sino que imprime directamente la plantilla renderizada.
     */
    public function index() {
        // Obtener todos los pedidos
        $orders = $this->repoOrders->getAll();

        // Renderizar la plantilla con los datos de los pedidos
        echo $this->templates->render('SeeOrders', ['orders' => $orders]);
    }
}