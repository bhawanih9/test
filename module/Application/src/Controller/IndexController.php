<?php

/**
 * @see       https://github.com/laminas/laminas-mvc-skeleton for the canonical source repository
 * @copyright https://github.com/laminas/laminas-mvc-skeleton/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-mvc-skeleton/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Application\Form\LoginForm;
use Application\Form\Filter\LoginFilter;
use Laminas\Validator;
use Laminas\I18n;
use Laminas\Validator\Regex;
use Laminas\Session\SessionManager;
use Laminas\Session\Validator\HttpUserAgent;

class IndexController extends AbstractActionController
{
    protected $_adminMapper;
	public function indexAction()
    {
        session_start();
        if (isset($_SESSION['u_id'])) {
            return $this->redirect()->toRoute('tracker');
        } else {
            $form = new LoginForm();
            $form->setName('logincheck');
            $form->setAttribute('class', 'form-signin');
            $form->setAttribute('class', 'form-signin form-horizontal');
            $form->setAttribute('style', 'background:white;border: 1px solid #ccc;');
            $viewModel = new ViewModel();
            $viewModel->setVariables(array('form' => $form));
            return $viewModel;
        }
    }
	public function logoutAction()
    {
        session_start();
        if (isset($_SESSION['u_id'])) {
            $this->getAdminService()->userLogout();
        }
        session_destroy();
        return $this->redirect()->toRoute('home');
    }

    public function logincheckAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $validator = new Regex(array('pattern' => '/[A-Za-z0-9_~\-!@#\$%\^&\*\(\)]+$/'));
            $post = $this->getRequest()->getPost()->toArray();
            $resultsArr=array();
            $form = new LoginForm('loginForm');
            $form->setInputFilter(new LoginFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $results = $this->getAdminService()->userLogin($post);
                print_r(json_encode($results));
            } else {
                print_r(json_encode($form->getMessages()));
            }
        } else {
            echo "Access Denied";
            exit;
        }
        exit;
    }
	public function getAdminService()
    {
        if (!$this->_adminMapper) {
            $sm = $this->getServiceLocator();
            $this->_adminMapper = $sm->get('Application\Model\AdminMapper');
        }
        return $this->_adminMapper;
    }


}
