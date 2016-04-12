var submitted = 0;

$(document).mouseup(function (e){
    var container = $('#mobile-user-menu');
    if (!container.is(e.target) && container.has(e.target).length === 0 && (e.target.id != 'user-menu-btn' && $(window).width() > 768)) {
        container.hide('fast');
    }
});

$(document).ready(function(){
    $('.alert-detail').hide();
    $('.alert .close').on('click', function(e) {
      e.preventDefault();
      $('.alert-detail').toggle();
    });

    $('#user-menu-btn').on('click touchend', function(e){
        e.preventDefault();
        $('#mobile-user-menu').toggle('fast');
    });

    $('#mobile-user-menu-btn').on('click touchend', function(e){
        e.preventDefault();

        if($('#mobile-user-menu').is(':visible')) {
            $('#mobile-user-menu').slideUp();
        } else {
            $('#mobile-user-menu').slideDown();
        }
    });

    $('#mobile-main-menu-btn').on('click touchend', function(e){
        e.preventDefault();

        if($('#mobile-main-menu').is(':visible')) {
            $('#mobile-main-menu').slideUp();
        } else {
            $('#mobile-main-menu').slideDown();
        }
    });

    $('#remember-btn').on('click touchend', function(e){
        e.preventDefault();
        if($(this).hasClass('btn-success')) {
            $(this).addClass('btn-danger');
            $(this).removeClass('btn-success');
            $(this).removeClass('active');
            $(this).html('<i class="fa fa-fw fa-times"></i>');
            $('#remember').val(0);
        } else {
            $(this).addClass('btn-success');
            $(this).addClass('active');
            $(this).removeClass('btn-danger');
            $(this).html('<i class="fa fa-fw fa-check"></i>');
            $('#remember').val(1);
        }
    });

    $('.login-form-submit').on('click touchend', function(e) {
        e.preventDefault();
        $('.login-form-submit').addClass('disabled');

        $.ajax({
            url: BASE_URL + 'login',
            type: 'post',
            data: $('#login-form').serialize(),
            dataType: 'json',
            success: function(resp) {
                if(resp.status != 'ok') {
                    $('#password').val('');
                    $('#login-error').html('<div class="alert alert-danger"><p>' + resp.message + '</p></div>');
                    $('.login-form-submit').removeClass('disabled');
                } else {
                    window.location.reload();
                }
            },
            error: function(data) {
                var error = $.parseJSON(data.responseText);
                $('#login-error').html('<div class="alert alert-danger"><p>' + error.message + '</p></div>');
            }
        });
    });

    $('#logout-link').on('click touchend', function(e){
        e.preventDefault();

        $.ajax({
            url: BASE_URL + 'logout',
            type: 'get',
            success: function(resp) {
                window.location.reload();
            }
        });
    });

    $('.message').stop().animate({top:'0'}, 300);
    var time = setTimeout(function(){
        $('.message').stop().animate({top:'-100px'}, 300);
    }, 2500);

    $(window).scroll(function() {
        if($(this).scrollTop() > 100){
            $('#goto-top').stop().animate({
                bottom: '35px'
                }, 300);
        } else {
            $('#goto-top').stop().animate({
               bottom: '-100px'
            }, 300);
        }
    });

    $('#goto-top').click(function() {
        $('html, body').stop().animate({
           scrollTop: 0
        }, 300, function() {
           $('#goto-top').stop().animate({
               bottom: '-100px'
            }, 300);
        });
    });

    $('#location-add-submit').on('click touchstart', function (e) {
        e.preventDefault();
        $.ajax({
            url: BASE_URL + 'location/add',
            type: 'post',
            data: $('#add-location-form').serialize(),
            dataType: 'json',
            success: function (resp) {
                if (resp.status != 'ok') {
                    $('#location-error').html('<div class="alert alert-danger"><p>' + resp.message + '</p></div>');
                } else {
                    $('#location_id').append('<option value="' + resp.value + '">' + resp.name + '</option>');
                    $('#location_id').select2('val', resp.value);
                    $('#addLocation').modal('hide');
                }
            },
            error: function(data) {
                var json = $.parseJSON(data.responseText);
                $('#location-error').html('<div class="alert alert-danger"><p>' + json.message + '</p></div>');
            }
        });
    });
});

function submitLoginForm(event)
{
    if(event.keyCode == 13 && submitted == 0) {
        $('.login-form-submit').click();
    }
}

$('#login').on('shown.bs.modal', function (e) {
    $('#login-email').focus();
});

function handleSelect2(id, route, max)
{
    var multiple = true;
    if(isNaN(max) || max == 1) {
        max = 1;
        multiple = false;
    }

    $(id).select2({
        multiple: multiple,
        minimumInputLength: 1,
        maximumSelectionSize: max,
        placeholder: 'Start typing for selection',
        quietMillis: 250,
        width: '100%',
        ajax: {
            url: route,
            data: function(term, page) {
                return {
                    term: term,
                    page: page
                };
            },
            results: function(data, page) {
                //console.log(data);
                return { results: data };
            }
        },
        initSelection: function(element, callback) {
            //console.log($(element).val());
            if($(element).val() != '') {
                $.ajax({
                    url: route,
                    data: { ids: $(element).val() },
                    dataType: 'json',
                    success: function(data) {
                        if(multiple) {
                            callback(data);
                        } else {
                            callback(data[0]);
                        }
                    }
                });
            }
        }
    });
}
