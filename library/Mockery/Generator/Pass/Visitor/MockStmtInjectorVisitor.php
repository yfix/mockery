<?php

namespace Mockery\Generator\Pass\Visitor;

use Mockery\Generator\PHPParser\ConditionalNodeVisitor;

class MockStmtInjectorVisitor extends \PHPParser_NodeVisitorAbstract implements ConditionalNodeVisitor
{
    protected $target;
    protected $finished = false;

    public function __construct(\PHPParser_Builder_Class $target)
    {
        $this->target = $target;
    }

    public function enterNode(\PHPParser_Node $node) {
        if ($node instanceof \PHPParser_Node_Stmt_Class && $node->name == "Mock") {
            $this->target->addStmts($node->stmts);
            $this->finished = true;
        }
    }

    public function isFinished()
    {
        return $this->finished;
    }


}
