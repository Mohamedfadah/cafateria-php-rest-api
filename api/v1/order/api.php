<?php
    require_once "../../../config/Rest.php";
    require_once "../../../utils/jwt.php";
    require_once "../../../models/Order.php";
    require_once "../../../models/Client.php";
    require_once "../../../models/Product.php";
    require_once "../../../models/Product_Order.php";

    class Api extends Rest
    {
        public function __construct()
        {
            parent::__construct();
        }

        public function getAllOrders()
        {
            $order = new Order;
            $orders = $order->getAllOrders();

            if (!is_array($orders)) {
                $this->returnResponse(SUCCESS_RESPONSE, ['message' => 'The are no Products.']);
            }

            $this->returnResponse(SUCCESS_RESPONSE, $orders);
        }

        public function getOrderById()
        {
            $orderId = $this->validateParameter('id', $this->param['id'], INTEGER);

            $order = new Order;
            $order->setId($orderId);
            $orders = $order->getOrderById();

            if (!is_array($orders)) {
                $this->returnResponse(SUCCESS_RESPONSE, ['message' => 'The are no Products.']);
            }

            $this->returnResponse(SUCCESS_RESPONSE, $orders);
        }

        public function getOrdersByClientId()
        {
            $client_id = $this->validateParameter('customer_id', $this->param['customer_id'], INTEGER);

            $order = new Order;
            $order->setCustomer_id($client_id);
            $orders = $order->getAllOrdersByClientId();

            if (!is_array($orders)) {
                $this->returnResponse(SUCCESS_RESPONSE, ['message' => 'The are no Products.']);
            }

            $this->returnResponse(SUCCESS_RESPONSE, $orders);
        }

        public function getLastOrderByClientId()
        {
            $client_id = $this->validateParameter('customer_id', $this->param['customer_id'], INTEGER);

            $order = new Order;
            $order->setCustomer_id($client_id);
            $orders = $order->getLastOrderByClientId();

            if (!is_array($orders)) {
                $this->returnResponse(SUCCESS_RESPONSE, ['message' => 'The are no Products.']);
            }

            $this->returnResponse(SUCCESS_RESPONSE, $orders);
        }

        public function getProductsOfLastOrderByClientId()
        {
            $client_id = $this->validateParameter('customer_id', $this->param['customer_id'], INTEGER);

            $order = new Order;
            $order->setCustomer_id($client_id);
            $orders = $order->getLastOrderByClientId();

            if (!is_array($orders)) {
                $this->returnResponse(SUCCESS_RESPONSE, ['message' => 'The are no Products.']);
            }

            $product_order = new Product_Order;
            $product_order->setOrder_id($orders["id"]);
            $products = $product_order->getProdsOrdersDetailsByOrderId();

            $this->returnResponse(SUCCESS_RESPONSE, $products);
        }

        public function getProductsDetailsOfLastOrderByClientId()
        {
            $client_id = $this->validateParameter('customer_id', $this->param['customer_id'], INTEGER);

            $order = new Order;
            $order->setCustomer_id($client_id);
            $orders = $order->getLastOrderByClientId();

            if (!is_array($orders)) {
                $this->returnResponse(SUCCESS_RESPONSE, ['message' => 'The are no Products 1.']);
            }

            $product_order = new Product_Order;
            $product_order->setOrder_id($orders["id"]);
            $products = $product_order->getProdsOrdersDetailsByOrderId();

            if (!is_array($products)) {
                $this->returnResponse(SUCCESS_RESPONSE, ['message' => 'The are no Products in order.']);
            }

            $prod = new Product();
            foreach ($products as $pro) {
                $prod->fillInIds($pro["product_id"]);
            }

            $finalProducts = $prod->getProdsIn();
            if (!is_array($finalProducts)) {
                $this->returnResponse(SUCCESS_RESPONSE, ['message' => 'The are no Products 2.']);
            }

            $this->returnResponse(SUCCESS_RESPONSE, $finalProducts);
        }
        
        public function getProductsDetailsOfSpecificOrder()
        {
            $order_id = $this->validateParameter('order_id', $this->param['order_id'], INTEGER);

            $product_order = new Product_Order;
            $product_order->setOrder_id($order_id);
            $products = $product_order->getProdsOrdersDetailsByOrderId();

            if (!is_array($products)) {
                $this->returnResponse(SUCCESS_RESPONSE, ['message' => 'The are no Products in order.']);
            }

            $prod = new Product();
            foreach ($products as $pro) {
                $prod->fillInIds($pro["product_id"]);
            }

            $finalProducts = $prod->getProdsIn();
            if (!is_array($finalProducts)) {
                $this->returnResponse(SUCCESS_RESPONSE, ['message' => 'The are no Products 2.']);
            }

            $this->returnResponse(SUCCESS_RESPONSE, $finalProducts);
        }
        
        public function getProductsOrderOfSpecificOrder()
        {
            $order_id = $this->validateParameter('order_id', $this->param['order_id'], INTEGER);

            $product_order = new Product_Order;
            $product_order->setOrder_id($order_id);
            $products = $product_order->getProdsOrdersDetailsByOrderId();

            if (!is_array($products)) {
                $this->returnResponse(SUCCESS_RESPONSE, ['message' => 'The are no Products in order.']);
            }

            $this->returnResponse(SUCCESS_RESPONSE, $products);
        }
        
        // public function getProductsDetailsOfOrderProduct()
        // {
        //     $o_p_id = $this->validateParameter('$id', $this->param['id'], INTEGER);

        //     $product_order = new Product_Order;
        //     $product_order->setOrder_id($o_p_id);
        //     $products = $product_order->getProdsDetailsByProdOrderId();

        //     if (!is_array($products)) {
        //         $this->returnResponse(SUCCESS_RESPONSE, ['message' => 'The are no Products in order.']);
        //     }

        //     $this->returnResponse(SUCCESS_RESPONSE, $products);
        // }

        public function getProductsDetailsOfOrdersBetweenByClientId()
        {
            $client_id = $this->validateParameter('customer_id', $this->param['customer_id'], INTEGER);
            $start = $this->validateParameter('start', $this->param['start'], STRING);
            $end = $this->validateParameter('end', $this->param['end'], STRING);

            $order = new Order;
            $order->setCustomer_id($client_id);
            $order->setBoundaryOfTime($start, $end);
            $orders = $order->getOrdersByTimeBoundary();

            if (!is_array($orders)) {
                $this->returnResponse(SUCCESS_RESPONSE, ['message' => 'The are no Products.']);
            }

            $this->returnResponse(SUCCESS_RESPONSE, $orders);
        }

        public function getProdDetails()
        {
            $prodId = $this->validateParameter('id', $this->param['id'], INTEGER);

            $prod = new Product;
            $prod->setId($prodId);
            $product = $prod->getProdDetailsById();
            if (!is_array($product)) {
                $this->returnResponse(SUCCESS_RESPONSE, ['message' => 'Product details not found.']);
            }

            $response['id'] 	= $product['id'];
            $response['name'] 	= $product['name'];
            $response['price'] 	= $product['price'];
            $response['status'] 	= $product['status'];
            $response['avatar'] 	= $product['avatar'];
            $response['cat_id'] 	= $product['cat_id'];
            $this->returnResponse(SUCCESS_RESPONSE, $response);
        }

        private function checkClientExist($cust_id)
        {
            $cust = new Client;
            $cust->setId($cust_id);
            $client = $cust->getClientDetailsById();

            return is_array($client);
        }

        private function checkProductsExist($data)
        {
            $message = null;
            $prod = new Product;
            foreach ($data as $product) {
                $this->validateParameter('prod_id', $product['prod_id'], INTEGER);
                $this->validateParameter('quantity', $product['quantity'], INTEGER);
                $this->validateParameter('price', $product['price'], INTEGER);
                    
                $prod->setId($product['prod_id']);
                if (!$prod->getProdDetailsById()) {
                    $message = 'Product not found.';
                    return $message;
                }
            }
            return $message;
        }

        private function checkDataExist($data)
        {
            $message = null;
            if (!isset($data)) {
                $message = "Data must be Exist.";
            } elseif (!is_array($data)) {
                $message = "Data must be in array.";
            } elseif (count($data) == 0) {
                $message = 'You didn\'t choose any product for order.';
            }
            return $message? $message : $this->checkProductsExist($data);
        }

        public function addOrder()
        {
            $messageOfExistData = $this->checkDataExist($this->param['data']);
            if ($messageOfExistData != null) {
                $this->returnResponse(SUCCESS_RESPONSE, $messageOfExistData);
            }
            
            $cust_id = $this->validateParameter('customer_id', $this->param['customer_id'], INTEGER);
            $totalPrice = $this->validateParameter('price', $this->param['price'], INTEGER);
            $note = isset($this->param['note'])? $this->param['note'] : "";

            // Start Check the Customer is exist or not
            if (!$this->checkClientExist($cust_id)) {
                $this->returnResponse(SUCCESS_RESPONSE, ['message' => 'The are no clients.']);
            }

            // Add Order
            $date = date('Y-m-d H:i:s');
            $order = new Order;
            $order->setCustomer_id($cust_id);
            $order->setDate($date);
            $order->setStatus(0);
            $order->setPrice($totalPrice);
            $order->setNote($note);

            if (!$order->insert()) {
                $message = 'Failed to insert.';
                $this->returnResponse(SUCCESS_RESPONSE, $message);
            }

            $newOrder = $order->getOrderByTime();
            if (!$newOrder) {
                $message = 'Can\'t get The Order.';
                $this->returnResponse(SUCCESS_RESPONSE, $message);
            }

            $data = $this->param['data'];
            $prod_order = new Product_Order;
            $prod_order->setOrder_id($newOrder["id"]);
            foreach ($data as $product) {
                $prod_order->push($product['prod_id'], $product['quantity'], $product['price']);
            }

            if (!$prod_order->insert()) {
                $message = 'Failed to insert.';
            } else {
                $message = "Products Inserted successfully.";
            }

            $this->returnResponse(SUCCESS_RESPONSE, $message);
        }

        public function updateStatus()
        {
            $id = $this->validateParameter('id', $this->param['id'], INTEGER);
            $status = $this->validateParameter('status', $this->param['status'], INTEGER);
            
            $order = new Order();
            $order->setId($id);
            $order->setStatus($status);

            if (!$order->getOrderById()) {
                $message = 'Order not found.';
                $this->returnResponse(SUCCESS_RESPONSE, $message);
            }
            
            if (!$order->updateStatus()) {
                $message = 'Failed to update.';
            } else {
                $message = "Updated successfully.";
            }

            $this->returnResponse(SUCCESS_RESPONSE, $message);
        }

        public function deleteOrder()
        {
            $id = $this->validateParameter('id', $this->param['id'], INTEGER);

            $order = new Order();
            $order->setId($id);

            if (!$order->getOrderById()) {
                $message = 'Order not found.';
                $this->returnResponse(SUCCESS_RESPONSE, $message);
            }

            if (!$order->delete()) {
                $message = 'Failed to delete.';
            } else {
                $message = "Deleted successfully.";
            }

            $this->returnResponse(SUCCESS_RESPONSE, $message);
        }
    }
        //////////////////////////////////////////////////////////////
