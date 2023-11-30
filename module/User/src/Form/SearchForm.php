<?php

namespace User\Form;

use Laminas\Form\Form;
use Laminas\Form\Element;

class SearchForm extends Form
{
    public function __construct($name = 'user', $options = [])
    {
        parent::__construct($name, $options);

        $this->add([
            'name' => 'name',
            'type' => Element\Text::class,
            'attributes' => [
                'id' => 'name',
                'class' => 'form-control col-6',
                'placeholder' => 'Digitar nome do usuário',
                'style' => 'display:inline; margin-right: 15px'
            ],
        ]);

        $this->add([
            'name' => 'submit',
            'type' => Element\Submit::class,
            'attributes' => [
                'type'  => 'submit',
                'value' => 'Pesquisar',
                'id'    => 'search',
                'class' => 'form-control col-2 btn btn-primary'
            ]
        ]);
    }
}