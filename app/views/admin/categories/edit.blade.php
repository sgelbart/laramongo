@section ('content')
    <h2>
        <small>Editar categoria</small> {{ $category->name }}
    </h2>

    @include('admin.categories._edit_tabs')

    <div id='category-form'>
        @include ('admin.categories._form')
    </div>

    <div id='category-characteristcs'> 
        @include ('admin.categories._characteristics')
    </div>

    <div id='category-hierarchy'> 
        @include ('admin.categories._hierarchy')
    </div>

@stop
