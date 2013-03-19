<?php

namespace Mockery\Generator\PHPParser;

interface ConditionalNodeVisitor extends \PHPParser_NodeVisitor
{

    public function isFinished();
}
