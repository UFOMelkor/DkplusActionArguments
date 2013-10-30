<?php
namespace DkplusActionArguments\Converter;

use Doctrine\Common\Persistence\ObjectRepository;

/**
 * Uses doctrine to convert the values.
 */
class DoctrineConverter extends Converter
{
    /** @var ObjectRepository */
    private $repository;

    /** @var string */
    private $method;

    /**
     * @param ObjectRepository $repository
     * @param string           $method
     */
    public function __construct(ObjectRepository $repository, $method)
    {
        $this->repository = $repository;
        $this->method     = $method;
    }

    /**
     * @param mixed $value
     * @return mixed the entity
     */
    public function convert($value)
    {
        return call_user_func(array($this->repository, $this->method), $value);
    }
}
