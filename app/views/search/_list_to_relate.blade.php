<table class='table table-stripped'>
    <tbody>
        @foreach ($products as $product)
            <tr>
                <td>
                    {{ $product->_id }}
                </td>
                <td>
                    {{ $product->name }}
                </td>
                <td>
                    <a
                        class="btn btn-primary btn-relate-product" data-method="POST"
                        href='{{ URL::action('Admin\ContentsController@addProduct', ['id'=>$aditional_id, 'product_id'=>$product->_id]) }}'
                    >
                        <i class="icon-magnet icon-white"></i>
                        <small>{{ l('content.relate_button') }}</small>
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
