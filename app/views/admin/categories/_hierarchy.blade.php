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
                    {{ Html::linkAction('Admin\CategoriesController@edit', $parent->name, ['id'=>$parent->_id]) }}
                </td>
                <td>
                    <a href='{{ URL::action('Admin\CategoriesController@detach', ['id'=>$category->_id, 'parent'=>$parent->_id]) }}' class='btn btn-danger btn-small' data-method='delete'>Remover relação</a>
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

    {{ Form::label('parent', 'Adicionar nova categoria pai', ['class'=>'control-label']) }}

    <div class="input-append">
        {{ Form::select('parent', $categories) }}

        {{ Form::button(
            'Adicionar categoria pai',
            ['type'=>'submit', 'class'=>'btn btn-primary', 'id'=>'submit-attach-category'] ) 
        }}
    </div>

    {{ Form::close() }}
</div>
