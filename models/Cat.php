<?php

class Cat
{
    private $conn;
    
    public $id;
    public $name;
    private $tableName = 'category';


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

    
    public function __construct($db = null)
    {
        $db = $db !== null ? $db : new Database();
        
        $this->conn = $db->connect();
    }

    public function getAllCategories()
    {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->tableName);
        $stmt->execute();
        $cats = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $cats;
    }

    public function getCategoryById()
    {
        $sql = "SELECT * FROM " . $this->tableName . " WHERE id = :id";
 
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        $cat = $stmt->fetch(PDO::FETCH_ASSOC);
        return $cat;
    }

    public function getCategoryByName()
    {
        $sql = "SELECT * FROM " . $this->tableName . " WHERE name = :name";
 
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':name', $this->name);
        $stmt->execute();
        $cat = $stmt->fetch(PDO::FETCH_ASSOC);
        return $cat;
    }

    public function insert()
    {
        $sql = 'INSERT INTO ' . $this->tableName . '(name) VALUES( :name )';

        if ($this->getCategoryByName()) {
            return false;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':name', $this->name);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function update()
    {
        $sql = 'UPDATE ' . $this->tableName . ' SET name = :name WHERE id = :id';

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':name', $this->name);

        if ($this->getCategoryByName()) {
            return false;
        }

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function delete()
    {
        $sql = 'DELETE FROM ' . $this->tableName . ' WHERE id = :id';

        if (!$this->getCategoryById()) {
            return false;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
