<?php

namespace SilverLeague\Console\Tests\Command\Member;

use SilverLeague\Console\Tests\Command\AbstractCommandTest;
use SilverStripe\Security\Member;

/**
 * Test the lock member command
 *
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class LockCommandTest extends AbstractCommandTest
{
    /**
     * Delete fixtured members after tests have run
     */
    protected function tearDown()
    {
        parent::tearDown();

        $testMember = Member::get()->filter(['Email' => 'sometestuser@example.com'])->first();
        if ($testMember && $testMember->exists()) {
            $testMember->delete();
        }
    }

    protected function getTestCommand()
    {
        return 'member:lock';
    }

    public function testExecute()
    {
        $member = $this->createMember();
        $this->assertFalse($member->isLockedOut());

        $tester = $this->executeTest(['email' => 'sometestuser@example.com']);
        /** @var Member $member */
        $member = Member::get()->byID($member->ID);
        $this->assertContains('Member sometestuser@example.com locked for', $tester->getDisplay());
        $this->assertTrue($member->isLockedOut());
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
        $member->Email = 'sometestuser@example.com';
        $member->Password = 'Opensesame1';
        $member->write();
        return $member;
    }
}
