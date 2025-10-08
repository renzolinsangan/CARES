<?php
include "kurt_dbCon.php";
header('Content-Type: application/json');
date_default_timezone_set('Asia/Manila');

if (!$db) {
    die(json_encode(["success" => false, "message" => "Database connection failed"]));
}

$token = isset($_POST['token']) ? trim($_POST['token']) : '';
$shopId = isset($_POST['shopId']) ? intval($_POST['shopId']) : 0;
$limit = isset($_POST['limit']) ? intval($_POST['limit']) : 5;

if (empty($token)) {
    die(json_encode(["success" => false, "message" => "Token is required"]));
}

if ($shopId <= 0) {
    die(json_encode(["success" => false, "message" => "Shop ID is required"]));
}

$accountQuery = "SELECT accountId FROM user_sessions WHERE token = ? AND isActive = 1";
$accountStmt = $db->prepare($accountQuery);
$accountStmt->bind_param("s", $token);
$accountStmt->execute();
$accountResult = $accountStmt->get_result();

if ($accountResult->num_rows === 0) {
    die(json_encode(["success" => false, "message" => "Invalid token"]));
}
$accountStmt->close();

$reviewQuery = "SELECT sr.ratingStar, sr.ratingFeedback, sr.stamp, sr.accountId,
                       ca.firstName, ca.surName, ca.photoUrl
                FROM cares_shopReview sr
                LEFT JOIN cares_account ca ON sr.accountId = ca.id
                WHERE sr.shopId = ?
                ORDER BY sr.stamp DESC
                LIMIT ?";

$reviewStmt = $db->prepare($reviewQuery);
$reviewStmt->bind_param("ii", $shopId, $limit);
$reviewStmt->execute();
$reviewResult = $reviewStmt->get_result();

$reviews = [];
while ($row = $reviewResult->fetch_assoc()) {
    $reviews[] = [
        'rating' => $row['ratingStar'],
        'feedback' => $row['ratingFeedback'] ?? '',
        'stamp' => $row['stamp'],
        'accountId' => $row['accountId'],
        'firstName' => $row['firstName'] ?? 'User',
        'surName' => $row['surName'] ?? '',
        'photoUrl' => $row['photoUrl'] ?? ''
    ];
}

$countQuery = "SELECT COUNT(*) as total FROM cares_shopReview WHERE shopId = ?";
$countStmt = $db->prepare($countQuery);
$countStmt->bind_param("i", $shopId);
$countStmt->execute();
$countResult = $countStmt->get_result();
$totalCount = $countResult->fetch_assoc()['total'];

echo json_encode([
    "success" => true,
    "reviews" => $reviews,
    "total" => $totalCount
]);

$reviewStmt->close();
$countStmt->close();
$db->close();
?>