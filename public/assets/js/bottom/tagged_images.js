// tagged_images.js

/**
 * For a .tagged-image display the relative .tagged-product-popover
 * of a tag on hover
 *
 */
taggedImages = function(){

    var init = function()
    {
        $('.tagged-product-popover').each(function(){
            var el = $(this).detach();
            $('body').append(el);
        });

        $('span.tagged-image [data-tag-for-popover]').hover(
        function(){ // Mouse in
            var el = $(this);
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
