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
    public function __construct(ObjectRepository $repository, $method = null)
    {
        $this->repository = $repository;
        $this->method     = $method ? $method : 'find';
    }

    /**
     * @param mixed $value
     * @return mixed the entity
     */
    public function apply($value)
    {
        return call_user_func(array($this->repository, $this->method), $value);
    }
}
