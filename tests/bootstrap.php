<?php

/**
 * Bootstrapping for the console unit tests
 *
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */

define('CONSOLE_BASE_DIR', realpath(__DIR__ . '/..'));

foreach([CONSOLE_BASE_DIR, realpath(CONSOLE_BASE_DIR . '/../..')] as $vendorPath) {
    if (file_exists($vendorPath . '/autoload.php')) {
        require_once $vendorPath . '/autoload.php';
    }
}
