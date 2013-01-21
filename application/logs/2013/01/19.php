<?php defined('SYSPATH') OR die('No direct script access.'); ?>

2013-01-19 17:40:37 --- DEBUG: No token found
2013-01-19 17:40:37 --- DEBUG: Continue Invoked
2013-01-19 20:52:39 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: favicon.ico ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2013-01-19 20:52:39 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: favicon.ico ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/jason/www/rta.casa.net/index.php(115): Kohana_Request->execute()
#1 {main}
2013-01-19 20:52:39 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: favicon.ico ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2013-01-19 20:52:39 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: favicon.ico ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/jason/www/rta.casa.net/index.php(115): Kohana_Request->execute()
#1 {main}
2013-01-19 22:43:58 --- ERROR: ErrorException [ 2 ]: Creating default object from empty value ~ APPPATH/classes/controller/post.php [ 707 ]
2013-01-19 22:43:58 --- STRACE: ErrorException [ 2 ]: Creating default object from empty value ~ APPPATH/classes/controller/post.php [ 707 ]
--
#0 /home/jason/www/rta.casa.net/application/classes/controller/post.php(707): Kohana_Core::error_handler(2, 'Creating defaul...', '/home/jason/www...', 707, Array)
#1 /home/jason/www/rta.casa.net/application/classes/controller/post.php(93): Controller_Post->create_attachment()
#2 [internal function]: Controller_Post->action_update()
#3 /home/jason/www/common/kohana/system/classes/kohana/request/client/internal.php(116): ReflectionMethod->invoke(Object(Controller_Post))
#4 /home/jason/www/common/kohana/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#5 /home/jason/www/common/kohana/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#6 /home/jason/www/rta.casa.net/index.php(115): Kohana_Request->execute()
#7 {main}
2013-01-19 22:44:07 --- ERROR: ErrorException [ 2 ]: Creating default object from empty value ~ APPPATH/classes/controller/post.php [ 707 ]
2013-01-19 22:44:07 --- STRACE: ErrorException [ 2 ]: Creating default object from empty value ~ APPPATH/classes/controller/post.php [ 707 ]
--
#0 /home/jason/www/rta.casa.net/application/classes/controller/post.php(707): Kohana_Core::error_handler(2, 'Creating defaul...', '/home/jason/www...', 707, Array)
#1 /home/jason/www/rta.casa.net/application/classes/controller/post.php(93): Controller_Post->create_attachment()
#2 [internal function]: Controller_Post->action_update()
#3 /home/jason/www/common/kohana/system/classes/kohana/request/client/internal.php(116): ReflectionMethod->invoke(Object(Controller_Post))
#4 /home/jason/www/common/kohana/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#5 /home/jason/www/common/kohana/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#6 /home/jason/www/rta.casa.net/index.php(115): Kohana_Request->execute()
#7 {main}
2013-01-19 22:44:13 --- ERROR: ErrorException [ 2 ]: Creating default object from empty value ~ APPPATH/classes/controller/post.php [ 707 ]
2013-01-19 22:44:13 --- STRACE: ErrorException [ 2 ]: Creating default object from empty value ~ APPPATH/classes/controller/post.php [ 707 ]
--
#0 /home/jason/www/rta.casa.net/application/classes/controller/post.php(707): Kohana_Core::error_handler(2, 'Creating defaul...', '/home/jason/www...', 707, Array)
#1 /home/jason/www/rta.casa.net/application/classes/controller/post.php(93): Controller_Post->create_attachment()
#2 [internal function]: Controller_Post->action_update()
#3 /home/jason/www/common/kohana/system/classes/kohana/request/client/internal.php(116): ReflectionMethod->invoke(Object(Controller_Post))
#4 /home/jason/www/common/kohana/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#5 /home/jason/www/common/kohana/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#6 /home/jason/www/rta.casa.net/index.php(115): Kohana_Request->execute()
#7 {main}
2013-01-19 22:45:34 --- ERROR: ErrorException [ 1 ]: Call to a member function set() on a non-object ~ APPPATH/classes/controller/post.php [ 707 ]
2013-01-19 22:45:34 --- STRACE: ErrorException [ 1 ]: Call to a member function set() on a non-object ~ APPPATH/classes/controller/post.php [ 707 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2013-01-19 22:45:43 --- ERROR: ErrorException [ 1 ]: Call to a member function set() on a non-object ~ APPPATH/classes/controller/post.php [ 707 ]
2013-01-19 22:45:43 --- STRACE: ErrorException [ 1 ]: Call to a member function set() on a non-object ~ APPPATH/classes/controller/post.php [ 707 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2013-01-19 22:45:51 --- ERROR: ErrorException [ 1 ]: Call to a member function set() on a non-object ~ APPPATH/classes/controller/post.php [ 707 ]
2013-01-19 22:45:51 --- STRACE: ErrorException [ 1 ]: Call to a member function set() on a non-object ~ APPPATH/classes/controller/post.php [ 707 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2013-01-19 22:45:59 --- ERROR: ErrorException [ 1 ]: Call to a member function set() on a non-object ~ APPPATH/classes/controller/post.php [ 707 ]
2013-01-19 22:45:59 --- STRACE: ErrorException [ 1 ]: Call to a member function set() on a non-object ~ APPPATH/classes/controller/post.php [ 707 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2013-01-19 22:48:05 --- ERROR: ErrorException [ 1 ]: Call to a member function set() on a non-object ~ APPPATH/classes/controller/post.php [ 707 ]
2013-01-19 22:48:05 --- STRACE: ErrorException [ 1 ]: Call to a member function set() on a non-object ~ APPPATH/classes/controller/post.php [ 707 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2013-01-19 22:48:15 --- ERROR: ErrorException [ 1 ]: Call to a member function set() on a non-object ~ APPPATH/classes/controller/post.php [ 707 ]
2013-01-19 22:48:15 --- STRACE: ErrorException [ 1 ]: Call to a member function set() on a non-object ~ APPPATH/classes/controller/post.php [ 707 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2013-01-19 22:48:21 --- ERROR: ErrorException [ 1 ]: Call to a member function set() on a non-object ~ APPPATH/classes/controller/post.php [ 707 ]
2013-01-19 22:48:21 --- STRACE: ErrorException [ 1 ]: Call to a member function set() on a non-object ~ APPPATH/classes/controller/post.php [ 707 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}