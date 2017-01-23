<?php

namespace SilverLeague\Console\Tests\Command\Config;

use SilverLeague\Console\Tests\Command\AbstractCommandTest;
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
        $this->assertTrue($this->command->getDefinition()->hasArgument('type'));
        $this->assertTrue($this->command->getDefinition()->hasOption('filter'));
    }

    /**
     * Ensure that passing an invalid "type" argument throws an exception
     *
     * @covers ::execute
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage foo is not a valid config type, options: all, yaml, static, overrides
     */
    public function testInvalidTypeThrowsException()
    {
        $this->executeTest(['type' => 'foo']);
    }

    /**
     * Test that the command can successfully be executed
     *
     * @covers ::execute
     */
    public function testExecute()
    {
        $result = $this->executeTest()->getDisplay();
        $this->assertContains('SilverStripe\\Control\\Director', $result);
        $this->assertContains('SilverStripe\\Core\\Injector\\Injector', $result);
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
        $this->assertContains('SilverStripe\\Control\\Director', $result);
        $this->assertNotContains('SilverStripe\\Core\\Injector\\Injector', $result);
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
     * Test that the source data can be set to YAML, static, overrides or "all"
     *
     * @covers \SilverLeague\Console\Command\Config\AbstractConfigCommand::getConfig
     * @covers \SilverLeague\Console\Command\Config\AbstractConfigCommand::getPropertyValue
     * @covers \SilverLeague\Console\Command\Config\AbstractConfigCommand::getStaticConfig
     * @covers \SilverLeague\Console\Command\Config\AbstractConfigCommand::getConfigOverrides
     * @covers \SilverLeague\Console\Command\Config\AbstractConfigCommand::getYamlConfig
     * @covers ::getMergedData
     */
    public function testAllDataContainsBothYamlAndStatic()
    {
        Config::inst()->update('Gorilla', 'warfare', 'magpie fairy bread');
        $result = $this->executeTest(['type' => 'all'])->getDisplay();
        $this->assertContains('has_one', $result);
        $this->assertContains('%$DisplayErrorHandler', $result);
        $this->assertContains('magpie fairy bread', $result);
    }

    /**
     * Ensure that the configuration source can be set to only YAML file data
     *
     * @covers \SilverLeague\Console\Command\Config\AbstractConfigCommand::getYamlConfig
     */
    public function testGetOnlyYamlConfiguration()
    {
        $result = $this->executeTest(['type' => 'yaml'])->getDisplay();
        $this->assertNotContains('has_one', $result);
        $this->assertContains('%$DisplayErrorHandler', $result);
    }

    /**
     * Ensure that the configuration source can be set to only private statics
     *
     * @covers \SilverLeague\Console\Command\Config\AbstractConfigCommand::getStaticConfig
     */
    public function testGetOnlyStaticConfiguration()
    {
        $result = $this->executeTest(['type' => 'static'])->getDisplay();
        $this->assertContains('has_one', $result);
        $this->assertNotContains('%$DisplayErrorHandler', $result);
    }

    /**
     * Test that override configuration only can be returned
     *
     * @covers \SilverLeague\Console\Command\Config\AbstractConfigCommand::getConfigOverrides
     */
    public function testGetOnlyOverrideConfiguration()
    {
        Config::inst()->update('Bookcase', 'dresser', 'drawers');
        $result = $this->executeTest(['type' => 'overrides'])->getDisplay();
        $this->assertContains('Bookcase', $result);
        $this->assertNotContains('Injector', $result);
    }

    /**
     * Test that the "all" type is treated the same as not providing one (i.e. default)
     *
     * @covers ::getSourceData
     */
    public function testAllIsDefaultType()
    {
        $typeAll = $this->executeTest(['type' => 'all'])->getDisplay();
        $typeNone = $this->executeTest()->getDisplay();

        $this->assertSame($typeAll, $typeNone);
    }

    /**
     * Ensure that numeric property keys are replaced with nada
     *
     * @covers ::getParsedOutput
     */
    public function testNumericKeysAreNotShown()
    {
        Config::inst()->update('FooBar', 'my_property', [1 => 'baz', 'bar' => 'banter']);
        $result = $this->executeTest(['type' => 'overrides', '--filter' => 'FooBar'])->getDisplay();
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
        Config::inst()->update('HeatingSupplies', 'brands', $input);
        $result = $this->executeTest()->getDisplay();
        $this->assertContains('"great": "Never-B-Cold",', $result);
    }

    /**
     * While this behaviour is not desireable, it is what it is. For now, test that the private statics
     * are gathered from children of Object only.
     *
     * @covers \SilverLeague\Console\Command\Config\AbstractConfigCommand::getStaticConfig
     */
    public function testFindStaticsForSubclassesOfObjectOnly()
    {
        $result = $this->executeTest(['type' => 'static'])->getDisplay();
        $this->assertNotContains(
            'Injector',
            $result,
            'Injector has a private static property, but does not extend Object - should not be displayed'
        );
        $this->assertContains('SilverStripe\\ORM\\DataObject', $result, 'DataObject should definitely be displayed.');
    }

    /**
     * Ensure that the ConfigManifest is returned
     *
     * @covers \SilverLeague\Console\Command\Config\AbstractConfigCommand::getConfigManifest
     */
    public function testGetConfigManifest()
    {
        $result = $this->command->getConfigManifest();
        $this->assertInstanceOf('SilverStripe\\Core\\Manifest\\ConfigManifest', $result);
    }
}
