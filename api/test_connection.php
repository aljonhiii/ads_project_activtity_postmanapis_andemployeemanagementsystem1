<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

include_once __DIR__ . '/database.php';
include_once __DIR__ . '/../class/DbTest.php';

$database = new Database();
$db = $database->getConnection();

$test = new DbTest($db);
$response = $test->checkConnection();

echo json_encode($response);
?>
