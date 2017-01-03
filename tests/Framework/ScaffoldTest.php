<?php

namespace SilverLeague\Console\Tests\Framework;

use SilverLeague\Console\Command\SilverStripeCommand;
use SilverLeague\Console\Framework\Loader\ConfigurationLoader;
use SilverLeague\Console\Framework\Loader\SilverStripeLoader;
use SilverLeague\Console\Framework\Bootstrap;
use SilverLeague\Console\Framework\Scaffold;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputOption;

/**
 * @coversDefaultClass \SilverLeague\Console\Framework\Scaffold
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class ScaffoldTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The test subject
     * @var Scaffold
     */
    protected $scaffold;

    /**
     * Initiate the test subject
     */
    public function setUp()
    {
        $this->scaffold = new Scaffold;
    }

    /**
     * Test that a Symfony Console Application is created, as well as the SilverStripe and Configuration Loaders
     *
     * @covers \SilverLeague\Console\Framework\ConsoleBase
     * @covers ::setSilverStripeLoader
     * @covers ::getSilverStripeLoader
     * @covers ::setConfigurationLoader
     * @covers ::getConfigurationLoader
     */
    public function testConstructor()
    {
        $this->assertInstanceOf(Application::class, $this->scaffold->getApplication());
        $this->assertInstanceOf(SilverStripeLoader::class, $this->scaffold->getSilverStripeLoader());
        $this->assertInstanceOf(ConfigurationLoader::class, $this->scaffold->getConfigurationLoader());
    }

    /**
     * Test that YAML configuration is loaded from the ConfigurationLoader in array format (YAML ~2.7 is
     * locked into the SilverStripe framework)
     *
     * @covers ::setConfiguration
     * @covers ::getConfiguration
     * @covers ::getConfigurationLoader
     * @covers \SilverLeague\Console\Framework\Loader\ConfigurationLoader
     */
    public function testLoadConfiguration()
    {
        $config = $this->scaffold->getConfiguration();

        $this->assertInternalType('array', $config);
        $this->assertArrayHasKey('Commands', $config);
        $this->assertContains("SilverLeague\Console\Command\Dev\BuildCommand", $config['Commands']);
    }

    /**
     * Test that the bootstrap method was called, initiating Bootstrap which defines this constant
     *
     * @covers ::bootstrap
     */
    public function testBootstrapWasCalled()
    {
        $this->assertTrue(defined('SILVERSTRIPE_ROOT_DIR'));
    }

    /**
     * Test that the run method will run the Symfony Application
     *
     * @covers ::run
     */
    public function testRun()
    {
        $mockApplication = $this->getMockBuilder(Application::class)
            ->disableOriginalConstructor()
            ->setMethods(['run'])
            ->getMock();

        $mockApplication
            ->expects($this->once())
            ->method('run');

        $this->scaffold->setApplication($mockApplication);
        $this->scaffold->run();
    }

    /**
     * Test that scaffoldApplication sets the name and version of the SilverStripe Console application, adds
     * Commands and the "flush" InputOption
     *
     * @covers ::scaffoldApplication
     * @covers ::addCommands
     */
    public function testApplicationIsScaffolded()
    {
        $this->assertSame(Scaffold::APPLICATION_NAME, $this->scaffold->getApplication()->getName());
        $this->assertContains(Scaffold::APPLICATION_VERSION, $this->scaffold->getApplication()->getVersion());
        $this->assertInstanceOf(
            InputOption::class,
            $this->scaffold->getApplication()->getDefinition()->getOption('flush')
        );
        $this->assertInstanceOf(SilverStripeCommand::class, $this->scaffold->getApplication()->find('dev:build'));
    }
}
