<?php

declare(strict_types=1);

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }

    public function forbiddenAction()
    {
        //todo: melhorar essa implementação
        $viewModel = new ViewModel();
        $viewModel->setTemplate('error/403');
        return $viewModel;
    }
}
