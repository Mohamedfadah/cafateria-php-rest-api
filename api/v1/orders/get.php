<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../../config/Database.php';
    include_once '../../../models/Order.php';

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $db = new Database();
        $db = $db->connect();

        $order = new Order($db);

        $res = $order->fetchAll();
        $resCount = $res->rowCount();

        if ($resCount > 0) {
            $order = array();

            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                array_push($order, array( 'id' => $id, 'date' => $date, 'status' => $status, 'price' => $price, 'customer_id' => $customer_id));
            }
            
            echo json_encode($order);
        } else {
            echo json_encode(array('message' => "No records found!"));
        }
    } else {
        echo json_encode(array('message' => "Error: incorrect Method!"));
    }
