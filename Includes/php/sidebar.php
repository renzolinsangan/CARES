<style>
/* SIDE NAVBAR */
.sidebar {
    height: 100vh;
    background-color: #ffffff;
    position: fixed;
    left: 0;
    padding-top: 30px;
    transition: all 0.3s;
    z-index: 1030;
}

.dashboard-card-container, .repair-shop-card-container, .issue-reports-card-container {
    border: none !important;
    cursor: pointer;
}

.dashboard-card-container.active, .repair-shop-card-container.active, .issue-reports-card-container.active {
    background-color: #1A3D63 !important;
    color: white !important;
}

.dashboard-card-container:hover, .dashboard-icon:hover, .repair-shop-card-container:hover, .issue-reports-card-container:hover {
    background-color: #1A3D63 !important;
    color: white !important;
}
</style>

<div id="sidebar" class="sidebar mt-1 p-4">
    <div class="col-12 mb-4">
        <div class="dashboard-card-container <?php echo $dashboardClass; ?> card d-block pt-2 pb-2 ps-3">
            <i class="dashboard-icon bi bi-house-door-fill me-2"></i>&nbsp;<span>Dashboard</span>
        </div>
    </div>
    <div class="col-12 mb-4">
        <div class="repair-shop-card-container <?php echo $shopValidationClass; ?> card d-block pt-2 pb-2 ps-3 pe-5">
            <i class="bi bi-folder-fill me-2"></i>&nbsp;<span>Shop Validation</span>
        </div>
    </div>
    <div class="col-12 mb-4">
        <div class="issue-reports-card-container <?php echo $issueReportsClass; ?> card d-block pt-2 pb-2 ps-3">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>&nbsp;<span>Issue Reports</span>
        </div>
    </div>
</div>