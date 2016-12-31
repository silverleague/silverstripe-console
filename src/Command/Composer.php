<?php

namespace SilverLeague\Console\Command;

use SilverStripe\Dev\BuildTask;
use Symfony\Component\Console\Command\Command;

/**
 * The Command Composer class is reponsible for converting a SilverStripe BuildTask into a Symfony Console Command
 *
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class Composer
{
    public function getCommandFromTask(BuildTask $task)
    {
        if (!is_callable([$task, 'run'])) {
            return false;
        }

        $name = sprintf('dev:tasks:test');//, strtolower($task->getTitle()));
        $command = new Command($name);

        return $command;
    }
}
