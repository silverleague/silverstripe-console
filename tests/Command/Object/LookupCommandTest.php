<?php

namespace SilverLeague\Console\Tests\Command\Object;

use Monolog\Logger;
use Psr\Log\LoggerInterface;
use SilverLeague\Console\Tests\Command\AbstractCommandTest;
use SilverStripe\ORM\DataObject;

/**
 * @coversDefaultClass \SilverLeague\Console\Command\Injector\LookupCommand
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class LookupCommandTest extends AbstractCommandTest
{
    protected function getTestCommand()
    {
        return 'injector:lookup';
    }

    /**
     * Ensure that the Injector's class resolution is returned for a given Object
     *
     * @covers ::execute
     */
    public function testExecuteWithRegularDependency()
    {
        $tester = $this->executeTest(['className' => LoggerInterface::class]);
        $output = $tester->getDisplay();
        $this->assertContains(Logger::class, $output);
    }

    public function testExecuteWithSilverStripeClass()
    {
        $tester = $this->executeTest(['className' => DataObject::class]);
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
        $this->assertTrue($this->command->getDefinition()->hasArgument('className'));
    }
}
