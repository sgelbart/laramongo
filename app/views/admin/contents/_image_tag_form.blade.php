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

    {{ Form::label('product_id', 'Product', ['class'=>'control-label']) }}
    {{ Form::select('product_id', $productsOption, false, ['data-chosen'=>'true'] ) }}

    {{ Form::button('Marcar produto', ['type'=>'submit', 'id'=>'submit-save-product', 'class'=>'btn btn-primary'] ) }}
    {{ Html::linkAction( 'Admin\ProductsController@index', 'Cancelar', [], ['class'=>'btn'] ) }}

{{ Form::close() }}