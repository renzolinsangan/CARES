<?php
$rootPath = $_SERVER['DOCUMENT_ROOT'] . "/CARES/";
set_include_path($rootPath);
include("Modules/phpInclude.php");

$userId = isset($_POST['userId']) ? $_POST['userId'] : '';
$firstName = isset($_POST['firstName']) ? $_POST['firstName'] : '';
$surName = isset($_POST['surName']) ? $_POST['surName'] : '';
$gender = isset($_POST['gender']) ? $_POST['gender'] : 0;
$email = isset($_POST['email']) ? $_POST['email'] : '';
$phoneNumber = isset($_POST['phoneNumber']) ? $_POST['phoneNumber'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User Management</title>
</head>
<body>
    <form method="POST" id="editForm" enctype="multipart/form-data"></form>
    <input type="hidden" name="userId" id="userId" value="<?php echo $userId; ?>" form="editForm">
    <div class="container p-4">
        <div class="row">
            <div class="col-6 mb-4">
                <label for="firstName">First Name</label>
                <div class="input-group">
                    <input type="text" class="form-control" name="firstName" id="firstName" value="<?php echo $firstName; ?>" form="editForm">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="col-6 mb-4">
                <label for="surName">Last Name</label>
                <div class="input-group">
                    <input type="text" class="form-control" name="surName" id="surName" value="<?php echo $surName; ?>" form="editForm">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="col-6 mb-4">
                <label for="phoneNumber">Phone Number</label>
                <div class="input-group">
                    <input type="num" class="form-control" min="11" name="phoneNumber" id="phoneNumber" value="<?php echo $phoneNumber; ?>" form="editForm">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="col-6 mb-4">
                <label for="gender">Gender</label>
                <div class="input-group">
                    <select name="gender" class="form-select" name="gender" id="gender" form="editForm">
                        <option disabled selected value>Select gender</option>
                        <option value="0" <?php echo ($gender === 'Male') ? 'selected' : ''; ?>>Male</option>
                        <option value="1" <?php echo ($gender === 'Female') ? 'selected' : ''; ?>>Female</option>
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="col-12">
                <label for="email">Email Address</label>
                <div class="input-group">
                    <input type="email" class="form-control" name="email" id="email" value="<?php echo $email; ?>" form="editForm">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="col-12 mt-4">
                <button type="submit" class="edit-btn btn btn-primary" form="editForm">Submit</button>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="../../Includes/js/usermanagement/editUserManagement-js.js"></script>
</body>
</html>