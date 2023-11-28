<?php

declare(strict_types=1);

namespace User\Controller;

use Auth\Service\AuthService;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\MvcEvent;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Session\Container as SessionContainer;
use Laminas\Session\SessionManager;
use User\Form\User as UserForm;
use User\Model\User;
use User\Model\UserTable;

class BaseController extends AbstractActionController
{

    protected $authService;

    protected $table;

    public function __construct(UserTable $table, SessionManager $sessionManager, AuthService $authService)
    {
        $this->table = $table;
        $this->authService = $authService;
        $sessionManager->start();
    }

    public function onDispatch(MvcEvent $event)
    {
        if (!$this->authService->hasIdentity()) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_403);
            return $this->redirect()->toRoute('forbidden');
        }
        return parent::onDispatch($event);
    }

}
