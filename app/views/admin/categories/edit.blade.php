@section ('content')
    <h2>
        Editar categoria
    </h2>

    @include('admin.categories._edit_tabs')

    <div id='category-form'>
        @include ('admin.categories._form')
    </div>

    <div id='category-hierarchy'> 
        <hr>
        <h3>Hierarchy!</h3>
    </div>

@stop
