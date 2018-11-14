<?php

namespace SilverLeague\Console\Framework\Loader;

use ReflectionClass;
use SilverLeague\Console\Command\Factory;
use SilverLeague\Console\Framework\ConsoleBase;
use SilverStripe\Core\ClassInfo;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Dev\BuildTask;

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
        $tasks = ClassInfo::subclassesFor(BuildTask::class);

        // Remove the BuildTask itself
        array_shift($tasks);

        foreach ($tasks as $taskClass) {
            if ($this->taskIsAbstract($taskClass)) {
                continue;
            }

            $task = Injector::inst()->get($taskClass);
            if ($command = $this->getCommandFactory()->getCommandFromTask($task)) {
                $commands[] = $command;
            }
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

    /**
     * Check whether the given task class is abstract
     *
     * @param string $className
     * @return bool
     */
    protected function taskIsAbstract($className)
    {
        $reflection = new ReflectionClass($className);
        return $reflection->isAbstract();
    }
}
