<?php

// HTTP-заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// подключаем файл для работы с БД и объектом Product
include_once "../config/database.php";
include_once "../objects/product.php";

// получаем соединение с базой данных
$database = new Database();
$db = $database->getConnection();

// подготовка объекта
$product = new Product($db);

// получаем id товара для редактирования из URL-параметров
$product->id = isset($_GET['id']) ? $_GET['id'] : die();

// получаем данные из URL-параметров
if (isset($_GET['name'])) {
    $product->name = $_GET['name'];
}
if (isset($_GET['price'])) {
    $product->price = $_GET['price'];
}
if (isset($_GET['description'])) {
    $product->description = $_GET['description'];
}
if (isset($_GET['category_id'])) {
    $product->category_id = $_GET['category_id'];
}

// обновление товара
if ($product->update()) {
    // установим код ответа - 200 OK
    http_response_code(200);

    // сообщим пользователю
    echo json_encode(array("message" => "Товар был обновлен"), JSON_UNESCAPED_UNICODE);
} else {
    // установим код ответа - 503 Service Unavailable
    http_response_code(503);

    // сообщим пользователю
    echo json_encode(array("message" => "Невозможно обновить товар"), JSON_UNESCAPED_UNICODE);
}

?>
