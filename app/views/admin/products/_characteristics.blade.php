<?php
    $f = array_merge( (array)$product->details, Input::old() );
?>

{{-- Since multiple line brackets are not yet implemented --}}
{{-- see: https://github.com/laravel/framework/issues/88  --}}
<?=
    Form::open([
        'url' => URL::action( 
            'Admin\ProductsController@characteristic',
            ['id'=>$product->_id]
        ),
        'method' => 'PUT',
        'class'=>'form-horizontal'
    ])
?>

    @foreach( $category->characteristics() as $charac )

        <div class="control-group">
            {{ Form::label(clean_case($charac->name), $charac->name, ['class'=>'control-label']) }}
            <div class="controls">
                <div class="{{ ($charac->getAttribute("layout-pre")) ? 'input-prepend ' : ''}}{{ ($charac->getAttribute("layout-pos")) ? 'input-append' : '' }}">
                    @if ($charac->getAttribute("layout-pre"))
                        <span class="add-on">{{ $charac->getAttribute("layout-pre") }}</span>
                    @endif

                    @if ($charac->type == 'option')
                        {{ Form::select(clean_case($charac->name), array_combine($charac->values, $charac->values), (string)(array_get( $f,clean_case($charac->name))) ) }}
                    @else
                        {{ Form::text(clean_case($charac->name), array_get( $f, clean_case($charac->name)) ) }}
                    @endif

                    @if ($charac->getAttribute("layout-pos"))
                        <span class="add-on">{{ $charac->getAttribute("layout-pos") }}</span>
                    @endif
                </div>
            </div>
        </div>

    @endforeach
        
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

        {{ Form::button('Salvar caracteristicas', ['type'=>'submit', 'id'=>'submit-save-product-characteristics', 'class'=>'btn btn-primary'] ) }}

        {{ HTML::action( 'Admin\ProductsController@index', 'Cancelar', [], ['class'=>'btn'] ) }}

    </div>
{{ Form::close() }}
