// tagged_images.js

/**
 * For a .tagged-image display the relative .tagged-product-popover
 * of a tag on hover
 *
 */
taggedImages = function(){

    var init = function()
    {
        $('span.tagged-image [data-tag-for-popover]').hover(
        function(){ // Mouse in
            var el = $(this);
            var popover = $('#'+el.attr('data-tag-for-popover'));

            var posX = el.offset().left + el.outerWidth() + 2;
            var posY = el.offset().top + el.outerHeight() + 2;

            popover.addClass('visible');
            popover.css('left', posX).css('top', posY);
        },
        function(){ // Mouse out
            var el = $(this);
            var popover = $('#'+el.attr('data-tag-for-popover'));

            popover.removeClass('visible');
            popover.css('left', -999).css('top', -999);
        })
    }

    init();
}

$(function(){
    taggedImages();
})
