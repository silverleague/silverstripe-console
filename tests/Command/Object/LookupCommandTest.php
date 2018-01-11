<?php

namespace SilverLeague\Console\Tests\Command\Object;

use Monolog\Logger;
use Psr\Log\LoggerInterface;
use SilverLeague\Console\Tests\Command\AbstractCommandTest;
use SilverStripe\ORM\DataObject;

/**
 * @coversDefaultClass \SilverLeague\Console\Command\Object\LookupCommand
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class LookupCommandTest extends AbstractCommandTest
{
    protected function getTestCommand()
    {
        return 'object:lookup';
    }

    /**
     * Ensure that the Injector's class resolution is returned for a given Object
     *
     * @covers ::execute
     */
    public function testExecuteWithRegularDependency()
    {
        $tester = $this->executeTest(['object' => LoggerInterface::class]);
        $output = $tester->getDisplay();
        $this->assertContains(Logger::class, $output);
    }

    public function testExecuteWithSilverStripeClass()
    {
        $tester = $this->executeTest(['object' => DataObject::class]);
        $output = $tester->getDisplay();
        $this->assertContains(DataObject::class, $output);
        $this->assertContains('silverstripe/framework', $output);
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
