@section ('content')
    <h2>
        Produtos
    </h2>

    <div class="btn-group">
        <a href='{{ URL::action( 'Admin\ProductsController@create' ) }}' class='btn btn-primary'>
            Novo Produto
        </a>

        <a href='{{ URL::action( 'Admin\ProductsController@import' ) }}' class='btn btn-inverse'>
            Importar CSV
        </a>
    </div>

    <table class='table table-stripped'>
        <thead>
            <tr>
                <th>Id</th>
                <th>Nome</th>
                <th>Marca</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>
                        {{ HTML::action( 'ProductsController@show', $product->name, ['id'=>$product->id] ) }}
                    </td>
                    <td>{{ $product->marca }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @include ('admin._pagination')
@stop
