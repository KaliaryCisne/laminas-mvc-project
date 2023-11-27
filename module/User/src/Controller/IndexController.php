<?php

declare(strict_types=1);

namespace User\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    private $table;

    public function __construct($table)
    {
        $this->table = $table;
    }

    public function indexAction()
    {
        return new ViewModel([
            'models' => $this->table->fetchAll()
        ]);
    }
}
