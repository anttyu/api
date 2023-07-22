<?php

namespace Application\Controllers;
use System\MVC\Controller;
use System\DB\Database;
use Application\Models\Category;

class ProductController extends Controller
{
    public $db;
    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $categoryModel = $this->model('Product', $this->db);
    }

    public function create()
    {
        $database = new Database();
        $db = $database->getConnection();
        $product = new Product($db);

        $name = isset($_GET['name']) ?? '';
        $price = isset($_GET['price']) ?? '';
        $description = isset($_GET['description']) ?? '';
        $category_id = isset($_GET['category_id']) ?? '';

        // убеждаемся, что данные не пусты
        if (!empty($name) && !empty($price) && !empty($description) && !empty($category_id))
        {
            $product->name = $name;
            $product->price = $price;
            $product->description = $description;
            $product->category_id = $category_id;
            $product->created = date("Y-m-d H:i:s");

            // создание товара
            if ($product->create())
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
        $database = new Database();
        $db = $database->getConnection();

        $product = new Product($db);

        $stmt = $product->read();
        $num = $stmt->rowCount();

        if ($num > 0) {

            $products_arr = array();
            $products_arr["records"] = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
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
        $database = new Database();
        $db = $database->getConnection();

        $product = new Product($db);

        $product->id = isset($_GET['id']) ?? die();

        if (isset($_GET['name']))
        {
            $product->name = $_GET['name'];
        }
        if (isset($_GET['price']))
        {
            $product->price = $_GET['price'];
        }
        if (isset($_GET['description']))
        {
            $product->description = $_GET['description'];
        }
        if (isset($_GET['category_id']))
        {
            $product->category_id = $_GET['category_id'];
        }

        if ($product->update())
        {
            http_response_code(200);

            echo json_encode(array("message" => "Товар был обновлен"), JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(503);

            echo json_encode(array("message" => "Невозможно обновить товар"), JSON_UNESCAPED_UNICODE);
        }
    }

    public function delete()
    {
        $database = new Database();
        $db = $database->getConnection();
        $product = new Product($db);
        $product->id = isset($_GET['id']) ?? die();

        if ($product->delete())
        {
            http_response_code(200);

            echo json_encode(array("message" => "Товар был удалён"), JSON_UNESCAPED_UNICODE);
        }
        else {
            http_response_code(503);

            echo json_encode(array("message" => "Не удалось удалить товар"));
        }
    }

    public function search()
    {
        $database = new Database();
        $db = $database->getConnection();

        $product = new Product($db);

        $keywords = isset($_GET["s"]) ?? "";

        $stmt = $product->search($keywords);
        $num = $stmt->rowCount();

        if ($num > 0)
        {

            $products_arr = array();
            $products_arr["records"] = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
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

    public function read_one()
    {
        $database = new Database();
        $db = $database->getConnection();

        $product = new Product($db);

        $product->id = isset($_GET["id"]) ?? die();

        $product->read_one();

        if ($product->name != null)
        {
            $product_arr = array(
                "id" =>  $product->id,
                "name" => $product->name,
                "description" => $product->description,
                "price" => $product->price,
                "category_id" => $product->category_id,
                "category_name" => $product->category_name
            );

            http_response_code(200);

            echo json_encode($product_arr);
        } else {
            http_response_code(404);

            echo json_encode(array("message" => "Товар не существует"), JSON_UNESCAPED_UNICODE);
        }
    }

    public function read_paging()
    {
        $utilities = new Utilities();

        $database = new Database();
        $db = $database->getConnection();

        $product = new Product($db);

        $stmt = $product->readPaging($from_record_num, $records_per_page);
        $num = $stmt->rowCount();

        if ($num > 0)
        {
            // массив товаров
            $products_arr = array();
            $products_arr["records"] = array();
            $products_arr["paging"] = array();

            // получаем содержимое нашей таблицы
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
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
            $total_rows = $product->count();
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
