<?php
$rootPath = $_SERVER['DOCUMENT_ROOT'] . "/CARES/";
set_include_path($rootPath);
include("Modules/phpInclude.php");
include("Includes/php/issuereports/issuereportsModules.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CARES</title>
    <link rel="stylesheet" href="../../Includes/css/issuereports-style.css">
    <?php include_once("Modules/header.php"); ?>
</head>
<body>
    <!-- HEADER -->
    <?php include("Includes/php/navbar.php"); ?>

    <div class="main-content-wrapper">
        <?php include("Includes/php/sidebar.php"); ?>
        <!-- MAIN CONTENT -->
        <div class="flex-grow-1 p-4" style="overflow-y: auto;">
            <div class="issuereports-text-container row mb-4">
                <div class="col-12">
                    <div class="issuereports-text-container-card card ps-4 pt-3 pb-3">
                        <h1 class="issuereports-text-title">Issue Reports</h1>
                        <p class="text-body-secondary">(Reports Section & Banning Users)</p>
                    </div>
                </div>
            </div>
            <div class="issuereports-table-container row mb-4">
                <div class="col-12">
                    <div class="issuereports-table-card card p-4">
                        <p class="fs-4 mb-0">Total Records: 2</p>
                        <div class="table-responsive">
                            <table class="issuereports-table table table-bordered table-responsive" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>Reporter Name</th>
                                        <th>Report Type</th>
                                        <th>Date</th>
                                        <th>Related Shop / Drivers</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            Allen Johnson
                                        </td>
                                        <td>
                                            Communication
                                        </td>
                                        <td>
                                            January 10, 2025
                                        </td>
                                        <td>
                                            <button class="btn btn-primary">View Reports</button>
                                        </td>
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
    <script type="text/javascript" src="../../Includes/js/issuereports-js.js"></script>
</body>
</html>