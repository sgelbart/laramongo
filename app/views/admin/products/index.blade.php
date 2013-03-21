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

    <form class="form-search" data-ajax="true" data-quicksearch-url='{{ URL::action( 'Admin\ProductsController@index' ) }}'>
        <input 
            type="text" name="search" value="{{ Input::get('search') }}"
            class="input-medium search-query" data-submit-on-type='true'
        >
        <button type="submit" class="btn">Buscar</button>
    </form>

    <div id='product-index'>
        @include ('admin.products._list')
    </div>
@stop
