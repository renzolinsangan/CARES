<?php
include "kurt_dbCon.php";
header('Content-Type: application/json');
date_default_timezone_set('Asia/Manila');
if (!$db) {
    die(json_encode(["success" => false, "message" => "Database connection failed"]));
}

$token = isset($_POST['token']) ? trim($_POST['token']) : '';
$vehicleId = isset($_POST['vehicleId']) ? intval($_POST['vehicleId']) : 0;
$isActive = isset($_POST['isActive']) ? intval($_POST['isActive']) : 0;

if (empty($token)) {
    die(json_encode(["success" => false, "message" => "Token is required"]));
}

if ($vehicleId <= 0) {
    die(json_encode(["success" => false, "message" => "Invalid vehicle ID"]));
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

$updateQuery = "UPDATE cares_vehicle SET isActivate = ? WHERE id = ? AND accountId = ?";
$updateStmt = $db->prepare($updateQuery);
$updateStmt->bind_param("iii", $isActive, $vehicleId, $accountId);
$success = $updateStmt->execute();
$updateStmt->close();
$db->close();

if ($success) {
    echo json_encode(["success" => true, "message" => "Vehicle status updated"]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to update vehicle status"]);
}
?>