<?php
include_once "../config/database.php";
include_once "../objects/cart.php";

$database = new Database();
$db = $database->getConnection();

$cart = new Cart($db);

$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : die();

$stmt = $cart->read_user_cart($user_id);
$num = $stmt->rowCount();

if ($num > 0) {
    $carts_arr = array();
    $carts_arr['carts'] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $cart_item = array(
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

?>
