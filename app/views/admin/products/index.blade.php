@section ('content')
    <h2>
        Produtos
    </h2>

    <div class="btn-group">
        <a href='{{ URL::action( 'Admin\ProductsController@create' ) }}' class='btn btn-primary'>
            Novo Produto
        </a>

        <a href='{{ URL::action( 'Admin\ProductsController@import' ) }}' class='btn btn-inverse'>
            Importar Produtos
        </a>
    </div>

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
                        {{ HTML::action( 'Admin\ProductsController@edit', $product->name, ['id'=>$product->_id] ) }}
                    </td>
                    <td>{{ $product->category }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @include ('admin._pagination')
@stop
