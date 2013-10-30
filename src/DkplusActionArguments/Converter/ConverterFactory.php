<?php
namespace DkplusActionArguments\Converter;

use Doctrine\Common\Persistence\ObjectRepository;
use Zend\ServiceManager\ServiceLocatorInterface;

class ConverterFactory
{
    /** @var \Zend\ServiceManager\ServiceLocatorInterface */
    private $services;

    public function __construct(ServiceLocatorInterface $services)
    {
        $this->services = $services;
    }

    public function create($converter, $targetTypeOrClass)
    {
        if (is_array($converter)) {
            return new CallbackConverter(array($this->services->get($converter[0]), $converter[1]));
        }

        if ($this->services->has($converter)) {
            return $this->services->get($converter);
        }

        /* @var $objectManager \Doctrine\Common\Persistence\ObjectManager */
        $objectManager = $this->services->get('Doctrine\\ORM\\EntityManager');
        $repository    = $objectManager->getRepository($targetTypeOrClass);

        if ($repository instanceof ObjectRepository) {
            return new DoctrineConverter($repository, $converter);
        }

        return null;
    }
}
