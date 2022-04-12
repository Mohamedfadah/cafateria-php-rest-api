<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../../config/Database.php';
    include_once '../../../models/Orders_Product.php';

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $db = new Database();
        $db = $db->connect();

        $orders_product = new Orders_Product($db);

        $res = $orders_product->fetchAll();
        $resCount = $res->rowCount();

        if ($resCount > 0) {
            $orders_product = array();

            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                array_push($orders_product, array( 'id' => $id, 'order_id' => $order_id, 'product_id' => $product_id, 'quantity' => $quantity, 'price' => $price));
            }
            
            echo json_encode($orders_product);
        } else {
            echo json_encode(array('message' => "No records found!"));
        }
    } else {
        echo json_encode(array('message' => "Error: incorrect Method!"));
    }
