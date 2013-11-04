<?php
namespace DkplusActionArgumentsTest\Controller;

use DkplusActionArgumentsTestModule\Entity\User;
use Zend\Console\Console;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase as TestCase;

/**
 * @covers DkplusActionArguments\Controller\AbstractActionController
 */
class ControllerTest extends TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $entityManager;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $repository;

    protected function setUp()
    {
        $this->setApplicationConfig(include __DIR__ . '/../../DkplusActionArgumentsTestModule/config/application.config.php');
        parent::setUp();

        $this->repository    = $this->getMockForAbstractClass('Doctrine\\Common\\Persistence\\ObjectRepository');
        $this->entityManager = $this->getMockForAbstractClass('Doctrine\\Common\\Persistence\\ObjectManager');
        $this->entityManager->expects($this->any())
                            ->method('getRepository')
                            ->with('DkplusActionArgumentsTestModule\\Entity\\User')
                            ->will($this->returnValue($this->repository));
        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setService('Doctrine\\ORM\\EntityManager', $this->entityManager);
        $serviceManager->setService('myRepository', $this->repository);
    }

    public function testShouldFindAnEntityByIdWithoutMapping()
    {
        $user = new User(5, 'Hans');
        $this->repository->expects($this->once())
                         ->method('find')
                         ->with(5)
                         ->will($this->returnValue($user));

        $this->dispatch('/view/5');
        $this->assertResponseStatusCode(200);
        $this->assertQueryContentContains('b', 'Hans');
    }
}
