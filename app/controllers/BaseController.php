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
        if ($this->layout)
        {
            $this->layout = \View::make($this->layout);
        }
    }

    /**
     * Overwrite the processResponse method of Controller in order to
     * set the header to application/javascript in case of ajax call.
     *
     * @param  Illuminate\Routing\Router  $router
     * @param  string  $method
     * @param  mixed   $response
     * @return Symfony\Component\HttpFoundation\Response
     */
    protected function processResponse($router, $method, $response)
    {
        $response = parent::processResponse($router, $method, $response);

        if( \Request::ajax() || substr($this->layout->getPath(),-3) == '.js' ) {
            $response->headers->set('Content-Type', 'application/javascript');
        }

        return $response;
    }
}
