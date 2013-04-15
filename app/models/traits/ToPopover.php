<?php namespaces Trait;

trait ToPopover
{
    /**
     * Rendering popover with object using the view
     * especified in $popoverView.
     * The rendered view the instance variable will be avaliable
     * with the object $instance.
     * @return string html_code_popover
     */
    public function renderPopover()
    {
        return View::make($this->popoverView, ['instance' => $this])->render();
    }
}
