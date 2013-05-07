<?php

class VideoContentTest extends Zizaco\TestCases\TestCase
{
    use TestHelper;

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
    public function testShouldRenderVideo()
    {
        $content = testContentProvider::saved('valid_video');

        // Check for the most relevant embeded code
        $this->assertContains( '<iframe',      $content->render() );
        $this->assertContains( 'youtube',      $content->render() );
        $this->assertContains( 'embed/',       $content->render() );
        $this->assertContains( $content->youTubeId, $content->render() );
        $this->assertContains( 'width="111"',  $content->render( 111 ) );
        $this->assertContains( 'height="222"', $content->render( 111, 222 ) );
        $this->assertContains( '</iframe>',    $content->render() );
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
