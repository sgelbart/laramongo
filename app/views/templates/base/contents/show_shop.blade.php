@section('content')
    <h1>Loja <b>{{ $content->name }}</b></h1>
    <h2>Endereço: <b>{{ $content->address }}</b></h2>
    <h2>CEP: <b>{{ $content->cep }}</b></h2>
    <h2>Fone(s): <b>{{ $content->phones }}</b></h2>
    <h2>Informações: <b>{{ $content->description }}</b></h2>

@stop
