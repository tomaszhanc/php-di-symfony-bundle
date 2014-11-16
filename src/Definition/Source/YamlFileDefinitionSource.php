<?php

namespace DI\Bundle\Symfony\Definition\Source;

use DI\Definition\Exception\DefinitionException;
use DI\Definition\MergeableDefinition;
use DI\Definition\Source\ArrayDefinitionSource;
use Symfony\Component\Yaml\Yaml;

class YamlFileDefinitionSource extends ArrayDefinitionSource
{
    private $file;

    private $initialized;

    public function __construct($file)
    {
        $this->file = $file;
    }

    public function getDefinition($name, MergeableDefinition $parentDefinition = null)
    {
        $this->initialize();

        return parent::getDefinition($name, $parentDefinition);
    }

    private function initialize()
    {
        if ($this->initialized === true) {
            return;
        }

        if (!is_readable($this->file)) {
            throw new DefinitionException("File {$this->file} doesn't exist or is not readable");
        }

        $definitions = Yaml::parse(file_get_contents($this->file));
        $definitions = $this->parseDefinitions($definitions);

        $this->addDefinitions($definitions);

        $this->initialized = true;
    }

    private function parseDefinitions(array $definitions)
    {
        return array_map(function($service) {
            if (preg_match('/^@/', $service)) {
                return \DI\link(preg_replace('/^@(.+)/', '$1', $service));
            } else {
                return \DI\object($service);
            }
        }, $definitions);
    }
}