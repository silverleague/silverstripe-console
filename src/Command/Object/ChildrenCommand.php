<?php

namespace SilverLeague\Console\Command\Object;

use SilverLeague\Console\Command\SilverStripeCommand;
use SilverLeague\Console\Framework\Utility\ObjectUtilities;
use SilverStripe\Core\ClassInfo;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * List all child classes of a given class name
 *
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class ChildrenCommand extends SilverStripeCommand
{
    use ObjectUtilities;

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('object:children')
            ->setDescription('List all child classes of a given class, e.g. "Page"')
            ->addArgument('object', InputArgument::REQUIRED, 'The class to find children for');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $object = $input->getArgument('object');
        $classes = (array) ClassInfo::subclassesFor($object);
        // Remove the class itself
        array_shift($classes);
        if (!$classes) {
            $output->writeln('There are no child classes for ' . $object);
            return;
        }
        sort($classes);
        $rows = array_map(function ($class) {
            return [$class, $this->getModuleName($class)];
        }, $classes);

        $output->writeln('<info>Child classes for ' . $object . ':</info>');
        $table = new Table($output);
        $table
            ->setHeaders(['Class name', 'Module'])
            ->setRows($rows)
            ->render();
    }
}
