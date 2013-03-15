<table class='table table-bordered table-striped' id='hierarchy-table'>
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
        <tr>
            <td>
                Capacidade
            </td>
            <td>
                Numero
            </td>
            <td>
                <span class="muted">&ltvalor&gt</span>
                btus/h
            </td>
            <td>
                Qualquer
            </td>
            <td>
                <div class='btn-group'>
                    <a class='btn btn-mini'>Modificar</a>
                    <a class='btn btn-danger btn-mini'>Excluir</a>
                </div>
            </td>
        </tr>
    </tbody>
</table>

<div class='well'>
    {{
        Form::open([   
            'url' => URL::action('Admin\CategoriesController@characteristic', ['id'=>$category->_id]),
            'method'=>'POST'
        ])
    }}

    {{ Form::label('type', 'Nova caracteristica', ['class'=>'control-label']) }}

    {{ Form::select('type', ['Numero','Numero decimal','Opções','Livre'], null, ['class'=>'input-block-level']) }}

    {{ Form::text('name', '', ['placeholder'=>'Nome da caracteristica', 'class'=>'input-block-level']) }}

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
