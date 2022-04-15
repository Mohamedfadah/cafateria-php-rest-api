<?php

class Database
{
    private $host = "109.106.246.1";
    private $user = "u635309332_afateria_root";
    private $db = "u635309332_cafateria_proj";
    private $pwd = "Cafateria123#";
    private $conn = null;


    public function connect()
    {
        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->db", $this->user, $this->pwd);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exp) {
            echo "Connection Error: " . $exp->getMessage();
        }

        return $this->conn;
    }
}
