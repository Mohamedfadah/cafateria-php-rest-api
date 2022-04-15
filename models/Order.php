<?php

require_once "Order.php";
require_once "Client.php";
class Order
{
    private $conn;
    
    public $id;
    public $date;
    public $status;
    public $price;
    public $note;
    public $customer_id;      // category id
    private $tableName = 'orders';

    public $startDate;
    public $endDate;


    public function setId($id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }
    public function setDate($date)
    {
        $this->date = $date;
    }
    public function getDate()
    {
        return $this->date;
    }
    public function setStatus($status)
    {
        $this->status = $status;
    }
    public function getStatus()
    {
        return $this->status;
    }
    public function setPrice($price)
    {
        $this->price = $price;
    }
    public function getPrice()
    {
        return $this->price;
    }
    public function setNote($note)
    {
        $this->note = $note;
    }
    public function getNote()
    {
        return $this->note;
    }
    public function setCustomer_id($customer_id)
    {
        $this->customer_id = $customer_id;
    }
    public function getCustomer_id()
    {
        return $this->customer_id;
    }

    
    public function __construct($db = null)
    {
        $db = $db !== null ? $db : new Database();
        
        $this->conn = $db->connect();
        $this->startDate="";
        $this->endDate="";
    }

    // Got
    public function getAllOrders()
    {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->tableName);
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $orders;
    }

    // Got By Date
    public function getOrderByTime()
    {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->tableName . " WHERE date = :date");
        $stmt->bindParam(':date', $this->date);

        $stmt->execute();
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        return $order;
    }

    public function getOrderById()
    {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->tableName . " WHERE id = :id");
        $stmt->bindParam(':id', $this->id);

        $stmt->execute();
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        return $order;
    }

    public function setBoundaryOfTime($start, $end)
    {
        $this->startDate = $start;
        $this->endDate = $end;
    }

    public function getOrdersByTimeBoundary()
    {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->tableName . " WHERE date BETWEEN :startDate AND :endDate");
        $stmt->bindParam(':startDate', $this->startDate);
        $stmt->bindParam(':endDate', $this->endDate);

        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $orders;
    }


    public function getAllOrdersByClientId()
    {
        $sql = "SELECT * FROM " . $this->tableName . " WHERE customer_id = :customer_id ORDER BY date DESC";
 
        $client = new Client();
        $client->setId($this->customer_id);
        if (!$client->getClientDetailsById()) {
            return false;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':customer_id', $this->customer_id);
        $stmt->execute();
        $prod = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $prod;
    }

    public function getLastOrderByClientId()
    {
        $sql = "SELECT * FROM " . $this->tableName . " WHERE customer_id = :customer_id ORDER BY date DESC";
 
        $client = new Client();
        $client->setId($this->customer_id);
        if (!$client->getClientDetailsById()) {
            return false;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':customer_id', $this->customer_id);
        $stmt->execute();
        $prod = $stmt->fetch(PDO::FETCH_ASSOC);
        return $prod;
    }

    public function getProdDetailsById()
    {
        $sql = "SELECT * FROM " . $this->tableName . " WHERE id = :id";
 
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        $prod = $stmt->fetch(PDO::FETCH_ASSOC);
        return $prod;
    }

    public function getProdDetailsByName()
    {
        $sql = "SELECT * FROM " . $this->tableName . " WHERE name = :name";
 
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':name', $this->name);
        $stmt->execute();
        $prods = $stmt->fetch(PDO::FETCH_ASSOC);
        return $prods;
    }

    public function getProdDetailsByCat()
    {
        $sql = "SELECT * FROM " . $this->tableName . " WHERE cat_id = :cat_id";
 
        $cat = new Cat();
        $cat->setId($this->cat_id);
        if (!$cat->getCategoryById()) {
            return false;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':cat_id', $this->cat_id);
        $stmt->execute();
        $prods = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $prods;
    }

    // Inserted
    public function insert()
    {
        $sql = 'INSERT INTO ' . $this->tableName . '(date, status, price, note, customer_id) VALUES( :date, :status, :price, :note, :customer_id )';

        $client = new Client();
        $client->setId($this->customer_id);
        if (!$client->getClientDetailsById()) {
            return false;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':date', $this->date);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':note', $this->note);
        $stmt->bindParam(':customer_id', $this->customer_id);
        
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function updateStatus()
    {
        $sql = 'UPDATE ' . $this->tableName . ' SET status = :status WHERE id = :id';

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':status', $this->status);

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

        if (!$this->getOrderById()) {
            return false;
        }

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
