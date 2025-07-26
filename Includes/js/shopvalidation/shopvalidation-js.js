$(document).ready(function() {
    $('#dataTable').DataTable({
        pageLength: 10,
        lengthChange: false,
        info : false,
        ordering : false,
        scrollX: true,
    });
});

function showDocuments(shopName) {
    const screenWidth = window.innerWidth;
    let imageWidth;
    let swalWidth;

    if (screenWidth >= 1600) {
        // Desktop large
        imageWidth = 450;
        swalWidth = '55vw';
    } else if (screenWidth >= 1200) {
        // Laptop or mid screens
        imageWidth = 350;
        swalWidth = '60vw';
    } else {
        // Smaller screens (optional fallback)
        imageWidth = 300;
        swalWidth = '90vw';
    }

    Swal.fire({
        title: shopName,
        html: `
            <div style="display: flex; justify-content: center; gap: 20px; text-align: center; flex-wrap: wrap;">
                <div>
                    <div style="margin-bottom: 8px; font-weight: bold;">Business Document</div>
                    <img src="https://unsplash.it/200/200?random=1" alt="Business Document"
                        style="width: ${imageWidth}px; height: auto; object-fit: contain; border-radius: 8px;">
                </div>
                <div>
                    <div style="margin-bottom: 8px; font-weight: bold;">Valid ID</div>
                    <img src="https://unsplash.it/200/200?random=2" alt="Valid ID"
                        style="width: ${imageWidth}px; height: auto; object-fit: contain; border-radius: 8px;">
                </div>
            </div>
        `,
        width: swalWidth,
        allowOutsideClick: false,
        showDenyButton: true,
        showCancelButton: true,
        cancelButtonText: "Approve",
        denyButtonText: "Cancel",
        confirmButtonText: "Reject",
        cancelButtonColor: "#28a745",
        denyButtonColor: "#6c757d",
        confirmButtonColor: "#dc3545"
    });
}
