<?php $is_leaf = $category->kind == 'leaf'; ?>

<div class='line'>
    <a
        data-name='{{ $category->name }}'
        {{ ($is_leaf) ? 'href="'.URL::action('Admin\CategoriesController@edit',['id'=>$category->_id]).'"' : '' }}
    >
        @if ($is_leaf)
            <i class='icon-leaf'></i>
        @elseif ($is_parent)
            <i class='icon-chevron-right'></i>
        @else
            <i class='icon-minus'></i>
        @endif
        {{ $category->name }}
    </a>
    @if ($is_leaf)
        <small>({{ $category->productCount() }})</small>
    @endif

    <div class='btn-group'>
        @if($is_leaf)
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
</div>
