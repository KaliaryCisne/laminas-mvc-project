<?php

namespace User\Model;

use Laminas\Filter\Digits;
use Laminas\Filter\ToInt;
use Laminas\InputFilter\InputFilterInterface;
use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\Factory;
use Laminas\Validator\EmailAddress;
use Laminas\Validator\NotEmpty;
use Laminas\Validator\StringLength;
use PHPUnit\Util\Filter;

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
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new Factory();

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
                'validators' => [
                    [
                        'name' => EmailAddress::class,
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min' => '2',
                            'max' => '100'
                        ]
                    ]
                ]
            ]));

            $inputFilter->add($factory->createInput([
                'name' => 'password',
                'required' => true,
                'filters' => [
                    [
                        'name' => 'StringTrim'
                    ],
                    [
                        'name' => ToInt::class
                    ],
                ],
                'validators' => [
                    [
                        'name' => NotEmpty::class,
                    ],
                    [
                        'name' => StringLength::class,
                        'options' => [
                            'min' => '6',
                            'max' => '10'
                        ]
                    ],
                    [
                        'name' => Digits::class
                    ]
                ]
            ]));

            $this->inputFilter = $inputFilter;
        }

        return  $this->inputFilter;
    }
}