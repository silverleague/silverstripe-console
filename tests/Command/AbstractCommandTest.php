<?php

namespace SilverLeague\Console\Tests\Command;

use SilverLeague\Console\Framework\Scaffold;

/**
 * An abstract base for testing SilverStripeCommand classes
 *
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
abstract class AbstractCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The test subject
     *
     * @var \SilverLeague\Console\Command\SilverStripeCommand
     */
    protected $command;

    /**
     * Add the command
     */
    public function setUp()
    {
        $this->command = (new Scaffold)->getApplication()->find($this->getTestCommand());
    }

    /**
     * Provide the command name to test, e.g. "object:lookup"
     *
     * @return string
     */
    abstract public function getTestCommand();
}
