<?php

namespace SilverLeague\Console\Framework;

use SilverStripe\Control\HTTPApplication;
use SilverStripe\Control\HTTPRequestBuilder;
use SilverStripe\Core\CoreKernel;

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
            exit(1);
        }
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
            if (file_exists($rootFolder . '/vendor/silverstripe/framework/src/Core/CoreKernel.php')) {
                define('SILVERSTRIPE_ROOT_DIR', $rootFolder);

                require_once $rootFolder . '/vendor/autoload.php';

                $_SERVER['REQUEST_URI'] = '/';
                $_SERVER['REQUEST_METHOD'] = 'GET';
                $_SERVER['SERVER_PROTOCOL'] = 'http';

                $request = HTTPRequestBuilder::createFromEnvironment();

                // Default application
                $kernel = new CoreKernel(BASE_PATH);
                $app = new HTTPApplication($kernel);
                $app->handle($request);

                return true;
            }
        }

        return false;
    }
}
