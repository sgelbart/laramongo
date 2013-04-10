<?php

class ContentsController extends BaseController {

    protected $contentRepo;

    function __construct( ContentRepository $contentRepo )
    {
        $this->contentRepo = $contentRepo;
    }

    /**
     * Display the specified resource.
     *
     * @return Response
     */
    public function show($slug)
    {
        $content = $this->contentRepo->findBySlug($slug);

        if( $content && $content->isVisible() )
        {
            $this->layout->content = View::make('contents.show_'.$content->kind)
                ->with( 'content', $content );
        }
        else
        {
            return Redirect::action('HomeController@index')
                ->with( 'flash', l('navigation.the_resource_was_found', ['resource'=>'conteúdo']) );
        }
    }
}
