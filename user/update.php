<?php

include_once "../config/database.php";
include_once "../objects/user.php";

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$user->id = isset($_GET['id']) ? $_GET['id'] : die();

if (isset($_GET['name'])) {
    $user->name = $_GET['name'];
}
if (isset($_GET['email'])) {
    $user->email = $_GET['email'];
}
if (isset($_GET['password'])) {
    $user->password = $_GET['password'];
}


if ($user->update()) {

    http_response_code(200);

    echo json_encode(array("message" => "Пользователь был обновлен"), JSON_UNESCAPED_UNICODE);
} else {
    http_response_code(503);

    echo json_encode(array("message" => "Невозможно обновить пользователя"), JSON_UNESCAPED_UNICODE);
}

?>
