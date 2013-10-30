<?php
namespace DkplusActionArguments\Service;

use Zend\Authentication\AuthenticationService;
use Zend\EventManager\EventManagerInterface;
use Zend\Permissions\Rbac\Rbac as ZendRbac;
use ZfcRbac\Assertion\AssertionInterface;
use ZfcRbac\Firewall\AbstractFirewall;
use ZfcRbac\Identity;
use ZfcRbac\Provider\AbstractProvider;
use ZfcRbac\Service\Rbac;

/**
 * Connects an assertion with a permission using ZfcRbac.
 *
 * It decorates \ZfcRbac\Service\Rbac to provide this ability.
 */
class ZfcRbacServiceDecorator extends Rbac implements RbacAssertionPermissionConnector
{
    /** @var Rbac */
    private $decorated;

    /** @var AssertionInterface[] */
    private $assertions = array();

    /**
     * @param  string                                   $permission
     * @param \Zend\Permissions\Rbac\AssertionInterface $assertion
     * @return void
     */
    public function connect($permission, $assertion)
    {
        $this->assertions[$permission] = $assertion;
    }

    /**
     * @param string                          $permission
     * @param null|\Closure|AssertionInterface $assert
     * @return bool
     */
    public function isGranted($permission, $assert = null)
    {
        if ($assert === null
            && isset($this->assertions[$permission])
        ) {
            $assert = $this->assertions[$permission];
        }
        return $this->decorated->isGranted($permission, $assert);
    }
    /**
     * @param EventManagerInterface $events
     * @return self
     */
    public function setEventManager(EventManagerInterface $events)
    {
        $this->decorated->setEventManager($events);
        return $this;
    }

    /**
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        return $this->decorated->getEventManager();
    }

    /**
     * @param array|string $roles
     * @return boolean
     */
    public function hasRole($roles)
    {
        return $this->decorated->hasRole($roles);
    }


    /**
     * @param string $name
     * @return AbstractFirewall
     */
    public function getFirewall($name)
    {
        return $this->decorated->getFirewall($name);
    }

    /**
     * @param AbstractFirewall $firewall
     * @return self
     */
    public function addFirewall(AbstractFirewall $firewall)
    {
        $this->decorated->addFirewall($firewall);
        return $this;
    }

    /**
     * @param AbstractProvider $provider
     * @return self
     */
    public function addProvider(AbstractProvider $provider)
    {
        $this->decorated->addProvider($provider);
        return $this;
    }

    /**
     * @return Identity\IdentityInterface
     */
    public function getIdentity()
    {
        return $this->decorated->getIdentity();
    }

    /**
     * @param  string|null|AuthenticationService|Identity\IdentityInterface $identity
     * @return self
     */
    public function setIdentity($identity = null)
    {
        $this->decorated->setIdentity($identity);
        return $this;
    }

    /** @return ZendRbac */
    public function getRbac()
    {
        return $this->decorated->getRbac();
    }

    /** @return \ZfcRbac\Service\RbacOptions */
    public function getOptions()
    {
        return $this->decorated->getOptions();
    }
}
