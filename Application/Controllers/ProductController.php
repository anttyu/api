<?php

namespace Application\Controllers;

use System\MVC\Controller;
use System\DB\Database;
use Application\Models\Product;

/**
 * Class ProductController
 * @package Application\Controllers
 */
class ProductController extends Controller
{
    /**
     * @var \PDO|null
     */
    public $db;
    /**
     * @var Product
     */
    public $product;

    /**
     * ProductController constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $categoryModel = $this->model('Product', $this->db);
        $this->product = new Product($this->db);
    }

    /**
     *
     */
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
            }
            else
            {
                // установим код ответа - 503 сервис недоступен
                http_response_code(503);
                // сообщим пользователю
                echo json_encode(array("message" => "Невозможно создать товар."), JSON_UNESCAPED_UNICODE);
            }
        }
        else
        {
            // установим код ответа - 400 неверный запрос
            http_response_code(400);
            // сообщим пользователю
            echo json_encode(array("message" => "Невозможно создать товар. Данные неполные."), JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     *
     */
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
        }
        else
        {
            http_response_code(404);
            echo json_encode(array("message" => "Товары не найдены."), JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * @param $id
     */
    public function update($id)
    {
        $id = (int) substr($id, strrpos($id, '/') + 1);
        $this->product->id = $id;

        parse_str(file_get_contents("php://input"), $patchData);

        if (isset($patchData['name']))
        {
            $this->product->name = $patchData['name'];
        }
        if (isset($patchData['price']))
        {
            $this->product->price = $patchData['price'];
        }
        if (isset($patchData['description']))
        {
            $this->product->description = $patchData['description'];
        }
        if (isset($patchData['category_id']))
        {
            $this->product->category_id = $patchData['category_id'];
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

    /**
     * @param $id
     */
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

    /**
     * @param $keywords
     */
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

    /**
     * @param $id
     */
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
                    "description" => $description,
                    "category_id" => $category_id,
                    "created" => $created,
                    "modified" => $modified
                );
                array_push($products_arr['records'], $product_item);
            }
            http_response_code(200);
            echo json_encode($product_item, JSON_UNESCAPED_UNICODE);
        }
        else
        {
            http_response_code(404);
            echo json_encode(array("message" => "Продукты не найдены"), JSON_UNESCAPED_UNICODE);
        }
    }
}
