<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

use Selenium\Locator as l;
use Zizaco\FactoryMuff\Facade\FactoryMuff as f;

    require_once 'PHPUnit/Autoload.php';
    require_once 'PHPUnit/Framework/Assert/Functions.php';

class FeatureContext extends BaseContext {}
