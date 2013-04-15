<table class='table table-stripped'>
    <thead>
        <tr>
            <th>LM</th>
            <th>Nome</th>
            <th>Ativo</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $product)
            <tr id='row-{{ $product->_id }}'>
                <td>
                    {{ $product->_id }}
                    {{ $product->renderPopover() }}
                </td>
                <td>
                    {{ Html::linkAction( 'Admin\ProductsController@edit', $product->name, ['id'=>$product->_id] ) }}
                </td>
                <td>
                    <a
                        href='{{ URL::action('Admin\ProductsController@toggle', ['id'=>$product->_id]) }}'
                        data-method="PUT" data-ajax="true"
                    >
                        {{ Form::checkbox('active','active', !$product->deactivated) }}
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@include ('admin._pagination')
