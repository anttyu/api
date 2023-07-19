<?php

// необходимые HTTP-заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// получаем соединение с базой данных
include_once "../config/database.php";

// создание объекта товара
include_once "../objects/product.php";
$database = new Database();
$db = $database->getConnection();
$product = new Product($db);

$name = isset($_GET['name']) ? $_GET['name'] : '';
$price = isset($_GET['price']) ? $_GET['price'] : '';
$description = isset($_GET['description']) ? $_GET['description'] : '';
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : '';

// убеждаемся, что данные не пусты
if (!empty($name) && !empty($price) && !empty($description) && !empty($category_id)) {

    $product->name = $name;
    $product->price = $price;
    $product->description = $description;
    $product->category_id = $category_id;
    $product->created = date("Y-m-d H:i:s");

    // создание товара
    if ($product->create()) {
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

?>
