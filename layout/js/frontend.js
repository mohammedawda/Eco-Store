$(function(){
    'use strict';
   
    //select between login and signup forms
    $('.login-page h1 span').click(function(){
        $(this).addClass('selected').siblings().removeClass('selected');
        if($(this).hasClass('x')){
            $(this).addClass('select').removeClass('selected');
        } 
        
        else{
            $('.login-page span').removeClass('select');
        }
        $('.login-page form').hide();
        $('.' + $(this).data('class')).fadeIn(100);
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

    //confirmation message on button
    $('.confirm').click(function(){
        return confirm('Are you sure?');
    });

});