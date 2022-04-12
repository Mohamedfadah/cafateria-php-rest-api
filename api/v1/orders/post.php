<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');

    include_once '../../../config/Database.php';
    include_once '../../../models/Order.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $db = new Database();
        $db = $db->connect();

        $order = new Order($db);

        $data = json_decode(file_get_contents("php://input"));

        $order->status = $data->status;
        $order->price = $data->price;
        $order->customer_id = $data->customer_id;
        
    
        if ($order->postData()) {
            echo json_encode(array('message' => 'Order added'));
        } else {
            echo json_encode(array('message' => 'Order Not added, try again!'));
        }
    } else {
        echo json_encode(array('message' => "Error: incorrect Method!"));
    }
