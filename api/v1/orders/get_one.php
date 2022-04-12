<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../../config/Database.php';
    include_once '../../../models/Order.php';

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $db = new Database();
        $db = $db->connect();

        $order = new Order($db);

        $data = json_decode(file_get_contents("php://input"));

        if (isset($data->id)) {
            $order->id = $data->id;

            if ($order->fetchOne()) {
                print_r(json_encode(array(
                    'id' => $order->id,
                    'date' => $order->date,
                    'status' => $order->status,
                    'price' => $order->price,
                    'customer_id' => $order->customer_id
                )));
            } else {
                echo json_encode(array('message' => "No records found!"));
            }
        } else {
            echo json_encode(array('message' => "Error: order ID is missing!"));
        }
    } else {
        echo json_encode(array('message' => "Error: incorrect Method!"));
    }
