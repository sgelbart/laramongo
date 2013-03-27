<?php
    if(isset($product))
        $f = array_merge( $product->attributes, Input::old() );
    else
        $f = array_merge( Input::old() );
?>

{{-- Since multiple line brackets are not yet implemented --}}
{{-- see: https://github.com/laravel/framework/issues/88  --}}
<?=
    Form::open([
        'url' => URL::action(
            isset( $action ) ? $action : 'Admin\ProductsController@store',
            isset( $product ) ? ['id'=>$product->_id] : []
        ),
        'method' => isset( $method ) ? $method : 'POST' 
    ])
?>
    <fieldset>
        {{ Form::label('name', 'Nome') }}
        {{ Form::text('name', array_get( $f,'name') ) }}

        {{ Form::label('category', 'Chave de entrada') }}
        {{ Form::select('category', $leafs, (string)(array_get( $f,'category')) ) }}

        {{ Form::label('description', 'Descrição') }}
        {{ Form::textarea('description', array_get( $f,'description') ) }}

        {{ Form::label('small_description', 'Descrição curta') }}
        {{ Form::text('small_description', array_get( $f,'small_description') ) }}

        @if ( Session::get('error') )
            <div class="alert alert-error">
                @if ( is_array(Session::get('error')) )
                    {{ Session::get('error')[0] }}
                @else
                    {{ Session::get('error') }}
                @endif
            </div>
        @endif

        <div class='form-actions'>

            {{ Form::button('Salvar produto', ['type'=>'submit', 'id'=>'submit-save-product', 'class'=>'btn btn-primary'] ) }}

            @if ( isset($product) )
                {{ Html::linkAction( 'Admin\ProductsController@destroy', 'Excluir', ['id'=>$product->_id], ['data-method'=>'delete', 'class'=>'btn btn-danger'] ) }}
            @endif

            {{ Html::linkAction( 'Admin\ProductsController@index', 'Cancelar', [], ['class'=>'btn'] ) }}

        </div>
    </fieldset>
{{ Form::close() }}
