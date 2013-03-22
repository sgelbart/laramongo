<?php

use Selenium\Locator as l;
use Zizaco\FactoryMuff\Facade\FactoryMuff as f;

class VisualizeCategoryHierarchyTest extends AcceptanceTestCase
{
    /**
     * Clean collection between every test
     */
    public function setUp()
    {
        parent::setUp();
        $this->cleanCollection( 'categories' );
    }

    public function testShouldVisualizeCategoryTree()
    {
        $this->buildSampleTree();

        $this->browser->open('/admin/categories');
            
        $this->assertExpandTreeIten('root');
        $this->assertExpandTreeIten('parentA');
        $this->assertExpandTreeIten('parentB');

        $this->assertExpandTreeIten('root', 'true');
        $this->assertExpandTreeIten('parentA', 'true');
        $this->assertExpandTreeIten('parentB', 'true');
    }

    public function testShouldGoToActionsOfCategories()
    {
        $root = $this->buildSampleTree();

        $this->browser->open('/admin/categories')
            ->click(l::linkContaining('Editar')) // Click in the first 'Editar' btn in the list
            ->waitForPageToLoad(1000);

        // The editar button should lead to the edit action of the category
        $this->assertLocation(URL::action('Admin\CategoriesController@edit', ['id'=>$root->_id]));
    }

    public function testShouldDoQuicksearch()
    {
        $root = $this->buildSampleTree();

        $this->browser
            ->open('/admin/categories')
            ->type(l::IdOrName('search'), 'childB')
            ->typeKeys(l::IdOrName('search'), 'childB');

        sleep(1);

        $this->assertQuicksearchNotMatchedIten('childA');
        $this->assertQuicksearchMatchedIten('childB');

        $this->browser
            ->type(l::IdOrName('search'), 'child')
            ->typeKeys(l::IdOrName('search'), 'child');

        sleep(1);

        $this->assertQuicksearchMatchedIten('childA');
        $this->assertQuicksearchMatchedIten('childB');
    }

    /**
     * Clicks in a element and then checks if the "collapsed" attribute is
     * the one that should be.
     */
    private function assertExpandTreeIten($name, $collapsedShouldBe='false')
    {
        $domId = 'tree_category_'.$this->getIdByName($name);

        // Grab the dom element
        $locator = l::css("#$domId");

        // Click in it
        $this->browser->click(l::linkContaining($name));

        // Check if the "collapse" attibute have the correct value
        $this->assertTrue(
            $this->browser->getAttribute((string)$locator."@collapsed") == $collapsedShouldBe,
            'Failed to assert the "collapsed" attribute value'
        );
    }

    private function assertQuicksearchMatchedIten($name)
    {
        $domId = 'tree_category_'.$this->getIdByName($name);

        // Grab the dom element
        $locator = l::css("#$domId a[data-name]");

        // Grab the classes within that element
        try{
            $classes = $this->browser->getAttribute((string)$locator."@class");

            // Check if the .not-important class is NOT present
            $this->assertNotContains('not-important', (string)$classes);
        }
        catch(Selenium\Exception $e)
        {
            // If the class attribute doesn't exists. Then it's ok too ;)
            $this->assertContains('Could not find element attribute', $e->getMessage());
        }

        
    }

    private function assertQuicksearchNotMatchedIten($name)
    {
        $domId = 'tree_category_'.$this->getIdByName($name);

        // Grab the dom element
        $locator = l::css("#$domId a[data-name]");

        // Grab the classes within that element
        $classes = $this->browser->getAttribute((string)$locator."@class");

        // Check if the .not-important class is present
        $this->assertContains('not-important', $classes);
    }

    /**
     * Build a tree for testing purposes;
     * The hierarchy is the following:
     * - root
     *   - parentA
     *     - childA (leaf)
     *   - parentB
     *     - childA (leaf)
     *     - childB (leaf)
     */
    private function buildSampleTree()
    {

        $root = f::create('Category', ['name'=>'root']);
        $parentA = f::create('Category', ['name'=>'parentA']);
            $parentA->attachToParents($root);
        $parentB = f::create('Category', ['name'=>'parentB']);
            $parentB->attachToParents($root);
        $childA = f::create('Category', ['name'=>'childA']);
            $childA->attachToParents($parentA);
            $childA->attachToParents($parentB);
        $childB = f::create('Category', ['name'=>'childB']);
            $childB->attachToParents($parentB);

        $childA->kind = 'leaf';
        $childB->kind = 'leaf';

        $root->save();
        $parentA->save();
        $parentB->save();
        $childA->save();
        $childB->save();

        return $root;
    }

    private function getIdByName($name)
    {
        $category = Category::first(['name'=>$name]);
        if($category)
        {
            return $category->_id;
        }
        else
        {
            return false;
        }
    }
}
