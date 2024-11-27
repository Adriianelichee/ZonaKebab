<?php
spl_autoload_register(function($clase) {
    // Obtener el directorio base del proyecto
    $baseDIR = __DIR__;

    // Definir los directorios donde buscar las clases
    $directorios = ['Repositorios', 'Modelos', 'Controladores', 'Vista', 'apiPhp','apiJS', 'img'];

    // Iterar sobre los directorios
    foreach ($directorios as $directorio) {
        $fichero = $baseDIR . DIRECTORY_SEPARATOR . $directorio . DIRECTORY_SEPARATOR . $clase . '.php';
        
        if (file_exists($fichero)) {
            require_once $fichero;
            return;
        }
    }

    // Si no se encuentra el archivo, imprimir un mensaje de error
    echo "No se pudo encontrar la clase: $clase<br>";
});

