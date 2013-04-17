
imageTagging = function(){

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

    var init = function()
    {
        $('.image-tagging span.tagged-image').click(function(event){
            var el = $(this);
            var mousePos = getPos(event, el, false);
            var coord = getPos(event, el, true);

            var popover = el.parent().find('.popover-tagging')

            popover.show()
                .css('left',mousePos.x - popover.width()/2)
                .css('top',mousePos.y - popover.height() - 10);

            popover.find('[name=x]').val(coord.x);
            popover.find('[name=y]').val(coord.y);
        });
    }

    init();
}

$(function(){
    imageTagging();
})
