<?php

declare(strict_types=1);

namespace User\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Session\Container as SessionContainer;
use User\Form\User as UserForm;
use User\Model\User;

class CreateController extends AbstractActionController
{
    private $table;

    public function __construct($table, $sessionManager)
    {
        $this->table = $table;
        $sessionManager->start();
    }

    public function indexAction()
    {
        $user = $this->table->getModel(null);
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
                    'controller' => 'create'
                ]);
            }
            $user->exchangeArray($form->getData());
            $this->table->saveModel($user);
        }

        return $this->redirect()->toRoute('user');
    }
}
