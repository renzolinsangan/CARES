<?php
include "kurt_dbCon.php";
header('Content-Type: application/json');

if (!$db) {
    die(json_encode(["success" => false, "message" => "Database connection failed"]));
}

$token = isset($_POST['token']) ? trim($_POST['token']) : '';
$firstName = isset($_POST['firstName']) ? trim($_POST['firstName']) : '';
$surName = isset($_POST['surName']) ? trim($_POST['surName']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$phoneNum = isset($_POST['phoneNum']) ? trim($_POST['phoneNum']) : '';
$gender = isset($_POST['gender']) ? intval($_POST['gender']) : 0;

if (empty($token)) {
    die(json_encode(["success" => false, "message" => "Token is required"]));
}

$getUserQuery = "SELECT accountId FROM user_sessions WHERE token = ? AND isActive = 1";
$getUserStmt = $db->prepare($getUserQuery);

if (!$getUserStmt) {
    die(json_encode(["success" => false, "message" => "Database error"]));
}

$getUserStmt->bind_param("s", $token);
$getUserStmt->execute();
$userResult = $getUserStmt->get_result();

if ($userResult->num_rows === 0) {
    die(json_encode(["success" => false, "message" => "Invalid or expired token"]));
}

$user = $userResult->fetch_assoc();
$userId = $user['accountId'];
$getUserStmt->close();

$getUserDataQuery = "SELECT signupType, email FROM cares_account WHERE id = ?";
$getUserDataStmt = $db->prepare($getUserDataQuery);

if (!$getUserDataStmt) {
    die(json_encode(["success" => false, "message" => "Database error"]));
}

$getUserDataStmt->bind_param("i", $userId);
$getUserDataStmt->execute();
$userDataResult = $getUserDataStmt->get_result();

if ($userDataResult->num_rows === 0) {
    die(json_encode(["success" => false, "message" => "User not found"]));
}

$userData = $userDataResult->fetch_assoc();
$signupType = $userData['signupType'];
$currentEmail = $userData['email'];
$getUserDataStmt->close();

if ($signupType == 0) {
    if (empty($email)) {
        die(json_encode(["success" => false, "message" => "Email is required"]));
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die(json_encode(["success" => false, "message" => "Invalid email format"]));
    }

    // Check if email is being changed
    if ($email !== $currentEmail) {
        $checkEmailQuery = "SELECT id FROM cares_account WHERE email = ? AND signupType = 0 AND id != ?";
        $checkEmailStmt = $db->prepare($checkEmailQuery);
        
        if ($checkEmailStmt) {
            $checkEmailStmt->bind_param("si", $email, $userId);
            $checkEmailStmt->execute();
            $checkEmailStmt->store_result();
            
            if ($checkEmailStmt->num_rows > 0) {
                die(json_encode(["success" => false, "message" => "Email already exists"]));
            }
            $checkEmailStmt->close();
        } else {
            die(json_encode(["success" => false, "message" => "Database error"]));
        }
    }
} else {
    $email = $currentEmail;
}

function normalizePhoneNumber($phone) {
    $phone = preg_replace('/[^0-9]/', '', $phone);
    
    if (substr($phone, 0, 2) === '63') {
        $phone = '0' . substr($phone, 2);
    }
    
    if (strlen($phone) !== 11) {
        return false;
    }
    
    return $phone;
}

$normalizedPhone = normalizePhoneNumber($phoneNum);
if ($normalizedPhone === false) {
    die(json_encode(["success" => false, "message" => "Invalid phone number format. Please use 09XXXXXXXXX format"]));
}

$checkPhoneQuery = "SELECT id FROM cares_account WHERE phoneNum = ? AND id != ?";
$checkPhoneStmt = $db->prepare($checkPhoneQuery);

if ($checkPhoneStmt) {
    $checkPhoneStmt->bind_param("si", $normalizedPhone, $userId);
    $checkPhoneStmt->execute();
    $checkPhoneStmt->store_result();
    
    if ($checkPhoneStmt->num_rows > 0) {
        die(json_encode(["success" => false, "message" => "Phone number already registered"]));
    }
    $checkPhoneStmt->close();
} else {
    die(json_encode(["success" => false, "message" => "Database error"]));
}

$updateQuery = "
    UPDATE cares_account 
    SET firstName = ?,
        surName = ?,
        email = ?,
        phoneNum = ?,
        gender = ?
    WHERE id = ?
";
$updateStmt = $db->prepare($updateQuery);

if ($updateStmt) {
    $updateStmt->bind_param(
        "ssssii", 
        $firstName, 
        $surName, 
        $email, 
        $normalizedPhone, 
        $gender,
        $userId
    );
    $updateStmt->execute();

    if ($updateStmt->affected_rows >= 0) {
        echo json_encode(["success" => true, "message" => "Profile updated successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update profile"]);
    }

    $updateStmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Database error"]);
}

$db->close();
?>