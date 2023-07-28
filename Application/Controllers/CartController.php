<?php

namespace Application\Controllers;

use System\MVC\Controller;
use System\DB\Database;
use Application\Models\Cart;

/**
 * Class CartController
 * @package Application\Controllers
 */
class CartController extends Controller
{
    /**
     * @var \PDO|null
     */
    public $db;
    /**
     * @var Cart
     */
    public $cart;

    /**
     * CartController constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $categoryModel = $this->model('Cart', $this->db);
        $this->cart = new Cart($this->db);
    }

    /**
     *
     */
    public function create()
    {
        $user_id = isset($_POST['user_id']) ?$_POST['user_id']: '';
        $product_id = isset($_POST['product_id']) ?$_POST['product_id']: '';
        $amount = isset($_POST['amount']) ?$_POST['amount']: '';

        if (!empty($user_id) && !empty($product_id) && !empty($amount))
        {
            $this->cart->user_id = $user_id;
            $this->cart->product_id = $product_id;
            $this->cart->amount = $amount;

            if ($this->cart->create())
            {
                http_response_code(201);
                echo json_encode(array("message" => "Товар добавлен в корзину."), JSON_UNESCAPED_UNICODE);
            }
            else
            {
                http_response_code(503);
                echo json_encode(array("message" => "Невозможно добавить в корзину."), JSON_UNESCAPED_UNICODE);
            }
        }
        else
        {
            http_response_code(400);
            echo json_encode(array("message" => "Невозможно добавить в корзину. Данные неполные."), JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * @param $id
     */
    public function delete($id)
    {
        $id = (int) substr($id, strrpos($id, '/') + 1);
        $this->cart->id = $id;
        if ($this->cart->delete())
        {
            http_response_code(200);
            echo json_encode(array("message" => "Товар был удалён"), JSON_UNESCAPED_UNICODE);
        }
        else
        {
            http_response_code(503);
            echo json_encode(array("message" => "Не удалось удалить товар"));
        }
    }

    /**
     *
     */
    public function read()
    {
        $stmt = $this->cart->read();
        $num = $stmt->rowCount();

        if ($num > 0)
        {
            $carts_arr = array();
            $carts_arr["records"] = array();

            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC))
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
        }
        else
        {
            http_response_code(404);
            echo json_encode(array("message" => "Корзины не найдены."), JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * @param $id
     */
    public function read_user_cart($id)
    {
        $user_id = (int) substr($id, strrpos($id, '/') + 1);
        $this->cart->id = $user_id;

        $stmt = $this->cart->read_user_cart($user_id);
        $num = $stmt->rowCount();

        if ($num > 0)
        {
            $carts_arr = array();
            $carts_arr['carts'] = array();

            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC))
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
        }
        else
        {
            http_response_code(404);
            echo json_encode(array("message" => "Корзины не найдены"), JSON_UNESCAPED_UNICODE);
        }
    }
    /**
     * @param $id
     */
    public function update($id)
    {
        $id = (int) substr($id, strrpos($id, '/') + 1);
        $this->cart->id = $id;

        parse_str(file_get_contents("php://input"), $patchData);
        $this->cart->amount = (int)(isset($patchData['amount']) ? $patchData['amount'] : '');

        if ($this->cart->update())
        {
            http_response_code(200);
            echo json_encode(array("message" => "Корзина была обновлена"), JSON_UNESCAPED_UNICODE);
        }
        else
        {
            http_response_code(503);
            echo json_encode(array("message" => "Не удалось обновить корзину"), JSON_UNESCAPED_UNICODE);
        }
    }

}