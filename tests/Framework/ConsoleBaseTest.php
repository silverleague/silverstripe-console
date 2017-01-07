<?php

namespace SilverLeague\Console\Tests\Framework;

use SilverLeague\Console\Framework\ConsoleBase;
use SilverLeague\Console\Framework\Scaffold;

/**
 * @coversDefaultClass \SilverLeague\Console\Framework\ConsoleBase
 * @package silverleague-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class ConsoleBaseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that the application can be injected, set and retrieved
     */
    public function testInitialiaseConsoleBase()
    {
        $application = (new Scaffold)->getApplication();

        $consoleBase = new ConsoleBase($application);

        $this->assertSame($application, $consoleBase->getApplication());
        $newApplication = clone $application;
        $consoleBase->setApplication($newApplication);
        $this->assertNotSame($application, $consoleBase->getApplication());
    }
}
