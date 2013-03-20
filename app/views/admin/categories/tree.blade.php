@section ('content')

    <h2>Categorias</h2>

    <div class='tree' data-tree='true'>
        {{ Category::showTree() }}
    </div>

@stop
