<?php

namespace SilverLeague\Console\Command\Config;

use SilverLeague\Console\Command\SilverStripeCommand;
use SilverStripe\Config\Collections\ConfigCollectionInterface;
use SilverStripe\Core\ClassInfo;
use SilverStripe\Core\Config\ConfigLoader;
use SilverStripe\Core\Object;

/**
 * Provide base functionality for retrieving configuration from SilverStripe
 *
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class AbstractConfigCommand extends SilverStripeCommand
{
    /**
     * Get the SilverStripe Config manifest/collection interface
     *
     * @return ConfigCollectionInterface
     */
    public function getConfig()
    {
        return ConfigLoader::instance()->getManifest();
    }
}
