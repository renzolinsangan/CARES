<link rel="stylesheet" href="<?php echo $serverPath; ?>Includes/css/navbar-style.css">
<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container-fluid ms-3 me-3 ms-md-5 me-md-5 p-3 p-md-1">
        <div class="header-text-container d-flex">
            <div class="cares-circle-icon me-2"></div>
            <a class="navbar-brand fs-4" href="<?php echo $_SERVER['PHP_SELF']; ?>">CARES</a>
        </div>
        <div class="header-icon-container d-flex align-items-center justify-content-center gap-3">
            <div class="notification-icon-container text-center">
                <div class="notification-icon">
                    <i class="bell-icon bi bi-bell fs-4"></i>
                </div>
            </div>
            <div class="logout-icon-container dropdown text-center">
                <div class="logout-icon" role="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="person-fill-icon bi bi-person-circle fs-4"></i>
                </div>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown" id="logoutButton">
                    <li>
                        <div class="d-flex justify-content-center align-items-center">
                            <i class="power-icon bi bi-power"></i>&nbsp;<span class="power-logout-text">Logout</span>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="sidebar-container text-center">
                <!-- Mobile Sidebar Toggle Button -->
                <i class="navbar-toggler bi bi-list fs-1" data-bs-toggle="collapse" data-bs-target="#adminNavbarIcons" aria-controls="adminNavbarIcons" aria-expanded="false" aria-label="Toggle navigation"></i>
            </div>
        </div>
    </div>
</nav>

<!-- COLLAPSIBLE SIDEBAR (Mobile Only) -->
<div class="collapse bg-white border-end d-lg-none" id="adminNavbarIcons">
    <div class="p-3">
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item mb-3">
                <a href="dashboard.php?active=dashboard" class="nav-link <?php echo $dashboardClass; ?>">
                <i class="bi bi-house-door-fill me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item mb-3">
                <a href="#" class="nav-link <?php echo $userManagementClass; ?>">
                <i class="bi bi-person-fill-gear"></i>&nbsp;User Management
                </a>
            </li>
            <li class="nav-item mb-3">
                <a href="#" class="nav-link <?php echo $shopValidationClass; ?>">
                <i class="bi bi-folder-fill me-2"></i> Shop Validation
                </a>
            </li>
            <li class="nav-item mb-3">
                <a href="#" class="nav-link <?php echo $issueReportsClass; ?>">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> Issue Reports
                </a>
            </li>
        </ul>
    </div>
</div>