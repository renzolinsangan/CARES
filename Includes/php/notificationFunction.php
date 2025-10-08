<?php
function dateConverter($date, $time) {
    $formattedStampDate = date("F j, Y", strtotime($date));
    $formattedStampTime = date("g:i A", strtotime($time));

    $stampDateTime = $formattedStampDate ." ". $formattedStampTime;
    return $stampDateTime;
}

$notificationDataArray = [];
$dateToday = date("Y-m-d");
$dateAMonth = date("Y-m-d", strtotime("-1 month"));


// PENDING SHOP VALIDATION
$sql = "SELECT shop_name, isValidated, stamp FROM cares_shop WHERE isValidated = 0 AND reportAction = 0 AND isArchive = 0";
$queryShopPendingNotification = $db->query($sql);

if($queryShopPendingNotification && $queryShopPendingNotification->num_rows > 0) {
    while($resultShopPendingNotification = $queryShopPendingNotification->fetch_object()) {
        $stampDate = explode(" ", $resultShopPendingNotification->stamp);
        $date = dateConverter($stampDate[0], $stampDate[1]);

        $notificationDataArray[] = [
            'icon' => '<i class="bi bi-hourglass-split text-warning"></i>',
            'message' => 'You have pending validation for Shop - ' . $resultShopPendingNotification->shop_name . '.',
            'dateCreated' => $date,
            'url' => $shopValidationPath,
        ];
    }
}

// USER CREATED NOTIFICATION 
$sql = "SELECT CONCAT(firstName, ' ', surName) AS fullName, createdAt FROM cares_account WHERE reportAction = 0 AND createdAt BETWEEN '$dateAMonth' AND '$dateToday'";
$queryUserCreatedNotification = $db->query($sql);

if($queryUserCreatedNotification && $queryUserCreatedNotification->num_rows > 0) {
    while($resultUserCreatedNotification = $queryUserCreatedNotification->fetch_object()) {
        $stampDate = explode(" ", $resultUserCreatedNotification->createdAt);
        $date = dateConverter($stampDate[0], $stampDate[1]);

        $notificationDataArray[] = [
            'icon' => "<i class='bi bi-person-plus-fill text-success'></i>",
            'message' => "New User registered - " . $resultUserCreatedNotification->fullName . ".",
            'dateCreated' => $date,
            'url' => $userManagementPath,
        ];
    }
}

// SHOP REPORT NOTIFICATION
$sql = "SELECT csr.reportId, csr.stamp, csr.updatedTime, cs.shop_name AS shopName FROM cares_shopReport csr LEFT JOIN cares_shop cs ON csr.shopId = cs.shopId WHERE csr.reason != '' AND cs.isArchive = 0 AND csr.stamp BETWEEN '$dateAMonth' AND '$dateToday'";
$queryShopReportNotification = $db->query($sql);

if($queryShopReportNotification && $queryShopReportNotification->num_rows > 0) {
    while($resultShopReportNotification = $queryShopReportNotification->fetch_object()) {
        $stampDate = explode(" ", $resultShopReportNotification->stamp);
        $date = dateConverter($stampDate[0], $stampDate[1]);

        $notificationDataArray[] = [
            'icon' => "<i class='bi bi-flag-fill text-danger'></i>",
            'message' => "Shop - " . $resultShopReportNotification->shopName . ' has been reported.',
            'dateCreated' => $date,
            'url' => $shopReportsPath,
        ];
    }
}

// USER REPORT NOTIFICATION
$sql = "SELECT cur.userReportId, cur.stamp, CONCAT(reported.firstName, ' ', reported.surName) AS nameReportedBy, CONCAT(reporter.firstName, ' ', reporter.surName) AS nameReporter FROM cares_userReport cur LEFT JOIN cares_account reported ON cur.reportedId = reported.id LEFT JOIN cares_account reporter ON cur.reportedBy = reporter.id WHERE cur.reason != '' AND cur.stamp BETWEEN '$dateAMonth' AND '$dateToday'";
$queryUserReportNotification = $db->query($sql);

if($queryUserReportNotification && $queryUserReportNotification->num_rows > 0) {
    while($resultUserReportNotification = $queryUserReportNotification->fetch_object()) {
        $stampDate = explode(" ", $resultUserReportNotification->stamp);
        $date = dateConverter($stampDate[0], $stampDate[1]);

        $notificationDataArray[] = [
            'icon' => "<i class='bi bi-flag-fill text-danger'></i>",
            'message' => "User - " . $resultUserReportNotification->nameReporter . " reported User - " . $resultUserReportNotification->nameReportedBy . ".",
            'dateCreated' => $date,
            'url' => $userReportsPath,
        ];
    }
}

usort($notificationDataArray, function($a, $b) {
    return strtotime($b['dateCreated']) - strtotime($a['dateCreated']);
});

// FOR TESTING
// echo "<pre>";
// print_r($notificationDataArray);
// echo "</pre>";
?>