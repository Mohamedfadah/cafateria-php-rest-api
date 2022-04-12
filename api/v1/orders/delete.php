<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');

    include_once '../../../config/Database.php';
    include_once '../../../models/Order.php';

	if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

		$db = new Database();
		$db = $db->connect();

		$order = new Order($db);

		$data = json_decode(file_get_contents("php://input"));
		var_dump($data->id);

		$order->id = isset($data->id) ? $data->id : NULL;

		if(! is_null($order->id)) {
	
			if($order->delete()) {
			echo json_encode(array('message' => 'Order deleted'));
			} else {
			echo json_encode(array('message' => 'Order Not deleted, try again!'));
			}
		} else {
		echo json_encode(array('message' => "Error: Order ID is missing!"));
		}
	} else {
		echo json_encode(array('message' => "Error: incorrect Method!"));
	}