<?php

include_once "../config/database.php";
include_once "../objects/cart.php";

$database = new Database();
$db = $database->getConnection();

$cart = new Cart($db);

$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : '';
$product_id = isset($_GET['product_id']) ? $_GET['product_id'] : '';
$amount = isset($_GET['amount']) ? $_GET['amount'] : '';

if (!empty($user_id) && !empty($product_id) && !empty($amount)) {

    $cart->user_id = $user_id;
    $cart->product_id = $product_id;
    $cart->amount = $amount;

    if ($cart->create()) {
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


