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

// Update user session to inactive and update last login
$currentTime = date('Y-m-d H:i:s');
$query = "UPDATE user_sessions SET isActive = 0, lastLogin = ? WHERE token = ?";

$stmt = $db->prepare($query);

if ($stmt) {
    $stmt->bind_param("ss", $currentTime, $token);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        echo json_encode([
            "success" => true,
            "message" => "Logout successful"
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Invalid token or already logged out"
        ]);
    }
    
    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Database error"]);
}

$db->close();
?>