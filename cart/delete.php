<?php

include_once "../config/database.php";
include_once "../objects/cart.php";

$database = new Database();
$db = $database->getConnection();

$cart = new Cart($db);
$cart->id = isset($_GET['id']) ? $_GET['id'] : die();

if ($cart->delete()) {
    http_response_code(200);
    echo json_encode(array("message" => "Товар был удалён"), JSON_UNESCAPED_UNICODE);
}
else {
    http_response_code(503);
    echo json_encode(array("message" => "Не удалось удалить товар"));
}