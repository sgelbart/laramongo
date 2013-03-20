@section ('content')

    <h2>Categorias</h2>

    <p>
        <a href='{{ URL::action( 'Admin\CategoriesController@create' ) }}' class='btn btn-primary' id='btn-create-new-category'>
            Nova Categoria
        </a>
    </p>

    <hr>

    <div class='tree' data-tree='true'>
        {{ Category::showTree() }}
    </div>

@stop
