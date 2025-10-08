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

$vehiclesData = isset($_POST['vehicles']) ? json_decode($_POST['vehicles'], true) : [];

if (empty($vehiclesData)) {
    die(json_encode(["success" => false, "message" => "No vehicles data provided"]));
}

$vehicleTypeMap = [
    'Car' => 0,
    'Motorcycle' => 1,
    'Van' => 2,
    'Truck' => 3,
    'Bus' => 4,
    'Jeep' => 5
];

$errors = [];
$successCount = 0;

foreach ($vehiclesData as $index => $vehicle) {
    $plateNumber = trim($vehicle['plateNumber']);
    $checkQuery = "SELECT id FROM cares_vehicle WHERE accountId = ? AND plate_number = ?";
    $checkStmt = $db->prepare($checkQuery);
    $checkStmt->bind_param("is", $accountId, $plateNumber);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    
    if ($checkResult->num_rows > 0) {
        $errors[] = [
            'index' => $index,
            'message' => "Vehicle with plate number '$plateNumber' already exists"
        ];
    }
    $checkStmt->close();
}

if (!empty($errors)) {
    echo json_encode([
        "success" => false,
        "message" => "Some vehicles already exist",
        "duplicates" => $errors
    ]);
    exit;
}

$insertQuery = "INSERT INTO cares_vehicle (
    accountId,
    vehicle_type,
    vehicle_brand,
    vehicle_model,
    plate_number,
    stamp
) VALUES (?, ?, ?, ?, ?, NOW())";

$insertStmt = $db->prepare($insertQuery);

foreach ($vehiclesData as $vehicle) {
    $vehicleType = $vehicleTypeMap[$vehicle['vehicleType']] ?? -1;
    $vehicleBrand = trim($vehicle['brand']);
    $vehicleModel = trim($vehicle['model']);
    $plateNumber = trim($vehicle['plateNumber']);
    
    $insertStmt->bind_param("issss", $accountId, $vehicleType, $vehicleBrand, $vehicleModel, $plateNumber);
    
    if ($insertStmt->execute()) {
        $successCount++;
    }
}

if ($successCount > 0) {
    $updateQuery = "UPDATE cares_account SET hasVehicle = 1 WHERE id = ? AND hasVehicle = 0";
    $updateStmt = $db->prepare($updateQuery);
    $updateStmt->bind_param("i", $accountId);
    $updateStmt->execute();
    $updateStmt->close();
    
    echo json_encode([
        "success" => true,
        "message" => "Successfully added $successCount vehicle(s)"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Failed to add vehicles"
    ]);
}

$insertStmt->close();
$db->close();
?>