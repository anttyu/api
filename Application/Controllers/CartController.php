<?php

namespace Application\Controllers;
use MVC\Controller;

class CartController extends Controller
{
    public function create()
    {
        $database = new Database();
        $db = $database->getConnection();

        $cart = new Cart($db);

        $user_id = isset($_GET['user_id']) ?? '';
        $product_id = isset($_GET['product_id']) ?? '';
        $amount = isset($_GET['amount']) ?? '';

        if (!empty($user_id) && !empty($product_id) && !empty($amount))
        {
            $cart->user_id = $user_id;
            $cart->product_id = $product_id;
            $cart->amount = $amount;

            if ($cart->create())
            {
                http_response_code(201);
                echo json_encode(array("message" => "Товар добавлен в корзину."), JSON_UNESCAPED_UNICODE);
            } else {

                http_response_code(503);
                echo json_encode(array("message" => "Невозможно добавить в корзину."), JSON_UNESCAPED_UNICODE);
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Невозможно добавить в корзину. Данные неполные."), JSON_UNESCAPED_UNICODE);
        }
    }

    public function delete()
    {
        $database = new Database();
        $db = $database->getConnection();

        $cart = new Cart($db);
        $cart->id = isset($_GET['id']) ?? die();

        if ($cart->delete())
        {
            http_response_code(200);
            echo json_encode(array("message" => "Товар был удалён"), JSON_UNESCAPED_UNICODE);
        }
        else {
            http_response_code(503);
            echo json_encode(array("message" => "Не удалось удалить товар"));
        }
    }

    public function read()
    {
        $database = new Database();
        $db = $database->getConnection();

        $cart = new Cart($db);

        $stmt = $cart->read();
        $num = $stmt->rowCount();

        if ($num > 0)
        {

            $carts_arr = array();
            $carts_arr["records"] = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
            {

                extract($row);
                $carts_item = array
                (
                    "id" => $id,
                    "user_id" => $user_id,
                    "product_id" => $product_id,
                    "amount" => $amount,
                );
                array_push($carts_arr["records"], $carts_item);
            }

            http_response_code(200);

            echo json_encode($carts_arr);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "Пользователи не найдены."), JSON_UNESCAPED_UNICODE);
        }
    }

    public function read_user_cart()
    {
        $database = new Database();
        $db = $database->getConnection();

        $cart = new Cart($db);

        $user_id = isset($_GET['user_id']) ?? die();

        $stmt = $cart->read_user_cart($user_id);
        $num = $stmt->rowCount();

        if ($num > 0)
        {
            $carts_arr = array();
            $carts_arr['carts'] = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
            {
                extract($row);

                $cart_item = array
                (
                    "id" => $id,
                    "user_id" => $user_id,
                    "product_id" => $product_id,
                    "amount" => $amount
                );

                array_push($carts_arr['carts'], $cart_item);
            }
            http_response_code(200);
            echo json_encode($carts_arr, JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "Корзины не найдены"), JSON_UNESCAPED_UNICODE);
        }
    }

    public function update()
    {
        $database = new Database();
        $db = $database->getConnection();

        $cart = new Cart($db);
        $cart->id = isset($_GET['id']) ?? die();

        if (isset($_GET['amount']))
        {
            $cart->amount = $_GET['amount'];
        }

        if ($cart->update())
        {
            http_response_code(200);
            echo json_encode(array("message" => "Пользователь был обновлен"), JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Невозможно обновить пользователя"), JSON_UNESCAPED_UNICODE);
        }
    }
}