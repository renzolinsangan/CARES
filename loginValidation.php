<?php
include('Modules/phpInclude.php');

$userNameInput = isset($_POST['userName']) ? $_POST['userName'] : '';
$userPasswordInput = isset($_POST['userPassword']) ? $_POST['userPassword'] : '';

// validation
if(empty($userNameInput)) {
    echo "usernameempty";
    exit();
} else if(empty($userPasswordInput)) {
    echo "userpasswordempty";
    exit();
} else {
    $adminDataArray = [];

    $sql = "SELECT adminId, userName, userPassword, firstName, surName FROM care_admin WHERE userName LIKE '$userNameInput'";
    $queryCareAdmin = $db->query($sql);

    if($queryCareAdmin && $queryCareAdmin->num_rows > 0) {
        $resultCareAdmin = $queryCareAdmin->fetch_object();
        $adminUserPassword = $resultCareAdmin->userPassword;

        if($userPasswordInput == $adminUserPassword) {
            $_SESSION['adminId'] = $resultCareAdmin->adminId;
            $_SESSION['userName'] = $resultCareAdmin->userName;
            $_SESSION['userPassword'] = $resultCareAdmin->userPassword;
            $_SESSION['firstName'] = $resultCareAdmin->firstName;
            $_SESSION['surName'] = $resultCareAdmin->surName;
            $_SESSION['active'] = "dashboard";

            echo "loginsuccess";
            exit();
        } else {
            echo "userpasswordwrong";
            exit();
        }
    } else {
        echo "bothwrong";
        exit();
    }
}
// validation
?>