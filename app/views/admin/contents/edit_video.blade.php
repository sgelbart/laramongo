@section ('content')
    <h2>
        Editar video
    </h2>

    @include('admin.contents._edit_tabs')

    <div id='content-form'>
        @include ('admin.contents._video_form')
    </div>

    <div id='content-relations'>
        @include ('admin.contents._relations')
    </div>

@stop
