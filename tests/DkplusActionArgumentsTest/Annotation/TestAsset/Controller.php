<?php
namespace DkplusActionArgumentsTest\Annotation\TestAsset;

class Controller
{
    /**
     * @Guard(assertion="foo")
     */
    public function dummyAction(Controller $controller = null)
    {
    }
}
 