@section ('content')
    <h2>
        Categorias
    </h2>

    <p>
        <a href='{{ URL::action( 'Admin\CategoriesController@create' ) }}' class='btn btn-primary' id='btn-create-new-category'>
            Nova Categoria
        </a>
    </p>

    <table class='table table-stripped' id='categories-table'>
        <thead>
            <tr>
                <th>Categoria</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
                <tr>
                    <td>
                        {{ HTML::action( 'Admin\CategoriesController@edit', $category->name, ['id'=>$category->_id] ) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
@stop
