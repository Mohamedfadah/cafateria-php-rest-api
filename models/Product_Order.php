<?php

require_once "Cat.php";
class Product_Order
{
    private $conn;
    
    public $id;
    public $order_id;   // order id
    public $prod_id;    // product id
    public $quantity;
    public $price;

    public $prod_id_array;    // product id
    public $quantity_array;
    public $price_array;
    private $prod_count_in_array;

    private $tableName = 'orders_product';


    public function setId($id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }
    public function setOrder_id($order_id)
    {
        $this->order_id = $order_id;
    }
    public function getOrder_id()
    {
        return $this->order_id;
    }
    public function setProd_id($prod_id)
    {
        $this->prod_id = $prod_id;
    }
    public function getProd_id()
    {
        return $this->prod_id;
    }
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }
    public function getQuantity()
    {
        return $this->quantity;
    }
    public function setPrice($price)
    {
        $this->price = $price;
    }
    public function getPrice()
    {
        return $this->price;
    }

    
    public function __construct($db = null)
    {
        $db = $db !== null ? $db : new Database();
        
        $this->conn = $db->connect();

        $prod_count_in_array = 0;
        $this->prod_id_array = [];    // product id
        $this->quantity_array = [];
        $this->price_array = [];
    }

    public function push($prod_id, $quantity, $price)
    {
        array_push($this->prod_id_array, $prod_id);
        array_push($this->quantity_array, $quantity);
        array_push($this->price_array, $price);
        $this->prod_count_in_array++;
    }

    public function getAllProdsOrders()
    {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->tableName);
        $stmt->execute();
        $prods = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $prods;
    }

    public function getProdsOrdersDetailsById()
    {
        $sql = "SELECT * FROM " . $this->tableName . " WHERE id = :id";
 
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        $prodOrder = $stmt->fetch(PDO::FETCH_ASSOC);
        return $prodOrder;
    }

    public function getProdsOrdersDetailsByOrderId()
    {
        $sql = "SELECT * FROM " . $this->tableName . " WHERE order_id = :order_id";
 
        $stmt = $this->conn->prepare($sql);
        // echo $this->order_id;
        $stmt->bindParam(':order_id', $this->order_id);
        $stmt->execute();
        $prod = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $prod;
    }

    public function insert()
    {
        $order = new Order();
        $order->setId($this->order_id);
        if ($order->getOrderById() == null) {
            return false;
        }
        
        if ($this->prod_count_in_array == 0) {
            $sql = 'INSERT INTO ' . $this->tableName . '(order_id, product_id, quantity, price) VALUES( :order_id, :product_id, :quantity, :price)';

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':order_id', $this->order_id);
            $stmt->bindParam(':product_id', $this->prod_id);
            $stmt->bindParam(':quantity', $this->quantity);
            $stmt->bindParam(':price', $this->price);
            exit();
        } else {
            $sql = 'INSERT INTO ' . $this->tableName . '(order_id, product_id, quantity, price) VALUES';
            for ($i = 0; $i < $this->prod_count_in_array; $i++) {
                $sql .= '(:order_id, :product_id' . $i . ', :quantity' . $i . ', :price' . $i . ')';
                if ($i != $this->prod_count_in_array - 1) {
                    $sql .= ',';
                }
            }
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':order_id', $this->order_id);
            for ($i = 0; $i < $this->prod_count_in_array; $i++) {
                $stmt->bindParam(':product_id' . $i, $this->prod_id_array[$i]);
                $stmt->bindParam(':quantity' . $i, $this->quantity_array[$i]);
                $stmt->bindParam(':price' . $i, $this->price_array[$i]);
            }
        }
        

        // var_dump($this->prod_id_array);
        // var_dump($this->quantity_array);
        // var_dump($this->price_array);
        
        if ($stmt->execute()) {
            // var_dump($this->prod_id_array);
            // var_dump($this->quantity_array);
            // var_dump($this->price_array);
            $this->prod_count_in_array = 0;
            return true;
        } else {
            return false;
        }
    }
    ///////////////////////////////////////////////////////////////////////////////
}
