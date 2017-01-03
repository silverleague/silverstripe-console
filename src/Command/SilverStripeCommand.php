<?php

namespace SilverLeague\Console\Command;

use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Dev\BuildTask;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * A slightly embellished Symfony Command class which is SilverStripe aware
 *
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class SilverStripeCommand extends Command
{
    /**
     * Retrieve an argument from the input interface, or use the Question helper to ask for input
     * if it wasn't provided. Will automatically hide input for password fields.
     *
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @param  string          $key      The argument key, e.g. "username"
     * @param  string          $question The question to ask, e.g. "Which username: "
     * @return string|null
     */
    protected function getOrAskForArgument(InputInterface $input, OutputInterface $output, $key, $question)
    {
        if ($supplied = $input->getArgument($key)) {
            return $supplied;
        }

        $question = new Question($question);
        if (stripos($key, 'password') !== false) {
            $question->setHidden(true);
            $question->setHiddenFallback(false);
        }

        return $this->getHelper('question')->ask($input, $output, $question);
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
