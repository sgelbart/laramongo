// image_tagging.js

/**
 * For a .tagged-image inside a element containing .image-tagging class
 * on click position and display the form (within a popover) in order to
 * tag products to the image.
 *
 */

imageTagging = function(){

    /**
     * Gets the x and y position of the mouse relative to an element
     * 
     * @param  {jQueryEvent}  event   In order to get the pageX an event should be used
     * @param  {DOM Element} element  The dom element that the position are gonna be picked
     * @param  {boolean} percentage   If true, the position will be returned as percentage
     * @return {json}                 Containing x and y
     */
    var getPos = function( event, element, percentage )
    {
        var pos = element.find('img').offset();
        var mouse = {};

        mouse.x = event.pageX - pos.left;
        mouse.y = event.pageY - pos.top;

        if( percentage )
        {
            mouse.x = ((mouse.x / element.width()  ) * 100).toFixed(2);
            mouse.y = ((mouse.y / element.height() ) * 100).toFixed(2);
        }

        return mouse;
    }

    /**
     * Set the position and display the Popover for product tagging
     *
     * @param  {DOM Element} element       The taggable image element
     * @param  {json} positionPixel        Containing x and y in PIXELS
     * @param  {json} positionPercentage   Containing x and y in Percentage
     * @param  {int}  tag_id               The id of an existing tag to be edited or removed
     * @return {null}
     */
    var preparePopover = function( element, positionPixel, positionPercentage, tag_id )
    {
        var popover = element.parent().find('.popover-tagging');

        popover.show()
            .css('left',positionPixel.x - popover.width()/2)
            .css('top',positionPixel.y - popover.height() - 10);

        popover.find('[name=x]').val(positionPercentage.x);
        popover.find('[name=y]').val(positionPercentage.y);
    }

    /**
     * Hide a popover
     *
     * @param  {DOM Element} element       Popover element
     * @return {null}
     */
    var closePopover = function( element )
    {
        element.fadeOut();
    }

    var init = function()
    {
        // Clicking to add a new tag
        $('.image-tagging span.tagged-image').click(function(event){
            var el = $(this);
            var mousePos = getPos(event, el, false);
            var coord = getPos(event, el, true);

            // Prepare the Popover form
            preparePopover( el, mousePos, coord);
        });

        // Close the tagging popover
        $('.image-tagging a[data-close-popover]').click(function(){
            closePopover($(this).closest('.popover-tagging'));
        })

        // To remove an existing tag
        $('.image-tagging .tagged-image a').removeAttr('href').click(function(){
            alert('remove');
        })
    }

    init();
}

$(function(){
    imageTagging();
})
