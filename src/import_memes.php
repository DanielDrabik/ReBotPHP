<?php
require __DIR__ . '/../vendor/autoload.php';

use ReBot\Bot\Database;

$db = new Database();

// add file to src/memes.json
$db->importFromJson(__DIR__ . '/memes.json');

echo "✅ Import finished!\n";
