
imageTagging = function(){
    $('.image-tagging span.tagged-image').click(function(e){

        var pos = $(this).find('img').offset();

        mouse = {}

        mouse.x = e.pageX - pos.left;
        mouse.y = e.pageY - pos.top;

        alert(mouse.x+' - '+mouse.y);
    });
}

$(function(){
    imageTagging();
})
