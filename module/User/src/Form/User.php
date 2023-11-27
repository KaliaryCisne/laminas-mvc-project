<?php

namespace User\Form;

use Laminas\Form\Element;
use Laminas\Form\Form;

class User extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('user');
        $this->setAttribute('method', 'post');

        $this->add([
            'name' => 'name',
            'attributes' => [
                'type' => 'text',
                'autofocus' => 'autofocus',
                'class' => 'form-control'
            ],
            'options' => [
                'label' => 'Nome'
            ]
        ]);

        $this->add([
            'name' => 'email',
            'attributes' => [
                'type' => Element\Email::class,
                'class' => 'form-control'
            ],
            'options' => [
                'label' => 'E-mail'
            ]
        ]);

        $this->add([
            'name' => 'password',
            'attributes' => [
                'type' => Element\Password::class,
                'class' => 'form-control'
            ],
            'options' => [
                'label' => 'Senha'
            ]
        ]);

        $this->add([
            'name' => 'submit',
            'attributes' => [
                'type'  => 'submit',
                'value' => 'Gravar',
                'id'    => 'submitbutton'
            ],
        ]);
    }
}