<?php

use Zizaco\FactoryMuff\Facade\FactoryMuff as f;

class DelayedTaskTest extends Zizaco\TestCases\TestCase
{
    use TestHelper;

    /**
     * MongoDB
     */
    protected $db;
    
    /**
     * Clean delayedTasks collection
     */
    public function setUp()
    {
        parent::setUp();
        $this->cleanCollection( 'temp_delayedTasks' );

        $connection = new Zizaco\Mongolid\MongoDbConnector;
        $database = Config::get('database.mongodb.default.database');
        $db = $connection->getConnection()->$database;
        $db->temp_delayedTasks->drop();

        $this->db = $db;

        Config::set('queue.default','sync');
    }

    public function testShouldProcess()
    {
        $task = new DelayedTask;
        $task->name = 'Sometask';
        $this->assertFalse($task->isDone());

        $task->process();
        $this->assertTrue($task->isDone());
    }

    public function testShouldProcessOnSave()
    {
        $task = new DelayedTask;
        $task->name = 'Sometask';
        $this->assertFalse($task->isDone());
        
        $task->save();

        // Get from database
        $task = DelayedTask::first($task->_id);
        $this->assertTrue($task->isDone());
    }

    public function testShouldBeProcessedByJob()
    {
        $this->db->temp_delayedTasks->insert(['name'=>'Sometask', 'tries'=>0]);

        Queue::push('ProcessDelayedTasks');

        $task = DelayedTask::first(['name'=>'Sometask']);
        $this->assertTrue($task->isDone());
    }

    public function testShouldNotBeProcessedAfterFiveFails()
    {
        $this->db->temp_delayedTasks->insert(['name'=>'lol', 'tries'=>0]);

        // Try to process the task 50 times.
        for ($i=0; $i < 50; $i++) { 

            $task = DelayedTask::first(['name'=>'lol']);

            // Checks if $tries attribute is being incremented
            if($task->tries < 5)
            {
                $this->assertEquals($i, $task->tries);
            }

            // Unset done. (this way the task will be processed again unless the tries reach its limit)
            unset($task->done);
            $task->save();

            Queue::push('ProcessDelayedTasks');
        }

        // Should not try more than 5 times
        $this->assertLessThan(6, $task->tries);
    }

    public function testShouldPolymorph()
    {
        $task = new DelayedTask;
        $task->name = 'Sometask';
        $task->type = 'import';
        $task = $task->polymorph( $task );

        $this->assertTrue($task instanceOf Import);
    }
}
