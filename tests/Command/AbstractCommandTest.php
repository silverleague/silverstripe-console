<?php

namespace SilverLeague\Console\Tests\Command;

use SilverLeague\Console\Framework\Scaffold;
use Symfony\Component\Console\Tester\CommandTester;

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
    abstract protected function getTestCommand();

    /**
     * Create a CommandTester and execute the command with given arguments
     *
     * @param  array $params
     * @return CommandTester
     */
    protected function executeTest(array $params = [])
    {
        $tester = new CommandTester($this->command);
        $tester->execute($params);
        return $tester;
    }
}
