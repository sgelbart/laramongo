<div class='search_box'>

    {{ Form::open( array('url' => 'search', 'method' => 'get' ) ) }}
        {{ Form::text('query') }}
        {{ Form::submit() }}
    {{ Form::close() }}

</div>

<ul class='horizon_menu'>
    <li><span class='construcao'>Construção</span></li>
    <li><span class='acabamento'>Acabamento</span></li>
    <li><span class='decoracao'>Decoração</span></li>
    <li><span class='jardinagem'>Jardinagem</span></li>
    <li><span class='bricolagem'>Bricolagem</span></li>
</ul>
