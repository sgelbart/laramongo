// remote.js

/**
 * Causes the click of an anchor to access the url using ajax.
 * Every element containing the data-remote=true attribute will
 * perform an ajax to the given link instead of reloading the
 * whole page. Usefull for javascript actions.
 * 
 */

remote = function(){

    $('a[remote=true]')
    .attr('remote-link', function(){
        if( $(this).attr('remote-link') )
        {
            return $(this).attr('remote-link');
        }
        else
        {
            return $(this).attr('href');
        }
    })
    .removeAttr('href')
    .attr('style','cursor:pointer;')
    .unbind('click')
    .click(function(){

        $.ajax({
            url: $(this).attr('remote-link')
        }).done(function() {
            // Display modal
        });

    });
};

$(function(){
    remote();
});
