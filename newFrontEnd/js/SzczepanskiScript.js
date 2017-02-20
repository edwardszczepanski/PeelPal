
$("#js-rotating").Morphist({
    // The entrance animation type (In).
    animateIn: "flipInX",
    // The exit animation type (Out). Refer to Animate.css for a list of available animations.
    animateOut: "flipOutX",
    // The delay between the changing of each object in milliseconds.
    speed: 3200,
    complete: function() {
        // Called after the entrance animation is executed.
    }
});

$(function() {
    if (window.location.href.indexOf('#quackcon') != -1) {
        $('#quackcon').modal('show');
    }
    if (window.location.href.indexOf('#quackhack') != -1) {
        $('#quackhack').modal('show');
    }
    if (window.location.href.indexOf('#enli') != -1) {
        $('#enli').modal('show');
    }
    if (window.location.href.indexOf('#d3popvis') != -1) {
        $('#d3popvis').modal('show');
    }
    if (window.location.href.indexOf('#ogpc') != -1) {
        $('#ogpc').modal('show');
    }
    if (window.location.href.indexOf('#codeday') != -1) {
        $('#codeday').modal('show');
    }
    if (window.location.href.indexOf('#johnsons') != -1) {
        $('#johnsons').modal('show');
    }
    if (window.location.href.indexOf('#perfectplaceforme') != -1) {
        $('#perfectplaceforme').modal('show');
    }
    if (window.location.href.indexOf('#azuqua') != -1) {
        $('#azuqua').modal('show');
    }

    $(".navbar a, footer a[href='#myPage']").on('click', function(event) {
        event.preventDefault();

        var hash = this.hash;

        $('html, body').animate({
            scrollTop: $(hash).offset().top
        }, 900, function() {
            window.location.hash = hash;
        });
    });

});
