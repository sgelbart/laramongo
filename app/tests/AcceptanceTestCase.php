<?php

class AcceptanceTestCase extends TestCase
{
    static protected $seleniumLaunched = false;

    static protected $serverLaunched = false;

    static protected $loadedBrowser = null;

    public $browser;

    public static function setUpBeforeClass()
    {
        static::launchSelenium();
        static::launchServer();
    }

    public static function tearDownAfterClass()
    {
        static::killServer();
        if(AcceptanceTestCase::$loadedBrowser)
        {
            AcceptanceTestCase::$loadedBrowser->stop();
            AcceptanceTestCase::$loadedBrowser = null;
        }
    }

    public function setUp()
    {
        parent::setUp();

        $this->startbrowser();
    }

    public function assertBodyHasText($needle)
    {
        $text = $this->browser->getBodyText();

        $needle = (array)$needle;

        foreach ($needle as $singleNiddle) {
            $this->assertContains($singleNiddle, $text, "Body text does not contain '$singleNiddle'");
        }
    }

    public function assertBodyHasNotText($needle)
    {
        $text = $this->browser->getBodyText();

        $needle = (array)$needle;

        foreach ($needle as $singleNiddle) {
            $this->assertNotContains($singleNiddle, $text, "Body text does not contain '$singleNiddle'");
        }
    }

    public function assertElementHasText($locator, $needle)
    {
        $text = $this->browser->getText($locator);

        $needle = (array)$needle;

        foreach ($needle as $singleNiddle) {
            $this->assertContains($singleNiddle, $text, "Body text does not contain '$singleNiddle'");
        }
    }

    public function assertElementHasNotText($locator, $needle)
    {
        $text = $this->browser->getText($locator);

        $needle = (array)$needle;

        foreach ($needle as $singleNiddle) {
            $this->assertNotContains($singleNiddle, $text, "Given element do contain '$singleNiddle' but it shoudn't");
        }
    }

    public function assertBodyHasHtml($needle)
    {
        $html = str_replace("\n", '', $this->browser->getHtmlSource());

        $needle = (array)$needle;

        foreach ($needle as $singleNiddle) {
            $this->assertContains($singleNiddle, $html, "Body html does not contain '$singleNiddle'");
        }
    }

    public function assertBodyHasNotHtml($needle)
    {
        $html = str_replace("\n", '', $this->browser->getHtmlSource());

        $needle = (array)$needle;

        foreach ($needle as $singleNiddle) {
            $this->assertNotContains($singleNiddle, $html, "Body html does not contain '$singleNiddle'");
        }
    }

    public function assertLocation($location)
    {
        $current_location = substr($this->browser->getLocation(), strlen($location)*-1);
        $pattern = '/^(http:)?\/\/(localhost)(:)?\d*(.*)/';

        preg_match($pattern, $current_location, $current_matches);
        $current_location = (isset($current_matches[4])) ? $current_matches[4] : $current_location;

        preg_match($pattern, $location, $shouldbe_matches);
        $current_location = (isset($shouldbe_matches[4])) ? $shouldbe_matches[4] : $location;

        $this->assertEquals($current_location, $current_location, "The current location ($current_location) is not '$location'");
    }

    protected function startBrowser()
    {
        // Set the Application URL containing the port of the test server
        Config::set(
            'app.url',
            Config::get('app.url').':4443'
        );
        App::setRequestForConsoleEnvironment(); // This is a must

        if(! AcceptanceTestCase::$loadedBrowser)
        {
            $client  = new Selenium\Client('localhost', 4444);
            $this->browser = $client->getBrowser('http://localhost:4443');
            $this->browser->start();
            $this->browser->windowMaximize();

            AcceptanceTestCase::$loadedBrowser = $this->browser;
        }
        else
        {
            $this->browser = AcceptanceTestCase::$loadedBrowser;
            $this->browser->open('/');
        }
        
    }

    protected static function launchSelenium()
    {
        if(AcceptanceTestCase::$seleniumLaunched)
            return;

        if(@fsockopen('localhost', 4444) == false)
        {
            $selenium_dir = $_SERVER['HOME'].'/.selenium';
            $files = scandir($selenium_dir);

            foreach ($files as $file) {
                if(substr($file,-4) == '.jar')
                {
                    $command = "java -jar $selenium_dir/$file";
                    static::execAsync($command);
                    sleep(1);
                    break;
                }
            }
        }

        AcceptanceTestCase::$seleniumLaunched = true;
    }

    protected static function launchServer()
    {
        if(AcceptanceTestCase::$serverLaunched)
            return;

        $command = "php artisan serve --port 4443";
        static::execAsync($command);

        AcceptanceTestCase::$serverLaunched = true;
    }

    protected static function killSelenium()
    {
        static::killProcessByPort('4444');
        AcceptanceTestCase::$seleniumLaunched = false;
    }

    protected static function killServer()
    {
        static::killProcessByPort('4443');
        AcceptanceTestCase::$serverLaunched = false;
    }

    private static function execAsync($command)
    {
        $force_async = " >/dev/null 2>&1 &";
        exec($command.$force_async);
    }

    private static function killProcessByPort($port)
    {
        $processInfo = exec("lsof -i :$port");
        preg_match('/^\S+\s*(\d+)/', $processInfo, $matches);

        if(isset($matches[1]))
        {
            $pid = $matches[1];
            exec("kill $pid");
        }
    }
}
