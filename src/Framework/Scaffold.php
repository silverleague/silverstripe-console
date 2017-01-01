<?php

namespace SilverLeague\Console\Framework;

use Symfony\Component\Console\Application;

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
     _____ __             __                            _____                   __
    / __(_) /  _____ ____/ /  ___ ___ ____ ___ _____   / ___/__  ___  ___ ___  / /__
   _\ \/ / / |/ / -_) __/ /__/ -_) _ `/ _ `/ // / -_) / /__/ _ \/ _ \(_-</ _ \/ / -_)
  /___/_/_/|___/\__/_/ /____/\__/\_,_/\_, /\_,_/\__/  \___/\___/_//_/___/\___/_/\__/
                                     /___/

NAME;

    /**
     * The application version (semver)
     * @var string
     */
    const APPLICATION_VERSION = '0.1.0';

    /**
     * The SilverStripe Loader class
     * @var SilverStripeLoader
     */
    protected $loader;

    /**
     * Instantiate the console Application
     */
    public function __construct()
    {
        parent::__construct(new Application);
        $this->setLoader(new SilverStripeLoader($this->getApplication()));
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
     * @return self
     */
    public function setLoader(SilverStripeLoader $loader)
    {
        $this->loader = $loader;
        return $this;
    }

    /**
     * Get the SilverStripeLoader
     *
     * @return SilverStripeLoader
     */
    public function getLoader()
    {
        return $this->loader;
    }

    /**
     * Scaffold the Application, including adding all requires commands and configuration
     *
     * @return self
     */
    protected function scaffoldApplication()
    {
        $this->getApplication()->setName(self::APPLICATION_NAME);
        $this->getApplication()->setVersion('Version ' . self::APPLICATION_VERSION);

        foreach ($this->getLoader()->getTasks() as $command) {
            $this->getApplication()->add($command);
        }

        return $this;
    }
}
