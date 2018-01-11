<?php

namespace SilverLeague\Console\Tests\Command;

use SilverLeague\Console\Command\SilverStripeCommand;
use SilverLeague\Console\Framework\Scaffold;
use SilverStripe\Config\Collections\ConfigCollectionInterface;
use SilverStripe\Core\Injector\Injector;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @coversDefaultClass \SilverLeague\Console\Command\SilverStripeCommand
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class SilverStripeCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that an argument is prompted for if it doesn't get provided, or returned if provided
     *
     * @covers ::getOrAskForArgument
     */
    public function testGetOrAskForArgument()
    {
        $questionHelper = $this
            ->getMockBuilder(QuestionHelper::class)
            ->setMethods(['ask'])
            ->getMock();

        $questionHelper
            ->expects($this->atLeastOnce())
            ->method('ask')
            ->with(
                $this->isInstanceOf(InputInterface::class),
                $this->isInstanceOf(OutputInterface::class),
                $this->isInstanceOf(Question::class)
            );

        $application = (new Scaffold)->getApplication();
        $application->getHelperSet()->set($questionHelper, 'question');

        $command = $application->find('member:create');

        $tester = new CommandTester($command);
        $tester->execute(['email' => 'john@example.com']);

        // Check that the email is returned since it existed
        $this->assertSame('john@example.com', $tester->getInput()->getArgument('email'));
    }

    /**
     * Test that the Injector is returned
     *
     * @covers ::getInjector
     */
    public function testShouldReturnInjector()
    {
        $this->assertInstanceOf(Injector::class, (new SilverStripeCommand('foo'))->getInjector());
    }

    /**
     * Test that a ConfigCollectionInterface instance is returned
     *
     * @covers ::getConfig
     */
    public function testShouldReturnConfig()
    {
        $this->assertInstanceOf(ConfigCollectionInterface::class, (new SilverStripeCommand('bar'))->getConfig());
    }
}
