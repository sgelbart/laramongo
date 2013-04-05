@section ('content')
    <h2>
        Processando arquivo:
    </h2>

    <div class='well'>
        {{ $id }}
    </div>

    <p>Aguarde...</p>

    <script>
        setTimeout(function(){
            window.location.reload(1);
        }, 3000);
    </script>


@stop
