<?php

require_once "Cat.php";
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
    }

    public function getAllOrders()
    {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->tableName);
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $orders;
    }

    public function getAllOrdersByClientId()
    {
        $sql = "SELECT * FROM " . $this->tableName . " WHERE customer_id = :customer_id";
 
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

    public function insert()
    {
        $sql = 'INSERT INTO ' . $this->tableName . '(name, price, status, avatar, cat_id) VALUES( :name, :price, :status, :avatar, :cat_id)';

        if ($this->getProdDetailsByName()) {
            return false;
        }

        $cat = new Cat();
        $cat->setId($this->cat_id);
        if (!$cat->getCategoryById()) {
            return false;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':avatar', $this->avatar);
        $stmt->bindParam(':cat_id', $this->cat_id);

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
        $sql = 'UPDATE ' . $this->tableName . ' SET name = :name, price = :price, status = :status, cat_id = :cat_id WHERE id = :id';

        $cat = new Cat();
        $cat->setId($this->cat_id);
        if (!$cat->getCategoryById()) {
            return false;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':cat_id', $this->cat_id);

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

        if (!$this->getProdDetailsById()) {
            return false;
        }

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
