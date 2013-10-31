<?php
namespace DkplusActionArgumentsTest\Annotation\TestAsset;

use DkplusActionArguments\Annotation\Guard;

class Controller
{
    /**
     * @Guard(assertion="foo")
     */
    public function dummyAction(Controller $controller = null)
    {
    }
}
