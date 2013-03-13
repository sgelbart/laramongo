@section ('content')
    @foreach( $category->parents() as $parent )
        <h2>{{ $parent }}</h2>
        <br>
    @endforeach

    <h1>{{ $category->name }}</h1>

@stop
