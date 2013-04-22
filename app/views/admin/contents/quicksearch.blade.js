$('#content-index').html("{{ escape_for_js (
    View::make('admin.contents._list', view_vars($__data))
        ->render()
) }}");

restfulizer();
