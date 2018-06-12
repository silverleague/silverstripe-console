<?php

namespace SilverLeague\Console\Tests\Command\Member;

use SilverLeague\Console\Tests\Command\AbstractCommandTest;
use SilverStripe\Security\Member;

/**
 * Test the unlock member command
 *
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class UnlockCommandTest extends AbstractCommandTest
{
    /**
     * Delete fixtured members after tests have run
     */
    protected function tearDown()
    {
        parent::tearDown();

        $testMember = Member::get()->filter(['Email' => 'somelockeduser@example.com'])->first();
        if ($testMember && $testMember->exists()) {
            $testMember->delete();
        }
    }

    protected function getTestCommand()
    {
        return 'member:unlock';
    }

    public function testExecute()
    {
        $member = $this->createMember();
        $this->assertTrue($member->isLockedOut());

        $tester = $this->executeTest(['email' => 'somelockeduser@example.com']);
        /** @var Member $member */
        $member = Member::get()->byID($member->ID);
        $this->assertContains('Member somelockeduser@example.com unlocked', $tester->getDisplay());
        $this->assertFalse($member->isLockedOut());
    }

    public function testMemberNotFound()
    {
        $result = $this->executeTest(['email' => 'pleasedontfindme@example.com']);
        $this->assertContains('Member with email "pleasedontfindme@example.com" was not found.', $result->getDisplay());
    }

    /**
     * Creates a dummy user for testing with
     *
     * @return Member
     */
    protected function createMember()
    {
        $member = Member::create();
        $member->Email = 'somelockeduser@example.com';
        $member->Password = 'Opensesame1';
        $member->LockedOutUntil = '2099-01-01 01:02:03';
        $member->write();
        return $member;
    }
}
