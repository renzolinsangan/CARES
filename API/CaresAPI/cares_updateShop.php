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

$shopId = isset($_POST['shopId']) ? intval($_POST['shopId']) : 0;
$shopName = isset($_POST['shopName']) ? trim($_POST['shopName']) : '';
$location = isset($_POST['location']) ? trim($_POST['location']) : '';
$expertise = isset($_POST['expertise']) ? trim($_POST['expertise']) : '';
$homePage = isset($_POST['homePage']) ? trim($_POST['homePage']) : null;
$phoneNum = isset($_POST['phoneNum']) ? trim($_POST['phoneNum']) : null;
$services = isset($_POST['services']) ? trim($_POST['services']) : '';
$startTime = isset($_POST['startTime']) ? trim($_POST['startTime']) : '';
$closeTime = isset($_POST['closeTime']) ? trim($_POST['closeTime']) : '';
$dayIndex = isset($_POST['dayIndex']) ? trim($_POST['dayIndex']) : '';
$latitude = isset($_POST['latitude']) ? floatval($_POST['latitude']) : null;
$longitude = isset($_POST['longitude']) ? floatval($_POST['longitude']) : null;
$isRejected = isset($_POST['isRejected']) && $_POST['isRejected'] === '1';

if (empty($shopId) || empty($shopName) || empty($location) || $expertise === '' || 
    empty($services) || empty($startTime) || empty($closeTime) || $dayIndex === '' ||
    $latitude === null || $longitude === null) {
    die(json_encode(["success" => false, "message" => "All required fields must be filled"]));
}

$shopCheckQuery = "SELECT shopId, isValidated FROM cares_shop WHERE shopId = ? AND accountId = ?";
$shopCheckStmt = $db->prepare($shopCheckQuery);
$shopCheckStmt->bind_param("ii", $shopId, $accountId);
$shopCheckStmt->execute();
$shopCheckResult = $shopCheckStmt->get_result();

if ($shopCheckResult->num_rows === 0) {
    die(json_encode(["success" => false, "message" => "Shop not found or unauthorized"]));
}

$shopData = $shopCheckResult->fetch_assoc();
$currentValidationStatus = $shopData['isValidated'];
$shopCheckStmt->close();

$shopLogo = null;
if (isset($_FILES['shopLogo'])) {
    $shopLogo = uploadFile($_FILES['shopLogo'], $accountId, 'shopLogo');
    if (!$shopLogo['success']) {
        die(json_encode(["success" => false, "message" => $shopLogo['message']]));
    }
    $shopLogo = $shopLogo['filename'];
}

$businessDocu = null;
if (isset($_FILES['businessDocu'])) {
    $businessDocu = uploadFile($_FILES['businessDocu'], $accountId, 'businessDocu');
    if (!$businessDocu['success']) {
        die(json_encode(["success" => false, "message" => $businessDocu['message']]));
    }
    $businessDocu = $businessDocu['filename'];
}

$validId = null;
if (isset($_FILES['validId'])) {
    $validId = uploadFile($_FILES['validId'], $accountId, 'validID');
    if (!$validId['success']) {
        die(json_encode(["success" => false, "message" => $validId['message']]));
    }
    $validId = $validId['filename'];
}

if ($isRejected && $currentValidationStatus == 2) {
    if (!isset($_FILES['businessDocu']) || !isset($_FILES['validId'])) {
        die(json_encode(["success" => false, "message" => "Both business permit and government ID must be uploaded"]));
    }
}

$updateQuery = "UPDATE cares_shop SET 
    shop_name = ?,
    location = ?,
    expertise = ?,
    home_page = ?,
    phoneNum = ?,
    services = ?,
    start_time = ?,
    close_time = ?,
    day_index = ?,
    latitude = ?,
    longitude = ?";

if ($currentValidationStatus == 1 && ($businessDocu !== null || $validId !== null)) {
    $updateQuery .= ", stamp = NOW()";
}

if ($isRejected && $currentValidationStatus == 2 && ($businessDocu !== null && $validId !== null)) {
    $updateQuery .= ", isValidated = 0";
}

if ($shopLogo !== null) {
    $updateQuery .= ", shopLogo = ?";
}
if ($businessDocu !== null) {
    $updateQuery .= ", business_docu = ?";
}
if ($validId !== null) {
    $updateQuery .= ", valid_id = ?";
}

$updateQuery .= " WHERE shopId = ?";

$updateStmt = $db->prepare($updateQuery);

$params = [$shopName, $location, $expertise, $homePage, $phoneNum, $services, $startTime, $closeTime, $dayIndex, $latitude, $longitude];
$types = 'sssssssssdd';

if ($shopLogo !== null) {
    $params[] = $shopLogo;
    $types .= 's';
}
if ($businessDocu !== null) {
    $params[] = $businessDocu;
    $types .= 's';
}
if ($validId !== null) {
    $params[] = $validId;
    $types .= 's';
}
$params[] = $shopId;
$types .= 'i';

$updateStmt->bind_param($types, ...$params);

if ($updateStmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Shop updated successfully"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Failed to update shop: " . $db->error
    ]);
}

$updateStmt->close();
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