<?php

use Models\Transaction;

include __DIR__ . '/../autoload.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$mysqli = new mysqli('localhost', 'root', 'root', 'jawwal_go', 3306);

$mysqli->set_charset('utf8mb4');

Transaction::$db = $mysqli;