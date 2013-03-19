<table class='table table-bordered table-striped' id='characteristics-table'>
    <thead>
        <tr>
            <th>Caracteristicas</th>
            <th>Tipo</th>
            <th>Layout</th>
            <th>Valores</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach ( $category->characteristics() as $charac )
            <tr>
                <td>
                    {{ $charac->name }}
                </td>
                <td>
                    {{ $charac->getTypeStr() }}
                </td>
                <td>
                    {{ $charac->displayLayout() }}
                </td>
                <td>
                    {{ $charac->getValuesStr() }}
                </td>
                <td>
                    <div class='btn-group'>
                        <a class='btn btn-mini'>Modificar</a>
                        {{ HTML::action(
                            'Admin\CategoriesController@destroy_characteristic', 
                            'Excluir',
                            ['id'=>$category->_id, 'charac_name'=>$charac->name],
                            ['class'=>'btn btn-danger btn-mini', 'data-method'=>'DELETE']
                        ) }}
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{ HTML::action('Admin\CategoriesController@validate_products', "Validar Produtos", ['id'=>$category->_id], ['class'=>'btn btn-inverse']) }}

<hr>

<div class='well'>
    {{
        Form::open([   
            'url' => URL::action('Admin\CategoriesController@add_characteristic', ['id'=>$category->_id]),
            'method'=>'POST'
        ])
    }}

    {{ Form::label('type', 'Nova caracteristica', ['class'=>'control-label']) }}

    {{ Form::select('type', ['int'=>'Numero','float'=>'Numero decimal','option'=>'Opções','string'=>'Livre'], null, ['class'=>'input-block-level']) }}

    {{ Form::text('name', '', ['placeholder'=>'Nome da caracteristica', 'class'=>'input-block-level', 'id'=>'characteristic-name']) }}

    {{ Form::label('layout-pre', 'Layout', ['class'=>'control-label']) }}

    {{ Form::text('layout-pre', '') }}
    <span class="muted">&ltvalor&gt</span>
    {{ Form::text('layout-pos', '') }}

    {{ Form::label('values', 'Possíveis valores', ['class'=>'control-label']) }}

    {{ Form::text('values', '', ['placeholder'=>'Madeira, Metal, Plastico, Vidro', 'class'=>'input-block-level']) }}

    <div>
        {{ Form::button(
            'Adicionar caracteristica',
            ['type'=>'submit', 'class'=>'btn btn-primary', 'id'=>'submit-create-characteristic'] )
        }}
    </div>

    {{ Form::close() }}
</div>
