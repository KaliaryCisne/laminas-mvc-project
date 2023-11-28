<?php

namespace User\Form;

use Laminas\Form\Element;
use Laminas\Form\Form;

class User extends Form
{
    public function __construct()
    {
        parent::__construct('user');
        $this->setAttribute('method', 'post');

        $this->add([
            'name' => 'id',
            'attributes' => [
                'type' => 'hidden',
                'class' => 'form-control'
            ],
            'options' => [
                'label' => 'Id'
            ]
        ]);

        $this->add([
            'name' => 'name',
            'attributes' => [
                'autofocus' => 'autofocus',
                'class' => 'form-control col-6'
            ],
            'options' => [
                'label' => 'Nome'
            ]
        ]);

        $this->add([
            'name' => 'email',
            'type' => Element\Email::class,
            'attributes' => [
                'class' => 'form-control col-6'
            ],
            'options' => [
                'label' => 'E-mail'
            ]
        ]);

        $this->add([
            'name' => 'password',
//            'type' => Element\Password::class,
            'attributes' => [
                'class' => 'form-control col-6'
            ],
            'options' => [
                'label' => 'Senha'
            ]
        ]);

        $this->add([
            'name' => 'submit',
            'attributes' => [
                'type'  => 'submit',
                'value' => 'Salvar',
                'id'    => 'submitbutton',
                'class' => 'form-control col-6 btn btn-primary'
            ],
        ]);
    }
}