<?php
include('Includes/php/usermanagement/usermanagementModules.php'); // FOR TOTAL REGISTERED USERS
include('Includes/php/shopvalidation/shopvalidationModules.php'); // FOR TOTAL SHOP VALIDATION
include('Includes/php/issuereports/issuereportsModules.php'); // FOR TOTAL ISSUE REPORTS

$fullName = $_SESSION['firstName'] . " " . $_SESSION['surName'];

// array data contents for users column
$dashboardCards = [
    [
        'title' => 'Total Registered Users',
        'value' => $totalUserRecords ?? 0,
        'img' => 'Includes/Images/team.png',
        'class' => 'total-registered-users-card'
    ],
    [
        'title' => 'Total Verified Shops',
        'value' => $shopCountArray[1]["totalVerifiedShop"],
        'img' => 'Includes/Images/repair-shop.png',
        'class' => 'total-verified-shops-card'
    ],
    [
        'title' => 'Pending Validation',
        'value' => $shopCountArray[0]["totalPendingShop"],
        'img' => 'Includes/Images/verification.png',
        'class' => 'pending-validation-card'
    ],
    [
        'title' => 'Total Issue Reports',
        'value' => $totalIssueReports ?? 0,
        'img' => 'Includes/Images/report-issue.png',
        'class' => 'total-issue-reports-card'
    ]
];
?>