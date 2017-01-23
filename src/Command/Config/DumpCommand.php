<?php

namespace SilverLeague\Console\Command\Config;

use SilverLeague\Console\Command\Config\AbstractConfigCommand;
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
     * The supported configuration sources
     *
     * @var array
     */
    protected $configTypes = ['all', 'yaml', 'static', 'overrides'];

    /**
     * @var string
     */
    protected $configType;

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
            ->addArgument('type', null, implode(', ', $this->configTypes), 'all')
            ->addOption('filter', null, InputOption::VALUE_REQUIRED, 'Filter the results (search)');

        $this->setHelp(<<<HELP
Dumps all of the processed configuration properties and their values. You can optionally filter the type to
control the source of data, for example use "yaml" to only return configuration values that were defined in
YAML configuration files. You can also add the --filter option with a search value to narrow the results.
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
        if ($input->getArgument('type') && !in_array($input->getArgument('type'), $this->configTypes)) {
            throw new \InvalidArgumentException(
                sprintf(
                    '%s is not a valid config type, options: %s',
                    $input->getArgument('type'),
                    implode(', ', $this->configTypes)
                )
            );
        }

        $this->filter = $input->getOption('filter');
        $this->configType = $input->getArgument('type');

        $data = $this->getParsedOutput();
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
     * Get source configuration data by the optional "type"
     *
     * @return array
     */
    protected function getSourceData()
    {
        switch ($this->configType) {
            case 'yaml':
                $output = $this->getYamlConfig();
                break;
            case 'static':
                $output = $this->getStaticConfig();
                break;
            case 'overrides':
                $output = $this->getConfigOverrides();
                break;
            case 'all':
            default:
                $output = $this->getMergedData();
                break;
        }
        return $output;
    }

    /**
     * Merge together the config manifests data in the same manner as \SilverStripe\Core\Config\Config::getUncached
     *
     * @return array
     */
    protected function getMergedData()
    {
        // Statics are the lowest priority
        $output = $this->getStaticConfig();

        // Then YAML is added
        foreach ($this->getYamlConfig() as $class => $property) {
            if (!array_key_exists($class, $output)) {
                $output[$class] = [];
            }
            $output[$class] = array_merge($property, $output[$class]);
        }

        // Then overrides are added last
        foreach ($this->getConfigOverrides() as $class => $values) {
            foreach ($values as $property => $value) {
                $output[$class][$property] = $value;
            }
        }

        return $output;
    }

    /**
     * Creates a table-friendly output array from the input configuration sources
     *
     * @return array
     */
    protected function getParsedOutput()
    {
        $output = [];
        foreach ($this->getSourceData() as $className => $classInfo) {
            foreach ($classInfo as $property => $values) {
                $row = [$className, $property];
                if (is_array($values)) {
                    foreach ($values as $key => $value) {
                        $row[] = is_numeric($key) ? '' : $key;
                        $row[] = is_array($value) ? json_encode($value, JSON_PRETTY_PRINT) : $value;
                        $output[] = $row;
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
                $output[] = $row;
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
