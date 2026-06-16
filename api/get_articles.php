<?php

$db = new SQLite3("../db/db.sqlite3");

header("Content-Type: application/json; charset=UTF-8");

$stmt = $db->prepare("SELECT * FROM articles ORDER BY post_at DESC");
$result = $stmt->execute();
$articles = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $articles[] = $row;
}

echo json_encode($articles);