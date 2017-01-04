<?php

namespace SilverLeague\Console\Tests\Dev;

use SilverLeague\Console\Framework\Scaffold;
use SilverLeague\Console\Tests\Command\AbstractCommandTest;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @coversDefaultClass \SilverLeague\Console\Command\Dev\BuildCommand
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class BuildTest extends AbstractCommandTest
{
    /**
     * {@inheritDoc}
     */
    public function getTestCommand()
    {
        return 'dev:build';
    }

    /**
     * Test that the name and description were set correctly
     *
     * @covers ::configure
     */
    public function testConfigure()
    {
        $this->assertSame($this->getTestCommand(), $this->command->getName());
        $this->assertContains('Builds the SilverStripe database', $this->command->getDescription());
    }

    /**
     * Test that the database is built correctly. This test assumes that the "tests/bootstrap.php" database
     * configuration can work in a test environment. If this test fails, you may need to tweak this code and/or allow
     * it to work in your environment.
     *
     * We have to buffer the CommandTester output to check its result because the SilverStripe framework uses "echo"
     * which we can't capture in an OutputInterface.
     *
     * @covers ::execute
     */
    public function testExecute()
    {
        $command = (new Scaffold)->getApplication()->find($this->getTestCommand());

        $tester = new CommandTester($command);

        ob_start();
        $tester->execute(['command' => $command->getName()]);
        $buffer = ob_get_clean();

        $this->assertContains('Database build completed!', $buffer);
    }
}
