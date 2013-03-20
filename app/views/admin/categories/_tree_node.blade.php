<a>
    @if ($category->kind == 'leaf')
        <i class='icon-leaf'></i>
    @elseif ($is_parent)
        <i class='icon-chevron-right'></i>
    @endif
    {{ $category->name }}
</a>

<div class='btn-group'>
    @if($category->kind == 'leaf')
        {{ HTML::action(
            'Admin\CategoriesController@products',
            'Produtos',
            ['id'=>$category->_id],
            ['class'=>'btn btn-primary btn-mini', 'id'=>'products-cat-'.$category->_id]
        ); }}
    @endif
    {{ HTML::action(
        'Admin\CategoriesController@edit',
        'Editar',
        ['id'=>$category->_id],
        ['class'=>'btn btn-mini', 'id'=>'edit-cat-'.$category->_id]
    ); }}
</div>
