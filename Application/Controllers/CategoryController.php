<?php

namespace Application\Controllers;

use System\MVC\Controller;
use System\DB\Database;
use Application\Models\Category;

class CategoryController extends Controller
{
    public $db;
    public $category;
    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $categoryModel = $this->model('Category', $this->db);
        $this->category = new Category($this->db);
    }

    public function read()
    {
        $stmt = $this->category->read();
        $num = $stmt->rowCount();

        if ($num > 0)
        {

            $categories_arr = array();
            $categories_arr["records"] = array();
            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC))
            {
                extract($row);
                $category_item = array
                (
                    "id" => $id,
                    "name" => $name,
                    "description" => html_entity_decode($description)
                );
                array_push($categories_arr["records"], $category_item);
            }
            http_response_code(200);
            echo json_encode($categories_arr);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "Категории не найдены"), JSON_UNESCAPED_UNICODE);
        }
    }
}