<?php defined('SYSPATH') or die('No direct script access.');
    /**
    * @author jason
    */

/**
* Set the routes. Each route must have a minimum of a name, a URI and a set of
* defaults for the URI.
*/


/************************************************************
* Core & User Routes
*************************************************************/

Route::set('admin', 'admin(/<action>(/<id>))')
  ->defaults(array(
        'controller' => 'admin',
        'action'   => 'index',
));

Route::set('devtools','devtools(/<action>)')
	->defaults(array(
		'controller' => 'devtools',
		'action'     => 'info'
	));

Route::set('user', 'user(/<action>(/<id>))', array('id' => '.+'))
  ->defaults(array(
    'controller' => 'user',
    'action'   => 'index',
  ));

Route::set('user/provider', 'user/provider(/<provider>)', array('provider' => '.+'))
  ->defaults(array(
    'controller' => 'user',
    'action'   => 'provider',
    'provider'    => NULL,
  ));

Route::set('user/provider_return', 'user/provider_return(/<provider>)', array('provider' => '.+'))
  ->defaults(array(
    'controller' => 'user',
    'action'   => 'provider_return',
    'provider'    => NULL,
  ));

Route::set('browse', '<action>.<format>', array( 'format' => 'php'))
      ->defaults(array(
         'controller' => 'browse'
      ));
/************************************************************
* End Core Routes
*************************************************************/

/*Wall - Right Now page for Logged in*/
Route::set('home', '<static_page>(?page=<page>)',
            array(
                'static_page' =>
                    'subscribed-people|subscribed-locations|subscribed-listings|subscribed-posts|my-subscribers|my-listings|my-settings|',
                'page' => '[0-9]+'
            )
         )
  ->defaults(array(
    'controller' => 'home',
    'action'   => 'index',
    /*'page'    => NULL,*/
  ));

/*Member Directory
* eg "members/?page=2
*/
Route::set('members', 'members(/<action>(/<id>(?page=<page>)))', array('page' => '[0-9]+'))
  ->defaults(array(
    'controller' => 'members',
    'action'   => 'index',
    'page'    => NULL,
  ));
/*Internal Routes (made to consodilate loops in one controller)
* eg "members/my_friends/18/?page=2
*/

/*Location Directory*/
Route::set('locations', 'locations(/<action>(/<id>(?page=<page>)))', array('page' => '[0-9]+'))
  ->defaults(array(
    'controller' => 'locations',
    'action'   => 'index',
    'page'    => 1
  ));

/*Listings Directory*/
Route::set('listings', 'listings(/<action>(/<id>(?page=<page>)))', array('page' => '[0-9]+'))
  ->defaults(array(
    'controller' => 'listings',
    'action'   => 'index',
  ));

Route::set('location', 'location(/<subtype>(/<slug>(/<action>(/<id>(?page=<page>)))))', array('subtype' => 'hdb|estate|private-apartment|private-landed|serviced-apartment|cluster-housing|lid', 'page' => '[0-9]+'))
  ->defaults(array(
    'controller' => 'location',
    'action'   => 'index',
    'page'    => 1
  ));

Route::set('listing', 'listing/<action>(/<id>)')
  ->defaults(array(
    'controller' => 'listing',
    'action'   => 'index'
  ));

Route::set('test', 'test(/<action>(/<id>))')
  ->defaults(array(
    'controller' => 'test',
    'action'   => 'index',
  ));

Route::set('comment', 'comment(/<action>(/<id>(/<number>)))')
  ->defaults(array(
    'controller' => 'comment',
    'action'   => 'index',
  ));

Route::set('post', 'post/<action>(/<id>(?page=<page>))')
  ->defaults(array(
    'controller' => 'post',
    'action'   => 'index',
  ));

Route::set('posts', 'posts/<action>(/<id>(?page=<page>))', array('page' => '[0-9]+'))
  ->defaults(array(
    'controller' => 'posts',
    'action'   => 'get',
  ));

Route::set('pages', 'pages/<action>(/<id>(?page=<page>))', array('page' => '[0-9]+'))
  ->defaults(array(
    'controller' => 'pages',
    'action'   => 'get',
  ));

Route::set('users', 'users/<action>(/<id>(?page=<page>))', array('page' => '[0-9]+'))
  ->defaults(array(
    'controller' => 'users',
    'action'   => 'get',
  ));

Route::set('member', '<username>(/<action>(/<id>))', array('username' => '[\.\pL\pN]++'))
  ->defaults(array(
    'controller' => 'member',
    'action'   => 'index',
  ));