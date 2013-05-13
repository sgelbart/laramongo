<?php namespace Admin;

use View, Input, Redirect, URL, Session, Response;
use Warning;

class WarningsController extends AdminController
{

    public function index()
    {
        $warnings = Warning::all();

        $this->layout->content = View::make('admin.warnings.index', compact('warnings'));
    }
}
