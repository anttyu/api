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

    public function read_one($id)
    {
        $id = (int) substr($id, strrpos($id, '/') + 1);
        $stmt = $this->category->read_one($id);
        $num = $stmt->rowCount();

        if ($num > 0)
        {
            $category_arr = array();
            $category_arr['records'] = array();
            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC))
            {
                extract($row);

                $category_item = array(
                    "id" => $id,
                    "name" => $name,
                    "description" => $description
                );
                array_push($category_arr['records'], $category_item);
            }
            http_response_code(200);
            echo json_encode($category_item, JSON_UNESCAPED_UNICODE);
        }
        else
        {
            http_response_code(404);
            echo json_encode(array("message" => "Категории не найдены"), JSON_UNESCAPED_UNICODE);
        }
    }

    public function create()
    {
        $name = isset($_POST['name']) ? $_POST['name'] : '';
        $description = isset($_POST['description']) ?$_POST['description']: '';

        if (!empty($name) && !empty($description))
        {
            $this->category->name = $name;
            $this->category->description = $description;
            $this->category->created = date("Y-m-d H:i:s");
            $this->category->modified = date("Y-m-d H:i:s");

            if ($this->category->create())
            {
                // установим код ответа - 201 создано
                http_response_code(201);
                // сообщим пользователю
                echo json_encode(array("message" => "Категория была создана."), JSON_UNESCAPED_UNICODE);
            }
            else
            {
                // установим код ответа - 503 сервис недоступен
                http_response_code(503);
                // сообщим пользователю
                echo json_encode(array("message" => "Невозможно создать категорию."), JSON_UNESCAPED_UNICODE);
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

    public function update($id)
    {
        $id = (int) substr($id, strrpos($id, '/') + 1);
        $this->category->id = $id;
        $this->category->modified = date("Y-m-d H:i:s");
        parse_str(file_get_contents("php://input"), $patchData);

        if (isset($patchData['name']))
        {
            $this->category->name = $patchData['name'];
        }
        if (isset($patchData['description']))
        {
            $this->category->description = $patchData['description'];
        }
        if ($this->category->update())
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
        $this->category->id = $id;

        if ($this->category->delete())
        {
            http_response_code(200);
            echo json_encode(array("message" => "Товар был удалён"), JSON_UNESCAPED_UNICODE);
        }
        else {
            http_response_code(503);
            echo json_encode(array("message" => "Не удалось удалить товар"));
        }
    }
}