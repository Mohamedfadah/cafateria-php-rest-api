<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');

    include_once '../../../config/Database.php';
    include_once '../../../models/Category.php';
	
    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {

		$db = new Database();
		$db = $db->connect();

		$category = new Category($db);

		$data = json_decode(file_get_contents("php://input"));

		$category->id = isset($data->id) ? $data->id : NULL;
		$category->name = $data->name;
		

		if(! is_null($category->id)) {

			if($category->putData()) {
			echo json_encode(array('message' => 'Category updated'));
			} else {
			echo json_encode(array('message' => 'Category Not updated, try again!'));
			}
		} else {
		echo json_encode(array('message' => "Error: Category ID is missing!"));
		}
	} else {
		echo json_encode(array('message' => "Error: incorrect Method!"));
	}