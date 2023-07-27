<?php

namespace Application\Controllers;

use System\MVC\Controller;
use System\DB\Database;
use Application\Models\User;
use System\Http\Response;

class UserController extends Controller
{
    public $db;
    public $user;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $categoryModel = $this->model('User', $this->db);
        $this->user = new User($this->db);
    }

    public function create()
    {
        $name = isset($_POST['name']) ? $_POST['name'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        if (!empty($name) && !empty($email) && !empty($password))
        {
            $this->user->name = $name;
            $this->user->email = $email;
            $this->user->password = $password;
            $this->user->registration_date = date("Y-m-d");

            if ($this->user->create())
            {
                http_response_code(201);
                echo json_encode(array("message" => "Пользователь был создан."), JSON_UNESCAPED_UNICODE);
            }
            else
            {
                http_response_code(503);
                echo json_encode(array("message" => "Невозможно создать пользователя."), JSON_UNESCAPED_UNICODE);
            }
        }
        else
        {
            http_response_code(400);
            echo json_encode(array("message" => "Невозможно создать пользователя. Данные неполные."), JSON_UNESCAPED_UNICODE);
        }
    }

    public function delete($id)
    {
        $id = (int) substr($id, strrpos($id, '/') + 1);
        $this->user->id = $id;
        if ($this->user->delete())
        {
            http_response_code(200);
            echo json_encode(array("message" => "Пользователь был удалён"), JSON_UNESCAPED_UNICODE);
        }
        else
        {
            http_response_code(503);
            echo json_encode(array("message" => "Не удалось удалить пользователя"));
        }
    }

    public function read()
    {
        $stmt = $this->user->read();
        $num = $stmt->rowCount();

        if ($num > 0)
        {
            $users_arr = array();
            $users_arr["records"] = array();

            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC))
            {
                extract($row);
                $users_item = array
                (
                    "id" => $id,
                    "name" => $name,
                    "email" => $email,
                    "password" => $password,
                    "registration_date" => $registration_date
                );
                array_push($users_arr["records"], $users_item);
            }
            http_response_code(200);
            echo json_encode($users_arr);
        }
        else
        {
            http_response_code(404);
            echo json_encode(array("message" => "Пользователи не найдены."), JSON_UNESCAPED_UNICODE);
        }
    }

    public function update($id)
    {
        $id = (int) substr($id, strrpos($id, '/') + 1);
        $this->user->id = $id;

        parse_str(file_get_contents("php://input"), $patchData);

        if (isset($patchData['name']))
        {
            $this->user->name = $patchData['name'];
        }
        if (isset($patchData['email']))
        {
            $this->user->email = $patchData['email'];
        }
        if (isset($patchData['password']))
        {
            $this->user->password = $patchData['password'];
        }

        if ($this->user->update())
        {
            http_response_code(200);
            echo json_encode(array("message" => "Пользователь был обновлен"), JSON_UNESCAPED_UNICODE);
        }
        else
        {
            http_response_code(503);
            echo json_encode(array("message" => "Невозможно обновить пользователя"), JSON_UNESCAPED_UNICODE);
        }
    }
    public function read_one($id)
    {
        $id = (int) substr($id, strrpos($id, '/') + 1);
        $stmt = $this->user->read_one($id);
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
                    "email" => $email,
                    "password" => $password
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