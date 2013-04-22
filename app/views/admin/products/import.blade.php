@section ('content')
    <h2>
        Importar produtos{{ ($conjugated) ? ' conjugados' : '' }}
    </h2>

    {{ Form::open([
        'url' => URL::action('Admin\ProductsController@doImport'),
        'method' => 'POST',
        'files' => true
    ]) }}

        {{ Form::hidden('conjugated', $conjugated) }}

        {{ Form::label('category', 'Chave de entrada') }}
        {{ Form::select('category', $leafs, null, ['data-chosen'=>'true']) }}

        {{ Form::label('csv_file', 'Escolha um arquivo csv para importar') }}
        <div class='well'>
            {{ Form::file('csv_file') }}
        </div>

        {{ Form::label('zip_file', 'Escolha um arquivo zip contendo as imagens') }}
        <div class='well'>
            {{ Form::file('zip_file') }}
        </div>

        @if ( Session::get('error') )
            <div class="alert alert-error">
                @if ( is_array(Session::get('error')) )
                    {{ Session::get('error')[0] }}
                @else
                    {{ Session::get('error') }}
                @endif
            </div>
        @endif

        <div class='form-actions'>
            {{ Form::button('Importar', ['type'=>'submit', 'class'=>'btn btn-primary', 'id'=>'submit-import-form'] ) }}
        </div>

    {{ Form::close() }}

@stop
