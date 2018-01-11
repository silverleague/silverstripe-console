<?php

namespace SilverLeague\Console\Tests\Command\Object;

use SilverLeague\Console\Tests\Command\AbstractCommandTest;
use SilverStripe\Security\Member;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @coversDefaultClass \SilverLeague\Console\Command\Object\DebugCommand
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class DebugCommandTest extends AbstractCommandTest
{
    /**
     * @var Member
     */
    protected $member;

    /**
     * Create a Member to play with
     */
    protected function setUp()
    {
        parent::setUp();

        $member = Member::create();
        $member->Email = 'unittest@example.com';
        $member->Password = 'NotRelevant1';
        $member->write();

        $this->member = $member;
    }

    /**
     * Delete fixtured members after tests have run
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->member->delete();
    }

    /**
     * {@inheritDoc}
     */
    protected function getTestCommand()
    {
        return 'object:debug';
    }

    /**
     * Test that when an invalid class name (non existent or not a DataObject) is given that an error is returned
     */
    public function testErrorWhenInvalidClassNameProvided()
    {
        $tester = $this->executeTest(['object' => "A\B\C\D\E\F\G"]);
        $this->assertContains('does not exist', $tester->getDisplay());

        $tester = $this->executeTest(['object' => "SilverStripe\Core\Object"]);
        $this->assertContains('is not a DataObject', $tester->getDisplay());
    }

    /**
     * Test that when all required arguments are provided and valid the data is retrieved and output directly
     */
    public function testGetDataImmediatelyWhenAllArgumentsProvidedAndSortingIsApplied()
    {
        $tester = $this->executeTest(['object' => Member::class, 'id' => $this->member->ID]);
        $display = $tester->getDisplay();
        $this->assertContains($this->member->Email, $display);

        // Should be identical
        $tester2 = $this->executeTest(['object' => Member::class, 'id' => $this->member->ID]);
        $this->assertSame($display, $tester2->getDisplay());

        // Should be different
        $tester3 = $this->executeTest(['object' => Member::class, 'id' => $this->member->ID, '--no-sort' => true]);
        $this->assertNotSame($display, $tester3->getDisplay());
    }

    /**
     * Simuluating the interactive input, test that we can lookup a model by a column and a value. Also test
     * that password columns are not returned
     */
    public function testSearchMemberByColumn()
    {
        $tester = new CommandTester($this->command);

        // Simulate interactive inputs
        $tester->setInputs(['Email', $this->member->Email]);

        $tester->execute(['object' => Member::class]);
        $display = $tester->getDisplay();
        $this->assertContains($this->member->Email, $display);

        // Check there's no passwords in the output
        $this->assertNotContains('Password', $display);
    }

    /**
     * Ensure that table output can be returned if the option is provided
     */
    public function testCanOutputAsTable()
    {
        $tester = $this->executeTest(['object' => Member::class, 'id' => $this->member->ID, '--output-table' => true]);

        $this->assertContains('+--------', $tester->getDisplay());
    }

    /**
     * Test that generic info output is provided about the class and ID
     */
    public function testGenericOutputIsGivenAboutContext()
    {
        $tester = $this->executeTest(['object' => Member::class, 'id' => $this->member->ID]);

        $display = $tester->getDisplay();
        $this->assertContains('Object: ' . Member::class, $display);
        $this->assertContains('ID: ' . $this->member->ID, $display);
    }
}
