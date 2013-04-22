@section ('content')

    <h2>Categorias</h2>

    <div class="navbar">
        <div class="navbar-inner">
            <div class="btn-group pull-right">
                <a href='{{ URL::action( 'Admin\CategoriesController@create' ) }}' class='btn btn-primary' id='btn-create-new-category'>
                    Nova Categoria
                </a>
            </div>

            <form class="form-search navbar-form pull-left" data-tree-search="true">
                <input 
                    type="text" name="search" value="" placeholder="Pesquisar"
                    class="input-medium search-query" data-submit-on-type='true'
                >
                {{-- <button type="submit" class="btn">Buscar</button> --}}
            </form>
        </div>
    </div>

    <div id='categories-tree'>
        @include ('admin.categories._tree')
    </div>
@stop
