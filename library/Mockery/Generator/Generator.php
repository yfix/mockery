<?php

namespace Mockery\Generator;

class Generator 
{
    public function generate(MockConfiguration $config)
    {
        $factory = new \PHPParser_BuilderFactory;
        
        $mock = $factory->class($config->getShortName());

        $addClassDefinition = new Pass\AddClassDefinitionPass;
        $addClassDefinition->execute($config, $mock);

        $addBaseMockDefinition = new Pass\AddBaseMockDefinitionPass;
        $addBaseMockDefinition->execute($config, $mock);

        if ($config->isInstanceMock()) {
            $addInstanceMockDefinition = new Pass\AddInstanceMockDefinitionPass;
            $addInstanceMockDefinition->execute($config, $mock);
        }

        $addTargetMockMethods = new Pass\AddTargetMockMethodsPass;
        $addTargetMockMethods->execute($config, $mock);

        $prettyPrinter = new \PHPParser_PrettyPrinter_Default;

        $mock = $mock->getNode();

        if ($config->getNamespaceName()) {
            $mock = new \PHPParser_Node_Stmt_Namespace(new \PHPParser_Node_Name($config->getNamespaceName()), array($mock));
        }

        /* echo "\n============================================================\n"; */
        /* echo($prettyPrinter->prettyPrint(array($mock))); */
        /* echo "\n============================================================\n"; */
        file_put_contents("/tmp/last_mock.php", "<?php " . $prettyPrinter->prettyPrint(array($mock)));
        eval($prettyPrinter->prettyPrint(array($mock)));
    }

}
