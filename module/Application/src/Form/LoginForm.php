<?php

namespace Application\Form;

use Laminas\Form\Form;

class LoginForm extends Form
{
    public function __construct()
    {
        parent::__construct();

        $this->add(
            [
                    'name' => 'u_name',
                    'options' => [
                        'label' => 'Username',
                        'label_attributes' => [
                            'class' => 'col-sm-3 control-label'
                        ]
                    ],
                    'attributes' => [
                        'class' => 'form-control',
                        'id' => 'u_name',
                        'name' => 'u_name',
                        'placeholder' => 'Username',
                    ],
                    'type' => 'text',
                ]
        );

        $this->add(
            [
                    'name' => 'upw',
                    'options' => [
                        'label' => 'Password',
                        'label_attributes' => [
                            'class' => 'col-sm-3 control-label'
                        ]
                    ],
                    'attributes' => [
                        'class' => 'form-control',
                        'id' => 'upw',
                        'name' => 'upw',
                        'placeholder' => 'Password',
                    ],
                    'type' => 'password',
                ]
        );

        $this->add(
            [
                    'type' => 'Button',
                    'name' => 'submit',
                    'options' => [
                        'label' => 'Login',
                        'label_options' => [
                            'disable_html_escape' => true,
                        ]
                    ],
                    'attributes' => [
                        'type' => 'submit',
                        'class' => 'btn btn-success'
                    ]
                ]
        );


        $this->add(
            [
                    'type' => 'Button',
                    'name' => 'reset',
                    'options' => [
                        'label' => 'Clear',
                        'label_options' => [
                            'disable_html_escape' => true,
                        ]
                    ],
                    'attributes' => [
                        'type' => 'reset',
                        'class' => 'btn btn-default'
                    ]
                ]
        );
    }
}
