<?php

class RegionsController extends BaseController
{
    public function create()
    {
        return View::make('regions.create');
    }

    public function store()
    {
        $region = Input::get('region');
        $sourceUrl = Session::get('path');

        Session::forget('path');

        if ($region) {
            Session::set('region', $region);
            return Redirect::to( $sourceUrl );
        } else {
            return Redirect::action('RegionsController@create');
        }
    }
}
