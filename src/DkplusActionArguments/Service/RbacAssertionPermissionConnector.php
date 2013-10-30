<?php
namespace DkplusActionArguments\Service;

/**
 * Connects an assertion with a permission.
 */
interface RbacAssertionPermissionConnector
{
    /**
     * @param string                                                                          $permission
     * @param \Zend\Permissions\Rbac\AssertionInterface|\ZfcRbac\Assertion\AssertionInterface $assertion
     * @return void
     */
    public function connect($permission, $assertion);
}
