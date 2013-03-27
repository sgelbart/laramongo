<?php

use Laramongo\Nas\S3;
use Mockery as m;

class nasS3Test extends TestCase {
    
    public function setUp()
    {
        parent::setUp();

        Config::set('s3.aws_access_key','222');
        Config::set('s3.aws_secret_key','111');
    }

    public function tearDown()
    {
        m::close();

        parent::tearDown();
    }

    public function testShouldSendFile()
    {
        $fileToUpload = 'assets/img/loading.gif';
        $expectedFullFilename = app()->path.'/../public/'.$fileToUpload;

        $oyatelS3 = m::mock('OyatelS3');

        // oyatelS3 object should run inputFile with expected full filename
        $oyatelS3
            ->shouldReceive('inputFile')
            ->with($expectedFullFilename)
            ->andReturn('fileObj');

        // oyatelS3 object should run puObject with those params
        $oyatelS3
            ->shouldReceive('putObject')
            ->with('fileObj',Config::get('s3.bucket'),$fileToUpload,\S3::ACL_PUBLIC_READ)
            ->andReturn(true);

        // Create the S3 instance
        $s3 = new S3;

        // Set it to use our mock ;)
        $s3->oyatelS3 = $oyatelS3;

        // Assert if the sendFile returns true
        $this->assertTrue($s3->sendFile($fileToUpload));
    }
}
