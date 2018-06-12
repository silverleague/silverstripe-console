<?php

namespace SilverLeague\Console\Tests\Command\Member;

use SilverLeague\Console\Tests\Command\AbstractCommandTest;
use SilverStripe\Security\Member;
use Symfony\Component\Console\Helper\QuestionHelper;

/**
 * @coversDefaultClass \SilverLeague\Console\Command\Member\CreateCommand
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class CreateCommandTest extends AbstractCommandTest
{
    protected function getTestCommand()
    {
        return 'member:create';
    }

    /**
     * Delete fixtured members after tests have run
     */
    protected function tearDown()
    {
        parent::tearDown();

        $testMember = Member::get()->filter(['Email' => 'unittest@example.com'])->first();
        if ($testMember && $testMember->exists()) {
            $testMember->delete();
        }
    }

    /**
     * Test that a Member can be created
     *
     * @covers ::execute
     */
    public function testExecute()
    {
        $questionHelper = $this
            ->getMockBuilder(QuestionHelper::class)
            ->setMethods(['ask'])
            ->getMock();

        $questionHelper
            ->expects($this->atLeastOnce())
            ->method('ask')
            ->willReturn(false);

        $this->command->getApplication()->getHelperSet()->set($questionHelper, 'question');

        $tester = $this->executeTest([
            'email'    => 'unittest@example.com',
            'password' => 'OpenSe$am3!',
        ]);
        $output = $tester->getDisplay();
        $this->assertContains('Member created', $output);

        $tester = $this->executeTest([
            'email'    => 'unittest@example.com',
            'password' => 'OpenSe$am3!',
        ]);
        $output = $tester->getDisplay();
        $this->assertContains('Member already exists', $output);
    }

    /**
     * Ensure that the InputArgument for at least one of the arguments has been added
     *
     * @covers ::configure
     */
    public function testConfigure()
    {
        $this->assertTrue($this->command->getDefinition()->hasArgument('email'));
    }
}
