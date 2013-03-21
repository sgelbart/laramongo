// tree.js

/**
 * Creates a collapsable tree for any element that have the data
 * -tree='true' attribute.
 *
 * Ex:
 *     <ul data-tree="true">...
 *     // Will turn this ul into a collapsable tree
 * 
 * If a "data-tree-session-url" attribute is provided  that url
 * will be called for each node oppening/closing in order to save
 * the tree state in session.
 *
 */

$(function(){

    function saveStateInSession( el )
    {
        var tree = el.closest('div[data-tree=true]');

        if( tree.attr('data-tree-session-url') )
        {
            // Do ajax request
            $.ajax({
                url: tree.attr('data-tree-session-url'),
                type: 'POST',
                data: {'id': el.attr('id'), 'state': el.attr('collapsed')}
            });
        }
    }

    function processChildOf( el )
    {
        var child = el.find('li');

        if( child.length > 0 )
        {
            if(child.attr('collapsed') == 'true')
            {
                child.find('ul').css('display','none');
            }
            else
            {
                child.find('ul').css('display','block');
            }

            processChildOf( child );
        }
    }
    
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

        processChildOf(el);
        saveStateInSession(el);
    });

    // Collapse everyone by default
    $('[data-tree=true]').find('li').each(function(){
        if(! $(this).attr('collapsed'))
        {
            $(this).attr('collapsed',"true");
        }

        if($(this).attr('collapsed') == 'true')
        {
            $(this).find('ul').css('display','none');    
        }
    })
})
