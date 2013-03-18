@section ('content')
    <h2>
        Editar produto
    </h2>

    @include('admin.products._edit_tabs')

    <div id='product-form'>
        @include ('admin.products._form')
    </div>

    <div id='product-characteristcs'> 
        @include ('admin.products._characteristics')
    </div>

@stop
