<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../../config/Database.php';
    include_once '../../../models/Client.php';

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $db = new Database();
        $db = $db->connect();

        $client = new Client($db);

        $res = $client->fetchAll();
        $resCount = $res->rowCount();

        if ($resCount > 0) {
            $client = array();

            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                array_push($client, array( 'id' => $id, 'name' => $name, 'username' => $username, 'pass' => $pass, 'email' => $email));
            }
            
            echo json_encode($client);
        } else {
            echo json_encode(array('message' => "No records found!"));
        }
    } else {
        echo json_encode(array('message' => "Error: incorrect Method!"));
    }
