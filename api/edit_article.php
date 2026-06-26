<?php

$db = new SQLite3("../db/db.sqlite3");

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

session_start();

if(!isset($_SESSION["userid"])) {
    http_response_code(401);
    echo json_encode([
        "success" => false,
        "code" => 4
    ]);
    exit();
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(!isset($_POST["id"], $_POST["title"], $_POST["content"])) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "code" => 3
        ]);
        exit();
    }

    $id = $_POST["id"];
    $title = $_POST["title"];
    $content = $_POST["content"];

    if($title == "" || $content == "") {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "code" => 2
        ]);
        exit();
    }

    $stmt = $db->prepare("UPDATE articles SET title = :title, content = :content WHERE id = :id");
    $stmt->bindValue(":title", $title);
    $stmt->bindValue(":content", $content);
    $stmt->bindValue(":id", $id);

    $stmt->execute();

    http_response_code(200);
    echo json_encode([
        "success" => true,
        "code" => 0
    ]);
    exit();
} else {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "code" => 1
    ]);
    exit();
}