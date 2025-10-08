<?php
include "kurt_dbCon.php";
header('Content-Type: application/json');
date_default_timezone_set('Asia/Manila');

if (!$db) {
    die(json_encode(["success" => false, "message" => "Database connection failed"]));
}

$token = isset($_POST['token']) ? trim($_POST['token']) : '';
$shopIds = isset($_POST['shopIds']) ? trim($_POST['shopIds']) : '';

if (empty($token)) {
    die(json_encode(["success" => false, "message" => "Token is required"]));
}

if (empty($shopIds)) {
    die(json_encode(["success" => false, "message" => "Shop IDs are required"]));
}

$accountQuery = "SELECT accountId FROM user_sessions WHERE token = ? AND isActive = 1";
$accountStmt = $db->prepare($accountQuery);
$accountStmt->bind_param("s", $token);
$accountStmt->execute();
$accountResult = $accountStmt->get_result();

if ($accountResult->num_rows === 0) {
    die(json_encode(["success" => false, "message" => "Invalid token"]));
}

$accountData = $accountResult->fetch_assoc();
$accountId = $accountData['accountId'];
$accountStmt->close();

$shopIdsArray = explode(',', $shopIds);
$shopIdsArray = array_map('intval', $shopIdsArray);
$shopIdsArray = array_filter($shopIdsArray);

if (empty($shopIdsArray)) {
    die(json_encode(["success" => false, "message" => "Invalid shop IDs"]));
}

$placeholders = str_repeat('?,', count($shopIdsArray) - 1) . '?';
$updateQuery = "UPDATE cares_shop SET isArchive = 1 WHERE id IN ($placeholders) AND accountId = ?";

$updateStmt = $db->prepare($updateQuery);
$params = array_merge($shopIdsArray, [$accountId]);
$types = str_repeat('i', count($params));
$updateStmt->bind_param($types, ...$params);

if ($updateStmt->execute()) {
    $affectedRows = $updateStmt->affected_rows;
    
    if ($affectedRows > 0) {
        echo json_encode([
            "success" => true,
            "message" => "Successfully archived $affectedRows shop(s)"
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "No shops were found to archive"
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Failed to archive shops: " . $db->error
    ]);
}

$updateStmt->close();
$db->close();
?>