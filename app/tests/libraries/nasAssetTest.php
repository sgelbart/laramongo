<?php

use Laramongo\Nas\Asset;

class nasAssetTest extends TestCase {

    public function setUp()
    {
        parent::setUp();

        Config::set('s3.base_url','S3/');
        Config::set('s3.enable',true);
    }

    public function testShouldGetUrlStatically()
    {
        $url = 'path/to/asset.jpg';

        // Assert if the url contain the S3 root
        $this->assertEquals('S3/'.$url, Asset::url($url));

        // Asserts if the url doens't contain the S3 root if
        // it's not enabled (for dev environment for ex:)
        Config::set('s3.enable',false);
        $this->assertEquals($url, Asset::url($url));
    }

    public function testShouldGetUrl()
    {
        $asset = new Asset;

        $url = 'path/to/asset.jpg';

        // Assert if the url contain the S3 root
        $this->assertEquals('S3/'.$url, $asset->urlTo($url));

        // Asserts if the url doens't contain the S3 root if
        // it's not enabled (for dev environment for ex:)
        Config::set('s3.enable',false);
        $this->assertEquals($url, $asset->urlTo($url));

    }

}
