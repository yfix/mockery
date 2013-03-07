<?php

namespace Mockery\Generator;

class CachingGenerator implements GeneratorInterface
{
    protected $generator;
    protected $cache = array();

    public function __construct(GeneratorInterface $generator)
    {
        $this->generator = $generator;
    }

    public function generate(MockConfiguration $config)
    {
        $hash = $config->getHash();
        if (isset($this->cache[$hash])) {
            $config->setName($this->cache[$hash]);
            return "";
        }

        $retval = $this->generator->generate($config);
        $this->cache[$hash] = $config->getName();

        return $retval;
    }

    public function define(MockConfiguration $config)
    {
        // hmmm don't like this. We should probably have a loader entirely 
        // separate to the generator
        $definition = $this->generate($config);
        eval($definition);
    }
}
