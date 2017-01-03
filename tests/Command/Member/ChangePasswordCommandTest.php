<?php

namespace SilverLeague\Console\Tests\Command\Member;

use SilverLeague\Console\Tests\Command\AbstractCommandTest;
use SilverStripe\Security\Member;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @coversDefaultClass \SilverLeague\Console\Command\Member\ChangePasswordCommand
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class ChangePasswordCommandTest extends AbstractCommandTest
{
    /**
     * Create a Member to play with
     */
    public function setUp()
    {
        parent::setUp();

        Member::get()->removeAll();
        $member = Member::create();
        $member->Email = 'unittest@example.com';
        $member->Password = 'notrelevant';
        $member->write();
    }

    /**
     * {@inheritDoc}
     */
    public function getTestCommand()
    {
        return 'member:change-password';
    }

    /**
     * Test that our existing Member's password can be changed
     *
     * @covers ::execute
     */
    public function testExecute()
    {
        $tester = new CommandTester($this->command);
        $tester->execute(
            [
                'command'  => $this->command->getName(),
                'email'    => 'unittest@example.com',
                'password' => 'newpassword'
            ]
        );

        $this->assertContains('Password updated', $tester->getDisplay());
    }

    /**
     * Test that if an email does not match a Member, then an error is returned
     *
     * @covers ::execute
     */
    public function testErrorWhenEmailNotFound()
    {
        $tester = new CommandTester($this->command);
        $tester->execute(
            [
                'command'  => $this->command->getName(),
                'email'    => 'joe.unpopular@example.com',
                'password' => 'helloworld'
            ]
        );

        $this->assertContains('Member with email "joe.unpopular@example.com" was not found', $tester->getDisplay());
    }

    /**
     * Ensure that the InputArgument for at least one of the arguments has been added
     *
     * @covers ::configure
     */
    public function testConfigure()
    {
        $this->assertTrue($this->command->getDefinition()->hasArgument('password'));
    }
}
