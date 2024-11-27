<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Modelos/User.php';
require_once __DIR__ . '/../Repositorios/RepoUser.php';

use League\Plates\Engine;

class DeleteIngredientController {
    protected $templates;
    protected $repoIngredient;

    public function __construct() {
        $basePath = dirname(__DIR__);
        $templatePath = $basePath . DIRECTORY_SEPARATOR . 'Vista' . DIRECTORY_SEPARATOR . 'Plantillas';
        $this->templates = new Engine($templatePath);

        $conexion = Conexion::getConection();
        $this->repoIngredient = new RepoIng($conexion);
    }

    public function index() {
        echo $this->templates->render('DeleteIngredient');
        
    }
    
}