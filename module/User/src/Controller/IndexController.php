<?php

declare(strict_types=1);

namespace User\Controller;

use Auth\Service\AuthService;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\MvcEvent;
use Laminas\View\Model\ViewModel;
use User\Form\SearchForm;
use User\Model\UserTable;

class IndexController extends AbstractActionController
{
    private $table;

    private $authService;

    public function __construct(UserTable $table, $sessionManager, AuthService $authService)
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

    public function indexAction()
    {
        $form = new SearchForm();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = $request->getPost();
            $name = $post['name'];
            $models = $this->table->getModelByName($name);
        } else {
            $models = $this->table->fetchAll();
        }

        return new ViewModel([
            'models' => $models,
            'form' => $form
        ]);
    }
}
