<?php

namespace User\Model;

use Laminas\InputFilter\InputFilterInterface;
use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\Factory;
use Laminas\Validator\EmailAddress;
use Laminas\Validator\Regex;
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
                            'max' => '100'
                        ]
                    ]
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
                'validators' => ($emailOld !== $emailNew) || is_null($emailOld)  ? [
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
                        ],
                    ],
                ] : []
//                'validators' => [
//                    [
//                        'name' => EmailAddress::class,
//                        'options' => [
//                            'encoding' => 'UTF-8',
//                            'min' => 2,
//                            'max' => 100,
//                        ],
//                    ],
//                    [
//                        'name' => UniqueEmailValidator::class,
//                        'options' => [
//                            'userTable' => $userTable
//                        ],
//                    ],
//                ],
            ]));

            $inputFilter->add($factory->createInput([
                'name' => 'password',
                'required' => true,
                'filters' => [
                    [
                        'name' => 'StringTrim'
                    ],
                ],
                'validators' => [
                    [
                        'name' => Regex::class,
                        'options' => [
                            'pattern' => '/^\d{6,10}$/',
                            'messages' => [
                                Regex::NOT_MATCH => 'A senha deve conter entre 6 e 10 dígitos numéricos.'
                            ],
                        ],
                    ],
                ]
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
}