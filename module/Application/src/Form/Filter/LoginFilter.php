<?php

namespace Application\Form\Filter;

use Laminas\InputFilter\InputFilter;

class LoginFilter extends InputFilter
{
    public function __construct()
    {
        $isEmpty = \Laminas\Validator\NotEmpty::IS_EMPTY;
        $invalidEmail = \Laminas\Validator\EmailAddress::INVALID_FORMAT;

        $this->add(
            [
            'name' => 'u_name',
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
                    'name' => 'NotEmpty',
                    'options' => [
                        'messages' => [
                            $isEmpty => 'Username can not be empty.'
                        ]
                    ],
                    'break_chain_on_failure' => true
                ],
                [
                    'name' => 'Regex',
                    'options' => [
                        'pattern' => '/[A-Za-z0-9_~\-!@#\$%\^&\*\(\)]+$/',
                            'messages' => [
                            \Zend\Validator\Regex::NOT_MATCH => "Invalid characters in Username"
                        ]
                    ],
                ]
            ]
            ]
        );

        $this->add(
            [
            'name' => 'upw',
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
                    'name' => 'NotEmpty',
                    'options' => [
                        'messages' => [
                            $isEmpty => 'Password can not be empty.'
                        ]
                    ],
                    'break_chain_on_failure' => true
                ],
                [
                    'name' => 'Regex',
                    'options' => [
                        'pattern' => '/[A-Za-z0-9_~\-!@#\$%\^&\*\(\)]+$/',
                            'messages' => [
                            \Zend\Validator\Regex::NOT_MATCH => "Invalid characters in Password"
                        ]
                    ],
                ]
            ]
            ]
        );
    }
}
