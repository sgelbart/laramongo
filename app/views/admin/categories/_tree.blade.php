<div
    class='tree' data-tree='true' id='categories-table'
    data-tree-session-url='{{ URL::action( 'Admin\CategoriesController@tree' ) }}' 
>
    {{ Category::renderTree( $treeState, null, true ) }}
</div>
