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

$query = "SELECT accountId FROM user_sessions WHERE token = ? AND isActive = 1";
$stmt = $db->prepare($query);

if (!$stmt) {
    die(json_encode(["success" => false, "message" => "Database error"]));
}

$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die(json_encode(["success" => false, "message" => "Invalid or expired token"]));
}

$user = $result->fetch_assoc();
$userId = $user['accountId'];
$stmt->close();

if (empty($_FILES['profile_image'])) {
    die(json_encode(["success" => false, "message" => "No file uploaded"]));
}

$file = $_FILES['profile_image'];

$oldPhotoQuery = "SELECT photoUrl FROM cares_account WHERE id = ?";
$oldPhotoStmt = $db->prepare($oldPhotoQuery);
$oldPhotoStmt->bind_param("i", $userId);
$oldPhotoStmt->execute();
$oldPhotoResult = $oldPhotoStmt->get_result();
$oldPhoto = null;
if ($row = $oldPhotoResult->fetch_assoc()) {
    $oldPhoto = $row['photoUrl'];
}
$oldPhotoStmt->close();

$uploadDir = 'profilePicture/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$allowedExtensions = ['jpg', 'jpeg', 'png'];
$maxFileSize = 5 * 1024 * 1024;

if ($file['error'] !== UPLOAD_ERR_OK) {
    die(json_encode(["success" => false, "message" => "File upload error"]));
}

if ($file['size'] > $maxFileSize) {
    die(json_encode(["success" => false, "message" => "File too large (max 5MB)"]));
}

$fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
if (!in_array($fileExt, $allowedExtensions)) {
    die(json_encode(["success" => false, "message" => "Invalid file type. Only JPG, JPEG, PNG allowed"]));
}

$timestamp = time();
$newFilename = $userId . '_' . $timestamp . '.' . $fileExt;
$destination = $uploadDir . $newFilename;

if (!move_uploaded_file($file['tmp_name'], $destination)) {
    die(json_encode(["success" => false, "message" => "Failed to save file"]));
}

$updateQuery = "UPDATE cares_account SET photoUrl = ? WHERE id = ?";
$updateStmt = $db->prepare($updateQuery);

if ($updateStmt) {
    $updateStmt->bind_param("si", $newFilename, $userId);
    $updateStmt->execute();
    
    if ($updateStmt->affected_rows > 0) {
        if ($oldPhoto && $oldPhoto !== $newFilename && file_exists($uploadDir . $oldPhoto)) {
            unlink($uploadDir . $oldPhoto);
        }
        
        echo json_encode([
            "success" => true,
            "message" => "Profile picture updated successfully",
            "photoUrl" => $newFilename
        ]);
    } else {
        if (file_exists($destination)) {
            unlink($destination);
        }
        echo json_encode(["success" => false, "message" => "Failed to update profile picture"]);
    }
    
    $updateStmt->close();
} else {
    if (file_exists($destination)) {
        unlink($destination);
    }
    echo json_encode(["success" => false, "message" => "Database error"]);
}

$db->close();
?>