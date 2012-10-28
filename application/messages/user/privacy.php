<?php defined('SYSPATH') or die('No direct script access.');
/*
    ->rule('contact', 'Controller_User::valid_privacy')
    ->rule('subscriptions', 'Controller_User::valid_privacy')
    ->rule('messages', 'Controller_User::valid_privacy')
    ->rule('mentions', 'Controller_User::valid_privacy')
    ->rule('profile.others_posts', 'Controller_User::valid_privacy')
    ->rule('profile.others_posts_visibility', 'Controller_User::valid_privacy');
 */
return [
    'default' => 'The value you entered is not recognised by the system'
];