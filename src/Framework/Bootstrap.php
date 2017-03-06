<?php

namespace SilverLeague\Console\Framework;

use SilverStripe\ORM\DB;

/**
 * Loads and configures SilverStripe
 *
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class Bootstrap
{
    /**
     * Ensure SilverStripe is loaded and configured
     */
    public function initialize()
    {
        if (!$this->findSilverStripe()) {
            echo 'A SilverStripe installation could not be found. Please run ssconsole from your '
                . 'SilverStripe root.', PHP_EOL;
            exit;
        }
        $this->getDb();
    }

    /**
     * Find and require SilverStripe. This will look in:
     *
     * - The current working directory (for when installed globally with composer)
     * - The next directory up (for when installed locally into a SilverStripe project)
     * - The console's "silverstripe" directory (for when installed in a build process)
     *
     * @return bool
     */
    protected function findSilverStripe()
    {
        if (defined('SILVERSTRIPE_ROOT_DIR')) {
            return true;
        }
        foreach ([getcwd(), CONSOLE_BASE_DIR . '/../', CONSOLE_BASE_DIR . '/silverstripe'] as $rootFolder) {
            if (file_exists($rootFolder . '/framework/src/Core/Core.php')) {
                define('SILVERSTRIPE_ROOT_DIR', $rootFolder);

                require_once $rootFolder . '/vendor/autoload.php';
                require_once $rootFolder . '/framework/src/Core/Core.php';
                return true;
            }
        }
        return false;
    }

    /**
     * Get the SilverStripe DB connector
     *
     * @return $this
     */
    protected function getDb()
    {
        global $databaseConfig;
        if ($databaseConfig) {
            DB::connect($databaseConfig);
        }
        return $this;
    }
}
