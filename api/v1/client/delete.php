<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');

    include_once '../../../config/Database.php';
    include_once '../../../models/Client.php';

	if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

		$db = new Database();
		$db = $db->connect();

		$Client = new Client($db);

		$data = json_decode(file_get_contents("php://input"));

		$Client->id = isset($data->id) ? $data->id : NULL;

		if(! is_null($Client->id)) {
	
			if($Client->delete()) {
			echo json_encode(array('message' => 'Client deleted'));
			} else {
			echo json_encode(array('message' => 'Client Not deleted, try again!'));
			}
		} else {
		echo json_encode(array('message' => "Error: Client ID is missing!"));
		}
	} else {
		echo json_encode(array('message' => "Error: incorrect Method!"));
	}