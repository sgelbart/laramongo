@section ('content')

    <h1>{{ $content->name }}</h1>

    {{ $content->render(700, 500) }}

@stop
