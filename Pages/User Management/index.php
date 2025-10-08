<?php
$rootPath = $_SERVER['DOCUMENT_ROOT'] . "/CARES/";
set_include_path($rootPath);
include("Modules/phpInclude.php");
include("Includes/php/usermanagement/usermanagementModules.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CARES</title>
    <link rel="stylesheet" href="../../Includes/css/usermanagement/usermanagement-style.css">
    <?php include_once("Modules/header.php"); ?>
</head>
<body>
    <!-- HEADER -->
    <?php include("Includes/php/navbar.php"); ?>

    <div class="main-content-wrapper">
        <?php include("Includes/php/sidebar.php"); ?>
        <!-- MAIN CONTENT -->
        <div class="flex-grow-1 p-4" style="overflow-y: auto;">
            <div class="usermanagement-text-container row mb-4">
                <div class="col-12">
                    <div class="usermanagement-text-container-card card ps-4 pt-3 pb-3">
                        <h1 class="usermanagement-text-title">User Management</h1>
                        <p class="text-body-secondary">(Repair Shop & Vehicle Owner)</p>
                    </div>
                </div>
            </div>
            <div class="usermanagement-table-container row mb-4">
                <div class="col-12">
                    <div class="usermanagement-table-card card p-4">
                        <p class="fs-4 mb-0">Total Records:&nbsp;<span class="fw-bold"><?php echo $totalUserRecords; ?></span></p>
                        <!-- <div class="table-responsive"> -->
                            <table class="usermanagement-table table table-bordered table-responsive" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Gender</th>
                                        <th>Email</th>
                                        <th>Phone Number</th>
                                        <th>Vehicle Model - Plate Number</th>
                                        <th>Role</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($caresAccountDetailsArray as $data) : ?>
                                        <tr>
                                            <td><?php echo $data['counter']; ?></td>
                                            <td><?php echo $data['firstName']; ?></td>
                                            <td><?php echo $data['surName']; ?></td>
                                            <td><?php echo $data['gender']; ?></td>
                                            <td><?php echo $data['email']; ?></td>
                                            <td><?php echo $data['phoneNumber']; ?></td>
                                            <td><?php echo $data['vehicleInfo']; ?></td>
                                            <td><?php echo $data['roleStatus']; ?></td>
                                            <td>
                                                <button class="edit-btn btn btn-outline-info" title="Edit User"
                                                    data-id="<?php echo $data['userId']; ?>"
                                                    data-firstname="<?php echo $data['firstName']; ?>"
                                                    data-surname="<?php echo $data['surName']; ?>"
                                                    data-gender="<?php echo $data['gender']; ?>"
                                                    data-email="<?php echo $data['email']; ?>"
                                                    data-phonenumber="<?php echo $data['phoneNumber']; ?>">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach;?>
                                </tbody>
                            </table>
                        <!-- </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id='modal-izi-editUser'><span class='izimodal-content-editUser'></span></div>

    <!-- JAVASCRIPT -->
    <?php include_once("Modules/footer.php"); ?>
    <script type="text/javascript" src="../../Includes/js/usermanagement/usermanagement-js.js"></script>
</body>
</html>