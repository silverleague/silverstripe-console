<?php

/**
 * Bootstrapping for the console unit tests
 *
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */

use SilverLeague\Console\Framework\Scaffold;

define('CONSOLE_BASE_DIR', realpath(__DIR__ . '/..'));

foreach([
    // Root project, i.e. in Travis builds or globally installed
    realpath(CONSOLE_BASE_DIR . '/vendor'),
    // As a vendor module, i.e. in a project
    realpath(CONSOLE_BASE_DIR . '/../..'
)] as $vendorPath) {
    if (file_exists($vendorPath . '/autoload.php')) {
        require_once $vendorPath . '/autoload.php';
        require_once $vendorPath . '/silverstripe/framework/tests/bootstrap.php';
    }
}

// Trigger bootstrapping
(new Scaffold);
