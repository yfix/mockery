<?php

namespace Mockery\Generator\PHPParser;

class ConditionalNodeTraverser extends \PHPParser_NodeTraverser
{
    protected $conditionalVisitors = array();
    protected $finished = false;

    protected function traverseArray(array $nodes)
    {
        /**
         * Visitors can mark themselves as finished, if they have all finished, 
         * we can stop traversing
         */
        if ($this->finished) {
            return $nodes;
        }

        $finished = true;
        foreach ($this->conditionalVisitors as $visitor) {
            $finished = $finished && $visitor->isFinished();
        }

        if ($finished) {
            $this->finished = true;
            return $nodes;
        }

        return parent::traverseArray($nodes);

    }

    public function addConditionalVisitor(ConditionalNodeVisitor $visitor)
    {
        $this->conditionalVisitors[] = $visitor;
        parent::addVisitor($visitor);
    }
}
