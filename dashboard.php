<?php 
include('Modules/phpInclude.php');
include('Includes/php/dashboard/dashboardModules.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CARES</title>
    <link rel="stylesheet" href="Includes/css/dashboard-style.css">
    <?php include_once("Modules/header.php"); ?>
</head>
<body>
    <!-- HEADER -->
    <?php include("Includes/php/navbar.php"); ?>

    <div class="main-content-wrapper">
        <?php include("Includes/php/sidebar.php"); ?>
        <!-- MAIN CONTENT -->
        <div class="flex-grow-1 p-4" style="overflow-y: auto;">
            <div class="dashboard-text-container row mb-4">
                <div class="col-12">
                    <div class="dashboard-text-container-card card ps-4 pt-3 pb-3">
                        <h1 class="dashboard-text-title">Dashboard</h1>
                        <p class="text-body-secondary">Welcome, <?php echo $fullName; ?>!</p>
                    </div>
                </div>
            </div>
            <div class="dashboard-container row">
                <?php foreach ($dashboardCards as $card): ?>
                    <div class="col-12 col-sm-3 col-md-3 mb-4">
                        <div class="<?= $card['class']; ?> card">
                            <div class="card-header text-center pb-0 pt-4">
                                <h5><?= $card['title']; ?></h5>
                            </div>
                            <div class="card-body text-center pb-4 pt-4">
                                <p class="fs-4"><?= $card['value']; ?></p>
                                <img src="<?= $card['img']; ?>" alt="<?= strtolower(str_replace(' ', '-', $card['title'])); ?>-icon">
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    <!-- JAVASCRIPT -->
    <?php include_once("Modules/footer.php"); ?>
    <script type="text/javascript" src="Includes/js/dashboard-js.js"></script>
</body>
</html>