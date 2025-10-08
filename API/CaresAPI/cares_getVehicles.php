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

$accountData = $accountResult->fetch_assoc();
$accountId = $accountData['accountId'];
$accountStmt->close();

$vehicleQuery = "SELECT id, vehicle_type, vehicle_brand, vehicle_model, plate_number, isActivate FROM cares_vehicle WHERE accountId = ? AND isArchive = 0 ORDER BY stamp DESC";
$vehicleStmt = $db->prepare($vehicleQuery);
$vehicleStmt->bind_param("i", $accountId);
$vehicleStmt->execute();
$vehicleResult = $vehicleStmt->get_result();

$vehicles = [];
while ($row = $vehicleResult->fetch_assoc()) {
    $vehicles[] = $row;
}

$vehicleStmt->close();
$db->close();

echo json_encode(["success" => true, "vehicles" => $vehicles]);
?>