$(document).ready(function() {
    $('#dataTable').DataTable({
        pageLength: 10,
        lengthChange: false,
        info : false,
        ordering : false,
        scrollX: true,
    });
});