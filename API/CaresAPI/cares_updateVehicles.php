<?php
include "kurt_dbCon.php";
header('Content-Type: application/json');
date_default_timezone_set('Asia/Manila');
if (!$db) {
    die(json_encode(["success" => false, "message" => "Database connection failed"]));
}

$token = isset($_POST['token']) ? trim($_POST['token']) : '';
$vehiclesData = isset($_POST['vehicles']) ? json_decode($_POST['vehicles'], true) : [];

if (empty($token)) {
    die(json_encode(["success" => false, "message" => "Token is required"]));
}

if (empty($vehiclesData)) {
    die(json_encode(["success" => false, "message" => "No vehicles data provided"]));
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

$updateQuery = "UPDATE cares_vehicle SET vehicle_brand = ?, vehicle_model = ?, plate_number = ? WHERE id = ? AND accountId = ?";
$updateStmt = $db->prepare($updateQuery);

$successCount = 0;
foreach ($vehiclesData as $vehicle) {
    $vehicleId = intval($vehicle['id']);
    $vehicleBrand = trim($vehicle['vehicle_brand']);
    $vehicleModel = trim($vehicle['vehicle_model']);
    $plateNumber = trim($vehicle['plate_number']);
    
    $updateStmt->bind_param("sssii", $vehicleBrand, $vehicleModel, $plateNumber, $vehicleId, $accountId);
    if ($updateStmt->execute()) {
        $successCount++;
    }
}

$updateStmt->close();
$db->close();

if ($successCount > 0) {
    echo json_encode(["success" => true, "message" => "The data was successfully updated."]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to update vehicles"]);
}
?>