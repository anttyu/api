<?php

include_once "../config/database.php";
include_once "../objects/user.php";

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$user->id = isset($_GET['id']) ? $_GET['id'] : die();

if ($user->delete()) {

    http_response_code(200);

    echo json_encode(array("message" => "Пользователь был удалён"), JSON_UNESCAPED_UNICODE);
}

else {

    http_response_code(503);

    echo json_encode(array("message" => "Не удалось удалить пользователя"));
}