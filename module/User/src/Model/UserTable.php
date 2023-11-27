<?php

namespace User\Model;

use Laminas\Db\TableGateway\TableGatewayInterface;
use Laminas\Db\Adapter\Driver\ResultInterface;

class UserTable
{
    /**
     * @var TableGatewayInterface
     */
    private $tableGateway;

    /**
     * @var string
     */
    private $keyName = 'id';

    /**
     * @param TableGatewayInterface $tableGateway
     */
    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    /**
     * @return ResultInterface
     */
    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    /**
     * @param $keyValue
     * @return User
     */
    public function	getModel($keyValue)
    {
        $rowset	= $this->tableGateway->select([
            $this->keyName => $keyValue
        ]);

        if ($rowset->count() > 0) {
            $row = $rowset->current();
        } else {
            $row = new User();
        }

        return $row;
    }

    /**
     * @param User $user
     */
    public function saveModel(User $user)
    {
        $data = [
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password
        ];

        $id = $user->id;
        if (empty($this->getModel($id)->id)) {
            $data['id'] = $id;
            $this->tableGateway->insert($data);
        } else {
            $this->tableGateway->update($data, [
               'id' => $id
            ]);
        }
    }

    /**
     * @param mixed $keyValue
     */
    public function deleteModel($keyValue)
    {
        $this->tableGateway->delete([
            $this->keyName => $keyValue
        ]);
    }
}