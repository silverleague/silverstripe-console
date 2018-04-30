<?php

namespace SilverLeague\Console\Tests\Command\Object;

use SilverLeague\Console\Tests\Command\AbstractCommandTest;
use SilverStripe\Assets\AssetControlExtension;
use SilverStripe\Security\Member;

/**
 * @coversDefaultClass \SilverLeague\Console\Command\Object\ExtensionsCommand
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class ExtensionsCommandTest extends AbstractCommandTest
{
    protected function getTestCommand()
    {
        return 'object:extensions';
    }

    /**
     * Ensure that the Injector's class resolution is returned for a given Object
     *
     * @covers ::execute
     */
    public function testExecute()
    {
        $tester = $this->executeTest(['object' => Member::class]);
        $output = $tester->getDisplay();
        $this->assertContains(AssetControlExtension::class, $output);
        $this->assertContains('silverstripe/assets', $output);
    }

    /**
     * Ensure that the InputArgument for the object is added
     *
     * @covers ::configure
     */
    public function testConfigure()
    {
        $this->assertTrue($this->command->getDefinition()->hasArgument('object'));
    }

    /**
     * Ensure that extra headers are added for CMS pages
     *
     * @covers ::getRows
     * @dataProvider rowsProvider
     *
     * @param array    $extensions
     * @param string[] $expected
     */
    public function testGetRows($extensions, $expected)
    {
        $this->assertSame($expected, $this->command->getRows($extensions));
    }

    /**
     * @return array[]
     */
    public function rowsProvider()
    {
        $extensions = [
            AssetControlExtension::class,
        ];

        return [
            [
                $extensions,
                [array_merge($extensions, ['silverstripe/assets', 0])],
            ],
        ];
    }
}
