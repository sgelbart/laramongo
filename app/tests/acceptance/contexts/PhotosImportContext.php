<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

use Selenium\Locator as l;
use Zizaco\FactoryMuff\Facade\FactoryMuff as f;

class PhotosImportContext extends BaseContext {

    public function __construct()
    {
        $this->cleanCollection( 'categories' );
        $this->cleanCollection( 'products' );
    }

    /**
     * @When /^I save the product$/
     */
    public function iSaveTheProduct()
    {
        throw new PendingException();
    }

    /**
     * @Then /^should get (\d+) photos$/
     */
    public function shouldGetPhotos($arg1)
    {
        throw new PendingException();
    }
}
