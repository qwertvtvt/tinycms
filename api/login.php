<?php

$db = new SQLite3("../db/db.sqlite3");

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(!isset($_POST["username"], $_POST["password"])) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "code" => 2
        ]);
        exit();
    }

    $username = $_POST["username"];
    $password = $_POST["password"];

    $stmt = $db->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
    $stmt->bindValue(":username", $username);

    $result = $stmt->execute();
    $user = $result->fetchArray(SQLITE3_ASSOC);

    if($user === false) {
        http_response_code(200);
        echo json_encode([
            "success" => false,
            "code" => 3
        ]);
        exit();
    }
    
    if(password_verify($password, $user["password"])) {
        http_response_code(200);
        echo json_encode([
            "success" => true,
            "code" => 0
        ]);
        session_start();
        $_SESSION["userid"] = $user["id"];
        exit();
    } else {
        http_response_code(200);
        echo json_encode([
            "success" => false,
            "code" => 3
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