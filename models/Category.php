<?php

class Category
{
    private $conn;
    
    public $id;
    public $name;
  

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function fetchAll()
    {
        $stmt = $this->conn->prepare('SELECT * FROM category');
        $stmt->execute();
        return $stmt;
    }

    public function fetchOne()
    {
        $stmt = $this->conn->prepare('SELECT  * FROM category WHERE id = ?');
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->id = $row['id'];
            $this->name = $row['name'];

            return true;
        }
        
        return false;
    }

    public function postData()
    {
        $stmt = $this->conn->prepare('INSERT INTO category SET name = :name');

        $stmt->bindParam(':name', $this->name);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function putData()
    {
        $stmt = $this->conn->prepare('UPDATE category SET name = :name WHERE id = :id');

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function delete()
    {
        $stmt = $this->conn->prepare('DELETE FROM category WHERE id = :id');
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}