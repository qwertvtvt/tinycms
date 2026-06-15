<?php

if($_SERVER["REQUEST_METHOD"] === "POST") {
    if(!isset($_POST["title"], $_POST["content"])) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "code" => 2
        ]);
        exit();
    }

    $title = htmlspecialchars($_POST["title"], ENT_QUOTES, "UTF-8");
    $content = htmlspecialchars($_POST["content"], ENT_QUOTES, "UTF-8");
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
            $destination = $uploadDir . $newFileName;
            move_uploaded_file($tmpName, $destination);
            array_push($images, $newFileName);
        }
    }

    
} else {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "code" => 1
    ]);
    exit();
}