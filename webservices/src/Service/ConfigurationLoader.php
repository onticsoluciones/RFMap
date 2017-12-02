<?php

namespace Ontic\RFMap\Webservices\Service;

use Ontic\RFMap\Webservices\Model\Configuration;
use Symfony\Component\Yaml\Yaml;

class ConfigurationLoader
{
    /** @var string */
    private $configFilePath;

    /**
     * @param string $filePath
     */
    public function __construct($filePath)
    {
        $this->configFilePath = $filePath;
    }

    /**
     * @return Configuration
     */
    public function load()
    {
        $data = Yaml::parseFile($this->configFilePath);
        $dbFileRelativePath = $data['environments']['production']['name'];
        $dbFileAbsolutePath = sprintf('%s/%s',
            dirname($this->configFilePath),
            $dbFileRelativePath);
        
        $configuration = new Configuration();
        $configuration->databasePath = $dbFileAbsolutePath;
        return $configuration;
    }
}