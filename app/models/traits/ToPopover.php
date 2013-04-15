<?php namespace Traits;

use View;

trait ToPopover
{
    /**
     * Rendering popover with object using the view
     * especified in $popoverView.
     * The rendered view the instance variable will be avaliable
     * with the object $instance.
     * @param  $content content display the popover on hover
     * @return string html_code_popover
     */
    public function renderPopover($content = '')
    {
        if(! $this->popoverView)
        {
            trigger_error(
                'The $popoverView attribute is not set into this model. '.
                'The ToPopover trait needs this attribute to exist.'
            );
        }

        $popoverContent = View::make($this->popoverView, ['instance' => $this])->render();

        $popoverHtml =
        '<span data-with-popover>'.
            $content .
        '<div class="popover top"><div class="arrow"></div>'.
            '<h3 class="popover-title">'. $this->name .'</h3>'.
            '<div class="popover-content">'.
                $popoverContent.
            '</div>'.
        '</div>'.
        '</span>';
        return $popoverHtml;
    }
}
