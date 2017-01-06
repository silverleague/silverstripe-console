<?php

namespace SilverLeague\Console\Command\Object;

use SilverLeague\Console\Command\SilverStripeCommand;
use SilverLeague\Console\Framework\Utility\ObjectUtilities;
use SilverStripe\Core\Injector\Injector;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Shows which Object is returned from the Injector
 *
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class LookupCommand extends SilverStripeCommand
{
    use ObjectUtilities;

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('object:lookup')
            ->setDescription('Shows which Object is returned from the Injector')
            ->addArgument('object', InputArgument::REQUIRED, 'The Object to look up');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $object = $input->getArgument('object');
        $resolvedTo = get_class(Injector::inst()->get($object));

        $output->writeln('<comment>' . $object . '</comment> resolves to <info>' . $resolvedTo . '</info>');
        if ($module = $this->getModuleName($object)) {
            $output->writeln('<info>Module:</info> <comment>' . $module . '</comment>');
        }
    }
}
