<?php
$rootPath = $_SERVER['DOCUMENT_ROOT'] . "/CARES/";
set_include_path($rootPath);
include("Modules/phpInclude.php");

$shopId = isset($_POST['shopId']) ? $_POST['shopId'] : '';
$shopStatus = isset($_POST['shopStatus']) ? $_POST['shopStatus'] : '';

$sql = "UPDATE cares_shop SET isValidated = $shopStatus WHERE id = $shopId";
$queryShopValidation = $db->query($sql);

if($queryShopValidation) {
    echo "success";
}
?>