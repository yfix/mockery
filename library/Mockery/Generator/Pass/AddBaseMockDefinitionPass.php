<?php

namespace Mockery\Generator\Pass;

use Mockery\Generator\MockConfiguration;
use Mockery\Generator\PHPParser\ConditionalNodeTraverser;

class AddBaseMockDefinitionPass
{
    protected static $cache;

    public function __construct(\PHPParser_NodeTraverser $traverser = null, \PHPParser_Parser $parser = null, $path = null)
    {
        $this->traverser = $traverser ?: new ConditionalNodeTraverser;
        $this->path = $path ?: __DIR__.'/../../Mock.php';
        $this->parser = $parser ?: new \PHPParser_Parser(new \PHPParser_Lexer);
    }
    
    public function execute(MockConfiguration $config, \PHPParser_Builder_Class $mock)
    {
        /**
         * Should resolve all names for use, so we don't have to worry about 
         * namespaces etc
         */
        $this->traverser->addVisitor(new \PHPParser_NodeVisitor_NameResolver());

        if ($config->requiresCallTypeHintRemoval()) {
            $visitor = new Visitor\RemoveMagicCallTypeHintVisitor;
            $this->traverser->addConditionalVisitor($visitor);
        }

        if ($config->requiresCallStaticTypeHintRemoval()) {
            $visitor = new Visitor\RemoveMagicCallStaticTypeHintVisitor;
            $this->traverser->addConditionalVisitor($visitor);
        }

        if ($config->isInstanceMock()) {
            $propertyVisitor = new Visitor\InstanceMockIgnoreVerificationVisitor();
            $this->traverser->addConditionalVisitor($propertyVisitor);
        }


        $interfaceInjector = new Visitor\MockInterfaceInjectorVisitor($mock);
        $this->traverser->addConditionalVisitor($interfaceInjector);

        if (!isset(static::$cache[$this->path])) {
            $base = $this->parser->parse(file_get_contents($this->path));
            static::$cache[$this->path] = $base;
        }

        $nodes = $this->traverser->traverse(static::$cache[$this->path]);

        /**
         * Now we've mangled the nodes, this traverser will copy the statements 
         * to our new mock
         */
        $traverser = new ConditionalNodeTraverser;
        $stmtInjector = new Visitor\MockStmtInjectorVisitor($mock);
        $traverser->addConditionalVisitor($stmtInjector);
        $traverser->traverse($nodes);
    }
}
