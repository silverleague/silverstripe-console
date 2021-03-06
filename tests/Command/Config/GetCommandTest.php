<?php

namespace SilverLeague\Console\Tests\Command\Config;

use SilverLeague\Console\Tests\Command\AbstractCommandTest;
use SilverStripe\ORM\DataObject;

/**
 * @coversDefaultClass \SilverLeague\Console\Command\Config\GetCommand
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class GetCommandTest extends AbstractCommandTest
{
    protected function getTestCommand()
    {
        return 'config:get';
    }

    /**
     * Ensure that the class and property arguments are required
     *
     * @covers ::configure
     */
    public function testConfigure()
    {
        $this->assertTrue($this->command->getDefinition()->getArgument('class')->isRequired());
        $this->assertTrue($this->command->getDefinition()->getArgument('property')->isRequired());
    }

    /**
     * Ensure a successful execution returns the value of the config property
     *
     * @covers ::execute
     */
    public function testExecute()
    {
        $result = $this->executeTest(['class' => DataObject::class, 'property' => 'fixed_fields'])->getDisplay();
        $this->assertContains('ClassName', $result);
    }

    /**
     * Ensure that null is returned for a non-existent value
     *
     * @covers ::execute
     */
    public function testNonExistentValue()
    {
        $result = $this->executeTest(['class' => 'Moegli', 'property' => 'penny_farthing'])->getDisplay();
        $this->assertContains('NULL', $result);
    }
}
