<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');

    include_once '../../../config/Database.php';
    include_once '../../../models/Orders_Product.php';

	if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

		$db = new Database();
		$db = $db->connect();

		$orders_product = new Orders_Product($db);

		$data = json_decode(file_get_contents("php://input"));

		$orders_product->id = isset($data->id) ? $data->id : NULL;

		if(! is_null($orders_product->id)) {
	
			if($orders_product->delete()) {
			echo json_encode(array('message' => 'Student deleted'));
			} else {
			echo json_encode(array('message' => 'Student Not deleted, try again!'));
			}
		} else {
		echo json_encode(array('message' => "Error: Student ID is missing!"));
		}
	} else {
		echo json_encode(array('message' => "Error: incorrect Method!"));
	}