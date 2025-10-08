<?php
include "kurt_dbCon.php";
header('Content-Type: application/json');
date_default_timezone_set('Asia/Manila');

if (!$db) {
    die(json_encode(["success" => false, "message" => "Database connection failed"]));
}

$token = isset($_POST['token']) ? trim($_POST['token']) : '';
$shopId = isset($_POST['shopId']) ? intval($_POST['shopId']) : 0;
$reportType = isset($_POST['reportType']) ? trim($_POST['reportType']) : '';
$reportDetails = isset($_POST['reportDetails']) ? trim($_POST['reportDetails']) : '';

if (empty($token)) {
    die(json_encode(["success" => false, "message" => "Token is required"]));
}

if (empty($reportType)) {
    die(json_encode(["success" => false, "message" => "Report type is required"]));
}

if ($shopId <= 0) {
    die(json_encode(["success" => false, "message" => "Shop ID is required"]));
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

$shopExistQuery = "SELECT shopId FROM cares_shop WHERE shopId = ? AND isArchive = 0";
$shopExistStmt = $db->prepare($shopExistQuery);
$shopExistStmt->bind_param("i", $shopId);
$shopExistStmt->execute();
$shopExistResult = $shopExistStmt->get_result();

if ($shopExistResult->num_rows === 0) {
    die(json_encode(["success" => false, "message" => "Shop not found"]));
}
$shopExistStmt->close();

$insertQuery = "INSERT INTO cares_shopReport (shopId, accountId, reportType, reportDetails, stamp) VALUES (?, ?, ?, ?, NOW())";
$insertStmt = $db->prepare($insertQuery);
$insertStmt->bind_param("iiss", $shopId, $accountId, $reportType, $reportDetails);

if ($insertStmt->execute()) {
    echo json_encode(["success" => true, "message" => "Report submitted successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to submit report"]);
}

$insertStmt->close();
$db->close();
?>