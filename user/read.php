<?php
include_once "../config/database.php";
include_once "../objects/user.php";

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$stmt = $user->read();
$num = $stmt->rowCount();

if ($num > 0) {

    $users_arr = array();
    $users_arr["records"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        extract($row);
        $users_item = array(
            "id" => $id,
            "name" => $name,
            "email" => $email,
            "password" => $password,
            "registration_date" => $registration_date
        );
        array_push($users_arr["records"], $users_item);
    }

    http_response_code(200);

    echo json_encode($users_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Пользователи не найдены."), JSON_UNESCAPED_UNICODE);
}