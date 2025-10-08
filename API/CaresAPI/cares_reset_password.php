<?php
include "kurt_dbCon.php";
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
header('Content-Type: application/json');
date_default_timezone_set('Asia/Manila');
if (!$db) {
    die(json_encode(["success" => false, "message" => "Database connection failed"]));
}

$emailOrPhone = isset($_POST['emailOrPhone']) ? trim($_POST['emailOrPhone']) : '';

if (empty($emailOrPhone)) {
    die(json_encode(["success" => false, "message" => "Email or phone number is required"]));
}

// Check if the input is an email or phone number
$isEmail = filter_var($emailOrPhone, FILTER_VALIDATE_EMAIL);
$query = $isEmail 
    ? "SELECT id, email FROM cares_account WHERE email = ? AND signupType = 0"
    : "SELECT id, email FROM cares_account WHERE phoneNum = ? AND signupType = 0";

$stmt = $db->prepare($query);

if ($stmt) {
    $stmt->bind_param("s", $emailOrPhone);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        die(json_encode(["success" => false, "message" => "Email or phone number not found"]));
    }
    
    $user = $result->fetch_assoc();
    $userId = $user['id'];
    $email = $user['email'];
    

    if (empty($email)) {
        die(json_encode(["success" => false, "message" => "No email associated with this account"]));
    }
    
    $resetToken = bin2hex(random_bytes(32));
   $expiryTime = date('Y-m-d H:i:s', strtotime('+20 minutes')); 
    
    $updateQuery = "UPDATE cares_account SET 
        resetToken = ?,
        resetTokenExpireAt = ?
        WHERE id = ?";
    $updateStmt = $db->prepare($updateQuery);
    $updateStmt->bind_param("ssi", $resetToken, $expiryTime, $userId);
    
    if ($updateStmt->execute()) {
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; 
            $mail->SMTPAuth = true;
            $mail->Username = 'cares8865@gmail.com';
            $mail->Password = 'nnzu qhew sdhb csgi';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            
            $mail->setFrom('cares8865@gmail.com', 'CARES Reset Password');
            $mail->addAddress($email);
            
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            
            $resetLink = "https://cares-webapp.online/API/CaresAPI/reset_password.php?token=$resetToken";
            $mail->Body = "
                <h2>Password Reset Request</h2>
                <p>Click the link below to reset your password. This link will expire in 20 minutes.</p>
                <p><a href='$resetLink'>Reset Password</a></p>
                <p>If you didn't request this, please ignore this email.</p>
            ";
            
            $mail->send();
            echo json_encode(["success" => true, "message" => "Password reset email sent"]);
        } catch (Exception $e) {
            echo json_encode(["success" => false, "message" => "Failed to send email: {$mail->ErrorInfo}"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Failed to generate reset token"]);
    }
    
    $updateStmt->close();
    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Database error"]);
}

$db->close();
?>