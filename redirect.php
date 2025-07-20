<?php
include('Modules/phpInclude.php');

$activePageStatus = $_GET['active'];

switch($activePageStatus) {
    case "dashboard":
        $_SESSION['active'] = $activePageStatus;
        header("Location: dashboard.php?active=" . $activePageStatus);
        break;
    case "usermanagement" :
        $_SESSION['active'] = $activePageStatus;
        header("Location: Pages/User%20Management/index.php?active=" . $activePageStatus);
        break;
    case "shopvalidation":
        $_SESSION['active'] = $activePageStatus;
        header("Location: Pages/Shop%20Validation/index.php?active=" . $activePageStatus);
        break;
    case "issuereports": 
        $_SESSION['active'] = $activePageStatus;
        header("Location: Pages/Issue%20Reports/index.php?active=" . $activePageStatus);
        break;
    default:
        echo "There is an error in redirect.php, please try again!";
        break;
}
?>