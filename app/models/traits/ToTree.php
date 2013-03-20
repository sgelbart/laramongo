<?php namespace Traits;

use HTML;

trait ToTree
{
    public static $_nodes;

    /**
     * Should contain a STATIC protected attribute
     * containing the options of the tree
     */
    /*
    static protected $treeOptions = array(
        'nodeView' => 'path.to.tree_node_view',
        'nodeName' => 'category'
    );
    */

    public static function renderTree()
    {
        $result = '<ul>';

        static::$_nodes = static::all()->toArray(false);

        foreach (static::$_nodes as $node) {
            if($node->isRoot())
            {
                $result .= $node->renderNode( true );
            }
        }

        $result .= '</ul>';

        return $result;
    }

    public function isRoot(){
        return count($this->parents()) == 0;
    }

    protected function renderNode( $is_parent = false )
    {
        $result = '<li>';

        $result .= \View::make( array_get( static::$treeOptions, 'nodeView', 'path.to.tree_node_view') )
            ->with( array_get(static::$treeOptions,'nodeName','node'), $this )
            ->with( 'is_parent', $is_parent )
            ->render();

        $has_child = false;
        foreach( static::$_nodes as $node ) {
            if( in_array((string)$this->_id, (array)$node->parents) )
            {
                if(! $has_child)
                {
                    $result .= '<ul>';
                    $has_child = true;
                }

                $result .= $node->renderNode( $has_child );
            }
        }

        if( $has_child )
        {
            $result .= '</ul>';
        }

        $result .= '</li>';

        return $result;
    }
}
