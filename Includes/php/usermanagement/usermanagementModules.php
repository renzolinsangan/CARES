<?php
$caresAccountDetailsArray = [];
$counter = 0; 

$sql = "SELECT ca.id, ca.firstName, ca.surName, ca.gender, ca.email, ca.phoneNum, GROUP_CONCAT(DISTINCT CONCAT('(', cv.vehicle_model, ' - ', cv.plate_number, ')') SEPARATOR ', ') AS vehicleInfo, MAX(cs.accountId) AS roleStatus FROM cares_account ca LEFT JOIN cares_vehicle cv ON ca.id = cv.accountId LEFT JOIN cares_shop cs ON ca.id = cs.accountId GROUP BY ca.id";
$queryCaresAccount = $db->query($sql);
$totalUserRecords = $queryCaresAccount->num_rows;

if($queryCaresAccount && $queryCaresAccount->num_rows > 0)
{
    while($resultCaresAccount = $queryCaresAccount->fetch_object())
    {
        $gender = ($resultCaresAccount->gender == 0) ? "Male" : "Female";
        $roleStatus = (!empty($resultCaresAccount->roleStatus)) ? "Repair Shop Owner" : "Vehicle Owner";

        $caresAccountDetailsArray[] = [
            'counter' => $counter+=1,
            'userId' => $resultCaresAccount->id ?? "N/A",
            'firstName' => $resultCaresAccount->firstName ?? "N/A",
            'surName' => $resultCaresAccount->surName ?? "N/A",
            'gender' => $gender ?? "N/A",
            'email' => $resultCaresAccount->email ?? "N/A",
            'phoneNumber' => $resultCaresAccount->phoneNum ?? "N/A",
            'vehicleType' => $vehicleType ?? "N/A",
            'vehicleInfo' => $resultCaresAccount->vehicleInfo ?? "N/A",
            'roleStatus' => $roleStatus ?? "N/A",
        ];
    }
}

// FOR TESTING
// echo "<pre>";
// print_r($caresAccountDetailsArray);
// echo "</pre>";
?>