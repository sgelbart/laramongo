// popover.js

/**
 * For every element containing the 'data-with-popover' attribute
 * on hover, position and display the .popover div within it
 *
 * Ex:
 *   <spam data-with-popover>
 *       <h1>Hover me!</h1>
 *       <div class='popover'>
 *           <p>This is the popover</p>
 *       </div>
 *   </span>
 *
 * PS: It's a good idea to use it with the "ToPopover" trait ;)
 *
 */

var Popover = function() {
    $('[data-with-popover]').hover(function() {
        var el = $(this);
        var popover = el.find('.popover');

        var x = el.offset().left;
        var y = el.offset().top;

        popover
            .css('left', x - popover.width()/2 + el.width()/2)
            .css('top', y - popover.height())
            .show();

    }, function() {
        var el = $(this);

        el.find('.popover').hide();
    });
};

$(function(){
    Popover();
});

