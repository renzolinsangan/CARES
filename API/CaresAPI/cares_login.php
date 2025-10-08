<?php
include "kurt_dbCon.php";
header('Content-Type: application/json');

if (!$db) {
    die(json_encode(["success" => false, "message" => "Database connection failed"]));
}

$isGoogleLogin = isset($_POST['isGoogleLogin']) && $_POST['isGoogleLogin'] == '1';

if ($isGoogleLogin) {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $googleId = isset($_POST['googleId']) ? trim($_POST['googleId']) : '';
    $deviceId = isset($_POST['deviceId']) ? trim($_POST['deviceId']) : '';

    if (empty($email) || empty($googleId) || empty($deviceId)) {
        die(json_encode(["success" => false, "message" => "Email, Google ID and device ID are required"]));
    }

    $query = "SELECT id, email, firstName, surName FROM cares_account WHERE googleId = ? AND signupType = 1";
    $stmt = $db->prepare($query);

    if ($stmt) {
        $stmt->bind_param("s", $googleId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            die(json_encode(["success" => false, "message" => "Google account not found"]));
        }
        
        $user = $result->fetch_assoc();
        
        $token = bin2hex(random_bytes(16));
        $currentTime = date('Y-m-d H:i:s');
        
        $checkSessionQuery = "SELECT id FROM user_sessions WHERE accountId = ? AND deviceId = ?";
        $checkStmt = $db->prepare($checkSessionQuery);
        $checkStmt->bind_param("is", $user['id'], $deviceId);
        $checkStmt->execute();
        $sessionExists = $checkStmt->get_result()->num_rows > 0;
        $checkStmt->close();
        
        if ($sessionExists) {
            $updateQuery = "UPDATE user_sessions SET 
                token = ?,
                lastLogin = ?,
                isActive = 1
                WHERE accountId = ? AND deviceId = ?";
            $updateStmt = $db->prepare($updateQuery);
            $updateStmt->bind_param("ssis", $token, $currentTime, $user['id'], $deviceId);
        } else {
            $updateQuery = "INSERT INTO user_sessions (
                accountId,
                deviceId,
                token,
                createdAt,
                lastLogin,
                isActive
            ) VALUES (?, ?, ?, ?, ?, 1)";
            $updateStmt = $db->prepare($updateQuery);
            $updateStmt->bind_param("issss", $user['id'], $deviceId, $token, $currentTime, $currentTime);
        }
        
        if ($updateStmt->execute()) {
            echo json_encode([
                "success" => true,
                "message" => "Google login successful",
                "token" => $token,
                "userId" => $user['id']
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to create session"]);
        }
        $updateStmt->close();
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Database error"]);
    }
} else {
    $loginInput = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $deviceId = isset($_POST['deviceId']) ? trim($_POST['deviceId']) : '';

    if (empty($loginInput) || empty($password) || empty($deviceId)) {
        die(json_encode(["success" => false, "message" => "Email/Phone, password and device ID are required"]));
    }

    // Check if input is a phone number (contains only digits or starts with +)
    $isPhone = preg_match('/^\+?\d+$/', $loginInput);
    
    if ($isPhone) {
        // Normalize phone number by removing + and leading 0
        $normalizedPhone = ltrim(preg_replace('/^\+/', '', $loginInput), '0');
        
        $query = "SELECT id, password FROM cares_account WHERE 
                 (REPLACE(phoneNum, '+', '') LIKE CONCAT('%', ?) OR 
                  REPLACE(phoneNum, '+', '') LIKE CONCAT('%', ?)) AND 
                 signupType = 0";
        $stmt = $db->prepare($query);
        
        if ($stmt) {
            $stmt->bind_param("ss", $normalizedPhone, ltrim($normalizedPhone, '0'));
            $stmt->execute();
            $result = $stmt->get_result();
        }
    } else {
        // Treat as email
        $query = "SELECT id, password FROM cares_account WHERE email = ? AND signupType = 0";
        $stmt = $db->prepare($query);
        
        if ($stmt) {
            $stmt->bind_param("s", $loginInput);
            $stmt->execute();
            $result = $stmt->get_result();
        }
    }

    if ($stmt) {
        if ($result->num_rows === 0) {
            die(json_encode(["success" => false, "message" => "Invalid credentials"]));
        }
        
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            $token = bin2hex(random_bytes(16));
            $currentTime = date('Y-m-d H:i:s');
            
            $checkSessionQuery = "SELECT id FROM user_sessions WHERE accountId = ? AND deviceId = ?";
            $checkStmt = $db->prepare($checkSessionQuery);
            $checkStmt->bind_param("is", $user['id'], $deviceId);
            $checkStmt->execute();
            $sessionExists = $checkStmt->get_result()->num_rows > 0;
            $checkStmt->close();
            
            if ($sessionExists) {
                $updateQuery = "UPDATE user_sessions SET 
                    token = ?,
                    lastLogin = ?,
                    isActive = 1
                    WHERE accountId = ? AND deviceId = ?";
                $updateStmt = $db->prepare($updateQuery);
                $updateStmt->bind_param("ssis", $token, $currentTime, $user['id'], $deviceId);
            } else {
                $updateQuery = "INSERT INTO user_sessions (
                    accountId,
                    deviceId,
                    token,
                    createdAt,
                    lastLogin,
                    isActive
                ) VALUES (?, ?, ?, ?, ?, 1)";
                $updateStmt = $db->prepare($updateQuery);
                $updateStmt->bind_param("issss", $user['id'], $deviceId, $token, $currentTime, $currentTime);
            }
            
            if ($updateStmt->execute()) {
                echo json_encode([
                    "success" => true,
                    "message" => "Login successful",
                    "token" => $token,
                    "userId" => $user['id']
                ]);
            } else {
                echo json_encode(["success" => false, "message" => "Failed to create session"]);
            }
            $updateStmt->close();
        } else {
            echo json_encode(["success" => false, "message" => "Invalid credentials"]);
        }
        
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Database error"]);
    }
}

$db->close();
?>