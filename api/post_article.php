<?php

$db = new SQLite3("../db/db.sqlite3");

header("Content-Type: application/json; charset=UTF-8");

session_start();

if(!isset($_SESSION["userid"])) {
    http_response_code(401);
    echo json_encode([
        "success" => false,
        "code" => 4
    ]);
    exit();
}

if($_SERVER["REQUEST_METHOD"] === "POST") {
    if(!isset($_POST["title"], $_POST["content"])) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "code" => 2
        ]);
        exit();
    }

    $id = generateId(5);
    $title = htmlspecialchars($_POST["title"], ENT_QUOTES, "UTF-8");
    $content = htmlspecialchars($_POST["content"], ENT_QUOTES, "UTF-8");
    $post_at = (int) floor(microtime(true)  * 1000);
    $images = [];

    if(!empty($_FILES)) {
        foreach ($_FILES['images']['tmp_name'] as $index => $tmpName) {
            if ($_FILES['images']['error'][$index] !== UPLOAD_ERR_OK) {
                continue;
            }
            $originalName = $_FILES['images']['name'][$index];
            $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (!in_array($extension, $allowed, true)) {
                continue;
            }
            $newFileName = uniqid('', true) . '.' . $extension;
            $destination = "../uploads/" . $newFileName;
            move_uploaded_file($tmpName, $destination);
            array_push($images, $newFileName);
        }
    }

    $stmt = $db->prepare("INSERT INTO articles (id, title, content, post_at, has_images) VALUES (:id, :title, :content, :post_at, :has_images)");
    $stmt->bindValue(":id", $id);
    $stmt->bindValue(":title", $title);
    $stmt->bindValue(":content", $content);
    $stmt->bindValue(":post_at", $post_at);
    $stmt->bindValue(":has_images", !empty($images));
    $stmt->execute();

    if(!empty($images)) {
        foreach($images as $image) {
            $stmt = $db->prepare("INSERT INTO uploads (filename, article_id) VALUES (:filename, :article_id)");
            $stmt->bindValue(":filename", $image);
            $stmt->bindValue(":article_id", $id);
            $stmt->execute();
        }
    }

    http_response_code(200);
    echo json_encode([
        "success" => true,
        "code" => 0
    ]);
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