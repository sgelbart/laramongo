<?php
    if(isset($product))
        $f = array_merge( $product->attributes, Input::old() );
    else
        $f = array_merge( Input::old() );
?>

{{-- Since multiple line brackets are not yet implemented --}}
{{-- see: https://github.com/laravel/framework/issues/88  --}}
<?=
    Form::open(
        URL::action(
            isset( $action ) ? $action : 'Admin\ProductsController@store',
            isset( $product ) ? ['id'=>$product->id] : []
        ),
        isset( $method ) ? $method : 'POST' 
    )
?>
    <fieldset>
        <div class='wrapper'>
            <div class='collumn'>
                {{ Form::label('name', 'Nome') }}
                {{ Form::text('name', array_get( $f,'name') ) }}

                {{ Form::label('family', 'Família') }}
                {{ Form::text('family', 'betoneiras', ['readonly'] ) }}

                {{ Form::label('chave_id', 'Chave ID') }}
                {{ Form::text('chave_id', array_get( $f,'chave_id') ) }}

                {{ Form::label('status', 'Status') }}
                {{ Form::text('status', array_get( $f,'status') ) }}

                {{ Form::label('description', 'Descrição') }}
                {{ Form::textarea('description', array_get( $f,'description') ) }}

                {{ Form::label('small_description', 'Descrição curta') }}
                {{ Form::text('small_description', array_get( $f,'small_description') ) }}

                {{ Form::label('capacidade_do_tambor', 'Capacidade do Tambor (Litros)') }}
                {{ Form::text('capacidade_do_tambor', array_get( $f,'capacidade_do_tambor') ) }}

            </div>
            <div class='collumn'>

                {{ Form::label('capacidade_de_mistura', 'Capacidade de Mistura (Litros)') }}
                {{ Form::text('capacidade_de_mistura', array_get( $f,'capacidade_de_mistura') ) }}

                {{ Form::label('producao_horaria', 'Produção Horária (m³)') }}
                {{ Form::text('producao_horaria', array_get( $f,'producao_horaria') ) }}

                {{ Form::label('alimentacao', 'Alimentação') }}
                {{ Form::text('alimentacao', array_get( $f,'alimentacao') ) }}

                {{ Form::label('voltagem', 'Voltagem') }}
                {{ Form::text('voltagem', array_get( $f,'voltagem') ) }}

                {{ Form::label('porencia_do_motor', 'Potência do Motor') }}
                {{ Form::text('porencia_do_motor', array_get( $f,'porencia_do_motor') ) }}

                {{ Form::label('espessura_da_chapa', 'Espessura da Chapa (mm)') }}
                {{ Form::text('espessura_da_chapa', array_get( $f,'espessura_da_chapa') ) }}

                {{ Form::label('peso', 'Peso (kg)') }}
                {{ Form::text('peso', array_get( $f,'peso') ) }}

                {{ Form::label('dimencoes', 'Dimenções (CxLxA)') }}
                {{ Form::text('dimencoes', array_get( $f,'dimencoes') ) }}

                {{ Form::label('marca', 'Marca') }}
                {{ Form::text('marca', array_get( $f,'marca') ) }}
            </div>
        </div>

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

            {{ Form::button('Salvar produto', ['type'=>'submit', 'class'=>'btn btn-primary'] ) }}

            @if ( isset($product) )
                {{ HTML::action( 'Admin\ProductsController@destroy', 'Excluir', ['id'=>$product->id], ['data-method'=>'delete', 'class'=>'btn btn-danger'] ) }}
            @endif

            {{ HTML::action( 'Admin\ProductsController@index', 'Cancelar', [], ['class'=>'btn'] ) }}

        </div>
    </fieldset>
{{ Form::close() }}
