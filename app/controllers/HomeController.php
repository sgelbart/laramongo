<?php

class HomeController extends BaseController {

    /*
    |--------------------------------------------------------------------------
    | Default Home Controller
    |--------------------------------------------------------------------------
    |
    */

    public function index()
    {
        $this->layout->content = View::make('static.home');
    }

}
