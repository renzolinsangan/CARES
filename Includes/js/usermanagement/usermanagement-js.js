$(document).ready(function() {
    $('#dataTable').DataTable({
        pageLength: 10,
        lengthChange: false,
        info : false,
        ordering : false,
        scrollX: true,
    });
});

$(document).on('click', '.edit-btn', function(e) {
    let userId = $(this).data('id');
    let firstName = $(this).data('firstname');
    let surName = $(this).data('surname');
    let gender = $(this).data('gender');
    let email = $(this).data('email');
    let phoneNumber = $(this).data('phonenumber');
    
    $('#modal-izi-editUser').iziModal({
        title                 : 'Edit User',
        headerColor           : '#011901',
        fullScreen            : false,
        transitionIn          : 'comingIn',
        transitionOut         : 'comingOut',
        radius                : 0,
        top                   : 150,
        width                 : 500,
        restoreDefaultContent : true,
        closeOnEscape         : true,
        closeButton           : true,
        overlayClose          : false,
        draggable             : false,
        onOpening : function(modal) 
        {
            modal.startLoading();
            $.ajax({
                url  : '../../Pages/User Management/modals/editUserModal', 
                type : 'POST',
                data : {
                    userId : userId,
                    firstName : firstName,
                    surName : surName,
                    gender : gender,
                    email : email,
                    phoneNumber : phoneNumber,
                },
                success : function(data) {
                    $(".izimodal-content-editUser").html(data);
                    modal.stopLoading();
                },
            });
        },
        onClosed: function(modal) {
            $("#modal-izi-editUser").iziModal("destroy");
        }
    });
    $("#modal-izi-editUser").iziModal("open");
});