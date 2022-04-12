<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');

    include_once '../../../config/Database.php';
    include_once '../../../models/Category.php';

	if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

		$db = new Database();
		$db = $db->connect();

		$category = new Category($db);

		$data = json_decode(file_get_contents("php://input"));

		$category->id = isset($data->id) ? $data->id : NULL;

		if(! is_null($category->id)) {
	
			if($category->delete()) {
			echo json_encode(array('message' => 'Category deleted'));
			} else {
			echo json_encode(array('message' => 'Category Not deleted, try again!'));
			}
		} else {
		echo json_encode(array('message' => "Error: Category ID is missing!"));
		}
	} else {
		echo json_encode(array('message' => "Error: incorrect Method!"));
	}