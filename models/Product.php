<?php

class Product
{
    private $conn;
    
    public $id;
    public $name;
    public $price;
    public $status;
    public $cat_id;


    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function fetchAll()
    {
        $stmt = $this->conn->prepare('SELECT * FROM product');
        $stmt->execute();
        return $stmt;
    }

    public function fetchOne()
    {
        $stmt = $this->conn->prepare('SELECT  * FROM product WHERE id = ?');
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->price = $row['price'];
            $this->status = $row['status'];
            $this->cat_id = $row['cat_id'];

            return true;
        }
        
        return false;
    }

    public function postData()
    {
        $stmt = $this->conn->prepare('INSERT INTO product SET name = :name, price = :price, status = :status, cat_id = :cat_id');

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':cat_id', $this->cat_id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function putData()
    {
        $stmt = $this->conn->prepare('UPDATE product SET name = :name, price = :price, status = :status, cat_id = :cat_id WHERE id = :id');

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':cat_id', $this->cat_id);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function delete()
    {
        $stmt = $this->conn->prepare('DELETE FROM product WHERE id = :id');
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}