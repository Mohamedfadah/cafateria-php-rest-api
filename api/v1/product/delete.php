<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');


    include_once '../../../config/Database.php';
    include_once '../../../models/Product.php';

	if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

		$db = new Database();
		$db = $db->connect();

		$product = new Product($db);

		$data = json_decode(file_get_contents("php://input"));

		$product->id = isset($data->id) ? $data->id : NULL;

		if(! is_null($product->id)) {
	
			if($product->delete()) {
			echo json_encode(array('message' => 'Product deleted'));
			} else {
			echo json_encode(array('message' => 'Product Not deleted, try again!'));
			}
		} else {
		echo json_encode(array('message' => "Error: Product ID is missing!"));
		}
	} else {
		echo json_encode(array('message' => "Error: incorrect Method!"));
	}