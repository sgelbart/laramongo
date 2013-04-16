<table class='table table-bordered table-striped' id='hierarchy-table'>
    <thead>
        <tr>
            <th>Categorias Pai</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach ( $category->parents() as $parent )
            <tr>
                <td>
                    {{ $parent->renderPopover(
                        Html::linkAction('Admin\CategoriesController@edit', $parent->name, ['id'=>$parent->_id])
                        );
                    }}
                </td>

                <td>
                    {{
                       Html::linkAction(
                        'Admin\CategoriesController@detach',
                           'Remover relação',
                           ['id'=>$category->_id, 'parent'=>$parent->_id],
                           ['class'=>'btn btn-danger btn-small', 'data-method'=>'delete']
                       )
                    }}
                </td>
            <tr>
        @endforeach
    </tbody>
</table>

<div class='well'>
    {{
        Form::open([
            'url' => URL::action('Admin\CategoriesController@attach', ['id'=>$category->_id]),
            'method'=>'POST'
        ])
    }}

    {{ Form::select('parent', $categories, null, ['data-chosen'=>'true']) }}

    {{ Form::button(
        'Adicionar categoria pai',
        ['type'=>'submit', 'class'=>'btn btn-primary', 'id'=>'submit-attach-category'] )
    }}

    {{ Form::close() }}
</div>
