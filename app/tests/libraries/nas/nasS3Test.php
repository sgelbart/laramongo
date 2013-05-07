<?php

use Laramongo\Nas\S3;
use Mockery as m;

class nasS3Test extends Zizaco\TestCases\TestCase {
    
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
        $expectedFullFilename = Config::get('s3.base_local_path').$fileToUpload;

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

    public function testShouldUploadDirectoryRecursively()
    {
        $dirToUpload['normal'] =      'assets/img/';
        $dirToUpload['alternative'] = 'assets/img';

        $oyatelS3 = m::mock('OyatelS3');

        // oyatelS3 object should run inputFile with expected full filename
        $oyatelS3
            ->shouldReceive('inputFile')
            ->andReturn('fileObj')
            ->atLeast()->times(4); // Should be called more than 1 times each time

        // oyatelS3 object should run puObject with those params
        $oyatelS3
            ->shouldReceive('putObject')
            ->andReturn(true)
            ->atLeast()->times(4); // Should be called more than 1 times each time

        // Create the S3 instance
        $s3 = new S3;

        // Set it to use our mock ;)
        $s3->oyatelS3 = $oyatelS3;

        // Assert if the send returns true (2x that's why the mocked methods are set to 4 times)
        $this->assertTrue($s3->send($dirToUpload['normal']));
        $this->assertTrue($s3->send($dirToUpload['alternative']));
    }
}
