<?php
    require_once "../../../config/Rest.php";
    require_once "../../../utils/jwt.php";
    require_once "../../../models/Cat.php";

    class Api extends Rest
    {
        public function __construct()
        {
            parent::__construct();
        }

        public function getAllCategories()
        {
            $cat = new Cat;
            $cat = $cat->getAllCategories();

            if (!is_array($cat)) {
                $this->returnResponse(SUCCESS_RESPONSE, ['message' => 'The are no Category.']);
            }

            $this->returnResponse(SUCCESS_RESPONSE, $cat);
        }

        public function getCategoryById()
        {
            $id = $this->validateParameter('id', $this->param['id'], INTEGER);

            $cat = new Cat;
            $cat->setId($id);

            $cat = $cat->getCategoryById();

            if (!is_array($cat)) {
                $this->returnResponse(SUCCESS_RESPONSE, ['message' => 'The are no Category with that id.']);
            }

            $this->returnResponse(SUCCESS_RESPONSE, $cat);
        }

        public function addCategory()
        {
            $name = $this->validateParameter('name', $this->param['name'], STRING);

            $cat = new Cat;
            $cat->setName($name);

            if (!$cat->insert()) {
                $message = 'Failed to insert.';
            } else {
                $message = "Inserted successfully.";
            }

            $this->returnResponse(SUCCESS_RESPONSE, $message);
        }

        public function updateCategory()
        {
            $id = $this->validateParameter('id', $this->param['id'], INTEGER);
            $name = $this->validateParameter('name', $this->param['name'], STRING);
    
            $cat = new Cat;
            $cat->setId($id);
            $cat->setName($name);

            if (!$cat->update()) {
                $message = 'Failed to update.';
            } else {
                $message = "Updated successfully.";
            }

            $this->returnResponse(SUCCESS_RESPONSE, $message);
        }

        public function deleteCategory()
        {
            $id = $this->validateParameter('id', $this->param['id'], INTEGER);

            $cat = new Cat;
            $cat->setId($id);

            if (!$cat->delete()) {
                $message = 'Failed to delete.';
            } else {
                $message = "Deleted successfully.";
            }

            $this->returnResponse(SUCCESS_RESPONSE, $message);
        }
    }
