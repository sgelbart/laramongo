<div class='search_box'>

    {{ Form::open( array('url' => 'quick_search', 'method' => 'get', 'data-ajax' => 'true' ) ) }}
        {{ Form::text('query','', array( 'autocomplete' => 'off', 'data-submit-on-type' => 'true' ) ) }}
        {{ Form::submit() }}
    {{ Form::close() }}

</div>
<div id='quicksearch' class='main-quicksearch' style='display:none;'>
    Resultados...
</div>

<ul class='horizon_menu'>
    <li><span class='construcao'>Construção</span></li>
    <li><span class='acabamento'>Acabamento</span></li>
    <li><span class='decoracao'>Decoração</span></li>
    <li><span class='jardinagem'>Jardinagem</span></li>
    <li><span class='bricolagem'>Bricolagem</span></li>
</ul>
