$(function(){
    'use strict';
    //Dashboard
    $('.toggle-info').click(function(){
        $(this).toggleClass('selected').parent().next('.panel-body').fadeToggle(100);
        if($(this).hasClass('selected')){
            $(this).html('<i class="fa fa-plus fa-lg"></i>');
        }

        else{
            $(this).html('<i class="fa fa-minus fa-lg"></i>');
        }
    });

    //hide placeholder on form focus
    $('[placeholder]').focus(function(){
        $(this).attr('data-text', $(this).attr('placeholder'));
        $(this).attr('placeholder', '');
    })//return placeholder on form blur
    .blur(function(){
        $(this).attr('placeholder', $(this).attr('data-text'));
    });

    //add astrisk on required field
    $('input').each(function(){
        if($(this).attr('required') === 'required'){
            $(this).after('<span class="astrisk">*</span>');
        }
    });

    //
    var passField = $('.password');
    $('.show-pass').hover(function(){
        passField.attr('type', 'text');
    }, function(){
        passField.attr('type', 'password');
    });

    //confirmation message on button
    $('.confirm').click(function(){
        return confirm('Are you sure?');
    });

    //category view option
    $('.cat h3').click(function(){
        $(this).next('.full-view').fadeToggle(200);
    });

    $('.option span').click(function(){
        $(this).addClass('active').siblings('span').removeClass('active');
        if($(this).data('view') === 'full'){
            $('.cat .full-view').fadeIn(200);
        }
        
        else{
            $('.cat .full-view').fadeOut(200);
        }
    });
});