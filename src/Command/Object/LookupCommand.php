<?php

namespace SilverLeague\Console\Command\Object;

use SilverLeague\Console\Command\SilverStripeCommand;
use SilverStripe\Core\Injector\Injector;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Shows which class/objet is returned from the Injector
 *
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class LookupCommand extends SilverStripeCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('object:lookup')
            ->setDescription('Shows which class is returned from the Injector')
            ->addArgument('class', InputArgument::REQUIRED, 'The class to look up');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $class = $input->getArgument('class');
        $resolvedTo = get_class(Injector::inst()->get($class));

        $output->writeln('<comment>' . $class . ' resolves to <info>' . $resolvedTo . '</info></comment>');
    }
}
