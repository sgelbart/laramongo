@section('content')
    <h2>Sinônimos</h2>

    <div class="navbar">
        <div class="navbar-inner">
            <div class="btn-group pull-right">
                <a href="{{ URL::action('Admin\SynonymsController@create') }}" class="btn btn-primary">
                    Novo sinônimo
                </a>
            </div>
            <form action="" class="form-search navbar-form pull-left">
                <input type="text" name="search" placeholder="Pesquisar" class="input-medium search-query"/>
            </form>
        </div>
    </div>

        @if(count($synonyms) > 0)
            <table class="table">
                <tr>
                    <th>Palavra</th>
                    <th>Sinônimos</th>
                    <th>Ações</th>
                </tr>

                @foreach($synonyms as $sym)
                    <tr>
                        <td>
                            <a href="{{ URL::action(
                                        'Admin\SynonymsController@edit', ['id' => $sym->_id ]
                                    )}}"
                            >
                               {{ $sym->word }}
                            </a>
                        </td>
                        <td>
                            @foreach($sym->related_word as $word)
                                <span class="label label-info">{{ $word }}</span>
                            @endforeach
                        </td>

                        <td>
                            <a href="{{ URL::action(
                                        'Admin\SynonymsController@destroy', ['id' => $sym->_id ]
                                    )}}"
                            class='btn btn-primary' data-method="delete">
                                <i class="icon icon-trash icon-white"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </table>
        @else
            Não existe nenhum sinônimo cadastrado.
        @endif
@stop
