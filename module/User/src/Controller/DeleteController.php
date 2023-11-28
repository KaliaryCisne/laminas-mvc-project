<?php

declare(strict_types=1);

namespace User\Controller;

class DeleteController extends BaseController
{
    public function indexAction()
    {
        $id = $this->params()->fromRoute('key', null);
        $this->table->deleteModel($id);
        return $this-$this->redirect()->toRoute('user');
    }
}
