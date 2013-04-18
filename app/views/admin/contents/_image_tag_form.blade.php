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

    {{ Form::button(l('content.save_tag_image'), ['type'=>'submit', 'id'=>'submit-tag-image', 'class'=>'btn btn-primary'] ) }}
    <a class='btn' data-close-popover='true'>Cancelar</a>
    <a class='btn btn-danger delete-tag' href='{{ URL::action('Admin\ContentsController@untagProduct',['id'=>$content->_id, 'tag_id'=>0]) }}' data-method='DELETE'>
        <i class='icon-trash icon-white'></i>
    </a>

{{ Form::close() }}
