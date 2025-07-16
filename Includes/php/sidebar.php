<link rel="stylesheet" href="Includes/css/sidebar-style.css">
<!-- SIDEBAR (visible on desktop only) -->
<div class="sidebar flex-shrink-0 p-3 bg-white border-end d-none d-lg-block" style="width: 245px;">
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item mb-3">
            <a href="dashboard.php?active=dashboard" class="nav-link <?php echo $dashboardClass; ?>">
            <i class="bi bi-house-door me-2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item mb-3">
            <a href="#" class="nav-link <?php echo $shopValidationClass; ?>">
            <i class="bi bi-folder-check me-2"></i> Shop Validation
            </a>
        </li>
        <li class="nav-item mb-3">
            <a href="#" class="nav-link <?php echo $issueReportsClass; ?>">
            <i class="bi bi-exclamation-triangle me-2"></i> Issue Reports
            </a>
        </li>
    </ul>
</div>