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
        $page = Input::get('page');

        $contents = $this->contentRepo->search( Input::get('search'), Input::get('kind') );
        $total_pages = $this->contentRepo->pageCount( $contents );
        $contents = $this->contentRepo->paginate( $contents, $page );

        $this->layout->content = View::make('admin.contents.index')
            ->with( 'contents', $contents )
            ->with( 'page', $page )
            ->with( 'total_pages', $total_pages );
    }
}
