<?php

namespace SilverLeague\Console\Framework\Utility;

use ReflectionClass;
use ReflectionException;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Manifest\ModuleManifest;

/**
 * Utility methods for handling SilverStripe Objects
 *
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
trait ObjectUtilities
{
    /**
     * Get a ReflectionClass from the given class name
     *
     * @param  string $className
     * @return ReflectionClass|false
     */
    public function getReflection($className)
    {
        try {
            return new ReflectionClass($className);
        } catch (ReflectionException $ex) {
            return false;
        }
    }

    /**
     * Given a class name, find and return which module it belongs to
     *
     * @param  string $className
     * @return string
     */
    public function getModuleName($className)
    {
        $class = $this->getReflection($className);
        if (!$class) {
            return '';
        }

        $relativePath = ltrim(substr($class->getFileName(), strlen(SILVERSTRIPE_ROOT_DIR)), '/');
        $folders = explode('/', $relativePath);

        // Handle root level modules
        $folder = array_shift($folders);

        if (empty($folder)) {
            return '';
        }

        // Handle vendor modules
        if ($folder === 'vendor') {
            $folder .=  '/' . array_shift($folders) . '/' . array_shift($folders);
        }

        $composerConfig = $this->getModuleComposerConfiguration($folder);
        return !empty($composerConfig['name']) ? $composerConfig['name'] : '';
    }

    /**
     * Given a folder name, load a possible composer.json configuration from inside it. This method will handle
     * the main project folder e.g. "mysite" by looking in the root folder rather than the folder itself.
     *
     * @param  string $folderName
     * @return array
     */
    public function getModuleComposerConfiguration($folderName)
    {
        $project = Config::inst()->get(ModuleManifest::class, 'project');
        if (!empty($project) && $project === $folderName) {
            $folderName = '.';
        }

        $filePath = $folderName . '/composer.json';
        if (!file_exists($filePath) || !is_readable($filePath)) {
            return [];
        }

        $composerConfig = file_get_contents($filePath);
        if (!$composerConfig) {
            return [];
        }

        return json_decode($composerConfig, true) ?: [];
    }
}
