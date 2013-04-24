<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

require_once 'PHPUnit/Autoload.php';
require_once 'PHPUnit/Framework/Assert/Functions.php';

class BaseContext extends BehatContext
{
    protected $testCase;
    protected $acceptanceCase;

    // Adding ability to run assertions at TestCase
    public function testCase()
    {
        if (! isset($this->testCase)) {
            $this->testCase = new ControllerTestCase;
            $this->testCase->setUp();
        }

        return $this->testCase;
    }

    // Adding ability to run assertions at fron_end test
    public function acceptanceCase()
    {
        if (! isset($this->acceptanceCase)) {
            $this->acceptanceCase = new AcceptanceTestCase;
            $this->acceptanceCase->setUp();
        }

        return $this->acceptanceCase;
    }

    public function __construct(array $parameters) {

        // import all context classes from context directory, except the abstract one

        $filesToSkip = array('AbstractContext.php', 'BaseContext.php');

        $path = dirname(__FILE__);
        $it = new RecursiveDirectoryIterator($path);
        /** @var $file  SplFileInfo */
        foreach ($it as $file) {
            if (!$file->isDir()) {
               $name = $file->getFilename();
               if (!in_array($name, $filesToSkip)) {
                   $class = pathinfo($name, PATHINFO_FILENAME);
                   require_once dirname(__FILE__) . '/' . $name;
                   $this->useContext($class, new $class($parameters));
               }
            }
        }
    }
}
