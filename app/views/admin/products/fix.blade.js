var row = $('#row-{{ $product->_id }}-fix');

@if( $product->isValid() )
    // Replace 'error' class with 'success'
    row.removeClass('error').addClass('success');

    // Replace input with a plain text with the input value
    row.find('input,select').removeClass('error');

    // Change the button
    row.find('button').removeClass('btn-primary').html('<i class="icon-wrench"></i>')

    // Focus in other input
    row.next().find('input[type=text],select').first().focus();

    // Blink the row to indicate that it was saved
    row.hide().fadeIn('fast');
@else
    {{ '/*' }}
    <?php print_r($product->errors->all()); ?>
    {{ "\n" }}
    {{ $product }}
    {{ '*/' }}

    // Replace 'success' class with 'error'
    row.removeClass('success').addClass('error');
    row.find('input,select').addClass('error');

    // Change the button
    row.find('button').addClass('btn-primary').html('<i class="icon-ok icon-white"></i>')

    // Blink the row to indicate that it still invalid
    row.hide().fadeIn('fast').fadeOut('fast').fadeIn('fast');
@endif
    

