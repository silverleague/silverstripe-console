<?php

namespace SilverLeague\Console\Tests\Framework\Utility;

use ReflectionClass;
use SilverLeague\Console\Framework\Utility\ObjectUtilities;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Manifest\ModuleManifest;
use SilverStripe\ORM\DataObject;

/**
 * @coverDefaultClass \SilverLeague\Console\Framework\Utility\ObjectUtilities
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class ObjectUtilitiesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ObjectUtilities
     */
    protected $utility;

    protected function setUp()
    {
        parent::setUp();

        $this->utility = $this
            ->getMockBuilder(ObjectUtilities::class)
            ->setMethods([])
            ->getMockForTrait();
    }

    /**
     * Test that a ReflectionClass is returned when a valid class name is provided
     */
    public function testGetReflectionClass()
    {
        $this->assertInstanceOf(ReflectionClass::class, $this->utility->getReflection(DataObject::class));
    }

    /**
     * Test that ReflectionExceptions are handled gracefully for non existent classes
     */
    public function testGetReflectionHandlesMissingClassesGracefully()
    {
        $this->assertFalse($this->utility->getReflection("Foo\Bar\Monkey\Man\Pluto\Saturn"));
    }

    /**
     * Test that a composer configured module name is returned for a valid SilverStripe module
     */
    public function testGetModuleNameForValidClass()
    {
        $this->assertSame('silverstripe/framework', $this->utility->getModuleName(DataObject::class));
    }

    /**
     * Test that an empty string is returned gracefully if a module cannot be found
     */
    public function testReturnEmptyStringWhenModuleNameCantBeFound()
    {
        $this->assertSame('', $this->utility->getModuleName('stdClass'));
    }

    /**
     * Ensure that the project name can be retrieved from its composer configuration, and if it's the $project (mysite)
     * then use the root directory composer.json
     *
     * @dataProvider composerConfigurationProvider
     */
    public function testGetModuleNameFromComposerConfiguration($projectName, $folderName, $expected)
    {
        $result = $this->utility->getModuleComposerConfiguration($folderName);

        $this->assertArrayHasKey('name', $result);
        $this->assertSame($expected, $result['name']);
    }

    /**
     * @return array[]
     */
    public function composerConfigurationProvider()
    {
        $project = Config::inst()->get(ModuleManifest::class, 'project');
        return [
            [$project, $project, 'silverleague/ssconsole'],
            [$project, 'vendor/silverstripe/framework', 'silverstripe/framework']
        ];
    }

    /**
     * Test that when an invalid class is passed to getModuleName it will return a blank string
     */
    public function testGetModuleNameFromInvalidClassReturnsEmptyString()
    {
        $this->assertSame('', $this->utility->getModuleName("Far\Out\There\Nowhere"));
    }

    /**
     * Test that when the module's folder name is empty or missing, a blank string is returned
     */
    public function testEmptyFolderNameInPathReturnsEmptyString()
    {
        $reflectionMock = $this
            ->getMockBuilder(ReflectionClass::class)
            ->disableOriginalConstructor()
            ->setMethods(['getFileName'])
            ->getMock();

        $reflectionMock
            ->expects($this->once())
            ->method('getFileName')
            ->willReturn(SILVERSTRIPE_ROOT_DIR . '/0/src/ORM/DataObject.php');

        $mock = $this
            ->getMockBuilder(ObjectUtilities::class)
            ->setMethods(['getReflection'])
            ->getMockForTrait();

        $mock
            ->expects($this->once())
            ->method('getReflection')
            ->willReturn($reflectionMock);

        $this->assertSame('', $mock->getModuleName(DataObject::class));
    }

    /**
     * Test that an unreadable composer file will return an empty array
     */
    public function testHandleUnreadableComposerFile()
    {
        $filename = 'vendor/silverstripe/framework/composer.json';
        chmod($filename, 0066);
        $this->assertSame([], $this->utility->getModuleComposerConfiguration('vendor/silverstripe/framework'));
        chmod($filename, 0644);
    }

    /**
     * Test that when composer.json is empty an empty array is returned
     */
    public function testEmptyComposerFile()
    {
        mkdir('nothing');
        touch('nothing/composer.json');
        $this->assertSame([], $this->utility->getModuleComposerConfiguration('nothing'));
        unlink('nothing/composer.json');
        rmdir('nothing');
    }

    /**
     * Test that if no composer.json file exists, an empty array is returned
     */
    public function testHandleMissingComposerFile()
    {
        $this->assertSame([], $this->utility->getModuleComposerConfiguration('bin'));
    }
}
