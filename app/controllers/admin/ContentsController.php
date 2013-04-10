<?php namespace Admin;

use Content, ArticleContent, VideoContent;
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

        $viewData = [
            'contents' => $contents,
            'page' => $page,
            'total_pages' => $total_pages,
        ];

        if(\Request::ajax())
        {
            return View::make('admin.contents.quicksearch', $viewData);
        }
        else
        {
            $this->layout->content = View::make('admin.contents.index', $viewData);
        }
    }

    /**
     * Display the article creation form
     *
     * @return Response
     */
    public function createArticle()
    {
        $this->layout->content = View::make('admin.contents.create_article');
    }

    /**
     * Display the video creation form
     *
     * @return Response
     */
    public function createVideo()
    {
        $this->layout->content = View::make('admin.contents.create_video');
    }

    /**
     * Display the article update form
     *
     * @return Response
     */
    public function edit($id)
    {
        $content = $this->contentRepo->first( $id );

        $viewData = [
            'content' => $content,
            'action' => 'Admin\ContentsController@update',
            'method' => 'PUT',
        ];

        if($content instanceof ArticleContent)
        {
            $this->layout->content = View::make('admin.contents.edit_article', $viewData);
        }
        elseif($content instanceof VideoContent)
        {
            $this->layout->content = View::make('admin.contents.edit_video', $viewData);
        }
        else
        {
            return Redirect::action('Admin\ContentsController@index')
                ->with( 'flash', l('navigation.the_resource_was_found', ['resource'=>'Conteúdo']) );
        }
    }

    /**
     * Saves a new resource
     *
     * @return Response
     */
    public function store()
    {
        $content = new Content;
        $content->fill( Input::all() );

        if( $this->contentRepo->createNew( $content ) )
        {
            return Redirect::action('Admin\ContentsController@index')
                ->with( 'flash', l('navigation.resource_created_sucessfully', ['resource'=>'conteúdo']) );
        }
        else
        {
            // Get validation errors
            $error = $content->errors->all();

            return Redirect::action('Admin\ContentsController@create'.ucfirst($content->kind))
                ->withInput()
                ->with( 'error', $error );
        }
    }

    /**
     * Updates an existing resource
     *
     * @return Response
     */
    public function update($id)
    {
        $content = $this->contentRepo->first($id);
        
        if($content)
            $content->fill( Input::all() );

        if( $this->contentRepo->update( $content ) )
        {
            return Redirect::action('Admin\ContentsController@index')
                ->with( 'flash', l('navigation.resource_updated_sucessfully', ['resource'=>'conteúdo']) );
        }
        else
        {
            // Get validation errors
            $error = ($content) ? $content->errors->all() : array();

            return Redirect::action('Admin\ContentsController@edit', ['id'=>$id])
                ->withInput()
                ->with( 'error', $error );
        }
    }

    /**
     * Retrieve all previously used tags that starts with
     * term (basically a quicksearch)
     */
    public function tags()
    {
        $tags = $this->contentRepo->existentTags(Input::get('term'));
        
        return Response::json($tags);
    }
}
