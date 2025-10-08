<?php
include "kurt_dbCon.php";
date_default_timezone_set('Asia/Manila');
if (!$db) {
    die("Database connection failed");
}

$token = isset($_GET['token']) ? trim($_GET['token']) : '';

if (empty($token)) {
    die("Invalid reset link");
}

$currentTime = date('Y-m-d H:i:s');
$query = "SELECT id FROM cares_account WHERE resetToken = ? AND resetTokenExpireAt > ?";
$stmt = $db->prepare($query);

if ($stmt) {
    $stmt->bind_param("ss", $token, $currentTime);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("Invalid or expired reset link");
    }

    $user = $result->fetch_assoc();
    $userId = $user['id'];
    $stmt->close();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $newPassword = isset($_POST['new_password']) ? trim($_POST['new_password']) : '';
        $confirmPassword = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : '';

        if (empty($newPassword) || empty($confirmPassword)) {
            $error = "Both fields are required";
        } elseif ($newPassword !== $confirmPassword) {
            $error = "Passwords do not match";
        } elseif (strlen($newPassword) < 8) {
            $error = "Password must be at least 8 characters";
        } elseif (!preg_match('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[!@#\$&*~]).{8,}$/', $newPassword)) {
            $error = "Password must include at least one uppercase letter, one lowercase letter, one number, and one special character (!@#\$&*~)";
        } else {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateQuery = "UPDATE cares_account SET password = ?, resetToken = NULL, resetTokenExpireAt = NULL WHERE id = ?";
            $updateStmt = $db->prepare($updateQuery);
            $updateStmt->bind_param("si", $hashedPassword, $userId);

            if ($updateStmt->execute()) {
                $success = "Password updated successfully. You can now login with your new password.";
            } else {
                $error = "Failed to update password";
            }

            $updateStmt->close();
        }
    }
} else {
    die("Database error");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <!-- Font Awesome for eye icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        .form-group {
            margin-bottom: 15px;
            position: relative;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="password"], input[type="text"] {
            width: 100%;
            padding: 8px;
            padding-right: 40px;
            box-sizing: border-box;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
        }
        .error { color: red; }
        .success { color: green; }
        .password-toggle {
            position: absolute;
            right: 10px;
            top: 35px;
            cursor: pointer;
            color: #666;
        }
        .password-hint {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        ul {
            padding-left: 20px;
        }
    </style>
</head>
<body>
    <h1>Reset Password</h1>

    <?php if (isset($error)): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <?php if (isset($success)): ?>
        <p class="success"><?php echo htmlspecialchars($success); ?></p>
    <?php else: ?>
        <form method="POST">
            <div class="form-group">
                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password" required>
                <span class="password-toggle" onclick="togglePassword('new_password', this)">
                <i class="fa-solid fa-eye-slash"></i>
                </span>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm New Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
                <span class="password-toggle" onclick="togglePassword('confirm_password', this)">
                   <i class="fa-solid fa-eye-slash"></i>
                </span>
            </div>
            <div class="password-hint">
                <strong>Password must contain:</strong>
                <ul>
                    <li>At least 8 characters</li>
                    <li>1 uppercase letter</li>
                    <li>1 lowercase letter</li>
                    <li>1 number</li>
                    <li>1 special character (!@#$&*~)</li>
                </ul>
            </div>
            <button type="submit">Reset Password</button>
        </form>

        <script>
        function togglePassword(fieldId, iconElement) {
    const field = document.getElementById(fieldId);
    const icon = iconElement.querySelector('i');
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    }
}

        </script>
    <?php endif; ?>
</body>
</html>

<?php
$db->close();
?>
