<?php

namespace Mockery\Generator\Pass;

use Mockery\Generator\MockConfiguration;

class AddInstanceMockDefinition
{
    public function __construct(\PHPParser_Template $template = null, \PHPParser_BuilderFactory $factory = null) 
    {
        $this->template = $template;
        $this->factory = $factory ?: new \PHPParser_BuilderFactory;
    }

    public function execute(MockConfiguration $config, \PHPParser_Builder_Class $mock)
    {
        $stmts = $this->getTemplate()->getStmts(array());
        $method = $this->factory->method('__construct')->addStmts($stmts);
        $mock->addStmt($method);
    }

    protected function getTemplate()
    {
        if ($this->template) {
            return $this->template;
        }

        $parser = new \PHPParser_Parser(new \PHPParser_Lexer);

        return $this->template = new \PHPParser_Template($parser, file_get_contents(__DIR__.'/templates/instance_mock.php'));
    }
}
