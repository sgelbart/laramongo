<?php namespace Traits;

use Html, Cache;

trait ToTree
{
    public static $_nodes;

    public static $_idzedArray;

    public static $_treeState;

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

    public static function renderTree( $treeStates, $options = null, $showHidden = false )
    {
        if(! $options)
        {
            $options = static::$treeOptions;
        }

        $cacheKey = str_replace('.', '_', implode('_', $options));
        //return Cache::remember($cacheKey, 0.1, function() use ($treeStates, $options, $showHidden){

            $result = '<ul class="roots">';

            if($showHidden)
            {
                $query = array();
            }
            else
            {   
                $query = ['hidden'=>['$ne'=>'true']];
            }
                
            static::$_nodes = static::where($query, ['name','type','parents'])->toArray(false, 1200);

            static::$_idzedArray = array();
            foreach (static::$_nodes as $node) {
                foreach ((array)$node->parents as $parent) {
                    static::$_idzedArray[(string)$parent][] = $node;
                }
            }

            static::$_treeState = $treeStates;

            foreach (static::$_nodes as $node) {
                if($node->isRoot())
                {
                    $result .= $node->renderNode( true, $options );
                }
            }

            $result .= '</ul>';
            
            return $result;

        //});
    }

    public function isRoot(){
        return count($this->parents) == 0;
    }

    protected function renderNode( $is_parent = false, $options )
    {
        $domId = 'tree_'.
                   array_get($options,'nodeName','node')
                   .'_'.$this->_id;

        $domState = 'collapsed="true"';

        if(array_get(static::$_treeState, $domId, false))
        {
            $domState = 'collapsed="'.array_get(static::$_treeState, $domId).'"';
        }

        $result = '<li id="'.$domId.'" '.$domState.'>';

        $has_child = false;
        $subResult = '';
        
        if(isset(static::$_idzedArray[(string)$this->_id]))
        foreach (static::$_idzedArray[(string)$this->_id] as $node) {
            static::$rendered++;
            if( in_array((string)$this->_id, (array)$node->parents) )
            {
                if(! $has_child)
                {
                    $subResult .= '<ul>';
                    $has_child = true;
                }
                $subResult .= $node->renderNode( $has_child, $options );
            }
        }

        $result .= \View::make(
            array_get( $options, 'nodeView', 'path.to.tree_node_view'),
            [
                array_get($options,'nodeName','node') => $this,
                'is_parent' => $has_child
            ]
        )->render();

        if( $has_child )
        {
            $subResult .= '</ul>';
        }

        $result .= $subResult;

        $result .= '</li>';

        return $result;
    }



    // FUCK THIS OFF
    /**
     * Store the time_start
     */
    private static $time_start = 0;

    /**
     * Stores the final time
     */
    private static $time_stop = 0;

    /**
     * Stores the final time
     */
    private static $rendered = 0;


    /**
     * Starts the time tracking
     */
    public static function start()
    {
        static::$time_start = static::micro_time();
    }

    /**
     * Ends the time tracking
     *
     * @return time taken in seconds
     */
    public static function end()
    {
        static::$time_stop = static::micro_time();
        $time_overall = bcsub(
            static::$time_stop,
            static::$time_start,
            6
        );

        return $time_overall;
    }

    /**
     * Ends the time tracking verbose
     * echo the time and memory usage
     */
    public static function end_v()
    {
        echo 'Execution Time: '.static::end()." seconds\n";
        echo 'Memory Usage: '.(int)(memory_get_peak_usage()/1024)." kB\n";
    }

    /**
     * Gets the current time properly
     */
    public static function micro_time() {
        $temp = explode(" ", microtime());
        return bcadd($temp[0], $temp[1], 6);
    }
}
