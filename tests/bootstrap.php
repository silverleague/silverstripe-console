<?php
/**
 * Bootstrapping for the console unit tests
 *
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */

define('CONSOLE_BASE_DIR', realpath(__DIR__ . '/..'));
require CONSOLE_BASE_DIR . '/vendor/autoload.php';

global $_FILE_TO_URL_MAPPING;
$_FILE_TO_URL_MAPPING[CONSOLE_BASE_DIR] = 'http://localhost';
