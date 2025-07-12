$(document).ready(function() {
    $('#logoutButton').click(function() {
        Swal.fire({
            title : "Logout Account",
            text : "Do you wish to logout from the system?",
            icon : "warning",
            showCancelButton : true,
            confirmButtonText : "Yes",
            cancelButtonText : "No",
            confirmButtonColor : "#3085d6",
            cancelButtonColor : "#d33",
            allowOutsideClick : false,
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "logoutValidation.php";
            }
        });
    })
});