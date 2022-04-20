<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");

    
    
    
    
    require_once('constants.php');
    require_once('database.php');
    class Rest
    {
        protected $request;
        protected $serviceName;
        protected $param;
        protected $dbConn;
        protected $userId;

        public function __construct()
        {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->throwError(REQUEST_METHOD_NOT_VALID, 'Request Method is not valid.');
            }

            $handler = fopen('php://input', 'r');
            $this->request = stream_get_contents($handler);
            $this->validateRequest();

            $db = new Database;
            $this->dbConn = $db->connect();

            if ('generatetoken' != strtolower($this->serviceName)) {
                $this->validateToken();
            }
        }

        // public function validateFileRequest()
        // {
        //     if ($_SERVER['CONTENT_TYPE'] !== 'multipart/form-data') {
        //         $this->throwError(REQUEST_CONTENTTYPE_NOT_VALID, 'Request content type is not valid');
        //     }

        //     $data = json_decode($this->request, true);

        //     if (!isset($data['name']) || $data['name'] == "") {
        //         $this->throwError(API_NAME_REQUIRED, "API name is required.");
        //     }
        //     $this->serviceName = $data['name'];

        //     if (!is_array($data['param'])) {
        //         $this->throwError(API_PARAM_REQUIRED, "API PARAM is required.");
        //     }
        //     $this->param = $data['param'];
        // }

        public function convertJsonToArray($json)
        {
            if (is_array($json)) {
                return $json;
            }
            return json_decode($json, true);
            // var_dump($json);
            // if (is_string($json)) {
            //     $tempJson = json_decode($json, true);
                
            //     // var_dump($json);
            //     if (is_array($tempJson)) {
            //         $json = $tempJson;
            //         foreach ($json as $key => $value) {
            //             $json[$key] = $this->convertJsonToArray($value);
            //         }
            //     } else {
            //         // $json = $tempJson;
            //         return $tempJson;
            //     }
            // }
            // return $json;
        }

        public function validateRequest()
        {
            if (!($_SERVER['CONTENT_TYPE'] === 'application/json')) {
                $this->throwError(REQUEST_CONTENTTYPE_NOT_VALID, 'Request content type is not valid');
            }

            $data = json_decode($this->request, true);
            // $data = $this->convertJsonToArray($this->request);

            if (!isset($data['name']) || $data['name'] == "") {
                $this->throwError(API_NAME_REQUIRED, "API name is required.");
            }
            $this->serviceName = $data['name'];

            $data = $this->convertJsonToArray($data);
            $data['param'] = $this->convertJsonToArray($data['param']);
            // print_r($data);
            // echo $data['param'];
            if (!is_array($data['param'])) {
                $this->throwError(API_PARAM_REQUIRED, "API PARAM is required.");
            }
            $this->param = $data['param'];
        }

        public function validateParameter($fieldName, $value, $dataType, $required = true)
        {
            if ($required == true && empty($value) == true) {
                $this->throwError(VALIDATE_PARAMETER_REQUIRED, $fieldName . " parameter is required.");
            }

            switch ($dataType) {
                case BOOLEAN:
                    if (!is_bool($value)) {
                        $this->throwError(VALIDATE_PARAMETER_DATATYPE, "Datatype is not valid for " . $fieldName . '. It should be boolean.');
                    }
                    break;
                case INTEGER:
                    if (!is_numeric($value)) {
                        $this->throwError(VALIDATE_PARAMETER_DATATYPE, "Datatype is not valid for " . $fieldName . '. It should be numeric.');
                    }
                    break;

                case STRING:
                    if (!is_string($value)) {
                        $this->throwError(VALIDATE_PARAMETER_DATATYPE, "Datatype is not valid for " . $fieldName . '. It should be string.');
                    }
                    break;
                
                default:
                    $this->throwError(VALIDATE_PARAMETER_DATATYPE, "Datatype is not valid for " . $fieldName);
                    break;
            }

            return $value;
        }

        public function validateToken()
        {
            try {
                $token = $this->getBearerToken();
                $payload = JWT::decode($token, SECRETE_KEY, ['HS256']);

                $stmt = $this->dbConn->prepare("SELECT * FROM client WHERE id = :userId");
                $stmt->bindParam(":userId", $payload->userId);
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!is_array($user)) {
                    $this->returnResponse(INVALID_USER_PASS, "This user is not found in our database.");
                }

                // if ($user['active'] == 0) {
                //     $this->returnResponse(USER_NOT_ACTIVE, "This user may be decactived. Please contact to admin.");
                // }
                $this->userId = $payload->userId;
            } catch (Exception $e) {
                $this->throwError(ACCESS_TOKEN_ERRORS, $e->getMessage());
            }
        }

        public function processApi()
        {
            try {
                $api = new API;

                $rMethod = new reflectionMethod('API', $this->serviceName);
                // echo $this->serviceName;
                if (!method_exists($api, $this->serviceName)) {
                    $this->throwError(API_DOST_NOT_EXIST, "API does not exist.");
                }
                // var_dump($api);
                $rMethod->invoke($api);
            } catch (Exception $e) {
                $this->throwError(API_DOST_NOT_EXIST, "API does not exist. found");
            }
        }

        public function throwError($code, $message)
        {
            header("content-type: application/json");
            $errorMsg = json_encode(['error' => ['status'=>$code, 'message'=>$message]]);
            echo $errorMsg;
            exit;
        }

        public function returnResponse($code, $data)
        {
            header("content-type: application/json");
            $response = json_encode(['response' => ['status' => $code, "result" => $data]]);
            echo $response;
            exit;
        }

        /**
        * Get hearder Authorization
        * */
        public function getAuthorizationHeader()
        {
            $headers = null;
            // $_SERVER = $this->convertJsonToArray($_SERVER);
            if (isset($_SERVER['Authorization'])) {
                // echo "rrrrrrrrrrrrrrrrrrrr";
                $headers = trim($_SERVER["Authorization"]);
            } elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
                // echo "ooooooooooooooo";

                $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
            } elseif (function_exists('apache_request_headers')) {
                // print_r(apache_response_headers());
                // var_dump($_POST);

                $requestHeaders = apache_request_headers();
                // print_r($requestHeaders);
                // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
                $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
                // var_dump($requestHeaders);
                $requestHeaders['Authorization'] = "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NTAyODAyNzYsImlzcyI6ImxvY2FsaG9zdCIsImV4cCI6MTY1NTQ2NDI3NiwidXNlcklkIjoiMyIsInJvbGUiOiIwIn0.ZTQg2JZgMUOsCgMZryvOa0TxWfj_k7mLqVqOLR2EGnM";
            
                // echo "bbbbbbbbbbbbbbbbbbbbb";
                if (isset($requestHeaders['Authorization'])) {
                    $headers = trim($requestHeaders['Authorization']);
                }
            }
            return $headers;
        }
        /**
         * get access token from header
         * */
        public function getBearerToken()
        {
            $headers = $this->getAuthorizationHeader();
            // HEADER: Get the access token from the header
            if (!empty($headers)) {
                if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                    return $matches[1];
                }
            }
            $this->throwError(ATHORIZATION_HEADER_NOT_FOUND, 'Access Token Not found');
        }
    }
