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

$query = "SELECT n.*, s.shop_name 
          FROM cares_notification n
          LEFT JOIN cares_shop s ON n.shopId = s.shopId
          WHERE n.accountId = ?
          AND (
              (n.notifType = 0 AND n.notifMessage IN (0, 1)) OR
              (n.notifType = 1 AND n.notifMessage IN (2, 3)) OR
              (n.notifType = 3 AND n.notifMessage IN (4, 5))
          )
          ORDER BY n.stamp DESC";

$stmt = $db->prepare($query);
$stmt->bind_param("i", $accountId);
$stmt->execute();
$result = $stmt->get_result();

$notifications = [];
while ($row = $result->fetch_assoc()) {
    $message = '';
    $title = '';
    
    if ($row['notifType'] == 0) {
        $title = "Shop Notification";
        if ($row['notifMessage'] == 1) {
            $message = "Your shop " . $row['shop_name'] . " has been successfully verified.";
        } else {
            $message = "Your shop " . $row['shop_name'] . " registration has been rejected.";
        }
    } else if ($row['notifType'] == 1) {
        $title = "Account Notification";
        if ($row['notifMessage'] == 2) {
            $message = "Your account has been suspended until " . date("F j, Y H:i", strtotime($row['suspendUntil'])) . ".";
        } else {
            $message = "Your account has been permanently banned.";
        }
    } else if ($row['notifType'] == 3) {
        $title = "Shop Notification";
        if ($row['notifMessage'] == 4) {
            $suspendUntilFormatted = date("F j, Y h:i A", strtotime($row['suspendUntil']));
            $message = "Your Shop " . $row['shop_name'] . " has been suspended until " . $suspendUntilFormatted . ".";
        } else if ($row['notifMessage'] == 5) {
            $message = "Your Shop " . $row['shop_name'] . " is permanently banned.";
        }
    }
    
    $notifications[] = [
        'id' => $row['id'],
        'title' => $title,
        'message' => $message,
        'stamp' => $row['stamp']
    ];
}

echo json_encode([
    "success" => true,
    "notifications" => $notifications
]);

$stmt->close();
$db->close();
?>