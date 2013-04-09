<?php

class ContentTest extends TestCase
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
    }

    public function testShouldValidateAndSaveContent()
    {
        // Valid content should be saved
        $content = testContentProvider::instance('valid_article');
        $this->assertTrue( $content->isValid() );
        $this->assertTrue( $content->save() );

        // Invalid content should not be saved
        $invalidContent = testContentProvider::instance('invalid_article');
        $this->assertFalse( $invalidContent->isValid() );
        $this->assertFalse( $invalidContent->save() );
    }

    /**
     * Asserts if a content is visible or not. This takes a decision
     * assembling the following facts:
     * - hidden is not any sort of 'true'
     * - content has an _id
     */
    public function testVisibility()
    {
        // A non-saved content should not be visible
        $content = testContentProvider::instance('valid_article');
        unset($content->_id);
        $this->assertFalse($content->isVisible());

        // A valid saved content should be not be visible if not aproved
        $content = testContentProvider::saved('valid_article');
        $this->assertFalse($content->isVisible());

        // A valid saved content should be visible if aproved
        $content->approved = true;
        $this->assertTrue($content->isVisible());

        // A hidden content should not be visible
        $content = testContentProvider::saved('hidden_valid_article');
        $this->assertFalse($content->isVisible());
    }

    /**
     * Should deactivate a content
     */
    public function testShouldHideContent()
    {
        $content = testcontentProvider::saved('valid_article');
        $content->hide();

        $this->assertTrue($content->hidden);
    }

    /**
     * Should activate a content
     */
    public function testShouldUnhideContent()
    {
        $content = testcontentProvider::saved('hidden_valid_article');
        $content->unhide();

        $this->assertNotEquals(true, $content->hidden);
    }

    /**
     * Should render the content
     */
    public function testShouldRender()
    {
        $content = testcontentProvider::saved('valid_article');

        $this->assertEquals( $content->article, $content->render() );
    }
}
