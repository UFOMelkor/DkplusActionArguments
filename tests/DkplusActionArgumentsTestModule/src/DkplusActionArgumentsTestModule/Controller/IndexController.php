<?php
namespace DkplusActionArgumentsTestModule\Controller;

use DkplusActionArguments\Annotation\MapParam;
use DkplusActionArguments\Controller\AbstractActionController;
use DkplusActionArgumentsTestModule\Entity\User;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function viewAction(User $user)
    {
        return array('user' => $user);
    }

    /**
     * @MapParam(from="name", to="user", using="findOneByName")
     */
    public function viewSingleAction(User $user)
    {
        $model = new ViewModel(array('user' => $user));
        $model->setTemplate('dkplus-action-arguments-test-module/index/view');
        return $model;
    }

    /**
     * @MapParam(to="users", using={"myRepository", "findAll"})
     */
    public function viewAllAction(array $users)
    {
        return array('users' => $users);
    }
}
