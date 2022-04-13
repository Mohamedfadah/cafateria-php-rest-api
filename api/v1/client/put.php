<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');

    include_once '../../../config/Database.php';
    include_once '../../../models/Client.php';

    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {

		$db = new Database();
		$db = $db->connect();

		$client = new Client($db);

		$data = json_decode(file_get_contents("php://input"));

		$client->id = isset($data->id) ? $data->id : NULL;
		$client->name = $data->name;
		$client->username = $data->username;
        $client->pass = $data->pass;
        $client->email = $data->email;

		if(! is_null($client->id)) {

			if($client->putData()) {

			echo json_encode(array('message' => 'Client updated'));
			} else {
			echo json_encode(array('message' => 'Client Not updated, try again!'));
			}
		} else {
		echo json_encode(array('message' => "Error: Client ID is missing!"));
		}
	} else {
		echo json_encode(array('message' => "Error: incorrect Method!"));
	}