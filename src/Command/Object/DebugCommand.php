<?php

namespace SilverLeague\Console\Command\Object;

use SilverLeague\Console\Command\SilverStripeCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

/**
 * Outputs a formatted data representation of a DataObject's values in either JSON or a table.
 *
 * The class name is required, but the ID is optional. If left blank an interactive search-by-column will be given
 * for all of the object's columns.
 *
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class DebugCommand extends SilverStripeCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('object:debug')
            ->setDescription('Outputs a visual representation of a DataObject')
            ->addArgument('object', InputArgument::REQUIRED, 'DataObject class name')
            ->addArgument('id', InputArgument::OPTIONAL, 'The ID, or field to search')
            ->addOption('no-sort', null, InputOption::VALUE_NONE, 'Do not sort the output')
            ->addOption('output-table', null, InputOption::VALUE_NONE, 'Output in a table');

        $this->setHelp(
            <<<TEXT
Look up a DataObject by class name and either it's ID or an interactive search-by-column.

If no ID is provided then an interactive prompt will ask for the column to search by, then autocomplete all available
values for that class's columns to choose from.

The default output format is JSON. You can add the --output-table option to output the results in a table instead.

By default the output will also be sorted by key. Do prevent this, add the --no-sort option.
TEXT
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $objectClass = $input->getArgument('object');
        if (!class_exists($objectClass) || !is_subclass_of($objectClass, "SilverStripe\ORM\DataObject")) {
            $output->writeln('<error>' . $objectClass . ' does not exist, or is not a DataObject.</error>');
            return;
        }

        $id = $this->getId($input, $output, $objectClass);
        $data = $this->getData($input, $objectClass, $id);
        if (!$data) {
            $output->writeln('<error>' . $objectClass . ' with ID ' . $id . ' was not found.</error>');
            return;
        }

        $output->writeln(
            [
                '<info>Object: ' . $objectClass . '</info>',
                '<info>ID: ' . $id . '</info>',
                ''
            ]
        );

        $asTable = $input->getOption('output-table');
        $this->output($output, $data, $asTable);
    }

    /**
     * Get the "id" argument either from that provided, or trigger the interactive lookup
     *
     * @param  InputInterface $input
     * @param  OutputInterface $output
     * @return int
     */
    protected function getId(InputInterface $input, OutputInterface $output, $objectClass)
    {
        $id = $input->getArgument('id');
        if (!$id || !is_numeric($id)) {
            $id = $this->askInteractively($input, $output, $objectClass);
        }
        return $id;
    }

    /**
     * Load the object by the given ID and return an optionally sorted array of its data
     *
     * @param  InputInterface $input
     * @param  string $objectClass
     * @param  int $id
     * @return array
     */
    protected function getData(InputInterface $input, $objectClass, $id)
    {
        $data = $objectClass::get()->byId($id);
        if (!$data) {
            return false;
        }
        $data = $data->toMap();

        $this->sanitizeResults($data);
        if (!$input->getOption('no-sort')) {
            ksort($data);
        }
        return $data;
    }

    /**
     * Find the DataObject entity to retrieve and return its ID. This method works by first asking to select
     * one of the object's data columns to filter with, then asking again with an autocompletion on that column
     * to assist with finding the entity you want to return.
     *
     * @param  InputInterface $input
     * @param  OutputInterface $output
     * @param  string $objectClass
     * @return int
     */
    protected function askInteractively(InputInterface $input, OutputInterface $output, $objectClass)
    {
        $choices = $objectClass::get()->toArray();
        $class = singleton($objectClass);
        $columns = array_keys($objectClass::getSchema()->databaseFields($objectClass));
        $this->sanitizeResults($columns);

        $question = new ChoiceQuestion('Choose a column to search by:', $columns);
        $column = $this->getHelper('question')->ask($input, $output, $question);

        $entities = $objectClass::get()->map('ID', $column)->toArray();
        $this->sanitizeResults($entities, true);

        $question = new Question('Look up ' . $class->i18n_singular_name() . ' by ' . $column . ': ');
        $question->setAutocompleterValues($entities);
        $entity = $this->getHelper('question')->ask($input, $output, $question);

        preg_match('/\[#(?<id>\d+)\]$/', $entity, $matches);
        if (!empty($matches['id'])) {
            return $matches['id'];
        }

        // Try and look up the column and value instead
        $object = $objectClass::get()->filter($column, $entity)->first();
        if ($object) {
            return $object->ID;
        }

        return 0;
    }

    /**
     * Remove array entries that might be passwords, and add the ID to the end of the value for when autocompleting
     *
     * @param  array &$results The data array, or array of column names
     * @param  bool $addId     Whether to add the ID to the value for display purposes
     * @return $this
     */
    protected function sanitizeResults(&$results, $addId = false)
    {
        foreach ($results as $key => $value) {
            if (stripos($value, 'Password') !== false || stripos($key, 'Password') !== false) {
                unset($results[$key]);
            }
            if ($addId) {
                $results[$key] .= ' [#' . $key . ']';
            }
        }

        return $this;
    }

    /**
     * Output the results to the console in either JSON format or in a table
     *
     * @param  OutputInterface $output
     * @param  array $data
     * @param  bool $asTable
     * @return $this
     */
    protected function output(OutputInterface $output, $data, $asTable = false)
    {
        if (!$asTable) {
            return $output->writeln(json_encode($data, JSON_PRETTY_PRINT));
        }

        array_walk($data, function (&$value, $key) {
            $value = [$key, $value];
        });

        $table = new Table($output);
        $table
            ->setHeaders(['Key', 'Value'])
            ->setRows($data)
            ->render();

        return $this;
    }
}
