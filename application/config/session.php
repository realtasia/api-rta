<?php defined('SYSPATH') or die('No direct script access.');
    /**
    * @author jason
    */

return array(
    'native' => array(
        'name' => 'synthetic-redacted',
        'lifetime' => 43200,
    ),
    'cookie' => array(
        'name' => 'synthetic-redacted',
        'encrypted' => TRUE,
        'lifetime' => 43200,
    ),
    'mongo' => array(
        'name' => 'synthetic-redacted',
        'encrypted' => TRUE,
        'lifetime' => 43200,
        'group' => 'synthetic-redacted',
        'table' => 'synthetic-redacted',
        'columns' => array(
            'session_id'  => 'synthetic-redacted',
            'last_active' => 'synthetic-redacted',
            'contents'    => 'synthetic-redacted'
        ),
        'gc' => 500,
    ),
);
