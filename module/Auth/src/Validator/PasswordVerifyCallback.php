<?php

namespace Application\Validator;

use Laminas\Validator\AbstractValidator;

class PasswordVerifyCallback extends AbstractValidator
{
    const INVALID = 'invalid';

    protected $messageTemplates = [
        self::INVALID => 'Senha inválida.',
    ];

    protected $userTable;

    public function __construct($options)
    {
        parent::__construct();
        $this->userTable = $options['userTable'];
    }

    public function isValid($value)
    {
        $this->setValue($value);
        if ($this->userTable->passwordVerify($value)) {
            $this->error(self::INVALID);
            return false;
        }
        return true;
    }

}