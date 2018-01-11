<?php

namespace SilverLeague\Console\Command;

use SilverLeague\Console\Command\Dev\Tasks\AbstractTaskCommand;
use SilverLeague\Console\Framework\ConsoleBase;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Core\Config\Config;
use SilverStripe\Dev\BuildTask;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * The Command Factory class is reponsible for converting a SilverStripe BuildTask into a Symfony Console Command
 *
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class Factory extends ConsoleBase
{
    /**
     * Given a BuildTask, convert it to a runnable Symfony Command
     *
     * @param  BuildTask $task
     * @return SilverStripeCommand|false
     */
    public function getCommandFromTask(BuildTask $task)
    {
        if (!is_callable([$task, 'run']) || !$task->isEnabled()) {
            return false;
        }

        $commandName = $this->getCommandName($task);
        if (empty($commandName)) {
            return false;
        }

        $command = new AbstractTaskCommand($commandName);
        $command->setApplication($this->getApplication());
        $command->setTask($task);
        $command->setDescription($task->getTitle());
        $command->setHelp($task->getDescription());
        $command->setCode($this->getTaskAsClosure($command));

        return $command;
    }

    /**
     * Get a Command name from the BuildTask segment
     *
     * @return string
     */
    public function getCommandName(BuildTask $task)
    {
        $taskSegment = Config::inst()->get(get_class($task), 'segment');
        if (empty($taskSegment)) {
            $taskSegment = get_class($task);
        }

        $segment = $this->getFriendlySegment($taskSegment);
        if (empty($segment)) {
            return '';
        }

        return sprintf('dev:tasks:%s', $segment);
    }

    public function getFriendlySegment($segment)
    {
        // Convert backslashes (from namespaced classes) to dashes
        $segment = str_replace('\\', '-', $segment);

        // Add dashes before any uppercase letter and return as lowercase
        $segment = strtolower(preg_replace('/(?<=[a-z])([A-Z]+)/', '-$1', $segment));

        // We don't really need "-task" on the end of every task.
        if (substr($segment, -5, 5) === '-task') {
            $segment = substr($segment, 0, strlen($segment) - 5);
        }

        return $segment;
    }

    /**
     * Get the BuildTask functionality as a closure
     *
     * @param  AbstractTaskCommand $command
     * @return callable
     */
    public function getTaskAsClosure(AbstractTaskCommand $command)
    {
        return function (InputInterface $input, OutputInterface $output) use ($command) {
            $io = new SymfonyStyle($input, $output);

            $io->title(sprintf('%s: %s', $command->getName(), $command->getDescription()));
            $io->section('Running...');

            $command->getTask()->run(new HTTPRequest('GET', '/'));
        };
    }
}
