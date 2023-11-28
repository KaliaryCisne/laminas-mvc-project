<?php

namespace Auth\Controller;

use Auth\Service\AuthService;
use Laminas\Mvc\Controller\AbstractActionController;

class LogoutController extends AbstractActionController
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function indexAction()
    {
        $this->authService->clearIdentity();
        return $this->redirect()->toRoute('login');
    }
}