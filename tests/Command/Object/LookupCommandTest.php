<?php

namespace SilverLeague\Console\Tests\Command\Object;

use SilverLeague\Console\Tests\Command\AbstractCommandTest;

/**
 * @coversDefaultClass \SilverLeague\Console\Command\Object\LookupCommand
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class LookupCommandTest extends AbstractCommandTest
{
    /**
     * {@inheritDoc}
     */
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
        $tester = $this->executeTest(['object' => 'Logger']);
        $output = $tester->getDisplay();
        $this->assertContains("Monolog\Logger", $output);
    }

    public function testExecuteWithSilverStripeClass()
    {
        $tester = $this->executeTest(['object' => "SilverStripe\ORM\DataObject"]);
        $output = $tester->getDisplay();
        $this->assertContains("SilverStripe\ORM\DataObject", $output);
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
