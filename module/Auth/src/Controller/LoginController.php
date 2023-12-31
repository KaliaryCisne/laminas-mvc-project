<?php

namespace Auth\Controller;

use Auth\Form\LoginForm;
use Auth\Service\AuthService;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Session\Container as SessionContainer;
use Laminas\View\Model\ViewModel;

class LoginController extends AbstractActionController
{
    private $authService;

    private $form;

    public function __construct(AuthService $authService, LoginForm $form)
    {
        $this->authService = $authService;
        $this->form = $form;
    }

    public function indexAction()
    {
        if ($this->authService->hasIdentity()) {
            return $this->redirect()->toRoute('home');
        }

        $request = $this->getRequest();

        if ($request->isPost()) {
            $this->form->setData($request->getPost());

            if ($this->form->isValid()) {
                $data = $this->form->getData();
                $result = $this->authService->auth($data['email'], $data['password']);

                if (!is_null($result) && $result->isValid()) {
                    return $this->redirect()->toRoute('home');
                }
            }
        }

        return new ViewModel(['form' => $this->form]);
    }
}