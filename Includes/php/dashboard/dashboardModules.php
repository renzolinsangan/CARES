<?php
$fullName = $_SESSION['firstName'] . " " . $_SESSION['surName'];
// array data contents for users column
$dashboardCards = [
    [
        'title' => 'Total Registered Users',
        'value' => '1000',
        'img' => 'Includes/Images/team.png',
        'class' => 'total-registered-users-card'
    ],
    [
        'title' => 'Total Verified Shops',
        'value' => '1000',
        'img' => 'Includes/Images/repair-shop.png',
        'class' => 'total-verified-shops-card'
    ],
    [
        'title' => 'Pending Validation',
        'value' => '1000',
        'img' => 'Includes/Images/verification.png',
        'class' => 'pending-validation-card'
    ],
    [
        'title' => 'Total Issue Reports',
        'value' => '1000',
        'img' => 'Includes/Images/report-issue.png',
        'class' => 'total-issue-reports-card'
    ]
];
?>