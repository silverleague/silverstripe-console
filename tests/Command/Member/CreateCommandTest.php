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
    /**
     * {@inheritDoc}
     */
    protected function getTestCommand()
    {
        return 'member:create';
    }

    /**
     * Test that a Member can be created
     *
     * @covers ::execute
     */
    public function testExecute()
    {
        // Remove any existing members from the database
        Member::get()->removeAll();

        $questionHelper = $this
            ->getMockBuilder(QuestionHelper::class)
            ->setMethods(['ask'])
            ->getMock();

        $questionHelper
            ->expects($this->atLeastOnce())
            ->method('ask')
            ->willReturn(false);

        $this->command->getApplication()->getHelperSet()->set($questionHelper, 'question');

        $tester = $this->executeTest(
            [
                'email'    => 'unittest@example.com',
                'password' => 'opensesame'
            ]
        );
        $output = $tester->getDisplay();
        $this->assertContains('Member created', $output);
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
