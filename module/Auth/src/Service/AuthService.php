<?php

namespace Auth\Service;

use Laminas\Authentication\Adapter\DbTable\CallbackCheckAdapter as DbTableAuthAdapter;
use Laminas\Authentication\AuthenticationService;
use Laminas\Authentication\Storage\Session;

class AuthService extends AuthenticationService
{
    private $authAdapter;

    public function __construct(DbTableAuthAdapter $authAdapter)
    {
        parent::__construct(new Session());
        $this->authAdapter = $authAdapter;
    }

    public function auth($email, $password)
    {
        $this->authAdapter->setIdentity($email)
            ->setCredential($password);

        $result = parent::authenticate($this->authAdapter);

        return $result;
    }

    public function clearIdentity()
    {
        parent::clearIdentity();
    }
}