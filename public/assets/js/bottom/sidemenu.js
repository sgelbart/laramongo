// sidemenu.js

/**
 * Controls the behavior of the side menu of the website
 *
 */

$(function(){

    $('.side_menu li').hover(function() {
        var el = $(this);

        var time = 650;
        if( el.parent().hasClass('roots') )
        {
            time = 1;
        }

        var t = setTimeout(function() {
            el.find('ul').first().fadeIn('fast');
        }, time);

        el.data('timeout', t);

    }, function() { 
        $(this).find('ul').first().fadeOut('fast');

        clearTimeout($(this).data('timeout'));
    });
})
