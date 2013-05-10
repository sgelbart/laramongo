@section('content')
    <h2>sinônimo: {{ $synonymous->_id }}</h2>

    @include('admin.synonyms._form')
@stop
