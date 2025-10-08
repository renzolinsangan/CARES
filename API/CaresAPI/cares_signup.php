<?php
include "kurt_dbCon.php";
header('Content-Type: application/json');

if (!$db) {
    die(json_encode(["success" => false, "message" => "Database connection failed"]));
}

$firstName = isset($_POST['firstName']) ? trim($_POST['firstName']) : '';
$surName = isset($_POST['surName']) ? trim($_POST['surName']) : '';
$gender = isset($_POST['gender']) ? intval($_POST['gender']) : 0;
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$phoneNum = isset($_POST['phoneNum']) ? trim($_POST['phoneNum']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';
$signupType = isset($_POST['signupType']) ? intval($_POST['signupType']) : 0;
$googleId = isset($_POST['googleId']) ? trim($_POST['googleId']) : '';
$photoUrl = isset($_POST['photoUrl']) ? trim($_POST['photoUrl']) : '';

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

if ($signupType == 1) {
    if (empty($firstName) || empty($email) || empty($googleId)) {
        die(json_encode(["success" => false, "message" => "Google signup requires first name, email, and Google ID"]));
    }

    $checkGoogleQuery = "SELECT id FROM cares_account WHERE googleId = ?";
    $checkGoogleStmt = $db->prepare($checkGoogleQuery);
    if ($checkGoogleStmt) {
        $checkGoogleStmt->bind_param("s", $googleId);
        $checkGoogleStmt->execute();
        $checkGoogleStmt->store_result();
        
        if ($checkGoogleStmt->num_rows > 0) {
            die(json_encode(["success" => false, "message" => "This Google account is already registered"]));
        }
        $checkGoogleStmt->close();
    } else {
        die(json_encode(["success" => false, "message" => "Database error"]));
    }

    $checkEmailQuery = "SELECT id FROM cares_account WHERE email = ? AND signupType = ?";
    $checkEmailStmt = $db->prepare($checkEmailQuery);
    if ($checkEmailStmt) {
        $checkEmailStmt->bind_param("si", $email, $signupType);
        $checkEmailStmt->execute();
        $checkEmailStmt->store_result();
        
        if ($checkEmailStmt->num_rows > 0) {
            die(json_encode(["success" => false, "message" => "This email is already registered with Google"]));
        }
        $checkEmailStmt->close();
    } else {
        die(json_encode(["success" => false, "message" => "Database error"]));
    }

    $query = "
        INSERT INTO cares_account 
        (firstName, surName, gender, email, phoneNum, googleId, signupType, photoUrl, createdAt)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
    ";
    $stmt = $db->prepare($query);

    if ($stmt) {
        $stmt->bind_param(
            "ssisssss", 
            $firstName, 
            $surName, 
            $gender, 
            $email, 
            $phoneNum, 
            $googleId, 
            $signupType,
            $photoUrl
        );
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(["success" => true, "message" => "Google signup successful"]);
        } else {
            echo json_encode(["success" => false, "message" => "Google signup failed"]);
        }

        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Database error"]);
    }
} else {
    if (empty($firstName) || empty($surName) || empty($email) || empty($phoneNum) || empty($password)) {
        die(json_encode(["success" => false, "message" => "All fields are required"]));
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die(json_encode(["success" => false, "message" => "Invalid email format"]));
    }

    // Normalize and validate phone number
    $normalizedPhone = normalizePhoneNumber($phoneNum);
    if ($normalizedPhone === false) {
        die(json_encode(["success" => false, "message" => "Invalid phone number format. Please use 09XXXXXXXXX format"]));
    }

    // Check if email already exists
    $checkEmailQuery = "SELECT id FROM cares_account WHERE email = ? AND signupType = ?";
    $checkStmt = $db->prepare($checkEmailQuery);
    if ($checkStmt) {
        $checkStmt->bind_param("si", $email, $signupType);
        $checkStmt->execute();
        $checkStmt->store_result();
        
        if ($checkStmt->num_rows > 0) {
            die(json_encode(["success" => false, "message" => "Email already exists for manual signup"]));
        }
        $checkStmt->close();
    } else {
        die(json_encode(["success" => false, "message" => "Database error"]));
    }

    // Check if phone number already exists
    $checkPhoneQuery = "SELECT id FROM cares_account WHERE phoneNum = ?";
    $checkPhoneStmt = $db->prepare($checkPhoneQuery);
    if ($checkPhoneStmt) {
        $checkPhoneStmt->bind_param("s", $normalizedPhone);
        $checkPhoneStmt->execute();
        $checkPhoneStmt->store_result();
        
        if ($checkPhoneStmt->num_rows > 0) {
            die(json_encode(["success" => false, "message" => "Phone number already registered"]));
        }
        $checkPhoneStmt->close();
    } else {
        die(json_encode(["success" => false, "message" => "Database error"]));
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $query = "
        INSERT INTO cares_account 
        (firstName, surName, gender, email, phoneNum, password, signupType, createdAt)
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
    ";
    $stmt = $db->prepare($query);

    if ($stmt) {
        $stmt->bind_param(
            "ssisssi", 
            $firstName, 
            $surName, 
            $gender, 
            $email, 
            $normalizedPhone, 
            $hashedPassword, 
            $signupType
        );
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(["success" => true, "message" => "Signup successful"]);
        } else {
            echo json_encode(["success" => false, "message" => "Signup failed"]);
        }

        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Database error"]);
    }
}

$db->close();
?>