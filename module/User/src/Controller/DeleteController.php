<?php

declare(strict_types=1);

namespace User\Controller;

use Laminas\Mvc\Controller\AbstractActionController;

class DeleteController extends AbstractActionController
{
    private $table;

    public function __construct($table)
    {
        $this->table = $table;
    }

    public function indexAction()
    {
        $id = $this->params()->fromRoute('key', null);
        $this->table->deleteModel($id);
        return $this-$this->redirect()->toRoute('user');
    }
}
