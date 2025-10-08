<script src="<?php echo $serverPath; ?>Templates/js/jquery.min.js" charset="UTF-8"></script>
<script src="<?php echo $serverPath;?>Templates/js/iziModal.min.js" charset="UTF-8"></script>
<script src="<?php echo $serverPath; ?>Templates/js/sweetalert.js" charset="UTF-8"></script>
<script src="<?php echo $serverPath; ?>Templates/js/bootstrap.bundle.min.js" charset="UTF-8"></script>
<script src="<?php echo $serverPath; ?>Templates/js/dataTables.min.js" charset="UTF-8"></script>
<script>
$(document).ready(function() {
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
                window.location.href = "<?php echo $serverPath;?>logoutValidation.php";
            }
        });
    });
}); 
</script>