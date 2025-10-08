<link rel="stylesheet" href="<?php echo $serverPath; ?>Includes/css/sidebar-style.css">
<!-- SIDEBAR (visible on desktop only) -->
<div class="sidebar flex-shrink-0 p-3 bg-white border-end d-none d-lg-block" style="width: 245px;">
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item mb-3">
            <a href="<?php echo $dashboardPath; ?>" class="nav-link <?php echo $dashboardClass; ?>">
            <i class="bi bi-house-door me-2"></i>&nbsp;Dashboard
            </a>
        </li>
        <li class="nav-item mb-3">
            <a href="<?php echo $userManagementPath; ?>" class="nav-link <?php echo $userManagementClass; ?>">
            <i class="bi bi-person-fill-gear"></i>&nbsp;User Management
            </a>
        </li>
        <li class="nav-item mb-3">
            <a href="<?php echo $shopValidationPath; ?>" class="nav-link <?php echo $shopValidationClass; ?>">
            <i class="bi bi-folder-check me-2"></i>&nbsp;Shop Validation
            </a>
        </li>
        <li class="nav-item mb-3">
            <a class="nav-link" data-bs-toggle="collapse" href="#issueReportsSubmenu" role="button" aria-expanded="false" aria-controls="issueReportsSubmenu">
                <i class="bi bi-exclamation-triangle me-2"></i>&nbsp;Issue Reports
                <i class="bi bi-chevron-down float-end"></i>
            </a>
            <div class="collapse" id="issueReportsSubmenu">
                <ul class="nav flex-column ms-3 mt-2">
                    <li class="nav-item mb-2">
                        <a href="<?php echo $userReportsPath; ?>" class="nav-link <?php echo $userReportsClass; ?>"><i class="bi bi-person-exclamation"></i>&nbsp;User Reports</a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="<?php echo $shopReportsPath; ?>" class="nav-link <?php echo $shopReportsClass; ?>"><i class="bi bi-shop"></i>&nbsp;Shop Reports</a>
                    </li>
                </ul>
            </div>
        </li>
    </ul>
</div>