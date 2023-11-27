<?php

declare(strict_types=1);

namespace User\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use User\Form\User as UserForm;
use User\Model\User;
use Laminas\Session\Container as SessionContainer;
use User\Model\UserTable;

class UpdateController extends AbstractActionController
{
    private $table;

    public function __construct(UserTable $table, $sessionManager)
    {
        $this->table = $table;
        $sessionManager->start();
    }

    public function indexAction()
    {
        $id = $this->params()->fromRoute('key');
        $user = $this->table->getModel($id);
        $form = new UserForm();
        $form->get('submit')->setValue('Salvar');
        $sessionContainer = new SessionContainer();
        if (isset($sessionContainer->model)) {
            $user->exchangeArray($sessionContainer->model->toArray());
            unset($sessionContainer->model);
            $form->setInputFilter($user->getInputFilter($this->table));
        }
        $form->bind($user);
        $form->isValid();
        return ['form' => $form];
    }

    public function saveAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form = new UserForm();
            $user = new User();
            $form->setInputFilter($user->getInputFilter($this->table));
            $post = $request->getPost();
            $form->setData($post);
            if (!$form->isValid()) {
                $sessionContainer = new SessionContainer();
                $sessionContainer->model = $post;
                return $this->redirect()->toRoute('user', [
                    'action' => 'index',
                    'controller' => 'update'
                ]);
            }
            $user->exchangeArray($form->getData());
            $this->table->saveModel($user);
        }

        return $this->redirect()->toRoute('user');
    }
}
