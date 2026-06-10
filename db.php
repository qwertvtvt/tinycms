<?php

$db = new SQLite3("./db/db.sqlite3");

$files = glob(__DIR__."/db_schema/*.sql");
sort($files);

foreach($files as $file) {
    $sql = file_get_contents($file);
    $db->exec($sql);
    echo "Executed: $file\n";
}