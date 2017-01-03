<?php

namespace SilverLeague\Console\Command\Dev;

use SilverLeague\Console\Command\SilverStripeCommand;
use SilverStripe\ORM\DatabaseAdmin;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * The BuildCommand will trigger SilverStripe's database rebuild operation
 *
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class BuildCommand extends SilverStripeCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('dev:build')
            ->setDescription('Builds the SilverStripe database');
    }

    /**
     * {@inheritDoc}
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        (new DatabaseAdmin)->build();
    }
}
