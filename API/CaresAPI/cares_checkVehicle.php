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

$query = "SELECT a.hasVehicle 
          FROM cares_account a
          JOIN user_sessions s ON a.id = s.accountId
          WHERE s.token = ? AND s.isActive = 1";
$stmt = $db->prepare($query);

if ($stmt) {
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(["success" => false, "message" => "Invalid token or session"]);
    } else {
        $row = $result->fetch_assoc();
        echo json_encode([
            "success" => true,
            "hasVehicle" => (int)$row['hasVehicle']
        ]);
    }
    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Database error"]);
}

$db->close();
?>