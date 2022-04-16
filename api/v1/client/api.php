<?php
    require_once "../../../config/Rest.php";
    require_once "../../../utils/jwt.php";
    require_once "../../../models/Client.php";

    class Api extends Rest
    {
        public function __construct()
        {
            parent::__construct();
        }

        public function generateToken()
        {
            $email = $this->validateParameter('email', $this->param['email'], STRING);
            $pass = $this->validateParameter('pass', $this->param['pass'], STRING);
            try {
                $stmt = $this->dbConn->prepare("SELECT * FROM client WHERE email = :email AND pass = :pass");
                $stmt->bindParam(":email", $email);
                $stmt->bindParam(":pass", $pass);
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!is_array($user)) {
                    $this->returnResponse(INVALID_USER_PASS, "Email or Password is incorrect.");
                }

                $payload = [
                    'iat' => time(),
                    'iss' => 'localhost',
                    'exp' => time() + (24*60*60),
                    'userId' => $user['id'],
                    'role' => $user['role']
                ];

                $token = JWT::encode($payload, SECRETE_KEY);
                
                $data = ['token' => $token];
                $this->returnResponse(SUCCESS_RESPONSE, $data);
            } catch (Exception $e) {
                $this->throwError(JWT_PROCESSING_ERROR, $e->getMessage());
            }
        }

        public function getClientByToken()
        {
            $client = new Client();
            $client->setId($this->userId);
            $client = $client->getClientDetailsById();

            if (!is_array($client)) {
                $this->returnResponse(SUCCESS_RESPONSE, ['message' => 'The are no clients with this id.']);
            }

            $this->returnResponse(SUCCESS_RESPONSE, $client);
        }

        public function getAllClients()
        {
            $cust = new Client;
            $client = $cust->getAllClients();

            if (!is_array($client)) {
                $this->returnResponse(SUCCESS_RESPONSE, ['message' => 'The are no clients.']);
            }

            $this->returnResponse(SUCCESS_RESPONSE, $client);
        }

        public function getClientDetails()
        {
            $clientId = $this->validateParameter('id', $this->param['id'], INTEGER);

            $cust = new Client;
            $cust->setId($clientId);
            $client = $cust->getClientDetailsById();
            if (!is_array($client)) {
                $this->returnResponse(SUCCESS_RESPONSE, ['message' => 'Client details not found.']);
            }

            $response['id'] 	= $client['id'];
            $response['name'] 	= $client['name'];
            $response['email'] 	= $client['email'];
            $response['pass'] 	= $client['pass'];
            $response['avatar'] 	= $client['avatar'];
            $response['role'] 	= $client['role'];
            $this->returnResponse(SUCCESS_RESPONSE, $response);
        }

        public function addClient()
        {
            $name = $this->validateParameter('name', $this->param['name'], STRING);
            $email = $this->validateParameter('email', $this->param['email'], STRING);
            $pass = $this->validateParameter('pass', $this->param['pass'], STRING);
            $avatar = "avatar.jpg";

            $cust = new Client;
            $cust->setName($name);
            $cust->setEmail($email);
            $cust->setPass($pass);
            $cust->setAvatar($avatar);

            if (!$cust->insert()) {
                $message = 'Failed to insert.';
            } else {
                $message = "Inserted successfully.";
            }

            $this->returnResponse(SUCCESS_RESPONSE, $message);
        }

        public function updateClient()
        {
            $id = $this->validateParameter('id', $this->param['id'], INTEGER);
            $name = $this->validateParameter('name', $this->param['name'], STRING);
            $email = $this->validateParameter('email', $this->param['email'], STRING);
            $pass = $this->validateParameter('pass', $this->param['pass'], STRING);
            // $avatar = "avatar.jpg";

            $cust = new Client;
            $cust->setId($id);
            $cust->setName($name);
            $cust->setEmail($email);
            $cust->setPass($pass);
            // $cust->setAvatar($avatar);

            if (!$cust->update()) {
                $message = 'Failed to update.';
            } else {
                $message = "Updated successfully.";
            }

            $this->returnResponse(SUCCESS_RESPONSE, $message);
        }

        public function deleteClient()
        {
            $id = $this->validateParameter('id', $this->param['id'], INTEGER);

            $cust = new Client;
            $cust->setId($id);

            if (!$cust->delete()) {
                $message = 'Failed to delete.';
            } else {
                $message = "Deleted successfully.";
            }

            $this->returnResponse(SUCCESS_RESPONSE, $message);
        }
    }
