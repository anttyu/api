<?php

namespace Application\Controllers;
use System\MVC\Controller;
use System\DB\Database;
use Application\Models\Category;

class UserController extends Controller
{
    public $db;
    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $categoryModel = $this->model('User', $this->db);
    }

    public function create()
    {
        $database = new Database();
        $db = $database->getConnection();

        $user = new User($db);

        $name = isset($_GET['name']) ? $_GET['name'] : '';
        $email = isset($_GET['email']) ? $_GET['email'] : '';
        $password = isset($_GET['password']) ? $_GET['password'] : '';

        if (!empty($name) && !empty($email) && !empty($password))
        {
            $user->name = $name;
            $user->email = $email;
            $user->password = $password;
            $user->registration_date = date("Y-m-d");

            if ($user->create())
            {
                http_response_code(201);
                echo json_encode(array("message" => "Пользователь был создан."), JSON_UNESCAPED_UNICODE);
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Невозможно создать пользователя."), JSON_UNESCAPED_UNICODE);
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Невозможно создать пользователя. Данные неполные."), JSON_UNESCAPED_UNICODE);
        }
    }

    public function delete()
    {
        $database = new Database();
        $db = $database->getConnection();

        $user = new User($db);

        $user->id = isset($_GET['id']) ? $_GET['id'] : die();

        if ($user->delete())
        {

            http_response_code(200);

            echo json_encode(array("message" => "Пользователь был удалён"), JSON_UNESCAPED_UNICODE);
        }

        else {

            http_response_code(503);

            echo json_encode(array("message" => "Не удалось удалить пользователя"));
        }
    }

    public function read()
    {
        $database = new Database();
        $db = $database->getConnection();

        $user = new User($db);

        $stmt = $user->read();
        $num = $stmt->rowCount();

        if ($num > 0) {

            $users_arr = array();
            $users_arr["records"] = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
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
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "Пользователи не найдены."), JSON_UNESCAPED_UNICODE);
        }
    }

    public function update()
    {
        $database = new Database();
        $db = $database->getConnection();

        $user = new User($db);

        $user->id = isset($_GET['id']) ? $_GET['id'] : die();

        if (isset($_GET['name']))
        {
            $user->name = $_GET['name'];
        }
        if (isset($_GET['email']))
        {
            $user->email = $_GET['email'];
        }
        if (isset($_GET['password']))
        {
            $user->password = $_GET['password'];
        }


        if ($user->update())
        {
            http_response_code(200);
            echo json_encode(array("message" => "Пользователь был обновлен"), JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Невозможно обновить пользователя"), JSON_UNESCAPED_UNICODE);
        }
    }
}