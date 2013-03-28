@section ('content')
    <h2>
        Produtos
    </h2>

    <div class="navbar">
        <div class="navbar-inner">
            <div class="btn-group pull-right">
                <a href='{{ URL::action( 'Admin\ProductsController@create' ) }}' class='btn btn-primary'>
                    Novo Produto
                </a>

                <a href='{{ URL::action( 'Admin\ProductsController@import' ) }}' class='btn btn-inverse'>
                    Importar Produtos
                </a>
            </div>

            <form class="form-search navbar-form pull-left" data-ajax="true" data-quicksearch-url='{{ URL::action( 'Admin\ProductsController@index' ) }}'>
                <input 
                    type="text" name="search" value="{{ Input::get('search') }}"
                    class="input-medium search-query" data-submit-on-type='true'
                    placeholder="Pesquisar"
                >
                &nbsp;
                <label class="checkbox">
                    <input type="hidden" name="deactivated" value="false">
                    <input
                        type="checkbox" name="deactivated" value="true" 
                        {{ (Input::get('deactivated') == 'true') ? 'checked="checked"' : '' }}
                        data-submit-on-click='true'
                    >
                    Todos
                </label>
                {{-- <button type="submit" class="btn">Buscar</button> --}}
            </form>
        </div>
    </div>

    <div id='product-index'>
        @include ('admin.products._list')
    </div>
@stop
