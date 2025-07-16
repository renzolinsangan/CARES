<?php
session_start();
include('Includes/php/dbConnection.php');
$serverPath = $_SERVER['DOCUMENT_ROOT'] . "/" . "CARES/";

$sessionActive = !empty($_SESSION['active']) ? $_SESSION['active'] : '';
$getActive  = (!empty($GET['active'])) ? $_GET['active'] : $sessionActive;

switch($getActive) {
    case "dashboard":
        $dashboardClass = "active";
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