<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../../config/Database.php';
    include_once '../../../models/Product.php';

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $db = new Database();
        $db = $db->connect();

        $product = new Product($db);

        $data = json_decode(file_get_contents("php://input"));

        if (isset($data->id)) {
            $product->id = $data->id;

            if ($product->fetchOne()) {
                print_r(json_encode(array(
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'status' => $product->status,
                    'cat_id' => $product->cat_id
                )));
            } else {
                echo json_encode(array('message' => "No records found!"));
            }
        } else {
            echo json_encode(array('message' => "Error: Product ID is missing!"));
        }
    } else {
        echo json_encode(array('message' => "Error: incorrect Method!"));
    }