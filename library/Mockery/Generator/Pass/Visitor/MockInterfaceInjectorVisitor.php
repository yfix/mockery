<?php

namespace Mockery\Generator\Pass\Visitor;

use Mockery\Generator\PHPParser\ConditionalNodeVisitor;

class MockInterfaceInjectorVisitor extends \PHPParser_NodeVisitorAbstract implements ConditionalNodeVisitor
{
    protected $target;
    protected $finished = false;

    public function __construct(\PHPParser_Builder_Class $target)
    {
        $this->target = $target;
    }

    public function enterNode(\PHPParser_Node $node) {
        if ($node instanceof \PHPParser_Node_Stmt_Class && $node->name == "Mock") {
            foreach ($node->implements as $interface) {
                $this->target->implement($interface);
            }
            $this->finished = true;
        }
    }

    public function isFinished()
    {
        return $this->finished;
    }


}
