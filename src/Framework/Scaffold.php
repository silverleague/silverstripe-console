<?php

namespace SilverLeague\Console\Framework;

use SilverLeague\Console\Framework\Loader\ConfigurationLoader;
use SilverLeague\Console\Framework\Loader\SilverStripeLoader;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputOption;

/**
 * The application scaffolder
 *
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class Scaffold extends ConsoleBase
{
    /**
     * The application name
     * @var string
     */
    const APPLICATION_NAME = <<<NAME
   _____ __             ______      _            _____                   __
  / __(_) /  _____ ____/ __/ /_____(_)__  ___   / ___/__  ___  ___ ___  / /__
 _\ \/ / / |/ / -_) __/\ \/ __/ __/ / _ \/ -_) / /__/ _ \/ _ \(_-</ _ \/ / -_)
/___/_/_/|___/\__/_/ /___/\__/_/ /_/ .__/\__/  \___/\___/_//_/___/\___/_/\__/
                                  /_/

NAME;

    /**
     * The application version (semver)
     * @var string
     */
    const APPLICATION_VERSION = '1.0.0';

    /**
     * The SilverStripe Loader class
     * @var SilverStripeLoader
     */
    protected $silverStripeLoader;

    /**
     * The Configuration Loader class
     * @var ConfigurationLoader
     */
    protected $configurationLoader;

    /**
     * The application configuration
     *
     * @var array
     */
    protected $configuration;

    /**
     * Instantiate the console Application
     */
    public function __construct()
    {
        parent::__construct(new Application);

        // Handle native SilverStripe flushing
        if (in_array('--flush', $_SERVER['argv'])) {
            $_GET['flush'] = 1;
        }

        $this->bootstrap();
        $this->setSilverStripeLoader(new SilverStripeLoader($this->getApplication()));
        $this->setConfigurationLoader(new ConfigurationLoader($this->getApplication()));
        $this->scaffoldApplication();
    }

    /**
     * Run the console Application
     *
     * @see Application::run
     * @return int Error code, or zero if successful
     */
    public function run()
    {
        return $this->getApplication()->run();
    }

    /**
     * Set the SilverStripeLoader
     *
     * @param  SilverStripeLoader $loader
     * @return $this
     */
    public function setSilverStripeLoader(SilverStripeLoader $loader)
    {
        $this->silverStripeLoader = $loader;
        return $this;
    }

    /**
     * Get the SilverStripeLoader
     *
     * @return SilverStripeLoader
     */
    public function getSilverStripeLoader()
    {
        return $this->silverStripeLoader;
    }

    /**
     * Get the console application's configuration
     *
     * @return array
     */
    public function getConfiguration()
    {
        if (is_null($this->configuration)) {
            $this->setConfiguration($this->getConfigurationLoader()->load());
        }
        return $this->configuration;
    }

    /**
     * Set the console application's configuration
     *
     * @param  array $configuration
     * @return $this
     */
    public function setConfiguration(array $configuration)
    {
        $this->configuration = $configuration;
        return $this;
    }

    /**
     * Get the configuration loader class
     *
     * @return ConfigurationLoader
     */
    public function getConfigurationLoader()
    {
        return $this->configurationLoader;
    }

    /**
     * Set the configuration loader class
     *
     * @param  ConfigurationLoader
     * @return $this
     */
    public function setConfigurationLoader(ConfigurationLoader $loader)
    {
        $this->configurationLoader = $loader;
        return $this;
    }

    /**
     * Call the SilverStripe bootstrap class
     *
     * @return $this
     */
    protected function bootstrap()
    {
        (new Bootstrap)->initialize();
        return $this;
    }

    /**
     * Scaffold the Application, including adding all requires commands and configuration
     *
     * @return $this
     */
    protected function scaffoldApplication()
    {
        $this->getApplication()->setName(self::APPLICATION_NAME);
        $this->getApplication()->setVersion('Version ' . self::APPLICATION_VERSION);

        $this->getApplication()->getDefinition()->addOption(
            new InputOption(
                'flush',
                'f',
                null,
                'Flush SilverStripe cache and manifest'
            )
        );

        $this->addCommands();

        return $this;
    }

    /**
     * Adds all automatically created BuildTask Commands, and all concrete Commands from configuration
     *
     * @return $this
     */
    protected function addCommands()
    {
        foreach ($this->getSilverStripeLoader()->getTasks() as $command) {
            $this->getApplication()->add($command);
        }

        foreach ($this->getConfiguration()['Commands'] as $commandClass) {
            $this->getApplication()->add(new $commandClass);
        }

        return $this;
    }
}
