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
    <link rel="stylesheet" href="../../Includes/css/issuereports/issuereports-style.css">
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
                        <h1 class="issuereports-text-title"><?php echo $textTitle; ?></h1>
                        <p class="text-body-secondary">(Reports Section & Sanction Level)</p>
                    </div>
                </div>
            </div>
            <div class="issuereports-table-container row mb-4">
                <div class="col-12">
                    <div class="issuereports-table-card card p-4">
                        <p class="fs-4 mb-0">Total Records: <?php echo count($arrayData); ?></p>
                        <div class="table-responsive">
                            <table class="issuereports-table table table-bordered table-responsive" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Reporter Name</th>
                                        <th>Report Type</th>
                                        <th><?php echo $header4Title; ?></th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($arrayData as $data) : ?>
                                        <tr>
                                            <td><?php echo $data['counter']; ?></td>
                                            <td><?php echo $data['nameReporter']; ?></td>
                                            <td><?php echo $data['reportType']; ?></td>
                                            <td><?php echo $data['nameReportedBy']; ?></td>
                                            <td><?php echo $data['stampDate']; ?></td>
                                            <td>
                                                <button type="button" class="btn <?php echo $data['buttonStyle']; ?>" title="View Reports" onclick="showReportsDetails('<?php echo $data['reportId']; ?>', '<?php echo $data['reportedBy']; ?>', '<?php echo $data['reportedId']; ?>', '<?php echo $data['nameReporter']; ?>', '<?php echo $data['nameReportedBy']; ?>', '<?php echo $data['reportType']; ?>', <?php echo $data['reportAction']; ?>, '<?php echo $data['suspendedUntil']; ?>', '<?php echo $data['reportDetails']; ?>', '<?php echo $data['reason']; ?>', <?php echo $data['status']?>)"><i class="bi <?php echo $data['buttonIcon']; ?>"></i></button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
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
    <script type="text/javascript" src="../../Includes/js/issuereports/issuereports-js.js"></script>
</body>
</html>