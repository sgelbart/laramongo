<table class='table table-stripped'>
    <thead>
        <tr>
            <th>LM</th>
            <th>Nome</th>
            <th>Ativo</th>
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
