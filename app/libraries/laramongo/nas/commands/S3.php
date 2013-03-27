<?php namespace Laramongo\Nas\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class S3 extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 's3:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send assets to S3';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $bucket = \Config::get('s3.bucket');
        $endpoint = \Config::get('s3.endpoint');

        $this->info("Sending assets to S3 bucket...");
        $this->comment("Destination $endpoint(endpoint) $bucket(bucket)");
        $this->comment("...");
        $s3 = new \Laramongo\Nas\S3;
        if($s3->send('assets/'))
        {
            $this->info("Assets were sent to S3 successfully.");
        }
        else
        {
            $this->error("Error while sending assets to S3.");   
        }
    }
}
