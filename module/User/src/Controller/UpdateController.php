<?php

declare(strict_types=1);

namespace User\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use User\Form\User as UserForm;

class UpdateController extends AbstractActionController
{
    public function indexAction()
    {
        $form = new UserForm();
        $form->get('submit')->setValue('Salvar');
        return ['form' => $form];
    }
}
