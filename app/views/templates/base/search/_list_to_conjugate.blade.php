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
                        class="btn btn-primary" data-method="PUT"
                        href='{{ URL::action('Admin\ProductsController@addToConjugated', ['conj_id'=>$aditional_id, 'id'=>$product->_id]) }}'
                    >
                        <i class="icon-plus icon-white"></i>
                        <small>Adicionar</small>
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
