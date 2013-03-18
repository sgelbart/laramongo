@section ('content')

    <h2>Categorias</h2>

    @include('admin.categories._tabs')

    <div class='tree' data-tree='true'>
        {{ Category::showTree() }}
    </div>

@stop
