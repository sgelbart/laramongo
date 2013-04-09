@if ( $contents->count() )

    <table class='table table-stripped'>
        <thead>
            <tr>
                <th>Data</th>
                <th>Nome</th>
                <th>Tipo</th>
                <th>Visível</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($contents as $content)
                <tr id='row-{{ $content->_id }}'>
                    <td>{{ $content->_id }}</td>
                    <td>
                        {{ Html::linkAction( 'Admin\ProductsController@edit', $content->name, ['id'=>$content->_id] ) }}
                    </td>
                    <td>
                        <a
                            href='{{ URL::action('Admin\ProductsController@toggle', ['id'=>$content->_id]) }}'
                            data-method="PUT" data-ajax="true"
                        >
                            {{ Form::checkbox('active','active', !$content->deactivated) }}
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @include ('admin._pagination')

@else
    <div class='well'>
        <i class='icon-info-sign'></i>
        {{ l('navigation.no_resource_was_found', ['resource'=>'conteúdo']) }}
    </div>
@endif
