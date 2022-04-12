<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');

		include_once '../../../config/Database.php';
    include_once '../../../models/Order.php';

    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {

		$db = new Database();
		$db = $db->connect();

		$order = new Order($db);

		$data = json_decode(file_get_contents("php://input"));

		$order->id = isset($data->id) ? $data->id : NULL;
		$order->status = $data->status;
		$order->price = $data->price;

		if(! is_null($order->id)) {

			if($order->putData()) {
			echo json_encode(array('message' => 'order updated'));
			} else {
			echo json_encode(array('message' => 'order Not updated, try again!'));
			}
		} else {
		echo json_encode(array('message' => "Error: order ID is missing!"));
		}
	} else {
		echo json_encode(array('message' => "Error: incorrect Method!"));
	}