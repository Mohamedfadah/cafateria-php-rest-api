<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../../config/Database.php';
    include_once '../../../models/Category.php';

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $db = new Database();
        $db = $db->connect();

        $category = new Category($db);

        $data = json_decode(file_get_contents("php://input"));

        if (isset($data->id)) {
            $category->id = $data->id;

            if ($category->fetchOne()) {
                print_r(json_encode(array(
                    'id' => $category->id,
                    'name' => $category->name
                )));
            } else {
                echo json_encode(array('message' => "No records found!"));
            }
        } else {
            echo json_encode(array('message' => "Error: Category ID is missing!"));
        }
    } else {
        echo json_encode(array('message' => "Error: incorrect Method!"));
    }