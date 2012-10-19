<?php defined('SYSPATH') or die('No direct script access.');
    /**
    * @author jason
    */

return array(
    'native' => array(
        'name' => '_session',
        'lifetime' => 43200,
    ),
    'cookie' => array(
        'name' => '_c_session',
        'encrypted' => TRUE,
        'lifetime' => 43200,
    ),
    'mongo' => array(
        'name' => '_m_session',
        'encrypted' => TRUE,
        'lifetime' => 43200,
        'group' => 'default',
        'table' => 'sessions',
        'columns' => array(
            'session_id'  => 'session_id',
            'last_active' => 'last_active',
            'contents'    => 'contents'
        ),
        'gc' => 500,
    ),
);
