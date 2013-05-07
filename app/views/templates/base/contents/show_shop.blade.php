@section('content')
    <h1>Loja <b>{{ $content->name }}</b></h1>
    <h2>Endereço: <b>{{ $content->address }}</b></h2>

    <a href="{{ $content->address }}" target="blank"><img alt="Staticmap?size=350x255&amp;center={{ $content->address }}&amp;zoom=15&amp;sensor=false&amp;markers=color:green|{{ $content->address }}," src="https://maps.google.com/maps/api/staticmap?size=700x400&amp;center={{ $content->address }},&amp;zoom=15&amp;sensor=false&amp;markers=color:green|{{ $content->address }},"></a>

    <h2>CEP:</h2> {{ $content->cep }}
    <h2>Fone(s):</h2> {{ $content->phones }}
    <h2>Informações:</h2> {{ $content->description }}

@stop
