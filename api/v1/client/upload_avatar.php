<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
	
    include_once '../../../config/Database.php';
    include_once '../../../models/Client.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['QUERY_STRING'])) {
        $db = new Database();

        $client = new Client($db);

        $data = json_decode(file_get_contents("php://input"));
        
        $queries = array();
        parse_str($_SERVER['QUERY_STRING'], $queries);

        if (isset($queries['id'])) {
            $client->id  = $queries['id'];
            if ($client->getClientDetailsById() && isset($_FILES["avatar"]["name"])) {
                $filename= $_FILES["avatar"]["name"];
            }
        }

        if (isset($filename)) {
            $tmp_name= $_FILES["avatar"]["tmp_name"];
            
            move_uploaded_file($tmp_name, "../../../storage/client_avatar/".time()."-".$filename);
            $client->setAvatar(time()."-".$filename);
        } else {
            echo json_encode(array('message' => 'Avatar required'));
        }
         

        if ($client->updateAvatar()) {
            echo json_encode(array('response' => ['status' => 200, 'result' => ['message' => 'Avatar added']]));
        } else {
            echo json_encode(array('message' => 'Client Not added, try again!'));
        }
    } else {
        echo json_encode(array('message' => "Error: incorrect Method!"));
    }
