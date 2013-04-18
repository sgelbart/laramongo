{{
    Form::open([
        'url' => URL::action(
            'Admin\ContentsController@tagProduct',
            ['id'=>$content->_id]
        ),
        'method'=> 'POST', 'class'=>'image-tag-form'
    ])
}}
    {{ Form::hidden('x', 50 ) }}
    {{ Form::hidden('y', 50 ) }}
    {{ Form::hidden('_id', '' ) }}

    {{ Form::label('product_id', 'Product', ['class'=>'control-label']) }}
    {{ Form::select('product_id', $productsOption, false, ['data-chosen'=>'true'] ) }}

    {{ Form::button('Marcar produto', ['type'=>'submit', 'id'=>'submit-save-product', 'class'=>'btn btn-primary'] ) }}
    <a class='btn' data-close-popover='true'>Cancelar</a>
    <a class='btn btn-danger delete-tag'><i class='icon-white icon-'</a>

{{ Form::close() }}
