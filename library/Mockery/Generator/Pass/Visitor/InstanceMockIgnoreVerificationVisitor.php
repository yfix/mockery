<?php

namespace Mockery\Generator\Pass\Visitor;

use Mockery\Generator\PHPParser\ConditionalNodeVisitor;

class InstanceMockIgnoreVerificationVisitor extends \PHPParser_NodeVisitorAbstract implements ConditionalNodeVisitor
{
    protected $finished = false;

    public function enterNode(\PHPParser_Node $node) {
        if ($node instanceof \PHPParser_Node_Stmt_PropertyProperty && $node->name == "_mockery_ignoreVerification") {
            $node->default = new \PHPParser_Node_Expr_ConstFetch(new \PHPParser_Node_Name('true'));
            $this->finished = true;
        }
    }

    public function isFinished()
    {
        return $this->finished;
    }
}
