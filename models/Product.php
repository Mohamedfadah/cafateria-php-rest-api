<?php

require_once "Cat.php";
class Product
{
    private $conn;
    
    public $id;
    public $name;
    public $status;
    public $price;
    public $avatar;
    public $catId;      // category id
    public $storage_prod_path;
    public $selectIds;
    private $tableName = 'product';


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
    public function setAvatar($avatar)
    {
        $this->avatar = $this->storage_prod_path . $avatar;
    }
    public function getAvatar()
    {
        return $this->avatar;
    }
    public function setCat_id($cat_id)
    {
        $this->cat_id = $cat_id;
    }
    public function getCat_id()
    {
        return $this->cat_id;
    }

    
    public function __construct($db = null)
    {
        $db = $db !== null ? $db : new Database();
        
        $this->conn = $db->connect();
        $this->storage_prod_path = "http://localhost:8080/Cafetria/storage/product_avatar/";
        $this->selectIds = array();
    }

    public function getAllProds()
    {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->tableName);
        $stmt->execute();
        $prods = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $prods;
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

    public function fillInIds($id)
    {
        array_push($this->selectIds, $id);
    }

    public function getProdsIn()
    {
        var_dump($this->selectIds);
        $sql = "SELECT * FROM " . $this->tableName . " WHERE id IN (" . implode(',', $this->selectIds) . ")";
 
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $prods = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
