<?php
$shopCountArray = [];
$shopValidationDetailsArray = [];
$counter = 0;
$totalPendingShops = 0;
$totalVerifiedShops = 0;

$sql = "SELECT 
            cs.shopId, cs.accountId, cs.shop_name, CONCAT(ca.firstName, ' ', ca.surName) AS shopOwner, 
            cs.location, cs.business_docu, cs.valid_id, cs.isValidated, cs.validationRemarks, cs.dateTimeValidated 
        FROM cares_shop cs 
        INNER JOIN cares_account ca ON cs.accountId = ca.id";
$queryShopDetails = $db->query($sql);
$totalShopDetailsRecords = $queryShopDetails->num_rows;

if($queryShopDetails && $queryShopDetails->num_rows > 0)
{
    while($resultShopDetails = $queryShopDetails->fetch_object())
    {
        switch($resultShopDetails->isValidated) {
            case 0 :
                $textClass = 'text-warning';
                $shopStatus = 'Pending';
                $totalPendingShops++;
                break;
            case 1 :
                $textClass = 'text-success';
                $shopStatus = 'Verified';
                $totalVerifiedShops++;
                break;
            case 2 :
                $textClass = 'text-danger';
                $shopStatus = 'Rejected';
                break;
            default :
                $textClass = '';
                $shopStatus = '';
                break;
        }

        $shopValidationDetailsArray[] = [
            'counter' => $counter+=1,
            'shopId' => $resultShopDetails->shopId,
            'accountId' => $resultShopDetails->accountId,
            'shopName' => $resultShopDetails->shop_name,
            'shopOwner' => $resultShopDetails->shopOwner,
            'location' => $resultShopDetails->location,
            'textClass' => $textClass,
            'businessDocu' => $resultShopDetails->business_docu,
            'validId' => $resultShopDetails->valid_id,
            'shopStatus' => $shopStatus,
            'validationRemarks' => empty($resultShopDetails->validationRemarks) ? 'N/A' : $resultShopDetails->validationRemarks,
            'dateTimeValidated' => $resultShopDetails->dateTimeValidated
        ];
    }
}

// FOR TOTAL COUNTS
$shopCountArray = [
    ['totalPendingShop' => $totalPendingShops],
    ['totalVerifiedShop' => $totalVerifiedShops],
];

// FOR TESTING
// echo "<pre>";
// print_r($shopValidationDetailsArray);
// echo "</pre>";
?>