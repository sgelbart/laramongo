<?php
    if(isset($synonymous))
        $f = array_merge( $synonymous->attributes, Input::old() );
    else
        $f = array_merge( Input::old() );
?>

{{
    Form::open([
        'url' => URL::action(
            isset( $action ) ? $action : 'Admin\SynonymsController@store',
            isset( $synonymous ) ? ['id'=>$synonymous->_id] : []
        ),
        'method' => isset( $method ) ? $method : 'POST',
        'class' => 'form-horizontal'
    ])
}}

    <fieldset class='control-group'>
        {{ Form::label('word', 'A palavra:', array('class' => 'control-label')) }}
        <div class="controls">
            {{ Form::text('word', array_get( $f,'word') ) }}
        </div>
    </fieldset>

    <fieldset>
        {{ Form::label('related_word', 'Relacionado com:', array('class' => 'control-label')) }}
        <div class="controls">
            <?php
                if(is_array(array_get( $f,'related_word')))
                    $related_word = implode(',', array_get( $f,'related_word'));
                else
                    $related_word = array_get( $f,'related_word');
            ?>
            {{ Form::textarea('related_word', $related_word, array('tag-picker') ) }}
        </div>
    </fieldset>

    <div class="form-actions">
        {{ Form::submit('Salvar', array('class' => 'btn btn-primary')) }}
    </div>
{{ Form::close() }}
