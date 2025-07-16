$(document).ready(function() {
    let maxHeight = 0;

    $('.dashboard-container .card').each(function () {
        const cardHeight = $(this).outerHeight();
        if (cardHeight > maxHeight) {
            maxHeight = cardHeight;
        }
    });

    $('.dashboard-container .card').height(maxHeight);

    $('#logoutButton').click(function() {
        Swal.fire({
            title : "Confirm Logout",
            text : "Are you sure you want to logout?",
            icon : "warning",
            showCancelButton : true,
            confirmButtonText : "Logout",
            cancelButtonText : "Cancel",
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