<?php

namespace SilverLeague\Console\Tests\Command;

use SilverLeague\Console\Command\Dev\Tasks\AbstractTaskCommand;
use SilverLeague\Console\Command\Factory;
use SilverLeague\Console\Framework\Scaffold;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Core\Config\Config;
use SilverStripe\Dev\Tasks\CleanupTestDatabasesTask;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @coversDefaultClass \SilverLeague\Console\Command\Factory
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class FactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that a Symfony command is bootstrapped and returned when a SilverStripe BuildTask is given
     */
    public function testGetCommandFromTask()
    {
        $task = new CleanupTestDatabasesTask;

        $command = $this->getFactory()->getCommandFromTask($task);
        $this->assertInstanceOf(AbstractTaskCommand::class, $command);
        $this->assertInstanceOf(Application::class, $command->getApplication());
        $this->assertSame($task, $command->getTask());
        $this->assertSame($task->getTitle(), $command->getDescription());
    }

    /**
     * Test that a SilverStrip task name is converted to a Symfony friendly command name
     *
     * @param string $input
     * @param string $expected
     * @dataProvider commandNameProvider
     */
    public function testGetCommandName($input, $expected)
    {
        Config::nest();

        $fakeTask = new CleanupTestDatabasesTask;
        Config::modify()->set(get_class($fakeTask), 'segment', $input);
        $this->assertSame($expected, $this->getFactory()->getCommandName($fakeTask));

        Config::unnest();
    }

    /**
     * @return array[]
     */
    public function commandNameProvider()
    {
        return [
            ['CleanupTestDatabasesTask', 'dev:tasks:cleanup-test-databases'],
            ['FooBar', 'dev:tasks:foo-bar'],
            ['FooBarTask', 'dev:tasks:foo-bar']
        ];
    }

    /**
     * Ensure that the BuildTask functionality can be returned as a closure for the Command
     */
    public function testGetTaskAsClosure()
    {
        $taskMock = $this
            ->getMockBuilder(CleanupTestDatabasesTask::class)
            ->setMethods(['run'])
            ->getMock();

        $taskMock
            ->expects($this->once())
            ->method('run')
            ->with($this->isInstanceOf(HTTPRequest::class));

        $factory = $this->getFactory();
        $command = $factory->getCommandFromTask($taskMock);
        $this->assertTrue(is_callable($factory->getTaskAsClosure($command)));

        $tester = new CommandTester($command);
        $tester->execute([]);
    }

    /**
     * Ensure that SilverStripe task URL segments are made "friendly"
     *
     * @param string $input
     * @param string $expected
     * @dataProvider segmentProvider
     */
    public function testGetFriendlySegment($input, $expected)
    {
        $this->assertSame($expected, $this->getFactory()->getFriendlySegment($input));
    }

    /**
     * @return array[]
     */
    public function segmentProvider()
    {
        return [
            ['SomeTask', 'some'],
            ['Some\\Namespaced\\TaskName', 'some-namespaced-task-name'],
            ['CleanupTestDatabasesTask', 'cleanup-test-databases'],
        ];
    }

    /**
     * Get a Factory to test with
     *
     * @return Factory
     */
    protected function getFactory()
    {
        return (new Scaffold)->getSilverStripeLoader()->getCommandFactory();
    }
}
