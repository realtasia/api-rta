<?php defined('SYSPATH') or die('No direct script access.');
/*
    ->rule('first_name', 'not_empty')
    ->rule('last_name', 'alpha')
    ->rule('number.mobile', 'not_empty')
    ->rule('gender', 'alpha')
    ->rule('password', 'not_empty')
    ->rule('email', 'email')
    ->rule('email', 'email_domain')
    ->rule('birthday', 'numeric');
    ->rule('message', 'not_empty')
    ->rule('number.mobile', 'not_empty')
    ->rule('user_type', 'not_empty')
    ->rule('display_name', 'not_empty')
    ->rule('email', 'email')
    ->rule('email', 'email_domain');
 */
return [
    'email' => [
        'email' => 'Please enter a valid email address',
        'email_domain' => 'Please enter a valid email address',
    ],
    'not_empty' => 'This is a required entry, please try again',
    'numeric' => 'Please enter numbers [0-9] only',
    'alpha' => 'Please enter letters [A-z] only',
];
