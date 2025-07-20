$(document).ready(function() {
    let maxHeight = 0;

    $('.dashboard-container .card').each(function () {
        const cardHeight = $(this).outerHeight();
        if (cardHeight > maxHeight) {
            maxHeight = cardHeight;
        }
    });

    $('.dashboard-container .card').height(maxHeight);
});