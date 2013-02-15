<?php

namespace Mockery\Generator\Visitor;

class InstanceMockIgnoreVerificationVisitor extends \PHPParser_NodeVisitorAbstract
{
    public function leaveNode(\PHPParser_Node $node) {
        if ($node instanceof \PHPParser_Node_Stmt_PropertyProperty && $node->name == "_mockery_ignoreVerification") {
            $node->default = new \PHPParser_Node_Expr_ConstFetch(new \PHPParser_Node_Name('true'));
        }
    }
}
