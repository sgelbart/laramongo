var row = $('#row-{{ $product->_id }}-fix');

@if( $product->isValid() )
    // Replace 'error' class with 'success'
    row.removeClass('error').addClass('success');

    // Replace input with a plain text with the input value
    row.find('input,select').parent().html(function(){
        return '<span class="padding-as-input">'+
            $(this).find('input,select').val()+
            '</span>';
    });

    // Remove the find button
    row.find('button').fadeOut(function(){$(this).remove();});
@else
    // Blink the row to indicate that it still invalid
    row.hide().fadeIn().fadeOut().fadeIn();
@endif
    

