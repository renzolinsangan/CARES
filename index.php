<?php
include('Modules/phpInclude.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CARES</title>
    <link rel="stylesheet" href="Includes/css/style.css">
    <?php include_once("Modules/header.php"); ?>
</head>
<body style="background-color: #B3CFE5;">
    <form action="loginValidation.php" method="POST" id="loginValidationForm"></form>
    <div class="container">
        <div class="login-form-row row p-5">
            <div class="login-form-col-left col col-md-6 col-sm-12 p-md-5">
                <div class="login-icon-container text-center mb-3">
                    <img class="login-icon" src="Includes/Images/cares-icon.jpg" alt="cares-icon">
                </div>
                <div class="login-text-container text-center">
                    <h1>CARES</h1>
                    <p>Finding Nearby Repair Shop</p>
                </div>
            </div>
            <div class="login-form-col-right col col-md-6 col-sm-12 p-md-5">
                <div class="login-input-container">
                    <div class="login-input-text">
                        <h1>Administrator</h1>
                        <p class="fs-4">Login Form</p>
                    </div>
                    <div class="input-group mb-4 mt-5">
                        <span class="input-group-text"><i class="bi bi-person-circle"></i></span>
                        <input type="text" class="form-control p-2" name="userName" id="userName" placeholder="Input Username" form="loginValidationForm">
                    </div>
                    <div class="input-group mb-5">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control p-2" name="userPassword" id="userPassword" placeholder="Input Password" form="loginValidationForm">
                        <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                            <i class="bi bi-eye-slash" id="eyeIcon"></i>
                        </span>
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" type="submit" id="loginButton" form="loginValidationForm">LOGIN</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT -->
    <?php include_once("Modules/footer.php"); ?>
    <script type="text/javascript" src="Includes/js/index-js.js"></script>
</body>
</html>