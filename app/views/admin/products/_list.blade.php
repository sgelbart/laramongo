<table class='table table-stripped'>
    <thead>
        <tr>
            <th>LM</th>
            <th>Nome</th>
            <th>Chave de Entrada</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $product)
            <tr>
                <td>{{ $product->_id }}</td>
                <td>
                    {{ Html::linkAction( 'Admin\ProductsController@edit', $product->name, ['id'=>$product->_id] ) }}
                </td>
                <td>{{ $product->category }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

@include ('admin._pagination')
