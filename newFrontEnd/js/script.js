

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
