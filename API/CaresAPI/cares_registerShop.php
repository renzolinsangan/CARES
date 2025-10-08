<?php
include "kurt_dbCon.php";
header('Content-Type: application/json');

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

$shopName = isset($_POST['shopName']) ? trim($_POST['shopName']) : '';
$location = isset($_POST['location']) ? trim($_POST['location']) : '';
$expertise = isset($_POST['expertise']) ? trim($_POST['expertise']) : '';
$homePage = isset($_POST['homePage']) ? trim($_POST['homePage']) : null;
$phoneNum = isset($_POST['phoneNum']) ? trim($_POST['phoneNum']) : '';
$services = isset($_POST['services']) ? trim($_POST['services']) : '';
$startTime = isset($_POST['startTime']) ? trim($_POST['startTime']) : '';
$closeTime = isset($_POST['closeTime']) ? trim($_POST['closeTime']) : '';
$dayIndex = isset($_POST['dayIndex']) ? trim($_POST['dayIndex']) : '';
$latitude = isset($_POST['latitude']) ? floatval($_POST['latitude']) : null;
$longitude = isset($_POST['longitude']) ? floatval($_POST['longitude']) : null;

if (empty($shopName) || empty($location) || $expertise === '' || empty($phoneNum) || 
    empty($services) || empty($startTime) || empty($closeTime) || $dayIndex === '' || 
    $latitude === null || $longitude === null) {
    die(json_encode(["success" => false, "message" => "All required fields must be filled"]));
}

$businessDocu = null;
$validId = null;

if (isset($_FILES['businessDocu'])) {
    $businessDocu = uploadFile($_FILES['businessDocu'], $accountId, 'businessDocu');
    if (!$businessDocu['success']) {
        die(json_encode(["success" => false, "message" => $businessDocu['message']]));
    }
    $businessDocu = $businessDocu['filename'];
}

if (isset($_FILES['validId'])) {
    $validId = uploadFile($_FILES['validId'], $accountId, 'validID');
    if (!$validId['success']) {
        die(json_encode(["success" => false, "message" => $validId['message']]));
    }
    $validId = $validId['filename'];
}

if (!$businessDocu || !$validId) {
    die(json_encode(["success" => false, "message" => "Both documents are required"]));
}

$insertQuery = "INSERT INTO cares_shop (
    accountId,
    shop_name,
    location,
    expertise,
    home_page,
    phoneNum,
    services,
    start_time,
    close_time,
    day_index,
    business_docu,
    valid_id,
    latitude,
    longitude,
    stamp
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

$insertStmt = $db->prepare($insertQuery);
$insertStmt->bind_param(
    "isssssssssssdd",
    $accountId,
    $shopName,
    $location,
    $expertise,
    $homePage,
    $phoneNum,
    $services,
    $startTime,
    $closeTime,
    $dayIndex,
    $businessDocu,
    $validId,
    $latitude,
    $longitude
);

if ($insertStmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Shop registered successfully"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Failed to register shop: " . $db->error
    ]);
}

$insertStmt->close();
$db->close();

function uploadFile($file, $accountId, $type) {
    $uploadDir = $type . '/';
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
    $maxFileSize = 5 * 1024 * 1024;

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ["success" => false, "message" => "File upload error"];
    }

    if ($file['size'] > $maxFileSize) {
        return ["success" => false, "message" => "File too large (max 5MB)"];
    }

    $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($fileExt, $allowedExtensions)) {
        return ["success" => false, "message" => "Invalid file type. Only JPG, JPEG, PNG, PDF allowed"];
    }

    $timestamp = time();
    $newFilename = $accountId . '_' . $timestamp . '.' . $fileExt;
    $destination = $uploadDir . $newFilename;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        return ["success" => false, "message" => "Failed to save file"];
    }

    return ["success" => true, "filename" => $newFilename];
}
?>