<?php
namespace DkplusActionArgumentsTestModule\Entity;

class User
{
    /** @var int */
    private $id;
    /** @var string */
    private $name;

    public function __construct($id, $name)
    {
        $this->id   = $id;
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
}
