<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

use Selenium\Locator as l;
use Zizaco\FactoryMuff\Facade\FactoryMuff as f;
use Mockery as m;
use Laramongo\ImageGrabber\RemoteImporter;
use Laramongo\ImageGrabber\Validator;

class PhotosImportContext extends BaseContext {

    public function __construct()
    {
        $this->cleanCollection( 'categories' );
        $this->cleanCollection( 'products' );
    }

    /**
     * @Given /^I have a the product "([^"]*)"$/
     */
    public function iHaveATheProduct($product_name)
    {
        $this->$product_name = testProductProvider::instance($product_name);
        $this->$product_name->_id = 100;
        $this->$product_name->save();
    }

    /**
     * @Then /^should get (\d+) photo$/
     */
    public function shouldGetPhoto($arg1)
    {
        $image_importer = m::mock(new RemoteImporter);
        $image_importer->shouldReceive('import')->atLeast(1);
    }

    /**
     * @Then /^should have (\d+) image name at log$/
     */
    public function shouldHaveImageNameAtLog($arg1)
    {
        $validator = m::mock(new Validator);
        $validator->shouldReceive('logInvalid')->atLeast(1);
        m::close();
    }

    /**
     * @Then /^should don\'t have this product at log$/
     */
    public function shouldDonTHaveThisProductAtLog()
    {
        $validator = m::mock('ValidatorShouldNot');
        m::close();
    }
}
