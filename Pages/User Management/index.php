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
    <link rel="stylesheet" href="../../Includes/css/usermanagement-style.css">
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
                        <p class="fs-4 mb-0">Total Records: 2</p>
                        <div class="table-responsive">
                            <table class="usermanagement-table table table-bordered table-responsive" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Gender</th>
                                        <th>Email</th>
                                        <th>Registered Vehicle</th>
                                        <th>Role</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>John Renzo</td>
                                        <td>Linsangan</td>
                                        <td>Male</td>
                                        <td>renzolinsangan11@gmail.com</td>
                                        <td>Mitsubishi</td>
                                        <td>Repair Shop Owner</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Kurt Bryant</td>
                                        <td>Arpilleda</td>
                                        <td>Male</td>
                                        <td>KBArpilleda@gmail.com</td>
                                        <td>Toyota</td>
                                        <td>Vehicle Owner</td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- JAVASCRIPT -->
    <?php include_once("Modules/footer.php"); ?>
    <script type="text/javascript" src="../../Includes/js/usermanagement-js.js"></script>
</body>
</html>