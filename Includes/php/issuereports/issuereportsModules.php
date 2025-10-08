<?php
$caresShopReportsHeaderArray = [];
$caresShopReportsArray = [];
$caresUserReportsArray = [];
$caresUserReportsHeaderArray = [];
$counterShop = 0;
$counterReports = 0;
$activePage = $_GET['active'];
$textTitle = ($activePage == "userreports") ? "User Reports" : "Shop Reports";
$header4Title = ($activePage == "userreports") ? "Related Drivers" : "Related Shops";

// FOR SHOP REPORTS
$sql = "SELECT csr.reportId, csr.shopId, csr.accountId, csr.reportType, csr.reportDetails, csr.reason, csr.stamp, csr.updatedTime, cs.shop_name AS shopName, CONCAT(ca.firstName, ' ', ca.surName) AS fullName, cs.reportAction, cs.suspendedUntil FROM cares_shopReport csr LEFT JOIN cares_shop cs ON csr.shopId = cs.shopId LEFT JOIN cares_account ca ON csr.accountId = ca.id";
$queryShopReportDetails = $db->query($sql);

if ($queryShopReportDetails && $queryShopReportDetails->num_rows > 0)
{
    while ($resultShopReportDetails = $queryShopReportDetails->fetch_object())
    {
        $stampDate = explode(" ", $resultShopReportDetails->stamp);
        $formattedStampDate = date("F j, Y", strtotime($stampDate[0]));
        $buttonIcon = (!empty($resultShopReportDetails->reason) && !empty($resultShopReportDetails->updatedTime)) ? "bi-info-circle" : "bi-exclamation-triangle";
        $buttonStyle = (!empty($resultShopReportDetails->reason) && !empty($resultShopReportDetails->updatedTime)) ? "btn-outline-info" : "btn-outline-warning";
        $caresShopReportsArray[] = [
            'counter'        => $counterShop+=1,
            'reportId'       => !empty($resultShopReportDetails->reportId) ? $resultShopReportDetails->reportId : 'N/A',
            'reportedBy'     => !empty($resultShopReportDetails->accountId) ? $resultShopReportDetails->accountId : 'N/A',
            'reportedId'     => !empty($resultShopReportDetails->shopId) ? $resultShopReportDetails->shopId : 'N/A',
            'reportType'     => !empty($resultShopReportDetails->reportType) ? $resultShopReportDetails->reportType : 'N/A',
            'reportDetails'  => !empty($resultShopReportDetails->reportDetails) ? $resultShopReportDetails->reportDetails : 'N/A',
            'reason'         => !empty($resultShopReportDetails->reason) ? $resultShopReportDetails->reason : 'N/A',
            'nameReportedBy' => !empty($resultShopReportDetails->shopName) ? $resultShopReportDetails->shopName : 'N/A',
            'nameReporter'   => !empty($resultShopReportDetails->fullName) ? $resultShopReportDetails->fullName : 'N/A',
            'stampDate'      => !empty($formattedStampDate) ? $formattedStampDate : 'N/A',
            'reportAction'   => !empty($resultShopReportDetails->reportAction) ? $resultShopReportDetails->reportAction : 0,
            'suspendedUntil' => !empty($resultShopReportDetails->suspendedUntil) ? $resultShopReportDetails->suspendedUntil : 'N/A',
            'status'         => 1,
            'buttonIcon'     => $buttonIcon,
            'buttonStyle'    => $buttonStyle,
        ];
    }
}

// FOR USER REPORTS
$sql = "SELECT cur.userReportId, cur.reportedBy, cur.reportedId, cur.reportType, cur.reportDetails, cur.reason, cur.stamp, cur.updatedTime, CONCAT(reported.firstName, ' ', reported.surName) AS nameReportedBy, CONCAT(reporter.firstName, ' ', reporter.surName) AS nameReporter, reported.reportAction, reported.suspendedUntil FROM cares_userReport cur LEFT JOIN cares_account reported ON cur.reportedId = reported.id LEFT JOIN cares_account reporter ON cur.reportedBy = reporter.id";
$queryUserReportDetails = $db->query($sql);

if($queryUserReportDetails && $queryUserReportDetails->num_rows > 0)
{
    while($resultUserReportDetails = $queryUserReportDetails->fetch_object())
    {
        $stampDate = explode(" ", $resultUserReportDetails->stamp);
        $formattedStampDate = date("F j, Y", strtotime($stampDate[0]));
        $buttonIcon = (!empty($resultUserReportDetails->reason) && !empty($resultUserReportDetails->updatedTime)) ? "bi-info-circle" : "bi-exclamation-triangle";
        $buttonStyle = (!empty($resultUserReportDetails->reason) && !empty($resultUserReportDetails->updatedTime)) ? "btn-outline-info" : "btn-outline-warning";
        $caresUserReportsArray[] = [
            'counter'        => $counterReports+=1,
            'reportId'       => !empty($resultUserReportDetails->userReportId) ? $resultUserReportDetails->userReportId : 'N/A',
            'reportedBy'     => !empty($resultUserReportDetails->reportedBy) ? $resultUserReportDetails->reportedBy : 'N/A',
            'reportedId'     => !empty($resultUserReportDetails->reportedId) ? $resultUserReportDetails->reportedId : 'N/A',
            'reportType'     => !empty($resultUserReportDetails->reportType) ? $resultUserReportDetails->reportType : 'N/A',
            'reportDetails'  => !empty($resultUserReportDetails->reportDetails) ? $resultUserReportDetails->reportDetails : 'N/A',
            'reason'         => !empty($resultUserReportDetails->reason) ? $resultUserReportDetails->reason : 'N/A',
            'nameReportedBy' => !empty($resultUserReportDetails->nameReportedBy) ? $resultUserReportDetails->nameReportedBy : 'N/A',
            'nameReporter'   => !empty($resultUserReportDetails->nameReporter) ? $resultUserReportDetails->nameReporter : 'N/A',
            'stampDate'      => !empty($formattedStampDate) ? $formattedStampDate : 'N/A',
            'reportAction'   => !empty($resultUserReportDetails->reportAction) ? $resultUserReportDetails->reportAction : 0,
            'suspendedUntil' => !empty($resultUserReportDetails->suspendedUntil) ? $resultUserReportDetails->suspendedUntil : 'N/A',
            'status'         => 2,
            'buttonIcon'     => $buttonIcon,
            'buttonStyle'    => $buttonStyle,
        ];
    }
}

$arrayData = ($activePage == "userreports") ? $caresUserReportsArray : $caresShopReportsArray;
$totalIssueReports = count($caresShopReportsArray) + count($caresUserReportsArray);

// FOR TESTING
// echo "<pre>";
// print_r($caresUserReportsArray);
// echo "</pre>";
?>