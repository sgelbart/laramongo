<?php namespace Admin;

use Category, Characteristic, Product;
use View, Input, Redirect, URL, MongoId, Session, Response;

class ContentsController extends AdminController {

    protected $contentRepo;

    function __construct( \ContentRepository $contentRepo )
    {
        $this->contentRepo = $contentRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $contents = $this->contentRepo->search( Input::get('search'), Input::get('kind') );

        $this->layout->content = View::make('admin.contents.index')
            ->with( 'contents', $contents );
    }
}
