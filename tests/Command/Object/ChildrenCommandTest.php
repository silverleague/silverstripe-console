<?php

namespace SilverLeague\Console\Tests\Command\Object;

use SilverLeague\Console\Tests\Command\AbstractCommandTest;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @coversDefaultClass \SilverLeague\Console\Command\Object\ChildrenCommand
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class ChildrenCommandTest extends AbstractCommandTest
{
    /**
     * {@inheritDoc}
     */
    public function getTestCommand()
    {
        return 'object:children';
    }

    /**
     * Ensure that the Injector's class resolution is returned for a given Object
     *
     * @covers ::execute
     */
    public function testExecute()
    {
        $tester = new CommandTester($this->command);
        $tester->execute(
            [
                'command' => $this->command->getName(),
                'object'  => "SilverStripe\Dev\BuildTask"
            ]
        );

        $output = $tester->getDisplay();
        $this->assertContains("SilverStripe\Dev\Tasks\CleanupTestDatabasesTask", $output);
    }

    /**
     * Ensure that the InputArgument for the object is added
     *
     * @covers ::configure
     */
    public function testConfigure()
    {
        $this->assertTrue($this->command->getDefinition()->hasArgument('object'));
    }
}
