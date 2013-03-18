@section ('content')
    <h2>
        Relatório de Importação
    </h2>

    <div class="alert alert-info">
        <h4>Produtos importados com sucesso</h4>
        <p>{{ count($success) }}</p>

        <h4>Produtos com erro</h4>
        <p>{{ count($failed) }}</p>
    </div>

    <h3>Falhas</h3>
    @if ( $failed != '' && is_array($failed) )
        @foreach ($failed as $f)
            <div class="alert alert-error">
                <h5>{{ $f->name }}</h5>
                @foreach ($f->errors->all() as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endforeach
    @else
        <div class="alert alert-success">
            Nenhuma falha foi encontrada
        </div>
    @endif


@stop
