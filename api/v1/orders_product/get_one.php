<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../../config/Database.php';
    include_once '../../../models/Orders_Product.php';

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $db = new Database();
        $db = $db->connect();

        $orders_product = new Orders_Product($db);

        $data = json_decode(file_get_contents("php://input"));

        if (isset($data->id)) {
            $orders_product->id = $data->id;

            if ($orders_product->fetchOne()) {
                print_r(json_encode(array(
                    'id' => $orders_product->id,
                    'order_id' => $orders_product->order_id,
                    'product_id' => $orders_product->product_id,
                    'quantity' => $orders_product->quantity,
                    'price' => $orders_product->price
                )));
            } else {
                echo json_encode(array('message' => "No records found!"));
            }
        } else {
            echo json_encode(array('message' => "Error: Student ID is missing!"));
        }
    } else {
        echo json_encode(array('message' => "Error: incorrect Method!"));
    }
