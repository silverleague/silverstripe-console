<?php

namespace SilverLeague\Console\Command\Object;

use SilverLeague\Console\Command\SilverStripeCommand;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Object;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * List all extensions of a given Object, e.g. "Page"
 *
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class ExtensionsCommand extends SilverStripeCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('object:extensions')
            ->setDescription('List all Extensions of a given Object, e.g. "Page"')
            ->addArgument('object', InputArgument::REQUIRED, 'The Object to find Extensions for');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $object = $input->getArgument('object');
        $extensions = Object::get_extensions($object);
        if (!$extensions) {
            $output->writeln('There are no Extensions registered for ' . $object);
            return;
        }
        sort($extensions);

        $isCmsClass = (class_exists('SilverStripe\\CMS\\Model\\SiteTree')
            && singleton($object) instanceof \SilverStripe\CMS\Model\SiteTree);

        $output->writeln('<info>Extensions for ' . $object . ':</info>');
        $table = new Table($output);
        $table
            ->setHeaders($this->getHeaders($isCmsClass))
            ->setRows($this->getRows($isCmsClass, $extensions))
            ->render();
    }

    /**
     * Return the header cells for the output table. CMS classes have an extra column.
     *
     * @param  bool $isCmsClass
     * @return string[]
     */
    protected function getHeaders($isCmsClass)
    {
        $headers = ['Class name', 'Added DB fields'];
        if ($isCmsClass) {
            $headers[] = 'Updates CMS fields';
        }

        return $headers;
    }

    /**
     * Return the rows for the output table containing extension statistics. CMS classes have an extra column.
     *
     * @param  bool $isCmsClass
     * @return array[]
     */
    protected function getRows($isCmsClass, $extensions)
    {
        $tableRows = [];
        foreach ($extensions as $extensionClass) {
            $row = [
                $extensionClass,
                // Add the number of DB fields that the class adds
                count((array) Config::inst()->get($extensionClass, 'db', Config::UNINHERITED))
            ];

            if ($isCmsClass) {
                // Add whether or not the extension updates CMS fields
                $row[] = method_exists(singleton($extensionClass), 'updateCMSFields') ? 'Yes' : 'No';
            }

            $tableRows[] = $row;
        }
        return $tableRows;
    }
}
