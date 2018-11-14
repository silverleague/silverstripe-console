<?php

namespace SilverLeague\Console\Command\Injector;

use SilverLeague\Console\Command\SilverStripeCommand;
use SilverLeague\Console\Framework\Utility\ObjectUtilities;
use SilverStripe\Core\Injector\Injector;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Shows which class is returned from the Injector
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
            ->setName('injector:lookup')
            ->setAliases(['object:lookup'])
            ->setDescription('Shows which class is returned from an Injector reference')
            ->addArgument('className', InputArgument::REQUIRED, 'The class name to look up');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $className = $input->getArgument('className');
        $resolvedTo = get_class(Injector::inst()->get($className));

        $output->writeln('<comment>' . $className . '</comment> resolves to <info>' . $resolvedTo . '</info>');
        if ($module = $this->getModuleName($resolvedTo)) {
            $output->writeln('<info>Module:</info> <comment>' . $module . '</comment>');
        }
    }
}
