function isInvalid(id, value, message) {
    if (!value) {
            $('#' + id)
            .addClass('is-invalid')
            .siblings('.invalid-feedback')
            .text(message);
    } else {
        $('#' + id).removeClass('is-invalid');
    }   
}

$(document).on('submit', '#editForm', function(e) {
    e.preventDefault();

    isInvalid('firstName', $('#firstName').val(), 'Please enter your first name');
    isInvalid('surName', $('#surName').val(), 'Please enter your last name');
    isInvalid('phoneNumber', $('#phoneNumber').val(), 'Please enter your phone number');
    isInvalid('email', $('#email').val(), 'Please enter your email');
    isInvalid('gender', $('#gender').val(), 'Please enter your gender');

    var formData = new FormData(this);

    $.ajax({
        url : "../../Includes/php/usermanagement/usermanagementAjax.php",
        type : "POST",
        data : formData,
        processData : false,
        contentType : false,
        dataType : "json",
        success : function(response) {
            console.log(response);
            if(response.status === 'editusersuccess') {
                Swal.fire({
                    title: 'User Data Updated!',
                    text: 'The user’s information has been successfully edited.',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 1500,
                }).then((result) => {
                    location.reload();
                });
            }
        }
    });
});