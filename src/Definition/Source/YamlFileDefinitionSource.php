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

        // @TODO konwencja - poprzedzone @ oznacza DI\link, pozostale DI\object i trzeba przeleciec array_costam

        $this->addDefinitions($definitions);

        $this->initialized = true;
    }
} 