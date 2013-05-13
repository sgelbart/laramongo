<table class='table table-bordered table-striped' id='characteristics-table'>
    <thead>
        <tr>
            <th>Prioridade</th>
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
                    {{
                        Form::open([   
                            'url' => URL::action('Admin\CategoriesController@update_characteristic', ['id'=>$category->_id, 'charac_name'=>$charac->name]),
                            'method'=>'PUT',
                            'class'=>'form-inline',
                            'style'=>'margin:0px'
                        ])
                    }}
                        {{ Form::text('priority', ($charac->priority) ?: 50, ['class'=>'input-mini']) }}
                    {{ Form::close() }}
                </td>
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
                        {{ Html::linkAction(
                            'Admin\CategoriesController@edit_characteristic', 
                            'Modificar',
                            ['id'=>$category->_id, 'charac_name'=>$charac->name],
                            ['class'=>'btn btn-mini', 'remote'=>'true']
                        ) }}

                        {{ Html::linkAction(
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

{{ Html::linkAction('Admin\CategoriesController@validate_products', "Validar Produtos", ['id'=>$category->_id], ['class'=>'btn btn-inverse']) }}

<hr>

<div class='well'>
    @include('admin.categories._characteristics_form')
</div>
