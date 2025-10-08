<?php
$rootPath = $_SERVER['DOCUMENT_ROOT'] . "/CARES/";
set_include_path($rootPath);
include("Modules/phpInclude.php");

$encodedDataArray = [];
$dateTime = date('Y-m-d H:i:s');

// POST DATA
$sanctionLevel = $_POST['sanctionLevel'] ?? '';
$reason = $db->real_escape_string($_POST['reason'] ?? '');
$suspensionTime = $_POST['suspensionTime'] ?? '';
$idReport = (int)($_POST['idReport'] ?? 0);
$reportedBy = $_POST['reportedBy'] ?? '';
$reportedId = (int)($_POST['reportedId'] ?? 0);
$banStatus = $_POST['banStatus'] ?? '';
$status = (int)($_POST['status'] ?? 0);

// Format suspension time properly if needed
if ($suspensionTime) {
    $suspensionTime = str_replace("T", " ", $suspensionTime);
}

// TABLE TERNARY DIFFERENTIATION
$tableNameReport = ($status == 1) ? 'cares_shopReport' : 'cares_userReport';
$tableIdNameReport = ($status == 1) ? 'reportId' : 'userReportId';
$tableNameSanction = ($status == 1) ? 'cares_shop' : 'cares_account';
$tableIdNameSanction = ($status == 1) ? 'shopId' : 'id';
$sanctionSuspensionData = ($sanctionLevel == 1) ? ", suspendedUntil = '$suspensionTime'" : ", suspendedUntil = '0000-00-00 00:00:00'";
$alertSanctionTitle = ($status == 1) ? "Shop" : "User";
$alertSanctionText = ($sanctionLevel == 1) ? "Suspended" : "Banned";

if($banStatus === 'unban' || $banStatus == 'unsuspend') {
    $reason = "";
    $dateTime = "";
    $sanctionSuspensionData = "";
    $sanctionLevel = 0;
    $alertSanctionText = $banStatus == "unban" ? "Unbanned" : "Unsuspend";
}

// UPDATE report details
$sql = "UPDATE $tableNameReport SET reason = '$reason', updatedTime = '$dateTime' WHERE $tableIdNameReport = $idReport";
$queryReportDetails = $db->query($sql);

// APPLY sanction
$sql = "UPDATE $tableNameSanction SET reportAction = $sanctionLevel $sanctionSuspensionData WHERE $tableIdNameSanction = $reportedId";
$queryReportAction = $db->query($sql);

if($queryReportDetails && $queryReportAction) {
    $encodedDataArray = [
        'status' => 'success',
        'alertTitle' => $alertSanctionTitle,
        'alertText' => $alertSanctionText,
    ];
} else {
    $encodedDataArray = [
        'status' => 'fail',
        'alertTitle' => $alertSanctionTitle,
        'alertText' => $alertSanctionText,
    ];
}

echo json_encode($encodedDataArray);

// FOR TESTING OF SHOWING DATA
// $testArray[] = [
//     'sanctionLevel' => $sanctionLevel,
//     'reason' => $reason,
//     'dateTime' => $dateTime,
//     'idReport' => $idReport,
//     'reportedBy' => $reportedBy,
//     'reportedId' => $reportedId,
//     'status' => $status,
// ];
?>