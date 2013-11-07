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
        $this->setApplicationConfig(
            include __DIR__ . '/../../DkplusActionArgumentsTestModule/config/application.config.php'
        );
        parent::setUp();

        $repositoryMethods   = array('find', 'findAll', 'findBy', 'findOneBy', 'getClassName', 'findOneByName');
        $this->repository    = $this->getMockBuilder('Doctrine\\Common\\Persistence\\ObjectRepository')
                                    ->setMethods($repositoryMethods)
                                    ->getMock();
        $this->entityManager = $this->getMockForAbstractClass('Doctrine\\Common\\Persistence\\ObjectManager');
        $this->entityManager->expects($this->any())
                            ->method('getRepository')
                            ->with('DkplusActionArgumentsTestModule\\Entity\\User')
                            ->will($this->returnValue($this->repository));
        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setService('Doctrine\\ORM\\EntityManager', $this->entityManager);
        $serviceManager->setService('myRepository', $this->repository);
    }

    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();
        $specFile = __DIR__
                  . '/../../DkplusActionArgumentsTestModule/config/autoload/dkplus-action-arguments.spec.global.php';
        if (file_exists($specFile)) {
            unlink($specFile);
        }
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

    public function testShouldResultInA404ResponseIfTheUserCouldNotBeFound()
    {
        $this->repository->expects($this->once())
                         ->method('find')
                         ->with(6)
                         ->will($this->returnValue(null));

        $this->dispatch('/view/6');
        $this->assertResponseStatusCode(404);
    }

    public function testShouldFindAnEntityWithMapping()
    {
        $user = new User(5, 'Hans');
        $this->repository->expects($this->once())
                         ->method('findOneByName')
                         ->with('hans')
                         ->will($this->returnValue($user));
        $this->dispatch('/view/hans');
        $this->assertResponseStatusCode(200);
        $this->assertQueryContentContains('b', 'Hans');
    }

    public function testShouldConvertWithoutRouteParameter()
    {
        $userA = new User(5, 'Hans');
        $userB = new User(6, 'Dirk');
        $this->repository->expects($this->once())
                         ->method('findAll')
                         ->will($this->returnValue(array($userA, $userB)));

        $this->dispatch('/view-all');
        $this->assertResponseStatusCode(200);
        $this->assertQueryContentContains('li', 'Hans');
        $this->assertQueryContentContains('li', 'Dirk');
    }
}
