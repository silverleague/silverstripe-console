<?php

namespace SilverLeague\Console\Framework\Loader;

use RuntimeException;
use SilverLeague\Console\Framework\ConsoleBase;
use Symfony\Component\Yaml\Yaml;

/**
 * The Configuration Loader is reponsible for loading the core configuration YAML file, and merging in any
 * other configuration files from other locations
 *
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class ConfigurationLoader extends ConsoleBase
{
    /**
     * The configuration file name to look for
     *
     * @var string
     */
    const CONFIGURATION_FILE = 'console.yml';

    /**
     * Load the YAML configuration for the application and return it
     *
     * @return array
     * @throws RuntimeException If the filecould not be loaded
     */
    public function load()
    {
        $filename = CONSOLE_BASE_DIR . '/' . self::CONFIGURATION_FILE;
        if (!file_exists($filename) || !is_readable($filename)) {
            throw new RuntimeException('The configuration YAML file does not exist!');
        }

        return Yaml::parse($filename);
    }
}
