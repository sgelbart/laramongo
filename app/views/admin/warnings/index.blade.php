@section('content')
    <h2>Palavras não encontradas</h2>

    @if(count($warnings) > 0)
        <table class="table">
            <tr>
                <th>Palavras</th>
                <th>última ocorrência</th>
                <th>Total de ocorrências</th>
            </tr>

            @foreach($warnings as $warn)
                <tr>
                    <td>{{ $warn->keyword }}</td>
                    <td>{{ date('d-m-Y h:i:s', $warn->lastSearch->sec) }}</td>
                    <td>{{ $warn->occurrences }}</td>
                </tr>
            @endforeach
        </table>
    @else
        <p>Não há nenhum registro.</p>
    @endif
@stop
