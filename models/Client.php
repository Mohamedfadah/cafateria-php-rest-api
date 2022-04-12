<?php

class Client
{
    private $conn;
    
    public $id;
    public $name;
    public $username;
    public $pass;
    public $email;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function fetchAll()
    {
        $stmt = $this->conn->prepare('SELECT * FROM client');
        $stmt->execute();
        return $stmt;
    }

    public function fetchOne()
    {
        $stmt = $this->conn->prepare('SELECT  * FROM client WHERE id = ?');
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->username = $row['username'];

            $this->pass = $row['pass'];
            $this->email = $row['email'];
            $this->img = $row['img'];

            return true;
        }
        
        return false;
    }

    public function postData()
    {
        $stmt = $this->conn->prepare('INSERT INTO client SET name = :name, username = :username, pass = :pass, email = :email');

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':pass', $this->pass);
        $stmt->bindParam(':email', $this->email);
        // $stmt->bindParam(':img', $this->img);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function putData()
    {
        $stmt = $this->conn->prepare('UPDATE client SET name = :name, username = :username, pass = :pass, email = :email ,img=:img WHERE id = :id');

        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':pass', $this->pass);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':img', $this->img);

        if ($stmt->execute()) {
            return true;
        }
    }
    public function uploadimg()
    {
        $stmt = $this->conn->prepare('UPDATE client SET img = :img WHERE id = :id');

        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':pass', $this->pass);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':img', $this->img);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function delete()
    {
        $stmt = $this->conn->prepare('DELETE FROM client WHERE id = :id');
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}