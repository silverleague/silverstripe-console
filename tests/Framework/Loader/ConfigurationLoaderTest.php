<?php

namespace SilverLeague\Console\Tests\Framework\Loader;

use SilverLeague\Console\Framework\Loader\ConfigurationLoader;
use SilverLeague\Console\Framework\Scaffold;

/**
 * @coversDefaultClass \SilverLeague\Console\Framework\Loader\ConfigurationLoader
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class ConfigurationLoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The test subject
     *
     * @var ConfigurationLoader
     */
    protected $configuration;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        $this->configuration = (new Scaffold)->getConfigurationLoader();
    }

    /**
     * Test that the full file path is returned
     *
     * @covers ::getFilePath
     */
    public function testEnsureConfigurationFileExists()
    {
        $this->assertTrue(file_exists($this->configuration->getFilePath()));
    }

    /**
     * Test that the YAML configuration file is parsed into an array
     *
     * @covers ::load
     */
    public function testParseYamlConfiguration()
    {
        $configuration = $this->configuration->load();

        $this->assertInternalType('array', $configuration);
        $this->assertArrayHasKey('Commands', $configuration);
        $this->assertContains("SilverLeague\Console\Command\Dev\BuildCommand", $configuration['Commands']);
    }

    /**
     * Test that an exception is thrown if the configuration file does not exist
     *
     * @covers ::getFilePath
     * @expectedException RuntimeException
     * @expectedExceptionMessage The configuration YAML file does not exist!
     */
    public function testExceptionThrownIfFileDoesNotExist()
    {
        $mockLoader = $this
            ->getMockBuilder(ConfigurationLoader::class)
            ->disableOriginalConstructor()
            ->setMethods(['getFilePath'])
            ->getMock();

        $mockLoader
            ->expects($this->once())
            ->method('getFilePath')
            ->willReturn('/path/to/file/that/should/never/exist');

        $mockLoader->load();
    }
}
