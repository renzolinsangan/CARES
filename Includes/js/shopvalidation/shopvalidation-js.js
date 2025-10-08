$(document).ready(function() {
    $('#dataTable').DataTable({
        pageLength: 10,
        lengthChange: false,
        info : false,
        ordering : false,
        scrollX: true,
    });
});

function ajax(shopId, accountId, shopStatus) {
    $.ajax({
        url : '../../Includes/php/shopvalidation/shopvalidationAjax.php',
        type : 'POST',
        dataType : 'json',
        data : {
            shopId : shopId,
            accountId : accountId,
            shopStatus : shopStatus,
        },
        success : function(data) {
            if(data.status === 'success') {
                Swal.fire({
                    title: data.value,
                    text: "Successful, " + data.value + " is reflected.",
                    icon: "success",
                    showConfirmButton: false,
                    timer : 1500,
                    scrollbarPadding: false,
                }).then((result) => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    title: "Error!",
                    text: "There is an error in query, please try again!",
                    icon: "error",
                    showConfirmButton: false,
                    timer: 1500,
                    scrollbarPadding: false,
                });
            }
        }
    })
}

function showDocuments(shopId, shopName, businessDocu, validId, shopStatus, accountId) {
    const screenWidth = window.innerWidth;
    let imageWidth;
    let swalWidth;
    var showConfirmButtonStatus = true;
    var showDenyButtonStatus = true;

    console.log(shopStatus);

    if (screenWidth >= 1600) {
        // Desktop large
        imageWidth = 450;
        imageHeight = 575;
        swalWidth = '55vw';
    } else if (screenWidth >= 1200) {
        // Laptop or mid screens
        imageWidth = 350;
        imageHeight = 350;
        swalWidth = '60vw';
    } else {
        // Smaller screens (optional fallback)
        imageWidth = 300;
        imageHeight = 300;
        swalWidth = '90vw';
    }

    if (shopStatus === 'Verified' || shopStatus === 'Rejected') {
        showConfirmButtonStatus = false;
        showDenyButtonStatus = false;
    }

    Swal.fire({
        title: shopName,
        html: `
            <div style="display: flex; justify-content: center; gap: 20px; text-align: center; flex-wrap: wrap;">
                <div>
                    <div style="margin-bottom: 8px; font-weight: bold;">Business Document</div>
                    <img src="${businessDocu}" alt="Business Document"
                        style="width: ${imageWidth}px; height: ${imageHeight}px; object-fit: fill; border-radius: 8px; box-shadow: 0 4px 12px rgba(10, 25, 49, 0.15);">
                </div>
                <div>
                    <div style="margin-bottom: 8px; font-weight: bold;">Valid ID</div>
                    <img src="${validId}" alt="Valid ID"
                        style="width: ${imageWidth}px; height: ${imageHeight}px; object-fit: fill; border-radius: 8px; box-shadow: 0 4px 12px rgba(10, 25, 49, 0.15);">
                </div>
            </div>
        `,
        width: swalWidth,
        allowOutsideClick: false,
        showCloseButton: true,
        showDenyButton: showDenyButtonStatus,
        showConfirmButton: showConfirmButtonStatus,
        denyButtonText: "Reject",
        confirmButtonText: "Approve",
        denyButtonColor: "#dc3545",
        confirmButtonColor: "#28a745"
    }).then((result) => {
        if (result.isConfirmed) {
            ajax(shopId, accountId, shopStatus=1);
        } else if (result.isDenied) {
            ajax(shopId, accountId, shopStatus=2);
        }
    });
}