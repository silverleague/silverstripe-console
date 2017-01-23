<?php

namespace SilverLeague\Console\Command\Config;

use SilverLeague\Console\Command\SilverStripeCommand;
use SilverStripe\Core\ClassInfo;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Object;

/**
 * Provide base functionality for retrieving configuration from SilverStripe
 *
 * @package silverstripe-console
 * @author  Robbie Averill <robbie@averill.co.nz>
 */
class AbstractConfigCommand extends SilverStripeCommand
{
    /**
     * @var array
     */
    protected $yamlConfig;

    /**
     * @var \SilverStripe\Core\Manifest\ConfigManifest
     */
    protected $configManifest;

    /**
     * Gets the parsed YAML configuration array from the ConfigManifest
     *
     * @return array
     */
    public function getYamlConfig()
    {
        if ($this->yamlConfig === null) {
            $manifest = $this->getConfigManifest();
            $this->yamlConfig = $this->getPropertyValue($manifest, 'yamlConfig');
        }
        return $this->yamlConfig;
    }

    /**
     * Assemble a data-set that would be returned from ConfigStaticManifest, if it were a bit more
     * useful for external API access.
     *
     * @return array
     */
    public function getStaticConfig()
    {
        $output = [];
        foreach (ClassInfo::subclassesFor(Object::class) as $class => $filename) {
            $classConfig = [];
            $reflection = new \ReflectionClass($class);
            foreach ($reflection->getProperties() as $property) {
                /** @var ReflectionProperty $property */
                if ($property->isPrivate() && $property->isStatic()) {
                    $property->setAccessible(true);
                    $classConfig[$property->getName()] = $property->getValue();
                }
            }
            if (!empty($classConfig)) {
                $output[$class] = $classConfig;
            }
        }
        return $output;
    }

    /**
     * Gets the ConfigManifest from the current Config instance
     *
     * @return \SilverStripe\Core\Manifest\ConfigManifest
     */
    public function getConfigManifest()
    {
        if ($this->configManifest === null) {
            $manifests = $this->getPropertyValue($this->getConfig(), 'manifests');
            $this->configManifest = array_shift($manifests);
        }
        return $this->configManifest;
    }

    /**
     * Gets any overrides made to the manifest
     *
     * @return array
     */
    public function getConfigOverrides()
    {
        $overrides = (array) $this->getPropertyValue($this->getConfig(), 'overrides');
        return !empty($overrides) ? array_shift($overrides) : [];
    }

    /**
     * Get the SilverStripe Config model
     *
     * @return Config
     */
    public function getConfig()
    {
        return Config::inst();
    }

    /**
     * Gets the value of a non-public property from the given class instance
     *
     * @param  object $class
     * @param  string $propertyName
     * @return mixed
     */
    protected function getPropertyValue($class, $propertyName)
    {
        $reflectionClass = new \ReflectionClass($class);
        $property = $reflectionClass->getProperty($propertyName);
        $property->setAccessible(true);
        return $property->getValue($class);
    }
}
