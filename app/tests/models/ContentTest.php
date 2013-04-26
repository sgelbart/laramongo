<?php

class ContentTest extends Zizaco\TestCases\TestCase
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

    /**
     * Should explode string when setting the tags
     */
    public function testShouldExplodeTagString()
    {
        $content = testContentProvider::instance('valid_article');

        $messed_string = 'Jardim,exterior  , Ambiente';
        $should_become = ['jardim','exterior','ambiente'];

        $content->tags = $messed_string;
        $this->assertEquals($should_become, $content->tags);
    }

    /**
     * A content should at least try to insert the
     * tags into database
     */
    public function testShouldInsertTagsWhenSave()
    {
        $content = testContentProvider::instance('valid_article');
        $content->tags = 'jardim, exterior, ambiente';
        $content->save();

        // Creates a raw connection and search for the tags in the collection
        $connection = new Zizaco\Mongolid\MongoDbConnector;

        foreach ($content->tags as $tag) {
            $this->assertNotNull( $connection->getConnection()->db->tags->findOne(['_id'=>$tag]) );
        }

        // Search for a non-existent tag
        $this->assertNull( $connection->getConnection()->db->tags->findOne(['_id'=>'a_non_existent_tag']) );
    }

    public function testShouldRenderPopover()
    {
        $content = testContentProvider::instance('valid_article');

        $this->assertContains('<div',$content->renderPopover());
        $this->assertContains('<span',$content->renderPopover());
        $this->assertContains('bacon',$content->renderPopover('bacon'));
    }
}
