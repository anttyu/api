<?php
include_once "../config/database.php";
include_once "../objects/cart.php";

$database = new Database();
$db = $database->getConnection();

$cart = new Cart($db);

$stmt = $cart->read();
$num = $stmt->rowCount();

if ($num > 0) {

    $carts_arr = array();
    $carts_arr["records"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        extract($row);
        $carts_item = array(
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