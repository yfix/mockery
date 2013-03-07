<?php

namespace Mockery\Generator;

interface GeneratorInterface 
{
    public function generate(MockConfiguration $config);
    public function define(MockConfiguration $config);
}
