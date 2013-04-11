<?php

class ImageContentTest extends TestCase
{
    /**
     * Clean collection between every test
     */
    public function setUp()
    {
        parent::setUp();
        $this->cleanCollection( 'products' );
        $this->cleanCollection( 'categories' );
        $this->cleanCollection( 'contents' );
        $this->cleanCollection( 'tags' );
    }

    /**
     * Should render the content
     */
    public function testShouldRenderImage()
    {
        $content = testContentProvider::saved('valid_image');

        // Check for the most relevant embeded code
        $this->assertContains( '<img',          $content->render() );
        $this->assertContains( 'src',           $content->render() );
        $this->assertContains( $content->image, $content->render() );
        $this->assertContains( $content->name,  $content->render() );
    }

    /**
     * Should get the video ID when setting the youTubeId
     * attribute
     */
    public function testShouldGetVideoCodeWhenSettingVideo()
    {
        $video = new VideoContent;
        $video->youTubeId = 'http://www.youtube.com/watch?v=BNQFsKCuwAcxY&bacon=lol';
        $this->assertEquals('BNQFsKCuwAcxY', $video->youTubeId );
    }
}
