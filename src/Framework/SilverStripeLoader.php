<?php

namespace SilverLeague\Console\Framework;

use SilverLeague\Console\Command\Composer;
use SilverStripe\Core\ClassInfo;
use SilverStripe\Core\Injector\Injector;
use Symfony\Component\Console\Application;

/**
 * The class responsible for loading/instantiating SilverStripe and accessing its class hierarchy, etc
 *
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class SilverStripeLoader
{
    protected $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * Return a set of Tasks from SilverStripe
     *
     * @return array
     */
    public function getTasks()
    {
        $commands = [];
        $tasks = ClassInfo::subclassesFor('SilverStripe\\Dev\\BuildTask');
        foreach ($tasks as $taskClass) {
            if ($taskClass === 'SilverStripe\\Dev\\BuildTask') {
                continue;
            }
            $task = Injector::inst()->get($taskClass);
            $commands[] = $this->getCommandComposer()->getCommandFromTask($task);
        }
        return $commands;
    }

    public function getCommandComposer()
    {
        return new Composer;
    }
}
