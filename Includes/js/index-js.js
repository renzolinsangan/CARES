function showSweetAlert(title, text, icon, confirmValidation, time, barPaddingValidation) {
    Swal.fire({
        title: title,
        text: text,
        icon: icon,
        showConfirmButton: confirmValidation,
        timer : time,
        scrollbarPadding: barPaddingValidation,
    });
}

$(document).ready(function() {
    $('#togglePassword').click(function () {
        const passwordInput = $('#userPassword');
        const icon = $('#eyeIcon');

        const isPassword = passwordInput.attr('type') === 'password';
        passwordInput.attr('type', isPassword ? 'text' : 'password');
        icon.toggleClass('bi-eye bi-eye-slash');
    });
    
    $('#loginValidationForm').submit(function(event) {
        event.preventDefault();

        var userName = $('#userName').val().trim();
        var userPassword = $('#userPassword').val().trim();

        $.ajax({
            url : "loginValidation.php",
            type : "POST",
            data : {
                userName : userName,
                userPassword : userPassword,
            },
            success: function(data) {
                console.log(data);
                switch (data) {
                    case 'loginsuccess' :
                        window.location.href = "dashboard.php?active=dashboard";
                        break;
                    case 'usernameempty':
                        showSweetAlert("Username Required!", "Please enter your username.", "warning", false, 1500, false);
                        break;
                    case 'userpasswordempty':
                        showSweetAlert("Password Required!", "Please enter your password.", "warning", false, 1500, false);
                        break;
                    case 'userpasswordwrong':
                        showSweetAlert("Incorrect Password!", "Please try again, enter your password.", "warning", false, 1500, false);
                        break;
                    case 'bothwrong':
                        showSweetAlert("Incorrect Username or Password!", "Please try again, enter your username and password.", "warning", false, 1500, false);
                        break;
                    default:
                        console.log("Unhandled response:", data);
                        break;
                }
            }
        });
    });
});