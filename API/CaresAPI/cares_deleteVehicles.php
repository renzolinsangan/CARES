<?php
include "kurt_dbCon.php";
header('Content-Type: application/json');
date_default_timezone_set('Asia/Manila');
if (!$db) {
    die(json_encode(["success" => false, "message" => "Database connection failed"]));
}

$token = isset($_POST['token']) ? trim($_POST['token']) : '';
$vehicleId = isset($_POST['vehicleId']) ? intval($_POST['vehicleId']) : 0;

if (empty($token)) {
    die(json_encode(["success" => false, "message" => "Token is required"]));
}

if ($vehicleId <= 0) {
    die(json_encode(["success" => false, "message" => "Invalid vehicle ID"]));
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

$updateQuery = "UPDATE cares_vehicle SET isArchive = 1 WHERE id = ? AND accountId = ?";
$updateStmt = $db->prepare($updateQuery);
$updateStmt->bind_param("ii", $vehicleId, $accountId);
$success = $updateStmt->execute();
$updateStmt->close();

if ($success) {
    $checkActiveQuery = "SELECT COUNT(*) as active_count FROM cares_vehicle WHERE accountId = ? AND isArchive = 0";
    $checkStmt = $db->prepare($checkActiveQuery);
    $checkStmt->bind_param("i", $accountId);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    $row = $checkResult->fetch_assoc();
    $checkStmt->close();
    
    if ($row['active_count'] == 0) {
        $updateAccountQuery = "UPDATE cares_account SET hasVehicle = 0 WHERE id = ?";
        $updateAccountStmt = $db->prepare($updateAccountQuery);
        $updateAccountStmt->bind_param("i", $accountId);
        $updateAccountStmt->execute();
        $updateAccountStmt->close();
    }
    
    $db->close();
    echo json_encode(["success" => true, "message" => "Vehicle archived successfully"]);
} else {
    $db->close();
    echo json_encode(["success" => false, "message" => "Failed to archive vehicle"]);
}
?>