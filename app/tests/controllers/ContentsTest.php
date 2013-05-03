<?php

use Mockery as m;

class ContentsTest extends ControllerTestCase
{
    use TestHelper;

    /**
     * Clean collection between every test
     */
    public function setUp()
    {
        parent::setUp();

        $this->cleanCollection( 'contents' );
    }

    /**
     * Show Article action should return 200 if exists
     *
     */
    public function testShouldShowArticle(){

        // Make sure that repo->findBySlug is called once
        $contentRepo = m::mock(new ContentRepository);
        $contentRepo->shouldReceive('findBySlug')->once()->passthru();
        App::instance("ContentRepository", $contentRepo);

        //Article
        $article = testContentProvider::saved('valid_article');
        $article->approved = true;
        $article->save();

        $this->requestAction('GET', 'ContentsController@show', ['slug'=>$article->slug]);
        $this->assertRequestOk();

        //Video
        $video = testContentProvider::saved('valid_video');
        $video->approved = true;
        $video->save();

        $this->requestAction('GET', 'ContentsController@show', ['slug'=>$video->slug]);
        $this->assertRequestOk();
    }

    /**
     * Show Article action should redirect to index if article not found
     *
     */
    public function testShouldNotShowNonExistentArticle(){

        // Make sure that repo->findBySlug is called once
        $contentRepo = m::mock(new ContentRepository);
        $contentRepo->shouldReceive('findBySlug')->once()->passthru();
        App::instance("ContentRepository", $contentRepo);

        $this->requestAction('GET', 'ContentsController@show', ['slug'=>'not_existent']);
        $this->assertRedirection();
        $this->assertSessionHas('flash','não encontrad');
    }
}
