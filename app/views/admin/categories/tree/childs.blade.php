@section ('content')
    <h1>{{ $category->name }}</h1>

    @foreach( $category->childs() as $child )
        <h2>{{ $child }}</h2>
        <br>
    @endforeach
@stop
