<?php

namespace SilverLeague\Console\Command;

use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Dev\BuildTask;
use Symfony\Component\Console\Command\Command;

/**
 * A slightly embellished Symfony Command class which is SilverStripe aware
 *
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class SilverStripeCommand extends Command
{
    /**
     * Contains the SilverStripe BuildTask used for this command, if imported from SilverStripe
     *
     * @var BuildTask
     */
    protected $buildTask;

    /**
     * Add a set of default SilverStripe options to all commands
     *
     * {@inheritDoc}
     *
     * @param string $name
     */
    public function __construct($name = null)
    {
        parent::__construct($name);

        $this->addOption('flush', 'f', null, 'Flush SilverStripe cache and manifest.');
    }

    /**
     * Get the original SilverStrpe BuildTask used
     *
     * @return BuildTask
     */
    public function getTask()
    {
        return $this->buildTask;
    }

    /**
     * Set the original SilverStripe BuildTask used
     *
     * @param  BuildTask $task
     * @return self
     */
    public function setTask(BuildTask $task)
    {
        $this->buildTask = $task;
        return $this;
    }

    /**
     * Get the SilverStripe Injector
     *
     * @return Injector
     */
    public function getInjector()
    {
        return Injector::inst();
    }

    /**
     * Get the configuration API handler
     *
     * @return Config
     */
    public function getConfig()
    {
        return Config::inst();
    }
}
