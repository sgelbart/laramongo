$('#product-index').html("{{ escape_for_js (
    View::make('admin.products._list', view_vars($__data))
        ->render()
) }}");

restfulizer();
