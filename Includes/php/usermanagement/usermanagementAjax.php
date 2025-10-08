<?php
$rootPath = $_SERVER['DOCUMENT_ROOT'] . "/CARES/";
set_include_path($rootPath);
include("Modules/phpInclude.php");

$responseArray = [];
$userId = isset($_POST['userId']) ? $_POST['userId'] : '';
$firstName = isset($_POST['firstName']) ? $_POST['firstName'] : '';
$surName = isset($_POST['surName']) ? $_POST['surName'] : '';
$phoneNumber = isset($_POST['phoneNumber']) ? $_POST['phoneNumber'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$gender = isset($_POST['gender']) ? $_POST['gender'] : '';

if(!empty($userId) && !empty($firstName) && !empty($surName) && !empty($phoneNumber) && !empty($email)) {
    $sql = "UPDATE cares_account 
            SET firstName = '$firstName', surName = '$surName', gender = $gender, email = '$email', phoneNum = '$phoneNumber'
            WHERE id = $userId";
    $queryUpdateUser = $db->query($sql);

    if($queryUpdateUser) {
        $responseArray = [
            'status' => 'editusersuccess'
        ];
    } else {
        $responseArray = [
            'status' => 'editusererror'
        ];
    }

    echo json_encode($responseArray);
}
?>