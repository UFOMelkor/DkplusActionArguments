<?php
namespace DkplusActionArguments\Service;

use ZfcRbac\Assertion\AssertionInterface;
use ZfcRbac\Identity;
use ZfcRbac\Service\AuthorizationService;

/**
 * Connects an assertion with a permission using ZfcRbac.
 *
 * It decorates \ZfcRbac\Service\AuthenticationService to provide this ability.
 */
class ZfcRbacServiceDecorator extends AuthorizationService implements RbacAssertionPermissionConnector
{
    /** @var AuthenticationService */
    private $decorated;

    /** @var AssertionInterface[] */
    protected $assertions = array();

    public function __construct(AuthorizationService $decorated)
    {
        $this->decorated = $decorated;
    }

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
}
