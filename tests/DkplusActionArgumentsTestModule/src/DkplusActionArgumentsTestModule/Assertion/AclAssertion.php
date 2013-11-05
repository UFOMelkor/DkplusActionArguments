<?php
namespace DkplusActionArgumentsTestModule\Assertion;

use DkplusActionArgumentsTestModule\Entity\User;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Assertion\AssertionInterface;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use Zend\Permissions\Acl\Role\RoleInterface;

class AclAssertion implements AssertionInterface
{
    /** @var User */
    protected $user;

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function assert(Acl $acl, RoleInterface $role = null, ResourceInterface $resource = null, $privilege = null)
    {
        return $this->user
            && $this->user->getName() == 'Hans';
    }
}
