<?php

namespace SilverLeague\Console\Framework;

use Symfony\Component\Console\Application;

/**
 * The application scaffolder
 *
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class Scaffold
{
    /**
     * The application name
     * @var string
     */
    const APPLICATION_NAME = 'SilverStripe Console';

    /**
     * The application version (semver)
     * @var string
     */
    const APPLICATION_VERSION = '0.1.0';

    /**
     * The Symfony console application
     * @var Application
     */
    protected $application;

    /**
     * The SilverStripe Loader class
     * @var SilverStripeLoader
     */

    /**
     * Instantiate the console Application
     */
    public function __construct()
    {
        $this->setApplication(new Application);
        $this->setLoader(new SilverStripeLoader);
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
     * Set the Application into the scaffold
     *
     * @param  Application $application
     * @return self
     */
    public function setApplication(Application $application)
    {
        $this->application = $application;
        return $this;
    }

    /**
     * Get the Symfony console Application
     *
     * @return Application
     */
    public function getApplication()
    {
        return $this->application;
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
        $this->getApplication()->setVersion(self::APPLICATION_VERSION);

        foreach ($this->getLoader()->getTasks() as $task) {
            // $this->getApplication()->add($)
            var_dump(($task));
        }

        return $this;
    }
}
