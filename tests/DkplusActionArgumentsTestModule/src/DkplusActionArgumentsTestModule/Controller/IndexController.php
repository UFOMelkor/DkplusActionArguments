<?php
namespace DkplusActionArgumentsTestModule\Controller;

use DkplusActionArguments\Annotation\MapParam;
use DkplusActionArguments\Controller\AbstractActionController;
use DkplusActionArgumentsTestModule\Entity\User;

class IndexController extends AbstractActionController
{
    public function viewAction(User $user)
    {
        return array('user' => $user);
    }

    /**
     * @MapParam(to="users", using={"myRepository", "findAll"})
     */
    public function viewAllAction(array $users)
    {
        return array('users' => $users);
    }
}
