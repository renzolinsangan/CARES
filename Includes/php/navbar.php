<link rel="stylesheet" href="<?php echo $serverPath; ?>Includes/css/style-navbar.css">
<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container-fluid ms-3 me-3 ms-md-5 me-md-5 p-3 p-md-1">
        <div class="header-text-container d-flex">
            <div class="cares-circle-icon me-2"></div>
            <a class="navbar-brand fs-4" href="<?php echo $_SERVER['PHP_SELF']; ?>">CARES</a>
        </div>
        <div class="header-icon-container d-flex align-items-center justify-content-center gap-3">
            <?php include('notificationFunction.php'); ?>
            <div class="notification-icon-container dropdown text-center">
                <div class="notification-icon position-relative" role="button" id="notificationDropdown"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bell-icon bi bi-bell fs-4"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        <?php echo count($notificationDataArray); ?>
                    </span>
                </div>

                <div class="dropdown-menu dropdown-menu-end p-0 shadow" aria-labelledby="notificationDropdown">
                    <div class="notification-list">
                        <!-- <h6 class="dropdown-header fw-bold text-primary">Notifications</h6> -->
                        <?php foreach($notificationDataArray as $data) : ?>
                            <a class="notification-redirection" href="<?php echo $data['url']; ?>">
                                <div class="row notification-card py-2">
                                    <div class="col-3 d-flex align-items-center justify-content-center">
                                        <?php echo $data['icon']; ?>
                                    </div>
                                    <div class="col-9">
                                        <small class="text-muted d-block text-end">
                                            <?php echo $data['dateCreated']; ?>
                                        </small>
                                        <div class="fw-normal text-primary">
                                            <?php echo $data['message']; ?>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                    <div class="notification-footer border-top">
                        <!-- <a class="dropdown-item text-center fw-bold text-primary" href="#">View all</a> -->
                        <h6 class="dropdown-header fw-bold text-primary text-center">Notifications</h6>
                    </div>
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
                <a href="<?php echo $dashboardPath; ?>" class="nav-link <?php echo $dashboardClass; ?>">
                <i class="bi bi-house-door-fill me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item mb-3">
                <a href="<?php echo $userManagementPath; ?>" class="nav-link <?php echo $userManagementClass; ?>">
                <i class="bi bi-person-fill-gear"></i>&nbsp;User Management
                </a>
            </li>
            <li class="nav-item mb-3">
                <a href="<?php echo $shopValidationPath; ?>" class="nav-link <?php echo $shopValidationClass; ?>">
                <i class="bi bi-folder-fill me-2"></i> Shop Validation
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
</div>