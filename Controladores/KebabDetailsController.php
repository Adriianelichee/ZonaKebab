<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Modelos/User.php';
require_once __DIR__ . '/../Repositorios/RepoUser.php';

use League\Plates\Engine;

class KebabDetailsController {
    protected $templates;
    protected $repoUser;
    protected $repoKebab; // Asume que tienes un modelo KebabModel para obtener los kebabs

    public function __construct() {
        // Usar __DIR__ para obtener la ruta absoluta del directorio actual
        $basePath = dirname(__DIR__);
        $templatePath = $basePath . DIRECTORY_SEPARATOR . 'Vista' . DIRECTORY_SEPARATOR . 'Plantillas';
        $this->templates = new Engine($templatePath);

        // Inicializar RepoUser (asumiendo que tienes una clase Conexion)
        $conexion = Conexion::getConection();
        $this->repoKebab = new RepoKebab($conexion);
    }    
    /**
     * Renderiza la página "HOME" con una lista de usuarios.
     *
     * Este método obtiene todos los usuarios de la base de datos utilizando RepoUser,
     * luego renderiza la plantilla 'home' con los datos de los usuarios.
     *
     * @return void Este método no devuelve un valor, sino que imprime directamente la plantilla renderizada.
     */
    public function index($kebabId) {
        $kebab = $this->repoKebab->getById($kebabId);
        if ($kebab) {
            $ingredients = $kebab->getIngredients();
            echo $this->templates->render('KebabDetails', ['kebab' => $kebab, 'ingredients' => $ingredients]);
        } else {
            // Manejar el caso cuando no se encuentra el kebab
            echo "Kebab no encontrado";
        }
    }
}