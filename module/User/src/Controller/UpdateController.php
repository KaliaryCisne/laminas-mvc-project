<?php

declare(strict_types=1);

namespace User\Controller;

use Laminas\Session\Container as SessionContainer;
use User\Form\UserForm as UserForm;
use User\Model\User;

class UpdateController extends BaseController
{
    public function indexAction()
    {
        $id = $this->params()->fromRoute('key');
        $user = $this->table->getModelById($id);
        $user->password = '';
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
            $userOld = $this->table->getModelById($post['id']);
            $form = new UserForm();
            $form->setData($post);
            $user = new User();
            $form->setInputFilter($user->getInputFilter($this->table, $post, $userOld));
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
