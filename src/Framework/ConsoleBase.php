<?php

namespace SilverLeague\Console\Framework;

use Symfony\Component\Console\Application;

/**
 * Forms the base for most Console classes to extend from. It is Application aware.
 *
 * @category silverstripe-console
 * @author   Robbie Averill <robbie@averill.co.nz>
 */
class ConsoleBase
{
    /**
     * The Symfony console application
     *
     * @var Application
     */
    protected $application;

    /**
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * Set the Application
     *
     * @param  Application $application
     * @return $this
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
}
