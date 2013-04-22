<?php namespace Traits;

use Html;

trait CategoryTree
{

    protected $treeUnderBuild;

    public static function showTree()
    {
        $tree = "<ul>\n";

        $roots = static::where(
            ['$or'=>[
                ['parents'=>null],
                ['parents'=>array()]
            ]]
        );

        foreach($roots as $root)
        {
            $tree .= static::renderBranch($root);
        }

        $tree .= "</ul>\n";
        return $tree;
    }

    protected static function renderBranch($branch)
    {
        $html = "";

        $childs = $branch->childs();
        $is_leaf = $branch->kind == 'leaf';

        $html .= "<li id=category-'$branch->_id' class='$branch->kind' data-name='$branch->name'>\n";
            $html .= "<a>".
                        $branch->name.
                        (($is_leaf) ? " <i class='icon-leaf'></i>" : "").
                    "</a>\n";

            $html .="<div class='btn-group'>\n";
            if($is_leaf)
            {
                $html .= Html::action(
                    'Admin\CategoriesController@products',
                    'Produtos',
                    ['id'=>$branch->_id],
                    ['class'=>'btn btn-primary btn-mini', 'id'=>'products-cat-'.$branch->_id]
                );
            }
            $html .= Html::action(
                'Admin\CategoriesController@edit',
                'Editar',
                ['id'=>$branch->_id],
                ['class'=>'btn btn-mini', 'id'=>'edit-cat-'.$branch->_id]
            );
            $html .="</div>\n";

            if($childs->count())
            {
                $html .= "<ul>\n";
                foreach ($childs as $child) {
                    $html .= static::renderBranch($child);
                }
                $html .= "</ul>\n";
            }

        $html .= "</li>\n";

        return $html;
    }
}
