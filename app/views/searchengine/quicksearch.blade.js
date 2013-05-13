$('#quicksearch').hide();

@if( $products || $categories || $contents )

    $('#quicksearch').html("{{ escape_for_js (
        View::make('searchengine._results', view_vars($__data))
            ->render()
    ) }}");

    $('#quicksearch').show();

    restfulizer();

@endif
