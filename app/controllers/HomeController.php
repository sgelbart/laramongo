<?php

class HomeController extends BaseController {

    public function index()
    {
        $this->layout->content = Template::make('static.home');
    }

}
