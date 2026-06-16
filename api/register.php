<?php

$db = new SQLite3("../db/db.sqlite3");

header("Content-Type: application/json; charset=UTF-8");

if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(!isset($_POST["username"], $_POST["password"])) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "code" => 2
        ]);
        exit();
    }

    $userid = generateId();
    $username = $_POST["username"];
    $password = $_POST["password"];

    if(trim($username) == "") {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "code" => 3
        ]);
        exit();
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $db->prepare("INSERT INTO users (id, username, password) VALUES (:userid, :username, :password)");
    $stmt->bindValue(":userid", $userid);
    $stmt->bindValue(":username", $username);
    $stmt->bindValue(":password", $hash);

    if($stmt->execute()) {
        http_response_code(200);
        echo json_encode([
            "success" => true,
            "code" => 0
        ]);
        exit();
    } else {
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "code" => 4
        ]);
        exit();
    }
} else {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "code" => 1
    ]);
    exit();
}

function generateId(int $length = 10): string {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomIndex = random_int(0, $charactersLength - 1);
        $randomString .= $characters[$randomIndex];
    }

    return $randomString;
}