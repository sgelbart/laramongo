@section ('content')
    <h2>
        Importação
    </h2>

    <div class='well'>
        <p>Por favor aguarde enquanto o arquivo é processado...</p>
        <div class="progress progress-striped active">
            <div class="bar" style="width: 100%;"></div>
        </div>
    </div>

    <script>
        setTimeout(function(){
            window.location.reload(1);
        }, 3000);
    </script>


@stop
