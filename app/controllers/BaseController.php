<?php

class BaseController extends Controller {

	protected $layout = 'layouts.website';

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$categories = Category::where(array('active'=>true));
			
			$this->layout = View::make($this->layout);
			$this->layout->with('categories', $categories);
		}
	}

}
