$('#product-index').html("{{ escape_for_js (
    View::make('search._list_to_conjugate', view_vars($__data))
        ->render()
) }}");

restfulizer();
