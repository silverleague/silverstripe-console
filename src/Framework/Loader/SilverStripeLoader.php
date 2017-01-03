<?php

namespace SilverLeague\Console\Framework\Loader;

use SilverLeague\Console\Command\Factory;
use SilverLeague\Console\Framework\ConsoleBase;
use SilverStripe\Core\ClassInfo;
use SilverStripe\Core\Injector\Injector;

/**
 * The class responsible for loading/instantiating SilverStripe and accessing its class hierarchy, etc
 *
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class SilverStripeLoader extends ConsoleBase
{
    /**
     * Return a set of Tasks from SilverStripe
     *
     * @return array
     */
    public function getTasks()
    {
        $commands = [];
        $tasks = ClassInfo::subclassesFor('SilverStripe\\Dev\\BuildTask');

        // Remove the BuildTask itself
        array_shift($tasks);

        foreach ($tasks as $taskClass) {
            $task = Injector::inst()->get($taskClass);
            $commands[] = $this->getCommandFactory()->getCommandFromTask($task);
        }

        return $commands;
    }

    /**
     * Get a new command factory instance to generate the console Command
     *
     * @return Factory
     */
    public function getCommandFactory()
    {
        return new Factory($this->getApplication());
    }
}
