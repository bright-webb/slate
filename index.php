<?php
    require_once __DIR__ . '/vendor/autoload.php';
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    define('APP_ROOT', __DIR__ . '/www'); // www is the folder where all the php files are stored
    $url = $_SERVER['REQUEST_URI'];
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if(preg_match('/\.[A-Za-z0-9]+$/', $path)){
       $path = preg_replace('/\.[A-Za-z0-9]+$/', '', $path);
    }
    
    $file_path = APP_ROOT . $path . '.php';
    if(file_exists($file_path)){
        return require($file_path);
    }
    else{
        if($url === '/'){
            require(APP_ROOT . '/default.php');
        }
        else{
            echo "file not found";
        }

    }
    
?>
