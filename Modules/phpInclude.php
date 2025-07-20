<?php
session_start();
$rootPath = $_SERVER['DOCUMENT_ROOT'] . "/CARES/";
include($rootPath . 'Includes/php/dbConnection.php');

$protocol = isset($_SERVER["HTTPS"]) ? "https://" : "http://";
$host = $_SERVER["HTTP_HOST"];
$serverPath = $protocol . $host . "/CARES/";

$sessionActive = !empty($_SESSION['active']) ? $_SESSION['active'] : '';
$getActive  = (!empty($GET['active'])) ? $_GET['active'] : $sessionActive;

switch($getActive) {
    case "dashboard":
        $dashboardClass = "active";
        break;
    case "usermanagement" :
        $userManagementClass = "active";
        break;
    case "shopvalidation":
        $shopValidationClass = "active";
        break;
    case "issuereports": 
        $issueReportsClass = "active";
        break;
    default:
        $dashboardClass = "";
        $shopValidationClass = "";
        $issueReportsClass = "";
        break;
}
?>