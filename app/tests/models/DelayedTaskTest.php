<?php

use Zizaco\FactoryMuff\Facade\FactoryMuff as f;

class DelayedTaskTest extends TestCase
{
    /**
     * Clean delayedTasks collection
     */
    public function setUp()
    {
        parent::setUp();
        $this->cleanCollection( 'delayedTasks' );

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
        $connection = new Zizaco\Mongolid\MongoDbConnector;
        $database = Config::get('lmongo::connections.default.database');
        $db = $connection->getConnection()->$database;
        $db->delayedTasks->insert(['name'=>'Sometask']);

        Queue::push('ProcessDelayedTasks');

        $task = DelayedTask::first(['name'=>'Sometask']);
        $this->assertTrue($task->isDone());
    }
}
