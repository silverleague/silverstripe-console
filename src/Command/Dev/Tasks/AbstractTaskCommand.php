<?php

namespace SilverLeague\Console\Command\Dev\Tasks;

use SilverLeague\Console\Command\SilverStripeCommand;
use SilverStripe\Dev\BuildTask;

/**
 * Not an actual abstract class, but used by the Command Factory to create Commands from BuildTasks
 *
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class AbstractTaskCommand extends SilverStripeCommand
{
    /**
     * Contains the SilverStripe BuildTask used for this command, if imported from SilverStripe
     *
     * @var BuildTask
     */
    protected $buildTask;

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
}
