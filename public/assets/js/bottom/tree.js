// tree.js

/**
 * Creates a collapsable tree for any element that have the data
 * -tree='true' attribute.
 *
 * Ex:
 *     <ul data-tree="true">...
 *     // Will turn this ul into a collapsable tree
 * 
 */

$(function(){
    
    // Make <li> elements collapsable
    $('[data-tree=true]').find('li').find('a').click(function(){
        var el = $(this).parent();

        if(el.attr('collapsed') == "true")
        {
            el.attr('collapsed',"false")
            el.find('ul').first().fadeIn(100);
        }
        else
        {
            el.attr('collapsed',"true")
            el.find('ul').first().fadeOut(100);
        }
    });

    // Collapse everyone by default
    $('[data-tree=true]').find('li').attr('collapsed',"true")
        .find('ul').css('display','none')
})
