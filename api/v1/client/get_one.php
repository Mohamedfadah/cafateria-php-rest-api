<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../../config/Database.php';
    include_once '../../../models/Client.php';

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $db = new Database();
        $db = $db->connect();

        $client = new Client($db);

        $data = json_decode(file_get_contents("php://input"));

        if (isset($data->id)) {
            $client->id = $data->id;

            if ($client->fetchOne()) {
                print_r(json_encode(array(
                    'id' => $client->id,
                    'name' => $client->name,
                    'username' => $client->username,
                    'pass' => $client->pass,
                    'email' => $client->email,
                    'avatar' => $client->avatar

                )));
            } else {
                echo json_encode(array('message' => "No records found!"));
            }
        } else {
            echo json_encode(array('message' => "Error: Client ID is missing!"));
        }
    } else {
        echo json_encode(array('message' => "Error: incorrect Method!"));
    }