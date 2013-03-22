<div class='side_menu'>
    <h3>Todos os departamentos</h3>
    {{ Category::renderMenu() }}
    {{--
    <ul>
        @foreach ($categories as $category)
            <a href='{{ URL::action('CategoriesController@show', ['id'=>$category->_id]) }}'>
                <li>{{ ucfirst( $category->name ) }}</li>
            </a>
        @endforeach
    </ul>
    --}}
</div>
