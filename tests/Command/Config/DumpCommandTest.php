<?php

namespace SilverLeague\Console\Tests\Command\Config;

use SilverLeague\Console\Tests\Command\AbstractCommandTest;
use SilverStripe\Config\Collections\ConfigCollectionInterface;
use SilverStripe\Core\Config\Config;

/**
 * @coversDefaultClass \SilverLeague\Console\Command\Config\DumpCommand
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class DumpCommandTest extends AbstractCommandTest
{
    /**
     * {@inheritDoc}
     */
    protected function getTestCommand()
    {
        return 'config:dump';
    }

    /**
     * Ensure that the InputOptions exist
     *
     * @covers ::configure
     */
    public function testConfigure()
    {
        $this->assertTrue($this->command->getDefinition()->hasOption('filter'));
    }

    /**
     * Test that the command can successfully be executed
     *
     * @covers ::execute
     */
    public function testExecute()
    {
        $result = $this->executeTest()->getDisplay();
        $this->assertContains('silverstripe\\control\\director', $result);
        $this->assertContains('silverstripe\\core\\injector\\injector', $result);
    }

    /**
     * Test that the results can be filtered
     *
     * @covers ::execute
     * @covers ::filterOutput
     */
    public function testExecuteWithFilteredResults()
    {
        $result = $this->executeTest(['--filter' => 'SilverStripe\\Control\\Director'])->getDisplay();
        $this->assertContains('silverstripe\\control\\director', $result);
        $this->assertNotContains('silverstripe\\core\\injector\\injector', $result);
    }

    /**
     * Ensure that the filter is applied to any column of the data
     *
     * @covers ::filterOutput
     */
    public function testFilterOnAnyColumn()
    {
        $result = $this->executeTest(['--filter' => '%$DisplayErrorHandler'])->getDisplay();
        $this->assertContains('pushHandler', $result);

        $result = $this->executeTest(['--filter' => 'pushHandler'])->getDisplay();
        $this->assertContains('%$DisplayErrorHandler', $result);
    }

    /**
     * Ensure that numeric property keys are replaced with nada
     *
     * @covers ::getParsedOutput
     */
    public function testNumericKeysAreNotShown()
    {
        Config::modify()->set('FooBar', 'my_property', [1 => 'baz', 'bar' => 'banter']);
        $result = $this->executeTest(['--filter' => 'FooBar'])->getDisplay();
        $this->assertNotContains('1', $result);
        $this->assertContains('bar', $result);
    }

    /**
     * Ensure that nested array values for properties are displayed as JSON. Since it crosses multiple lines,
     * we can't assert it exactly.
     *
     * @covers ::getParsedOutput
     */
    public function testNestedArrayValuesAreDisplayedAsJson()
    {
        $input = ['brands' => ['good' => 'Heatings R Us', 'great' => 'Never-B-Cold', 'best' => 'Luv-Fyre']];
        Config::modify()->set('HeatingSupplies', 'brands', $input);
        $result = $this->executeTest()->getDisplay();
        $this->assertContains('"great": "Never-B-Cold",', $result);
    }

    /**
     * Ensure that a ConfigCollectionInterface is returned
     *
     * @covers \SilverLeague\Console\Command\Config\AbstractConfigCommand::getConfig
     */
    public function testGetConfigCollectionInterface()
    {
        $result = $this->command->getConfig();
        $this->assertInstanceOf(ConfigCollectionInterface::class, $result);
    }
}
