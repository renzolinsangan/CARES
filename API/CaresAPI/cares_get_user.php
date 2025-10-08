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

$query = "SELECT s.accountId, s.isActive, 
                 u.firstName, u.surName, u.email, u.phoneNum, u.photoUrl, 
                 u.gender, u.signupType, u.createdAt, u.reportAction, u.suspendedUntil
          FROM user_sessions s 
          JOIN cares_account u ON s.accountId = u.id 
          WHERE s.token = ? AND s.isActive = 1";

$stmt = $db->prepare($query);

if ($stmt) {
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        die(json_encode(["success" => false, "message" => "Invalid or expired token"]));
    }
    
    $user = $result->fetch_assoc();
    
    echo json_encode([
        "success" => true,
        "message" => "User data retrieved successfully",
        "user" => [
            "id" => $user['accountId'],
            "firstName" => $user['firstName'],
            "surName" => $user['surName'],
            "email" => $user['email'],
            "phoneNum" => $user['phoneNum'],
            "photoUrl" => $user['photoUrl'],
            "gender" => $user['gender'],
            "signupType" => $user['signupType'],
            "createdAt" => $user['createdAt'],
            "reportAction" => $user['reportAction'],
            "suspendedUntil" => $user['suspendedUntil']
        ]
    ]);
    
    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Database error"]);
}

$db->close();
?>