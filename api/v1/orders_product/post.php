<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');

    include_once '../../../config/Database.php';
    include_once '../../../models/Orders_Product.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $db = new Database();
        $db = $db->connect();

        $orders_product = new Orders_Product($db);

        $data = json_decode(file_get_contents("php://input"));

        $orders_product->order_id = $data->order_id;
        $orders_product->product_id = $data->product_id;
        $orders_product->quantity = $data->quantity;
        $orders_product->price = $data->price;

    
        if ($orders_product->postData()) {
            echo json_encode(array('message' => 'Student added'));
        } else {
            echo json_encode(array('message' => 'Student Not added, try again!'));
        }
    } else {
        echo json_encode(array('message' => "Error: incorrect Method!"));
    }
