<?php

namespace SilverLeague\Console\Command\Config;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Lookup configuration settings by class and property name
 *
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class GetCommand extends AbstractConfigCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('config:get')
            ->setDescription('Look up a specific configuration value')
            ->addArgument('class', InputArgument::REQUIRED)
            ->addArgument('property', InputArgument::REQUIRED);

        $this->setHelp(<<<HELP
Look up a specific configuration value and output it directly. This command can be used for build processes,
automated scripts, quick checks etc where raw output is required outside of the SilverStripe application.
HELP
        );
    }

    /**
     * {@inheritDoc}
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $result = $this->getConfig()->get(
            $input->getArgument('class'),
            $input->getArgument('property'),
            true
        );
        $output->writeln(var_export($result, true));
    }
}
