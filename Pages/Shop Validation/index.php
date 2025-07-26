<?php
$rootPath = $_SERVER['DOCUMENT_ROOT'] . "/CARES/";
set_include_path($rootPath);
include("Modules/phpInclude.php");
include("Includes/php/shopvalidation/shopvalidationModules.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CARES</title>
    <link rel="stylesheet" href="../../Includes/css/shopvalidation/shopvalidation-style.css">
    <?php include_once("Modules/header.php"); ?>
</head>
<body>
    <!-- HEADER -->
    <?php include("Includes/php/navbar.php"); ?>

    <div class="main-content-wrapper">
        <?php include("Includes/php/sidebar.php"); ?>
        <!-- MAIN CONTENT -->
        <div class="flex-grow-1 p-4" style="overflow-y: auto;">
            <div class="shopvalidation-text-container row mb-4">
                <div class="col-12">
                    <div class="shopvalidation-text-container-card card ps-4 pt-3 pb-3">
                        <h1 class="shopvalidation-text-title">Shop Validation</h1>
                        <p class="text-body-secondary">(Repair Shop Document Approval)</p>
                    </div>
                </div>
            </div>
            <div class="shopvalidation-table-container row mb-4">
                <div class="col-12">
                    <div class="shopvalidation-table-card card p-4">
                        <p class="fs-4 mb-0">Total Records: 2</p>
                        <div class="table-responsive">
                            <table class="shopvalidation-table table table-bordered table-responsive" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Shop Name</th>
                                        <th>Owner Name</th>
                                        <th>Location</th>
                                        <th>Shop Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($shopValidationDetailsArray as $data) : ?>
                                        <tr>
                                            <td><?php echo $data['counter']; ?></td>
                                            <td><?php echo $data['shopName']; ?></td>
                                            <td><?php echo $data['shopOwner']; ?></td>
                                            <td><?php echo $data['location']; ?></td>
                                            <td class="<?php echo $data['textClass']; ?> fw-bold"><?php echo $data['shopStatus']; ?></td>
                                            <td><button class="btn btn-outline-info" title="View Documents" onclick="showDocuments('<?php echo $data['shopName']; ?>')"><i class="bi bi-files-alt"></i></button></td>
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
    <script type="text/javascript" src="../../Includes/js/shopvalidation/shopvalidation-js.js"></script>
</body>
</html>