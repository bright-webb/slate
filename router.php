<?php
    define('APP_ROOT', __DIR__ . '/www');

    if(file_exists(APP_ROOT . $_SERVER['REQUEST_URI'])){
        return false;
    }
    
    $_SERVER['REQUEST_URI'] = '/www' . $_SERVER['REQUEST_URI'];
    
    if(is_dir(APP_ROOT . $_SERVER['REQUEST_URI'])){
        $_SERVER['REQUEST_URI'] .= '/default';
    }

    if(pathinfo($_SERVER['REQUEST_URI'], PATHINFO_EXTENSION) === ''){
        $_SERVER['REQUEST_URI'] .= '.php';
    }

    if(file_exists(APP_ROOT . $_SERVER['REQUEST_URI'])){
        require_once(APP_ROOT . $_SERVER['REQUEST_URI']);
    }
    else{
        require_once(APP_ROOT . '/default');
    }
?>