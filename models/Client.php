<?php

class Client
{
    private $conn;
    
    public $id;
    public $name;
    public $username;
    public $pass;
    public $email;
    public $avatar;
    public $storage_client_path;

    public function __construct($db)
    {
        $this->conn = $db;
        $this->storage_client_path = "http://localhost:8080/cafateria-php/storage/client_avatar/".time()."-";
    }

    public function fetchAll()
    {
        $stmt = $this->conn->prepare('SELECT * FROM client');
        $stmt->execute();
        return $stmt;
    }

    public function login_email_pass()
    {
        if(!isset($this->email) || !isset($this->pass)){
            return false;
        }

        $stmt = $this->conn->prepare('SELECT * FROM client WHERE email = :email and pass = :pass');
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':pass', $this->pass);

        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->username = $row['username'];

            $this->pass = $row['pass'];
            $this->email = $row['email'];
            $this->avatar = $row['avatar'];

            return true;
        }
        return false;
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
            $this->avatar = $row['avatar'];

            return true;
        }
        
        return false;
    }

    public function postData()
    {
        $stmt = $this->conn->prepare('INSERT INTO client SET name = :name, username = :username, pass = :pass, email = :email, avatar = :avatar');

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':pass', $this->pass);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':avatar', $this->avatar);

        // TODO:: CHECK IF USERNAME OR EMAIL ARE EXISTS

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function putData()
    {
        $stmt = $this->conn->prepare('UPDATE client SET name = :name, username = :username, pass = :pass, email = :email WHERE id = :id');

        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':pass', $this->pass);
        $stmt->bindParam(':email', $this->email);
        // $stmt->bindParam(':avatar', $this->avatar);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    public function uploadAvatar()
    {
        $stmt = $this->conn->prepare('UPDATE client SET avatar = :avatar WHERE id = :id');

        if(!isset($this->id) || !isset($this->avatar))
            return false;
        
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':avatar', $this->avatar);

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

    public function generateToken() {
        $email = $this->conn->prepare('DELETE FROM client WHERE id = :id');
    }

    public function validateParameter() {
        $email = $this->conn->prepare('DELETE FROM client WHERE id = :id');
    }

    
}