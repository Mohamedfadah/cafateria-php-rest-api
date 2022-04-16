<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");

	
	spl_autoload_register(function ($className) {
        $path = strtolower($className) . ".php";
        if (file_exists($path)) {
            require_once($path);
        } else {
            echo "File $path is not found.";
        }
    });
    $api = new Api;
    $api->processApi();
