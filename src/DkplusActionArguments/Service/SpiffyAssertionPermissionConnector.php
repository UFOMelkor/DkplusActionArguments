<?php
namespace DkplusActionArguments\Service;

use SpiffyAuthorize\Service\RbacService;

/**
 * Connects an assertion with a permission using SpiffyAuthorize.
 */
class SpiffyAssertionPermissionConnector implements RbacAssertionPermissionConnector
{
    /** @var RbacService */
    private $rbacService;

    /** @param RbacService $rbacService */
    public function __construct(RbacService $rbacService)
    {
        $this->rbacService = $rbacService;
    }

    /**
     * @param string                                    $permission
     * @param \Zend\Permissions\Rbac\AssertionInterface $assertion
     * @return void
     */
    public function connect($permission, $assertion)
    {
        $this->rbacService->registerAssertion($permission, $assertion);
    }
}
