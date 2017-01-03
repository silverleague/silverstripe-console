<?php

namespace SilverLeague\Console\Tests\Framework\Loader;

use SilverLeague\Console\Command\Factory;
use SilverLeague\Console\Framework\Loader\SilverStripeLoader;
use SilverLeague\Console\Framework\Scaffold;
use Symfony\Component\Console\Command\Command;

/**
 * @coversDefaultClass \SilverLeague\Console\Framework\Loader\SilverStripeLoader
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class SilverStripeLoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that the Command Factory is returned, and that it is correctly Application aware
     *
     * @covers ::getCommandFactory
     * @covers \SilverLeague\Console\Framework\ConsoleBase
     */
    public function testGetCommandFactory()
    {
        $scaffold = new Scaffold;

        $factory = $scaffold
            ->getSilverStripeLoader()
            ->getCommandFactory();

        $this->assertInstanceOf(Factory::class, $factory);
        $this->assertSame($factory->getApplication(), $scaffold->getApplication());
    }

    /**
     * Test that SilverStripe BuildTasks can be retrieved as bootstrapped Commands
     *
     * @covers ::getTasks
     * @covers ::getCommandFactory
     */
    public function testGetTasksAsCommands()
    {
        $application = new Scaffold;
        $loader = $application->getSilverStripeLoader();

        $commands = $loader->getTasks();
        $this->assertInternalType('array', $commands);
        $this->assertNotEmpty($commands); // Framework ships with a couple
        foreach ($commands as $command) {
            $this->assertInstanceOf(Command::class, $command);
        }
    }
}
