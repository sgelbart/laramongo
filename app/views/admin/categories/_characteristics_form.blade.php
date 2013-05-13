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
