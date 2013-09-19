// Some general UI pack related JS

$(function () {
    // Custom selects
    $("select").dropkick();
});

$(document).ready(function() {
    // Todo list
    $(".todo li").click(function() {
        $(this).toggleClass("todo-done");
    });

    // Init tooltips
    $("[data-toggle=tooltip]").tooltip("show");

    // Init tags input
    $("#tagsinput").tagsInput();

    // Init jQuery UI slider
    $("#slider").slider({
        min: 1,
        max: 5,
        value: 2,
        orientation: "horizontal",
        range: "min"
    });


    // JS input/textarea placeholder
    $("input, textarea").placeholder();

    // Make pagination demo work
    $(".pagination a").click(function() {
        if (!$(this).parent().hasClass("previous") && !$(this).parent().hasClass("next")) {
            $(this).parent().siblings("li").removeClass("active");
            $(this).parent().addClass("active");
        }
    });

    $(".btn-group a").click(function() {
        $(this).siblings().removeClass("active");
        $(this).addClass("active");
    });

    // Disable link click not scroll top
    $("a[href='#']").click(function() {
        return false
    });

    $('#register').submit(function (e) {
        $('.control-group').removeClass('error');
        e.preventDefault();
        var emailRegex = new RegExp("^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$", 'i');


        var btnText = $('.btn-primary').html();
        $('.btn-primary').text('...');

        //TODO Validate email
        var email = $('#email').val();
        if (!emailRegex.test(email)) {
            var cge = $('.control-group.email');
            var originalEmailText = cge.find('.help-block').text();
            cge.addClass('error').find('.help-block').text($('#email').data('error-text'));
            return false;
        }
        //TODO Validate URL
        var url = $('#url').val();

        var urlRegex = new RegExp('^(https?://)(.+)$', 'i');
        if (!urlRegex.test(url)) {
            var cgu = $('.control-group.url');
            var originalUrlText = cgu.find('.help-block').text();
            cgu.addClass('error').find('.help-block').text($('#url').data('error-text'));
            return false;
        }

        //TODO Try to register and if not successful (not registered and too many searches already) show payment form

        var params = {email: email, url: url};

        var res = $.post('/register', params);

        res.always(function(){
            $('.btn-primary').html(btnText);
        });
        res.done(function (data) {
            $('#register-wrapper').animate({top: '-400px'});

            $('#buy').hide();
            $('#success').show().animate({top: '43px'});
        });
        res.fail(function (xhr) {
            $('#register-inter').fadeOut(100);
            var data = JSON.parse(xhr.responseText);
            for (var i = 0; i < data.errors.length; i++) {
                var error = data.errors[i];

                var group = $('#' + error.element).parent();
                group.addClass('error');
                group.find('.help-block').text(error.message);

            }

        });

    });

    $('#cancel-pay').click(function(e) {
        e.preventDefault();
        $('#register-wrapper').animate({top: '43px'});

        $('#buy').animate({top: '530px'});
    })

});

