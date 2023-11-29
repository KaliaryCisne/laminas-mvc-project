<?php

declare(strict_types=1);

namespace User\Controller;


use Laminas\Session\Container as SessionContainer;
use User\Form\UserForm as UserForm;
use User\Model\User;

class CreateController extends BaseController
{
    public function indexAction()
    {
        $user = $this->table->getModelById(null);
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
            $post = $request->getPost();
            $form = new UserForm();
            $form->setData($post);
            $user = new User();
            $form->setInputFilter($user->getInputFilter($this->table, $post, null));
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
