var row = $('tr#row-{{ $product->_id }}');

@if( $product->deactivated )
    row.find('input[type=checkbox]').attr('checked', false);
@else
    row.find('input[type=checkbox]').attr('checked', true);
@endif
