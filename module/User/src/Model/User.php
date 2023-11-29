<?php

namespace User\Model;

use Laminas\InputFilter\InputFilterInterface;
use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\Factory;
use Laminas\Validator\EmailAddress;
use Laminas\Validator\NotEmpty;
use Laminas\Validator\Regex;
use Laminas\Validator\StringLength;
use User\Validator\UniqueEmailValidator;

class User
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $password;

    /**
     * @var InputFilterInterface
     */
    private $inputFilter;

    /**
     *  Popula os atributos da classe
     *	@param	array $data
     */
    public function	exchangeArray(array	$data)
    {
        foreach($data as $attribute	=>	$value){
            $this->$attribute =	$value;
        }
    }

    /**
     *
     *	@return	\Laminas\InputFilter\InputFilterInterface
     */
    public function getInputFilter(UserTable $userTable, $post = null, $userOld = null)
    {

        $emailNew = !is_null($post) ? $post['email'] : null;
        $emailOld = !is_null($userOld) ? $userOld->email : null;
        $passwordNew = !is_null($post) ? $post['password'] : null;

        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new Factory();

            $inputFilter->add($factory->createInput([
                'name' => 'id',
                'required' => false,
            ]));

            $inputFilter->add($factory->createInput([
                'name' => 'name',
                'required' => true,
                'filters' => [
                    [
                        'name' => 'StripTags'
                    ],
                    [
                        'name' => 'StringTrim'
                    ]
                ],
                'validators' => [
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min' => '2',
                            'max' => '100',
                            'messages' => [
                                StringLength::TOO_SHORT => 'Nome precisa conter entre 2 e 100 caracteres',
                                StringLength::TOO_LONG => 'Nome precisa conter entre 2 e 100 caracteres'
                            ]
                        ]
                    ],
                    [
                        'name' => NotEmpty::class,
                        'options' => [
                            'messages' => [
                                NotEmpty::IS_EMPTY => 'Nome não pode ser vazio'
                            ],
                        ],
                    ],
                ]
            ]));

            $inputFilter->add($factory->createInput([
                'name' => 'email',
                'required' => true,
                'filters' => [
                    [
                        'name' => 'StringTrim'
                    ]
                ],
                'validators' => $this->getRuleEmail($emailOld, $emailNew, $userTable)
            ]));

            $inputFilter->add($factory->createInput([
                'name' => 'password',
                'required' => is_null($emailOld),
                'filters' => [
                    [
                        'name' => 'StringTrim'
                    ],
                ],
                'validators' => $this->getRulePassword($passwordNew, $emailOld)
            ]));

            $this->inputFilter = $inputFilter;
        }

        return  $this->inputFilter;
    }

    public function getArrayCopy()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password
        ];
    }

    private function getRulePassword($passwordNew, $emailOld)
    {
        // senha preenchida e edicao
        if (!empty($passwordNew) && !is_null($emailOld)) {
            return [
                [
                    'name' => Regex::class,
                    'options' => [
                        'pattern' => '/^\d{6,10}$/',
                        'messages' => [
                            Regex::NOT_MATCH => 'A senha deve conter entre 6 e 10 dígitos numéricos.'
                        ],
                    ],
                ],
                [
                    'name' => NotEmpty::class,
                    'options' => [
                        'messages' => [
                            NotEmpty::IS_EMPTY => 'A senha deve conter entre 6 e 10 dígitos numéricos.'
                        ],
                    ],
                ],
            ];
        }

        // criacao
        if (is_null($emailOld)) {
            return [
                [
                    'name' => Regex::class,
                    'options' => [
                        'pattern' => '/^\d{6,10}$/',
                        'messages' => [
                            Regex::NOT_MATCH => 'A senha deve conter entre 6 e 10 dígitos numéricos.'
                        ],
                    ],
                ],
                [
                    'name' => NotEmpty::class,
                    'options' => [
                        'messages' => [
                            NotEmpty::IS_EMPTY => 'A senha deve conter entre 6 e 10 dígitos numéricos.'
                        ],
                    ],
                ],
            ];
        }

        return [];
    }

    private function getRuleEmail($emailOld, $emailNew, $userTable)
    {
        // Alterou o email ou é uma criação de usuário
       if ($emailOld !== $emailNew || is_null($emailOld)  ) {
            return [
                [
                    'name' => UniqueEmailValidator::class,
                    'options' => [
                        'userTable' => $userTable
                    ]
                ],
                [
                    'name' => EmailAddress::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 2,
                        'max' => 100,
                        'messages' => [
                            EmailAddress::INVALID_FORMAT => 'O email precisa ser válido. Ex: email@email.com'
                        ],
                    ],
                ],
                [
                    'name' => NotEmpty::class,
                    'options' => [
                        'messages' => [
                            NotEmpty::IS_EMPTY => 'Email é necessário.'
                        ],
                    ]
                ]
            ];
       }  else {
           return [];
       }
    }

}