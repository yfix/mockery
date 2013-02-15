<?php

namespace Mockery\Generator;

class Generator 
{
    /**
     * Use PHPParser to build a mock based on $config
     *
     * @param MockConfiguration $config
     * @return \PHPParser_Node_Stmt
     */
    public function build(MockConfiguration $config)
    {
        $factory = new \PHPParser_BuilderFactory;
        
        $mock = $factory->class($config->getShortName());

        $addClassDefinition = new Pass\AddClassDefinitionPass;
        $addClassDefinition->execute($config, $mock);

        $addBaseMockDefinition = new Pass\AddBaseMockDefinitionPass;
        $addBaseMockDefinition->execute($config, $mock);

        if ($config->isInstanceMock()) {
            $addInstanceMockConstructor = new Pass\AddInstanceMockConstructorPass;
            $addInstanceMockConstructor->execute($config, $mock);
        }

        $addTargetMockMethods = new Pass\AddTargetMockMethodsPass;
        $addTargetMockMethods->execute($config, $mock);

        $mock = $mock->getNode();

        if ($config->getNamespaceName()) {
            $mock = new \PHPParser_Node_Stmt_Namespace(new \PHPParser_Node_Name($config->getNamespaceName()), array($mock));
        }

        return $mock;
    }

    /**
     * Generate PHP for a mock based on $config
     *
     * @param MockConfiguration $config
     * @return string
     */
    public function generate(MockConfiguration $config)
    {
        $mock = $this->build($config);
        $prettyPrinter = new \PHPParser_PrettyPrinter_Default;
        return $prettyPrinter->prettyPrint(array($mock));
    }

    /**
     * Declare a class based on $config
     *
     * @param MockConfiguration $config
     */
    public function define(MockConfiguration $config)
    {
        $definition = $this->generate($config);
        eval($definition);
    }

}
