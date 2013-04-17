$('#product-index').html("{{ escape_for_js (
    View::make('search._list_to_mass_relate', view_vars($__data))
        ->render()
) }}");

restfulizer();
