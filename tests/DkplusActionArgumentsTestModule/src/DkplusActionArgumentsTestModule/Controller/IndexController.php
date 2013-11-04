<?php
namespace DkplusActionArgumentsTestModule\Controller;

use DkplusActionArguments\Controller\AbstractActionController;
use DkplusActionArgumentsTestModule\Entity\User;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function viewAction(User $user)
    {
        return array('user' => $user);
    }

    public function viewAllAction()
    {
        return new ViewModel();
    }
}
