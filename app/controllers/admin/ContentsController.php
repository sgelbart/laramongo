<?php namespace Admin;

use Content, ArticleContent, ImageContent, VideoContent;
use View, Input, Redirect, URL, MongoId, Session, Response, App;

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
     * Display the image creation form
     *
     * @return Response
     */
    public function createImage()
    {
        $this->layout->content = View::make('admin.contents.create_image');
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
            'categories' => \Category::toOptions( ['kind'=>['$ne'=>'leaf']] ),
            'action' => 'Admin\ContentsController@update',
            'method' => 'PUT',
        ];

        if($content instanceof ArticleContent)
        {
            $this->layout->content = View::make('admin.contents.edit_article', $viewData);
        }
        elseif($content instanceof ImageContent)
        {
            $productRepo = App::make('ProductRepository');
            $viewData['productsOption'] = $productRepo->toOptions($content->products());

            $this->layout->content = View::make('admin.contents.edit_image', $viewData);
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
        $image = Input::file('image_file') ?: Input::get('image_file');

        if( $this->contentRepo->createNew( $content, $image ) )
        {
            return Redirect::action('Admin\ContentsController@edit', ['id'=>$content->_id])
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
        $image = Input::file('image_file') ?: Input::get('image_file');
        
        if($content)
            $content->fill( Input::all() );

        if( $this->contentRepo->update( $content, $image ) )
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

    /**
     * Add reference to the product_id in the content
     *
     * @return Response
     */
    public function addProduct($id, $product_id)
    {
        $content = $this->contentRepo->first($id);

        if( $this->contentRepo->relateToProduct( $content, $product_id ) )
        {
            return Redirect::action('Admin\ContentsController@edit', ['id'=>$content->_id,'tab'=>'content-relations'])
                ->with( 'flash', l('content.relation_created_sucessfully', ['resource'=>'conteúdo']) );
        }
        else
        {
            // Get validation errors
            $error = ($content) ? $content->errors->all() : array();

            return Redirect::action('Admin\ContentsController@edit', ['id'=>$content->_id,'tab'=>'content-relations'])
                ->with( 'flash_error', $error );
        }
    }

    /**
     * Should tag a product into an ImageContent
     * @param  int    $id         ImageContent id
     * @return Response
     */
    public function tagProduct($id)
    {
        $content = $this->contentRepo->first($id);

        if(
            $this->contentRepo->tagToProduct(
                $content, Input::get('product_id'), Input::get('x'), Input::get('y')
            )
        )
        {
            return Redirect::action('Admin\ContentsController@edit', ['id'=>$content->_id,'tab'=>'content-image-tagging'])
                ->with( 'flash', l('content.tag_created_sucessfully') );
        }
        else
        {
            return Redirect::action('Admin\ContentsController@edit', ['id'=>$content->_id,'tab'=>'content-image-tagging'])
                ->with( 'flash_error', l('content.tag_not_sucessfully') );
        }
    }

    /**
     * Should remove an existing tag of a product
     * @param  int    $id         ImageContent id
     * @param  int    $tag_id     Id of the tag that will be removed
     * @return Response
     */
    public function untagProduct($id, $tag_id)
    {
        $content = $this->contentRepo->first($id);

        $this->contentRepo->removeTagged( $content, $tag_id );
        
        return Redirect::action('Admin\ContentsController@edit', ['id'=>$content->_id,'tab'=>'content-image-tagging'])
            ->with( 'flash', l('content.tag_removed_sucessfully') );
    }

    /**
     * Remove reference to the product_id in the content
     *
     * @return Response
     */
    public function removeProduct($id, $product_id)
    {
        $content = $this->contentRepo->first($id);

        $this->contentRepo->removeProduct( $content, $product_id );
        
        return Redirect::action('Admin\ContentsController@edit', ['id'=>$content->_id,'tab'=>'content-relations'])
            ->with( 'flash', l('content.relation_removed_sucessfully', ['resource'=>'conteúdo']) );
    }

    /**
     * Add reference to the category_id in the content
     *
     * @return Response
     */
    public function addCategory($id, $category_id = null)
    {
        if(! $category_id)
            $category_id = Input::get('category_id');

        $content = $this->contentRepo->first($id);

        if( $this->contentRepo->relateToCategory( $content, $category_id ) )
        {
            return Redirect::action('Admin\ContentsController@edit', ['id'=>$content->_id,'tab'=>'content-relations'])
                ->with( 'flash', l('content.relation_created_sucessfully', ['resource'=>'conteúdo']) );
        }
        else
        {
            // Get validation errors
            $error = ($content) ? $content->errors->all() : array();

            return Redirect::action('Admin\ContentsController@edit', ['id'=>$content->_id,'tab'=>'content-relations'])
                ->with( 'error', $error );
        }
    }

    /**
     * Remove reference to the category_id in the content
     *
     * @return Response
     */
    public function removeCategory($id, $category_id)
    {
        $content = $this->contentRepo->first($id);

        $this->contentRepo->removeCategory( $content, $category_id );
        
        return Redirect::action('Admin\ContentsController@edit', ['id'=>$content->_id,'tab'=>'content-relations'])
            ->with( 'flash', l('content.relation_removed_sucessfully', ['resource'=>'conteúdo']) );
    }
}
