<?php
    require_once "../../../config/Rest.php";
    require_once "../../../utils/jwt.php";
    require_once "../../../models/Product.php";

    class Api extends Rest
    {
        public function __construct()
        {
            parent::__construct();
        }

        public function getAllProds()
        {
            $prod = new Product;
            $prods = $prod->getAllProds();

            if (!is_array($prods)) {
                $this->returnResponse(SUCCESS_RESPONSE, ['message' => 'The are no Products.']);
            }

            $this->returnResponse(SUCCESS_RESPONSE, $prods);
        }

        public function getProdsByCat()
        {
            $cat_id = $this->validateParameter('cat_id', $this->param['cat_id'], INTEGER);

            $prod = new Product;
            $prod->setCat_id($cat_id);
            $prods = $prod->getProdDetailsByCat();

            if (!is_array($prods)) {
                $this->returnResponse(SUCCESS_RESPONSE, ['message' => 'The are no Products.']);
            }

            $this->returnResponse(SUCCESS_RESPONSE, $prods);
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

        public function addProd()
        {
            $name = $this->validateParameter('name', $this->param['name'], STRING);
            $price = $this->validateParameter('price', $this->param['price'], INTEGER);
            $status = $this->validateParameter('status', $this->param['status'], INTEGER);
            $cat_id = $this->validateParameter('cat_id', $this->param['cat_id'], INTEGER);
            $avatar = "avatar.jpg";

            $status > 0? $status = 1 : $status = 0;

            $prod = new Product();
            $prod->setName($name);
            $prod->setPrice($price);
            $prod->setStatus($status);
            $prod->setAvatar($avatar);
            $prod->setCat_id($cat_id);

            if (!$prod->insert()) {
                $message = 'Failed to insert.';
            } else {
                $message = "Inserted successfully.";
            }

            $this->returnResponse(SUCCESS_RESPONSE, $message);
        }

        public function updateProd()
        {
            $id = $this->validateParameter('id', $this->param['id'], INTEGER);
            $name = $this->validateParameter('name', $this->param['name'], STRING);
            $price = $this->validateParameter('price', $this->param['price'], INTEGER);
            $status = $this->validateParameter('status', $this->param['status'], INTEGER);
            $cat_id = $this->validateParameter('cat_id', $this->param['cat_id'], INTEGER);
            // $avatar = "avatar.jpg";

            $status > 0? $status = 1 : $status = 0;

            $prod = new Product();
            $prod->setId($id);
            $prod->setName($name);
            $prod->setPrice($price);
            $prod->setStatus($status);
            $prod->setCat_id($cat_id);
            // $cust->setAvatar($avatar);

            if (!$prod->update()) {
                $message = 'Failed to update.';
            } else {
                $message = "Updated successfully.";
            }

            $this->returnResponse(SUCCESS_RESPONSE, $message);
        }
        
        public function updateProdStatus()
        {
            $id = $this->validateParameter('id', $this->param['id'], INTEGER);
            $status = $this->validateParameter('status', $this->param['status'], INTEGER);
            // $avatar = "avatar.jpg";

            $status > 0? $status = 1 : $status = 0;

            $prod = new Product();
            $prod->setId($id);
            $prod->setStatus($status);
            // $cust->setAvatar($avatar);

            if (!$prod->updateStatus()) {
                $message = 'Failed to update.';
            } else {
                $message = "Updated successfully.";
            }

            $this->returnResponse(SUCCESS_RESPONSE, $message);
        }

        public function deleteProd()
        {
            $id = $this->validateParameter('id', $this->param['id'], INTEGER);

            $prod = new Product();
            $prod->setId($id);

            if (!$prod->delete()) {
                $message = 'Failed to delete.';
            } else {
                $message = "Deleted successfully.";
            }

            $this->returnResponse(SUCCESS_RESPONSE, $message);
        }
    }
