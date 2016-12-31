<?php

namespace SilverLeague\Console\Framework;

use Symfony\Component\Console\Exception\RuntimeException;

/**
 * The class responsible for loading/instantiating SilverStripe and accessing its class hierarchy, etc
 *
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class SilverStripeLoader
{
    /**
     * Instantiate the connection to the SilverStripe instance
     *
     * @throws RuntimeException If SilverStripe could not be loaded
     */
    public function __construct()
    {
        if (!$this->findSilverStripe()) {
            throw new RuntimeException('Failed to load SilverStripe.');
        }
    }

    /**
     * Return a set of Tasks from SilverStripe
     *
     * @return array
     */
    public function getTasks()
    {
        return \SilverStripe\Core\ClassInfo::subclassesFor('SilverStripe\\Dev\\BuildTask');
    }

    /**
     * Attempts to locate and include the SilverStripe core
     *
     * @return bool Whether or not the framework was found
     */
    protected function findSilverStripe()
    {
        foreach ([getcwd(), SILVERLEAGUE_CONSOLE_ROOT . '/../'] as $rootFolder) {
            if (file_exists($rootFolder . '/framework/src/Core/Core.php')) {
                require_once $rootFolder . '/vendor/autoload.php';
                require_once $rootFolder . '/framework/src/Core/Core.php';
                return true;
            }
        }
        return false;
    }
}
