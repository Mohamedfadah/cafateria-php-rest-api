<?php

class Order
{
    private $conn;
    
    public $id;
    public $date;
    public $status;
    public $price;
    public $customer_id;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function fetchAll()
    {
        $stmt = $this->conn->prepare('SELECT * FROM orders');
        $stmt->execute();
        return $stmt;
    }

    public function fetchOne()
    {
        $stmt = $this->conn->prepare('SELECT  * FROM orders WHERE id =:id');
        $stmt->bindParam(':id', $this->id);
        // var_dump ($stmt);
        // exit;
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->id = $row['id'];
            $this->date = $row['date'];
            $this->status = $row['status'];
            $this->price = $row['price'];
            $this->customer_id = $row['customer_id'];

  

            return true;
        }
        
        return false;
    }

    public function postData()
    {
      
        $stmt = $this->conn->prepare('INSERT INTO orders SET  status = :status, price = :price,client_id = :client_id');

        // $stmt->bindParam(':date', $this->date);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':client_id', $this->client_id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function putData()
    {
        $stmt = $this->conn->prepare('UPDATE orders SET  status = :status, price = :price WHERE id = :id');

        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':price', $this->price);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function delete()
    {
        $stmt = $this->conn->prepare('DELETE FROM orders WHERE id = :id');
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
