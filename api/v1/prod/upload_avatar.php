<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');

    include_once '../../../config/Database.php';
    include_once '../../../models/Product.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['QUERY_STRING'])) {
        $db = new Database();

        $prod = new Product($db);

        $data = json_decode(file_get_contents("php://input"));
        
        $queries = array();
        parse_str($_SERVER['QUERY_STRING'], $queries);

        if (isset($queries['id'])) {
            $prod->id  = $queries['id'];
            if ($prod->getProdDetailsById() && isset($_FILES["avatar"]["name"])) {
                $filename= $_FILES["avatar"]["name"];
            }
        }

        if (isset($filename)) {
            $tmp_name= $_FILES["avatar"]["tmp_name"];
            
            move_uploaded_file($tmp_name, "../../../storage/product_avatar/".time()."-".$filename);
            $prod->setAvatar(time()."-".$filename);
        } else {
            echo json_encode(array('message' => 'Avatar required'));
        }
         

        if ($prod->updateAvatar()) {
            echo json_encode(array('response' => ['status' => 200, 'result' => ['message' => 'Avatar added']]));
        } else {
            echo json_encode(array('message' => 'Product Not added, try again!'));
        }
    } else {
        echo json_encode(array('message' => "Error: incorrect Method!"));
    }
