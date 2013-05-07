<?php
    if(isset($content))
        $f = array_merge( $content->getAttributes(), Input::old() );
    else
        $f = array_merge( Input::old() );
?>

{{
    Form::open([
        'url' => URL::action(
            isset( $action ) ? $action : 'Admin\ContentsController@store',
            isset( $content ) ? ['id'=>$content->_id] : []
        ),
        'method'=> isset( $method ) ? $method : 'POST',
        'files'=>true,
        'class'=>'form-horizontal'
    ])
}}

    {{ Form::hidden('type', 'shop' ) }}

    <div class="control-group">
        {{ Form::label('name', 'Nome da Loja', ['class'=>'control-label']) }}
        <div class="controls">
            {{ Form::text('name', array_get( $f,'name') ) }}
        </div>
    </div>

    <div class="control-group">
        {{ Form::label('address', 'Endereço', ['class'=>'control-label']) }}
        <div class="controls">
            {{ Form::text('address', array_get( $f,'address') ) }}
            <span class="help-block">Endereço da loja</span>
        </div>
    </div>

    <div class="control-group">
        {{ Form::label('cep', 'CEP', ['class'=>'control-label']) }}
        <div class="controls">
            {{ Form::text('cep', array_get( $f,'cep') ) }}
        </div>
    </div>

    <div class="control-group">
        {{ Form::label('description', 'Descrição', ['class'=>'control-label']) }}
        <div class="controls">
            {{ Form::textarea('description', array_get( $f,'description') ) }}
        </div>
    </div>

    <div class="control-group">
        {{ Form::label('slug', 'Slug da Loja', ['class'=>'control-label']) }}
        <div class="controls">
            {{ Form::text('slug', array_get( $f,'slug') ) }}
            <span class="help-block">Url para acessar a loja</span>
        </div>
    </div>

    <div class="control-group">
        {{ Form::label('tags', 'Tags', ['class'=>'control-label']) }}
        <div class="controls">
            <?php
                if(is_array(array_get( $f,'tags')))
                    $tags = implode(',', array_get( $f,'tags'));
                else
                    $tags = array_get( $f,'tags');
            ?>
            {{ Form::text('tags', $tags, ['tag-picker'=>URL::action('Admin\ContentsController@tags')] ) }}
        </div>
    </div>

    <div class="control-group">
        <span class='control-label'>Opções</span>
        <div class="controls">
            <label for='checkbox-hidden' class='checkbox'>
                Invisível
                {{ Form::hidden('hidden', false ) }}
                {{ Form::checkbox('hidden', 'true', array_get( $f,'hidden'), ['id'=>'checkbox-hidden']) }}
            </label>

            <label for='checkbox-approved' class='checkbox'>
                Aprovado
                {{ Form::hidden('approved', false ) }}
                {{ Form::checkbox('approved', 'true', array_get( $f,'approved'), ['id'=>'checkbox-approved']) }}
            </label>
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
        {{ Form::button('Salvar loja', ['type'=>'submit', 'class'=>'btn btn-primary', 'id'=>'submit-form'])}}


        {{ Html::linkAction( 'Admin\ContentsController@index', 'Cancelar', [], ['class'=>'btn'] ) }}
    </div>
{{ Form::close() }}
