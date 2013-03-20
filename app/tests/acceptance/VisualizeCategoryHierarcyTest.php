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

    /**
     * Clicks in a element and then checks if the "collapsed" attribute is
     * the one that should be.
     */
    private function assertExpandTreeIten($name, $collapsedShouldBe='false')
    {
        // Grab the dom element
        $locator = l::css("[data-name=$name]");

        // Click in it
        $this->browser->click(l::linkContaining($name));

        // Check if the "collapse" attibute have the correct value
        $this->assertTrue(
            $this->browser->getAttribute((string)$locator."@collapsed") == $collapsedShouldBe,
            'Failed to assert the "collapsed" attribute value'
        );
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
}
