<ul class="nav nav-tabs">
    <li {{ (Request::is('*index*') || Request::is('*/categories')) ? 'class="active"' : '' }}>
        {{ HTML::action('Admin\CategoriesController@index', 'Listagem') }}
    </li>
    <li {{ (Request::is('*tree*')) ? 'class="active"' : '' }}>
        {{ HTML::action('Admin\CategoriesController@tree', 'Arvore') }}
    </li>
</ul>
