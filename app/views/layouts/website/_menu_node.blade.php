<?php $is_leaf = $category->type == 'leaf'; ?>

<div class='line'>
    <a
        data-name='{{ $category->name }}'
        href='{{ URL::action('CategoriesController@show', ['id'=>$category->_id]) }}'
    >
        {{ $category->name }}
    </a>
</div>
