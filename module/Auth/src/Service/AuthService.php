<?php

namespace Auth\Service;

use Laminas\Authentication\Adapter\DbTable\CallbackCheckAdapter as DbTableAuthAdapter;
use Laminas\Authentication\AuthenticationService;
use Laminas\Authentication\Storage\Session;
use User\Model\UserTable;

class AuthService extends AuthenticationService
{
    private $authAdapter;

    private $userTable;

    public function __construct(DbTableAuthAdapter $authAdapter, UserTable $userTable)
    {
        parent::__construct(new Session());
        $this->authAdapter = $authAdapter;
        $this->userTable = $userTable;
    }

    public function auth($email, $password)
    {
        $storedPassword = $this->getUserStoredPassword($email);
        if (password_verify($password, $storedPassword)) {
            $this->authAdapter->setIdentity($email)
                ->setCredential($storedPassword);

            $result = parent::authenticate($this->authAdapter);

            return $result;
        }
    }

    public function clearIdentity()
    {
        parent::clearIdentity();
    }

    private function getUserStoredPassword($email)
    {
        $resultSet = $this->userTable->getModelByEmail($email);
        if (!is_null($resultSet->email)) {
            return $resultSet->password;
        }
        return null;
    }
}