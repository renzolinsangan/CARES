<?php
session_start();
$rootPath = $_SERVER['DOCUMENT_ROOT'] . "/CARES/";
include($rootPath . 'Includes/php/dbConnection.php');

date_default_timezone_set("Asia/Manila");

$protocol = isset($_SERVER["HTTPS"]) ? "https://" : "http://";
$host = $_SERVER["HTTP_HOST"];
$serverPath = $protocol . $host . "/CARES/";

$dashboardPath = $serverPath . "redirect.php?active=dashboard";
$userManagementPath = $serverPath . "redirect.php?active=usermanagement";
$shopValidationPath = $serverPath . "redirect.php?active=shopvalidation";
$userReportsPath = $serverPath . "redirect.php?active=userreports";
$shopReportsPath = $serverPath . "redirect.php?active=shopreports";

$dashboardClass = "";
$userManagementClass = "";
$shopValidationClass = "";
$userReportsClass = "";
$shopReportsClass = "";

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
    case "userreports": 
        $userReportsClass = "active";
        break;
    case "shopreports":
        $shopReportsClass = "active";
        break;
    default:
        $dashboardClass = "";
        $userManagementClass = "";
        $shopValidationClass = "";
        $userReportsClass = "";
        $shopReportsClass = "";
        break;
}
?>