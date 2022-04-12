<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');

    include_once '../../../config/Database.php';
    include_once '../../../models/Category.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $db = new Database();
        $db = $db->connect();

        $category = new Category($db);

        $data = json_decode(file_get_contents("php://input"));

        $category->name = $data->name;
       
    
        if ($category->postData()) {
            echo json_encode(array('message' => 'Category added'));
        } else {
            echo json_encode(array('message' => 'Category Not added, try again!'));
        }
    } else {
        echo json_encode(array('message' => "Error: incorrect Method!"));
    }