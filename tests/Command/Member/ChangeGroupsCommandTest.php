<?php

namespace SilverLeague\Console\Command\Member;

use SilverLeague\Console\Tests\Command\AbstractCommandTest;
use SilverStripe\Security\Member;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

/**
 * Change a member's assigned groups
 *
 * @coversDefaultClass \SilverLeague\Console\Command\Member\ChangeGroupsCommand
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class ChangeGroupsCommandTest extends AbstractCommandTest
{
    /**
     * Ensure a clean slate for each test run
     *
     * {@inheritDoc}
     */
    public function setUp()
    {
        parent::setUp();
        Member::get()->removeAll();
    }

    /**
     * {@inheritDoc}
     */
    protected function getTestCommand()
    {
        return 'member:change-groups';
    }

    /**
     * Test that an error message is returned if the Member does not exist
     *
     * @covers ::execute
     */
    public function testReportMemberNotFound()
    {
        $tester = $this->executeTest(['email' => 'lonely.so.lonely@example.com']);
        $this->assertContains('Member with email "lonely.so.lonely@example.com" was not found', $tester->getDisplay());
    }

    /**
     * Test that when a Group is chosen from the multiselect list, the user is assigned to that Group or Groups
     *
     * @covers ::execute
     */
    public function testAddToGroups()
    {
        $member = $this->createMember();

        $this->mockQuestionHelper();
        $tester = $this->executeTest(['email' => 'changemygroup@example.com']);
        $this->assertContains('Groups updated.', $tester->getDisplay());

        $memberCodes = Member::get()
            ->filter('Email', 'changemygroup@example.com')
            ->first()
            ->Groups()
            ->column('Code');

        $this->assertSame(['content-authors'], $memberCodes);
    }

    /**
     * Creates a dummy user for testing with
     *
     * @return Member
     */
    protected function createMember()
    {
        $member = Member::create();
        $member->Email = 'changemygroup@example.com';
        $member->Password = 'opensesame';
        $member->write();
        return $member;
    }

    /**
     * Mock a QuestionHelper and tell it to return a predefined choice for which Group to assign
     *
     * @return QuestionHelper
     */
    protected function mockQuestionHelper()
    {
        $mock = $this
            ->getMockBuilder(QuestionHelper::class)
            ->setMethods(['ask'])
            ->getMock();

        $mock
            ->expects($this->once())
            ->method('ask')
            ->with(
                $this->isInstanceOf(InputInterface::class),
                $this->isInstanceOf(OutputInterface::class),
                $this->callback(
                    function ($argument) {
                        return $argument instanceof ChoiceQuestion
                            && $argument->isMultiselect()
                            && !empty($argument->getChoices());
                    }
                )
            )
            ->willReturn(['content-authors']);

        $this->command->getApplication()->getHelperSet()->set($mock, 'question');
    }
}
