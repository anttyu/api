<?php

namespace Application\Controllers;

use System\MVC\Controller;
use System\DB\Database;
use Application\Models\Product;

class ProductController extends Controller
{
    public $db;
    public $product;
    public $name;
    public $price;
    public $category_id;
    public $created;
    public $modified;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $categoryModel = $this->model('Product', $this->db);
        $this->product = new Product($this->db);
    }

    public function create()
    {
        $name = isset($_POST['name']) ? $_POST['name'] : '';
        $price = isset($_POST['price']) ?$_POST['price']: '';
        $description = isset($_POST['description']) ?$_POST['description']: '';
        $category_id = isset($_POST['category_id']) ?$_POST['category_id']: '';

        // убеждаемся, что данные не пусты
        if (!empty($name) && !empty($price) && !empty($description) && !empty($category_id))
        {
            $this->product->name = $name;
            $this->product->price = $price;
            $this->product->description = $description;
            $this->product->category_id = $category_id;
            $this->product->created = date("Y-m-d H:i:s");

            // создание товара
            if ($this->product->create())
            {
                // установим код ответа - 201 создано
                http_response_code(201);

                // сообщим пользователю
                echo json_encode(array("message" => "Товар был создан."), JSON_UNESCAPED_UNICODE);
            } else {
                // установим код ответа - 503 сервис недоступен
                http_response_code(503);

                // сообщим пользователю
                echo json_encode(array("message" => "Невозможно создать товар."), JSON_UNESCAPED_UNICODE);
            }
        } else {
            // установим код ответа - 400 неверный запрос
            http_response_code(400);

            // сообщим пользователю
            echo json_encode(array("message" => "Невозможно создать товар. Данные неполные."), JSON_UNESCAPED_UNICODE);
        }
            }

    public function read()
    {

        $stmt = $this->product->read();
        $num = $stmt->rowCount();

        if ($num > 0) {

            $products_arr = array();
            $products_arr["records"] = array();

            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC))
            {
                extract($row);
                $product_item = array
                (
                    "id" => $id,
                    "name" => $name,
                    "description" => html_entity_decode($description),
                    "price" => $price,
                    "category_id" => $category_id,
                    "category_name" => $category_name
                );
                array_push($products_arr["records"], $product_item);
            }
            http_response_code(200);
            echo json_encode($products_arr);
        } else {
            http_response_code(404);

            echo json_encode(array("message" => "Товары не найдены."), JSON_UNESCAPED_UNICODE);
        }
    }

    public function update()
    {
        $this->product->id = isset($_GET['id']) ?? die();

        if (isset($_GET['name']))
        {
            $this->product->name = $_GET['name'];
        }
        if (isset($_GET['price']))
        {
            $this->product->price = $_GET['price'];
        }
        if (isset($_GET['description']))
        {
            $this->product->description = $_GET['description'];
        }
        if (isset($_GET['category_id']))
        {
            $this->product->category_id = $_GET['category_id'];
        }

        if ($this->product->update())
        {
            http_response_code(200);
            echo json_encode(array("message" => "Товар был обновлен"), JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Невозможно обновить товар"), JSON_UNESCAPED_UNICODE);
        }
    }

    public function delete($id)
    {
        $id = (int) substr($id, strrpos($id, '/') + 1);
        $this->product->id = $id;

        if ($this->product->delete())
        {
            http_response_code(200);
            echo json_encode(array("message" => "Товар был удалён"), JSON_UNESCAPED_UNICODE);
        }
        else {
            http_response_code(503);
            echo json_encode(array("message" => "Не удалось удалить товар"));
        }
    }

    public function search($keywords)
    {
        $keywords =  substr($keywords, strrpos($keywords, '/') + 1);
        $stmt = $this->product->search($keywords);
        $num = $stmt->rowCount();

        if ($num > 0)
        {
            $products_arr = array();
            $products_arr["records"] = array();
            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC))
            {
                extract($row);
                $product_item = array(
                    "id" => $id,
                    "name" => $name,
                    "description" => html_entity_decode($description),
                    "price" => $price,
                    "category_id" => $category_id,
                    "category_name" => $category_name
                );
                array_push($products_arr["records"], $product_item);
            }
            http_response_code(200);
            echo json_encode($products_arr);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "Товары не найдены."), JSON_UNESCAPED_UNICODE);
        }
    }

    public function read_one($id)
    {
        $id = (int) substr($id, strrpos($id, '/') + 1);
        $stmt = $this->product->read_one($id);
        $num = $stmt->rowCount();

        if ($num > 0)
        {
            $products_arr = array();
            $products_arr['records'] = array();
            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC))
            {
                extract($row);

                $product_item = array(
                    "id" => $id,
                    "name" => $name,
                    "description" => $price,
                    "category_id" => $category_id,
                    "created" => $created,
                    "modified" => $modified
                );
                array_push($products_arr['records'], $product_item);
            }
            http_response_code(200);
            echo json_encode($product_item, JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "Продукты не найдены"), JSON_UNESCAPED_UNICODE);
        }
    }

    public function read_paging()
    {
        $utilities = new \Utilities();
        $stmt = $this->product->readPaging($from_record_num, $records_per_page);
        $num = $stmt->rowCount();

        if ($num > 0)
        {
            // массив товаров
            $products_arr = array();
            $products_arr["records"] = array();
            $products_arr["paging"] = array();

            // получаем содержимое нашей таблицы
            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC))
            {
                // извлечение строки
                extract($row);
                $product_item = array
                (
                    "id" => $id,
                    "name" => $name,
                    "description" => html_entity_decode($description),
                    "price" => $price,
                    "category_id" => $category_id,
                    "category_name" => $category_name
                );
                array_push($products_arr["records"], $product_item);
            }

            // подключим пагинацию
            $total_rows = $this->product->count();
            $page_url = "{$home_url}product/read_paging.php?";
            $paging = $utilities->getPaging($page, $total_rows, $records_per_page, $page_url);
            $products_arr["paging"] = $paging;

            // установим код ответа - 200 OK
            http_response_code(200);

            // вывод в json-формате
            echo json_encode($products_arr);
        } else {

            // код ответа - 404 Ничего не найдено
            http_response_code(404);

            // сообщим пользователю, что товаров не существует
            echo json_encode(array("message" => "Товары не найдены"), JSON_UNESCAPED_UNICODE);
        }
    }
}
