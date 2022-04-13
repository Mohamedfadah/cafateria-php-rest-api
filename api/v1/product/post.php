<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');

    include_once '../../../config/Database.php';
    include_once '../../../models/Product.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $db = new Database();
        $db = $db->connect();

        $product = new Product($db);

        $data = json_decode(file_get_contents("php://input"));


        $filename= $_FILES["avatar"]["name"];
        $tmp_name= $_FILES["avatar"]["tmp_name"];

        move_uploaded_file($tmp_name, "images/".$filename );
        $avatar = "images/".$filename;

        $product->name = $data->name;
        $product->price = $data->price;
        $product->status = $data->status;
        $product->cat_id = $data->cat_id;
        $product->avatar = $data->avatar;

    
        if ($product->postData()) {
            echo json_encode(array('message' => 'Product added'));
        } else {
            echo json_encode(array('message' => 'Product Not added, try again!'));
        }
    } else {
        echo json_encode(array('message' => "Error: incorrect Method!"));
    }