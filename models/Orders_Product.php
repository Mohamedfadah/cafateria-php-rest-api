<?php

class Orders_Product
{
    private $conn;
    
    public $id;
    public $order_id;
    public $product_id;
    public $quantity;
    public $price;
    

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function fetchAll()
    {
        $stmt = $this->conn->prepare('SELECT * FROM orders_product');
        $stmt->execute();
        return $stmt;
    }

    public function fetchOne()
    {
        $stmt = $this->conn->prepare('SELECT  * FROM orders_product WHERE id =:id');
        $stmt->bindParam(':id', $this->id);
        // var_dump ($stmt);
        // exit;
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->order_id = $row['order_id'];
            $this->product_id = $row['product_id'];
            $this->quantity = $row['quantity'];
            $this->price = $row['price'];
            
  
            return true;
        }
        
        return false;
    }

    public function postData()
    {
      
        $stmt = $this->conn->prepare('INSERT INTO orders_product SET  order_id = :order_id, product_id = :product_id,quantity = :quantity,price = :price');

        // $stmt->bindParam(':date', $this->date);
        $stmt->bindParam(':order_id', $this->order_id);
        $stmt->bindParam(':product_id', $this->product_id);
        $stmt->bindParam(':quantity', $this->quantity);
        $stmt->bindParam(':price', $this->price);

        

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function putData()
    {
        $stmt = $this->conn->prepare('UPDATE orders_product SET  quantity = :quantity, price = :price WHERE id = :id');

        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':quantity', $this->quantity);
        $stmt->bindParam(':price', $this->price);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function delete()
    {
        $stmt = $this->conn->prepare('DELETE FROM orders_product WHERE id = :id');
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
