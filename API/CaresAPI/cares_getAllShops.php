<?php
include "kurt_dbCon.php";
header('Content-Type: application/json');
date_default_timezone_set('Asia/Manila');

if (!$db) {
    die(json_encode(["success" => false, "message" => "Database connection failed"]));
}

$token = isset($_POST['token']) ? trim($_POST['token']) : '';

if (empty($token)) {
    die(json_encode(["success" => false, "message" => "Token is required"]));
}

$accountQuery = "SELECT accountId FROM user_sessions WHERE token = ? AND isActive = 1";
$accountStmt = $db->prepare($accountQuery);
$accountStmt->bind_param("s", $token);
$accountStmt->execute();
$accountResult = $accountStmt->get_result();

if ($accountResult->num_rows === 0) {
    die(json_encode(["success" => false, "message" => "Invalid token"]));
}

$accountStmt->close();

$shopQuery = "SELECT * FROM cares_shop WHERE isArchive = 0 AND isValidated = 1 ORDER BY stamp DESC";
$shopStmt = $db->prepare($shopQuery);
$shopStmt->execute();
$shopResult = $shopStmt->get_result();

$shops = [];
while ($row = $shopResult->fetch_assoc()) {
    $shops[] = $row;
}

$shopStmt->close();
$db->close();

echo json_encode(["success" => true, "shops" => $shops]);