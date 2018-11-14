<?php

namespace SilverLeague\Console\Command\Config;

use SilverStripe\Core\Convert;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Outputs a combined representation of all SilverStripe configuration
 *
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class DumpCommand extends AbstractConfigCommand
{
    /**
     * @var string|null
     */
    protected $filter;

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('config:dump')
            ->setDescription('Dumps all of the processed configuration properties and their values')
            ->addOption('filter', null, InputOption::VALUE_REQUIRED, 'Filter the results (search)');

        $this->setHelp(<<<HELP
Dumps all of the processed configuration properties and their values. You can optionally add the --filter option
with a search value to narrow the results.
HELP
        );
    }

    /**
     * {@inheritDoc}
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws InvalidArgumentException If an invalid configuration type is provided
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->filter = $input->getOption('filter');

        $data = $this->getParsedOutput($this->getConfig()->getAll());
        if ($this->filter) {
            $data = $this->filterOutput($data, $this->filter);
        }

        $table = new Table($output);
        $table
            ->setHeaders(['Class name', 'Property', 'Key', 'Value'])
            ->setRows($data)
            ->render();
    }

    /**
     * Creates a table-friendly output array from the input configuration source
     *
     * @param  array $data
     * @return array
     */
    protected function getParsedOutput($data)
    {
        $output = [];
        foreach ($data as $className => $classInfo) {
            foreach ($classInfo as $property => $values) {
                $row = [$className, $property];
                if (is_array($values) || is_object($values)) {
                    foreach ($values as $key => $value) {
                        $row[] = is_numeric($key) ? '' : $key;
                        if (is_array($value)) {
                            $value = Convert::raw2json($value, JSON_PRETTY_PRINT);
                        } elseif (is_object($value)) {
                            $value = get_class($value);
                        } else {
                            $value = (string) $value;
                        }
                        $row[] = $value;
                        if (array_filter($row) != []) {
                            $output[] = $row;
                        }
                        // We need the class and property data for second level values if we're filtering.
                        if ($this->filter !== null) {
                            $row = [$className, $property];
                        } else {
                            $row = ['', ''];
                        }
                    }
                } else {
                    $row[] = '';
                    $row[] = $values;
                }

                if (array_filter($row) != []) {
                    $output[] = $row;
                }
            }
        }
        return $output;
    }

    /**
     * Apply a search filter to the results
     *
     * @param  array  $data   The pre-parsed output data
     * @param  string $filter The string to search on
     * @return array          Rows that have a string match on any of their fields
     */
    protected function filterOutput($data, $filter)
    {
        $output = [];
        foreach ($data as $values) {
            foreach ($values as $value) {
                if (is_string($value) && stripos($value, $filter) !== false) {
                    $output[] = $values;
                }
            }
        }
        return $output;
    }
}
