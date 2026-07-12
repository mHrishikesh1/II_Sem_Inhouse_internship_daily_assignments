$(document).ready(function() {
    $('.toggle-btn').click(function() {
        $(this).next('.hidden-details').slideToggle();
        if ($(this).text() === "Show Details") {
            $(this).text("Hide Details");
        } else {
            $(this).text("Show Details");
        }
    });
    
});