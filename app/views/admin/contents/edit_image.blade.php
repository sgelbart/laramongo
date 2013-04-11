@section ('content')
    <h2>
        Editar imagem
    </h2>

    @include('admin.contents._edit_tabs')

    <div id='content-form'>
        @include ('admin.contents._image_form')
    </div>

    <div id='content-relations'>
        @include ('admin.contents._relations')
    </div>

@stop
