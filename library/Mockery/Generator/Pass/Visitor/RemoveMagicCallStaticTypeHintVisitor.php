<?php

namespace Mockery\Generator\Pass\Visitor;

use Mockery\Generator\PHPParser\ConditionalNodeVisitor;

class RemoveMagicCallStaticTypeHintVisitor extends \PHPParser_NodeVisitorAbstract implements ConditionalNodeVisitor
{
    protected $finished = false;

    public function enterNode(\PHPParser_Node $node) {
        if ($node instanceof \PHPParser_Node_Stmt_ClassMethod && $node->name == "__callStatic") {
            $params = $node->params;
            $params[1]->type = null;
            $this->finished = true;
        }

    }

    public function isFinished()
    {
        return $this->finished;
    }
}
