<?php

$db = new SQLite3("../db/db.sqlite3");

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$stmt = $db->prepare("SELECT * FROM articles ORDER BY post_at DESC");
$result = $stmt->execute();
$articles = [];
$cnt = 0;
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $articles[] = $row;
    if($row["has_images"] == 1) {
        $stmt = $db->prepare("SELECT filename FROM uploads WHERE article_id = :id");
        $stmt->bindValue(":id", $row["id"]);
        $img_result = $stmt->execute();
        while($img = $img_result->fetchArray(SQLITE3_ASSOC)) {
            $articles[$cnt]["images"][] = $img["filename"];
        }
    }
    $cnt++;
}

echo json_encode($articles);