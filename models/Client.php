<?php

class Client
{
    private $conn;
    
    public $id;
    public $name;
    // public $username;
    public $pass;
    public $email;
    public $avatar;
    public $storage_client_path;
    private $tableName = 'client';


    public function setId($id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }
    public function setName($name)
    {
        $this->name = $name;
    }
    public function getName()
    {
        return $this->name;
    }
    public function setPass($pass)
    {
        $this->pass = $pass;
    }
    public function getPass()
    {
        return $this->pass;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function setAvatar($avatar)
    {
        $this->avatar = $this->storage_client_path . $avatar;
    }
    public function getAvatar()
    {
        return $this->avatar;
    }

    
    public function __construct($db = null)
    {
        $db = $db !== null ? $db : new Database();
        
        $this->conn = $db->connect();
        $this->storage_client_path = "http://localhost:80/c/v3/storage/client_avatar/";
        // $this->storage_client_path = "http://cafeteria.elfabrikaa.online/Cafetria2/storage/client_avatar/";
    }

    public function getAllClients()
    {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->tableName . " WHERE role = 0");
        $stmt->execute();
        $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $clients;
    }

    public function getTheLastClient()
    {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->tableName . " ORDER BY id DESC LIMIT 1");
        $stmt->execute();
        $prod = $stmt->fetch(PDO::FETCH_ASSOC);
        return $prod;
    }

    public function getClientDetailsById()
    {
        $sql = "SELECT * FROM client WHERE id = :id";
 
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        $client = $stmt->fetch(PDO::FETCH_ASSOC);
        return $client;
    }

    public function getClientDetailsByEmail()
    {
        $sql = "SELECT * FROM client WHERE email = :email";
 
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();
        $client = $stmt->fetch(PDO::FETCH_ASSOC);
        return $client;
    }

    public function insert()
    {
        $sql = 'INSERT INTO ' . $this->tableName . '(name, email, pass, avatar) VALUES( :name, :email, :pass, :avatar)';

        if ($this->getClientDetailsByEmail()) {
            return false;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':pass', $this->pass);
        $stmt->bindParam(':avatar', $this->avatar);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function updateAvatar()
    {
        $sql = 'UPDATE ' . $this->tableName . ' SET avatar = :avatar WHERE id = :id';

        if (!isset($this->id) || !isset($this->avatar)) {
            return false;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':avatar', $this->avatar);
        
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function update()
    {
        $sql = 'UPDATE ' . $this->tableName . ' SET name = :name, email = :email, pass = :pass WHERE id = :id';

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':pass', $this->pass);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function delete()
    {
        $sql = 'DELETE FROM ' . $this->tableName . ' WHERE id = :id';

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
