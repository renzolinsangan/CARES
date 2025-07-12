<style>
    .navbar {
        background-color: #ffffff;
        box-shadow: 0px 5px 21px -5px #CDD1E1;
    }

    .cares-circle-icon {
        width: 50px;
        height: auto;
        background-image: url("Includes/php/cares-icon.jpg"); /* Blue background */
        background-size: cover;
        background-position: center;
        color: #F6FAFD;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 24px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .navbar-toggler {
        border: none !important;
        color: #1A3D63 !important;
    }

    .navbar-toggler:focus {
        outline: none !important;
        box-shadow: none !important;
    }

    .navbar-brand {
        color: #1A3D63 !important;
        font-weight: bolder;
    }

    .icon-label, .bell-icon, .person-fill-icon { 
        color: #1A3D63 !important;
        font-weight: bolder;
    }

    .power-logout-text {
        color: #1A3D63;
        font-weight: 500 !important;
    }

    .header-icon-container i {
    display: block;
    font-size: 1.2rem;
    color: #333;
    }

    .icon-label {
    font-size: 0.70rem;
    margin-top: 2px;
    color: #555;
    }

    .notification-icon-container, .logout-icon-container {
        cursor: pointer;
    }
</style>

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
                <!-- <div class="icon-label">Notification</div> -->
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
        </div>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbarIcons" aria-controls="adminNavbarIcons" aria-expanded="false" aria-label="Toggle navigation">
            <i class="bi bi-list fs-1"></i>
        </button>
    </div>
</nav>