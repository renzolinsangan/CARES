<?php
$rootPath = $_SERVER['DOCUMENT_ROOT'] . "/";
set_include_path($rootPath);
include("Modules/phpInclude.php");

$encodedDataArray = [];
$shopId = isset($_POST['shopId']) ? $_POST['shopId'] : '';
$accountId = isset($_POST['accountId']) ? $_POST['accountId'] : '';
$shopStatus = isset($_POST['shopStatus']) ? $_POST['shopStatus'] : '';
$alertMessage = ($shopStatus == 1) ? "Verified" : "Rejected";
$notifMessage = ($shopStatus == 1) ? 1 : 0;
$dateTime = date('Y-m-d H:i:s');

$sql = "INSERT INTO cares_notification (notifType, accountId, shopId, notifMessage, stamp) VALUES (0, '$accountId', '$shopId', $notifMessage, '$dateTime')";
$queryCaresNotification = $db->query($sql);

$sql = "UPDATE cares_shop SET isValidated = $shopStatus WHERE shopId = $shopId";
$queryShopValidation = $db->query($sql);

if($queryShopValidation) {
    $encodedDataArray = [
        'status' => 'success',
        'value' => $alertMessage,
    ];
} else {
    $encodedDataArray = [
        'status' => 'error',
        'value' => $alertMessage,
    ];
}

echo json_encode($encodedDataArray);
?>