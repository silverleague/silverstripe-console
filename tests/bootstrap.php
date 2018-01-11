<?php
/**
 * Bootstrapping for the console unit tests
 *
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */

define('CONSOLE_BASE_DIR', realpath(__DIR__ . '/..'));
require_once CONSOLE_BASE_DIR . '/vendor/autoload.php';
