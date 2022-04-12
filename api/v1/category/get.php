<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../../config/Database.php';
    include_once '../../../models/Category.php';

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $db = new Database();
        $db = $db->connect();

        $category = new Category($db);

        $res = $category->fetchAll();
        $resCount = $res->rowCount();

        if ($resCount > 0) {
            $category = array();

            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                array_push($category, array( 'id' => $id, 'name' => $name));
            }
            
            echo json_encode($category);
        } else {
            echo json_encode(array('message' => "No records found!"));
        }
    } else {
        echo json_encode(array('message' => "Error: incorrect Method!"));
    }