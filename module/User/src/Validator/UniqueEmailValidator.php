<?php

namespace User\Validator;

use Laminas\Validator\AbstractValidator;
use User\Model\UserTable;

class UniqueEmailValidator extends AbstractValidator
{
    public const EMAIL_EXISTS = 'emailExists';

    protected $messageTemplates = [
        self::EMAIL_EXISTS => "Este e-mail já está em uso.",
    ];

    protected $userTable;

    public function __construct($options)
    {
        parent::__construct();
        $this->userTable = $options['userTable'];
    }

    public function isValid($value)
    {
        if ($this->userTable->emailExists($value)) {
            $this->error(self::EMAIL_EXISTS);
            return false;
        }
        return true;
    }
}