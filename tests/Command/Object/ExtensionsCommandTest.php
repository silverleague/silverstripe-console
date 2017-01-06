<?php

namespace SilverLeague\Console\Tests\Command\Object;

use SilverLeague\Console\Tests\Command\AbstractCommandTest;

/**
 * @coversDefaultClass \SilverLeague\Console\Command\Object\ExtensionsCommand
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class ExtensionsCommandTest extends AbstractCommandTest
{
    /**
     * {@inheritDoc}
     */
    public function getTestCommand()
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
        $tester = $this->executeTest(['object'  => "SilverStripe\Forms\GridField\GridFieldDetailForm"]);
        $output = $tester->getDisplay();
        $this->assertContains('SilverStripe\ORM\Versioning\VersionedGridFieldDetailForm', $output);
        $this->assertContains('silverstripe/framework', $output);
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
     * @covers ::getHeaders
     * @dataProvider headerProvider
     *
     * @param bool     $isCms
     * @param string[] $expected
     */
    public function testGetHeaders($isCms, $expected)
    {
        $this->assertSame($expected, $this->command->getHeaders($isCms));
    }

    /**
     * @return string[]
     */
    public function headerProvider()
    {
        return [
            [true, ['Class name', 'Module', 'Added DB fields', 'Updates CMS fields']],
            [false, ['Class name', 'Module', 'Added DB fields']]
        ];
    }

    /**
     * Ensure that extra headers are added for CMS pages
     *
     * @covers ::getRows
     * @dataProvider rowsProvider
     *
     * @param bool     $isCms
     * @param array    $extensions
     * @param string[] $expected
     */
    public function testGetRows($isCms, $extensions, $expected)
    {
        $this->assertSame($expected, $this->command->getRows($isCms, $extensions));
    }

    /**
     * @return array[]
     */
    public function rowsProvider()
    {
        $extensions = [
            "SilverStripe\Assets\AssetControlExtension"
        ];

        return [
            [
                false,
                $extensions,
                [array_merge($extensions, ['silverstripe/framework', 0])]
            ],
            [
                true,
                $extensions,
                [array_merge($extensions, ['silverstripe/framework', 0, 'Yes'])]
            ]
        ];
    }
}
