// tagged_images.js

/**
 * For a .tagged-image display the relative .tagged-product-popover
 * of a tag on hover
 *
 */
taggedImages = function(){

    /**
     * Moves all the .tagged-product-popover to the
     * bottom of the body in order to get an actual
     * absolute position (out of any other relative
     * element)
     * 
     * @return null
     */
    var placePopoverOnBottom = function()
    {
        $('.tagged-product-popover').each(function(){
            var el = $(this).detach();
            $('body').append(el);
        });
    }

    /**
     * Position and display the popover of a tag. The
     * data-tag-for-popover attribute define the ID of the
     * popover element. It's gonna be placed and displayed
     * next to the tag (el) passed.
     * 
     * @param  {DOM Element} el The tag containing the data-tag-for-popover
     * @return {null}
     */
    var displayPopoverOf = function( el )
    {
        var popover = $('#'+el.attr('data-tag-for-popover'));
        var tagXPercent = parseFloat(el[0].style.left);

        if(tagXPercent > 75)
        {
            var posX = el.offset().left - el.outerWidth()*0.8 - popover.width();
        }
        else
        {
            var posX = el.offset().left + el.outerWidth() + 18;
        }
        var posY = el.offset().top - popover.width()/2 + 2;

        popover.addClass('visible');
        popover.css('left', posX).css('top', posY);
    }

    /**
     * Hides the popover of an tag element. The popover
     * that is gonna be hidden is defined in the data-tag
     * -for-popover of the el element.
     * 
     * @param  {DOM Element} el The tag containing the data-tag-for-popover
     * @return {null}
     */
    var hidePopoverOf = function( el )
    {
        var popover = $('#'+el.attr('data-tag-for-popover'));

        popover.removeClass('visible');
        popover.css('left', -999).css('top', -999);
    }

    var init = function()
    {
        placePopoverOnBottom();

        $('span.tagged-image [data-tag-for-popover]').hover(
        function(){ // Mouse in
            var el = $(this);
            
            displayPopoverOf( el );
        },
        function(){ // Mouse out
            var el = $(this);
            
            hidePopoverOf( el );
        })
    }

    init();
}

$(function(){
    taggedImages();
})
