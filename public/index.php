<?php
require_once '../config/db.php'; 
require_once '../app/controllers/TheatreController.php'; 
require_once '../app/controllers/DescriptionController.php'; 

$url = isset($_GET['url']) ? $_GET['url'] : 'home/index';
$parts = explode('/', $url);
$controllerName = ucfirst($parts[0]) . 'Controller';
$methodName = isset($parts[1]) ? $parts[1] : 'index';


$controllerFile = '../app/controllers/' . $controllerName . '.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    
    // Ensure the controller class exists
    if (class_exists($controllerName)) {
        $controller = new $controllerName();
        
        // Ensure the method exists in the controller
        if (method_exists($controller, $methodName)) {
            // Check if the method requires an ID parameter
            if (isset($_GET['id']) && $methodName == 'description') {
                $id = $_GET['id'];
                $controller->$methodName($id);
            } else {
                $controller->$methodName();
            }
        } else {
            echo "Method $methodName not found in $controllerName";
        }
    } else {
        echo "Class $controllerName not found";
    }
} else {
    echo "Controller file $controllerFile not found";
}
?>