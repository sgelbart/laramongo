$(function(){

    // When scroll page
    $(window).scroll(function() {

        var win = $(window);
        var paginator = $('[data-nextpage]');

        // If paginator is not loading and it's in screen
        if (! paginator.attr('loading') && (win.scrollTop() + win.height() > paginator.offset().top - 200))
        {
            // Get next page
            var page = paginator.attr('data-nextpage');

            // Set loading to true
            paginator.attr('loading','true');

            // Do ajax request
            $.ajax({
                url: window.location+'?page='+page,
            }).done(function( data ) {

                if(data) // If receive some content
                {
                    paginator.before(data); // Show content
                    paginator.attr('data-nextpage', parseInt(page) + 1);
                    paginator.removeAttr('loading');
                }
                else // Else, dispay 'End of results'
                {
                    paginator.text('Fim dos resultados');
                }
            });
        }
    });
});
