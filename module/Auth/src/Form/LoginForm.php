<?php

namespace Auth\Form;

use Laminas\Form\Element;
use Laminas\Form\Form;

class LoginForm extends Form
{
    public function __construct($name = 'login', $options = [])
    {
        parent::__construct($name, $options);

        $this->add([
            'name' => 'email',
            'type' => Element\Email::class,
            'options' => [
                'label' => 'Email',
            ],
            'attributes' => [
                'type' => 'email',
                'required' => true,
                'class' => 'form-control col-6'
            ],
        ]);

        $this->add([
            'name' => 'password',
            'options' => [
                'label' => 'Password',
            ],
            'attributes' => [
                'type' => 'password',
                'required' => true,
                'class' => 'form-control col-6'
            ],
        ]);

        $this->add([
            'name' => 'submit',
            'type' => Element\Submit::class,
            'attributes' => [
                'value' => 'Login',
                'id' => 'submitbutton',
                'class' => 'form-control col-6 btn btn-primary'
            ],
        ]);
    }
}