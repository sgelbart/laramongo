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
                    <td>{{ $warn->lastSearch }}</td>
                    <td></td>
                </tr>
            @endforeach
        </table>
    @else
        <p>Não há nenhum registro.</p>
    @endif
@stop
