<?php

namespace User\Model;

use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Where;
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
    public function	getModelById($keyValue)
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

    public function	getModelByEmail($email)
    {
        $rowset	= $this->tableGateway->select([
            'email' => $email
        ]);

        if ($rowset->count() > 0) {
            $row = $rowset->current();
        } else {
            $row = new User();
        }

        return $row;
    }

    public function	getModelByName($name)
    {
        $name = strtolower($name);
        $result	= $this->tableGateway->select(function (Select $select) use($name) {
            $where = new Where();
            $where->like('name', '%' . $name . '%');
            $select->where($where);
        });

        if ($result->count() > 0) {
            return $result;
        }

        return [];
    }

    /**
     * @param User $user
     */
    public function saveModel(User $user)
    {
        $data = [
            'name' => $user->name,
            'email' => $user->email,
        ];


        if (!empty($user->password)) {
            $data['password'] = password_hash($user->password, PASSWORD_BCRYPT);
        }

        $id = $user->id;
        if (empty($id)) {
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

    public function emailExists($email)
    {
        $rowset = $this->tableGateway->select(['email' => $email]);
        $row = $rowset->current();
        return $row ? true : false;
    }
}