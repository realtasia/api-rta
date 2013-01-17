<?php defined('SYSPATH') OR die('No direct script access.'); ?>

2013-01-15 14:26:18 --- ERROR: HTTP_Exception_404 [ 404 ]: The requested URL corrections/pages was not found on this server. ~ SYSPATH/classes/kohana/request/client/internal.php [ 87 ]
2013-01-15 14:26:18 --- STRACE: HTTP_Exception_404 [ 404 ]: The requested URL corrections/pages was not found on this server. ~ SYSPATH/classes/kohana/request/client/internal.php [ 87 ]
--
#0 /home/jason/www/common/kohana/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#1 /home/jason/www/common/kohana/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#2 /home/jason/www/rta.casa.net/index.php(114): Kohana_Request->execute()
#3 {main}
2013-01-15 14:26:18 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: favicon.ico ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2013-01-15 14:26:18 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: favicon.ico ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/jason/www/rta.casa.net/index.php(114): Kohana_Request->execute()
#1 {main}
2013-01-15 14:26:18 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: favicon.ico ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2013-01-15 14:26:18 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: favicon.ico ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/jason/www/rta.casa.net/index.php(114): Kohana_Request->execute()
#1 {main}
2013-01-15 15:37:46 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: favicon.ico ~ SYSPATH/classes/kohana/request.php [ 1142 ]
2013-01-15 15:37:46 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: favicon.ico ~ SYSPATH/classes/kohana/request.php [ 1142 ]
--
#0 /home/jason/www/rta.casa.net/index.php(114): Kohana_Request->execute()
#1 {main}
2013-01-15 15:53:27 --- ERROR: ErrorException [ 2 ]: Missing argument 1 for Mongo_Collection::limit(), called in /home/jason/www/rta.casa.net/application/classes/controller/corrections.php on line 17 and defined ~ MODPATH/mongodb/classes/mongo/collection.php [ 357 ]
2013-01-15 15:53:27 --- STRACE: ErrorException [ 2 ]: Missing argument 1 for Mongo_Collection::limit(), called in /home/jason/www/rta.casa.net/application/classes/controller/corrections.php on line 17 and defined ~ MODPATH/mongodb/classes/mongo/collection.php [ 357 ]
--
#0 /home/jason/www/common/kohana/modules/mongodb/classes/mongo/collection.php(357): Kohana_Core::error_handler(2, 'Missing argumen...', '/home/jason/www...', 357, Array)
#1 /home/jason/www/rta.casa.net/application/classes/controller/corrections.php(17): Mongo_Collection->limit()
#2 [internal function]: Controller_Corrections->action_pages()
#3 /home/jason/www/common/kohana/system/classes/kohana/request/client/internal.php(116): ReflectionMethod->invoke(Object(Controller_Corrections))
#4 /home/jason/www/common/kohana/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#5 /home/jason/www/common/kohana/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#6 /home/jason/www/rta.casa.net/index.php(114): Kohana_Request->execute()
#7 {main}
2013-01-15 15:57:27 --- ERROR: ErrorException [ 2 ]: strtolower() expects parameter 1 to be string, array given ~ APPPATH/classes/controller/corrections.php [ 37 ]
2013-01-15 15:57:27 --- STRACE: ErrorException [ 2 ]: strtolower() expects parameter 1 to be string, array given ~ APPPATH/classes/controller/corrections.php [ 37 ]
--
#0 [internal function]: Kohana_Core::error_handler(2, 'strtolower() ex...', '/home/jason/www...', 37, Array)
#1 [internal function]: strtolower(Array)
#2 /home/jason/www/rta.casa.net/application/classes/controller/corrections.php(37): array_map('strtolower', Array)
#3 [internal function]: Controller_Corrections->action_pages()
#4 /home/jason/www/common/kohana/system/classes/kohana/request/client/internal.php(116): ReflectionMethod->invoke(Object(Controller_Corrections))
#5 /home/jason/www/common/kohana/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/jason/www/common/kohana/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/jason/www/rta.casa.net/index.php(114): Kohana_Request->execute()
#8 {main}
2013-01-15 16:02:28 --- ERROR: ErrorException [ 2 ]: strtolower() expects parameter 1 to be string, array given ~ APPPATH/classes/controller/corrections.php [ 39 ]
2013-01-15 16:02:28 --- STRACE: ErrorException [ 2 ]: strtolower() expects parameter 1 to be string, array given ~ APPPATH/classes/controller/corrections.php [ 39 ]
--
#0 [internal function]: Kohana_Core::error_handler(2, 'strtolower() ex...', '/home/jason/www...', 39, Array)
#1 [internal function]: strtolower(Array)
#2 /home/jason/www/rta.casa.net/application/classes/controller/corrections.php(39): array_map('strtolower', Array)
#3 [internal function]: Controller_Corrections->action_pages()
#4 /home/jason/www/common/kohana/system/classes/kohana/request/client/internal.php(116): ReflectionMethod->invoke(Object(Controller_Corrections))
#5 /home/jason/www/common/kohana/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/jason/www/common/kohana/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/jason/www/rta.casa.net/index.php(114): Kohana_Request->execute()
#8 {main}
2013-01-15 16:03:43 --- ERROR: ErrorException [ 2 ]: strtolower() expects parameter 1 to be string, array given ~ APPPATH/classes/controller/corrections.php [ 39 ]
2013-01-15 16:03:43 --- STRACE: ErrorException [ 2 ]: strtolower() expects parameter 1 to be string, array given ~ APPPATH/classes/controller/corrections.php [ 39 ]
--
#0 [internal function]: Kohana_Core::error_handler(2, 'strtolower() ex...', '/home/jason/www...', 39, Array)
#1 [internal function]: strtolower(Array)
#2 /home/jason/www/rta.casa.net/application/classes/controller/corrections.php(39): array_map('strtolower', Array)
#3 [internal function]: Controller_Corrections->action_pages()
#4 /home/jason/www/common/kohana/system/classes/kohana/request/client/internal.php(116): ReflectionMethod->invoke(Object(Controller_Corrections))
#5 /home/jason/www/common/kohana/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/jason/www/common/kohana/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/jason/www/rta.casa.net/index.php(114): Kohana_Request->execute()
#8 {main}
2013-01-15 16:25:29 --- ERROR: ErrorException [ 2 ]: array_merge(): Argument #3 is not an array ~ APPPATH/classes/controller/corrections.php [ 33 ]
2013-01-15 16:25:29 --- STRACE: ErrorException [ 2 ]: array_merge(): Argument #3 is not an array ~ APPPATH/classes/controller/corrections.php [ 33 ]
--
#0 [internal function]: Kohana_Core::error_handler(2, 'array_merge(): ...', '/home/jason/www...', 33, Array)
#1 /home/jason/www/rta.casa.net/application/classes/controller/corrections.php(33): array_merge(Array, Array, NULL)
#2 [internal function]: Controller_Corrections->action_pages()
#3 /home/jason/www/common/kohana/system/classes/kohana/request/client/internal.php(116): ReflectionMethod->invoke(Object(Controller_Corrections))
#4 /home/jason/www/common/kohana/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#5 /home/jason/www/common/kohana/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#6 /home/jason/www/rta.casa.net/index.php(114): Kohana_Request->execute()
#7 {main}
2013-01-15 17:23:51 --- ERROR: ErrorException [ 1 ]: Call to undefined method Controller::logged_in() ~ APPPATH/classes/controller/page.php [ 22 ]
2013-01-15 17:23:51 --- STRACE: ErrorException [ 1 ]: Call to undefined method Controller::logged_in() ~ APPPATH/classes/controller/page.php [ 22 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2013-01-15 17:24:37 --- ERROR: ErrorException [ 1 ]: Access to undeclared static property: Controller_Page::$params ~ APPPATH/classes/controller/page.php [ 334 ]
2013-01-15 17:24:37 --- STRACE: ErrorException [ 1 ]: Access to undeclared static property: Controller_Page::$params ~ APPPATH/classes/controller/page.php [ 334 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2013-01-15 17:25:57 --- ERROR: ErrorException [ 4096 ]: Argument 1 passed to iterator_to_array() must implement interface Traversable, array given ~ APPPATH/classes/controller/page.php [ 343 ]
2013-01-15 17:25:57 --- STRACE: ErrorException [ 4096 ]: Argument 1 passed to iterator_to_array() must implement interface Traversable, array given ~ APPPATH/classes/controller/page.php [ 343 ]
--
#0 [internal function]: Kohana_Core::error_handler(4096, 'Argument 1 pass...', '/home/jason/www...', 343, Array)
#1 /home/jason/www/rta.casa.net/application/classes/controller/page.php(343): iterator_to_array(Array)
#2 [internal function]: Controller_Page->action_keyword()
#3 /home/jason/www/common/kohana/system/classes/kohana/request/client/internal.php(116): ReflectionMethod->invoke(Object(Controller_Page))
#4 /home/jason/www/common/kohana/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#5 /home/jason/www/common/kohana/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#6 /home/jason/www/rta.casa.net/index.php(114): Kohana_Request->execute()
#7 {main}
2013-01-15 17:34:56 --- ERROR: ErrorException [ 2 ]: preg_match() expects parameter 2 to be string, array given ~ APPPATH/classes/controller/page.php [ 350 ]
2013-01-15 17:34:56 --- STRACE: ErrorException [ 2 ]: preg_match() expects parameter 2 to be string, array given ~ APPPATH/classes/controller/page.php [ 350 ]
--
#0 [internal function]: Kohana_Core::error_handler(2, 'preg_match() ex...', '/home/jason/www...', 350, Array)
#1 /home/jason/www/rta.casa.net/application/classes/controller/page.php(350): preg_match('/^sengk/', Array)
#2 [internal function]: Controller_Page->action_keyword()
#3 /home/jason/www/common/kohana/system/classes/kohana/request/client/internal.php(116): ReflectionMethod->invoke(Object(Controller_Page))
#4 /home/jason/www/common/kohana/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#5 /home/jason/www/common/kohana/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#6 /home/jason/www/rta.casa.net/index.php(114): Kohana_Request->execute()
#7 {main}
2013-01-15 17:54:50 --- ERROR: ErrorException [ 2 ]: Missing argument 2 for Controller_Page::{closure}() ~ APPPATH/classes/controller/page.php [ 353 ]
2013-01-15 17:54:50 --- STRACE: ErrorException [ 2 ]: Missing argument 2 for Controller_Page::{closure}() ~ APPPATH/classes/controller/page.php [ 353 ]
--
#0 /home/jason/www/rta.casa.net/application/classes/controller/page.php(353): Kohana_Core::error_handler(2, 'Missing argumen...', '/home/jason/www...', 353, Array)
#1 [internal function]: Controller_Page->{closure}('richards place')
#2 /home/jason/www/rta.casa.net/application/classes/controller/page.php(357): array_filter(Array, Object(Closure))
#3 [internal function]: Controller_Page->action_keyword()
#4 /home/jason/www/common/kohana/system/classes/kohana/request/client/internal.php(116): ReflectionMethod->invoke(Object(Controller_Page))
#5 /home/jason/www/common/kohana/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/jason/www/common/kohana/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/jason/www/rta.casa.net/index.php(114): Kohana_Request->execute()
#8 {main}
2013-01-15 18:04:16 --- ERROR: MongoCursorException [ 16352 ]: localhost:27017: positional operator (keywords.$) requires corresponding field in query specifier ~ MODPATH/mongodb/classes/mongo/collection.php [ 1029 ]
2013-01-15 18:04:16 --- STRACE: MongoCursorException [ 16352 ]: localhost:27017: positional operator (keywords.$) requires corresponding field in query specifier ~ MODPATH/mongodb/classes/mongo/collection.php [ 1029 ]
--
#0 /home/jason/www/common/kohana/modules/mongodb/classes/mongo/collection.php(1029): MongoCursor->next()
#1 /home/jason/www/rta.casa.net/application/classes/controller/page.php(346): Mongo_Collection->getNext()
#2 [internal function]: Controller_Page->action_keyword()
#3 /home/jason/www/common/kohana/system/classes/kohana/request/client/internal.php(116): ReflectionMethod->invoke(Object(Controller_Page))
#4 /home/jason/www/common/kohana/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#5 /home/jason/www/common/kohana/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#6 /home/jason/www/rta.casa.net/index.php(114): Kohana_Request->execute()
#7 {main}
2013-01-15 18:07:35 --- ERROR: ErrorException [ 1 ]: Call to undefined method Controller::logged_in() ~ APPPATH/classes/controller/page.php [ 403 ]
2013-01-15 18:07:35 --- STRACE: ErrorException [ 1 ]: Call to undefined method Controller::logged_in() ~ APPPATH/classes/controller/page.php [ 403 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2013-01-15 18:07:37 --- ERROR: ErrorException [ 1 ]: Call to undefined method Controller::logged_in() ~ APPPATH/classes/controller/page.php [ 403 ]
2013-01-15 18:07:37 --- STRACE: ErrorException [ 1 ]: Call to undefined method Controller::logged_in() ~ APPPATH/classes/controller/page.php [ 403 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2013-01-15 18:07:39 --- ERROR: ErrorException [ 1 ]: Call to undefined method Controller::logged_in() ~ APPPATH/classes/controller/page.php [ 403 ]
2013-01-15 18:07:39 --- STRACE: ErrorException [ 1 ]: Call to undefined method Controller::logged_in() ~ APPPATH/classes/controller/page.php [ 403 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2013-01-15 18:07:53 --- ERROR: ErrorException [ 1 ]: Call to undefined method Controller::logged_in() ~ APPPATH/classes/controller/page.php [ 403 ]
2013-01-15 18:07:53 --- STRACE: ErrorException [ 1 ]: Call to undefined method Controller::logged_in() ~ APPPATH/classes/controller/page.php [ 403 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2013-01-15 18:07:55 --- ERROR: ErrorException [ 1 ]: Call to undefined method Controller::logged_in() ~ APPPATH/classes/controller/page.php [ 403 ]
2013-01-15 18:07:55 --- STRACE: ErrorException [ 1 ]: Call to undefined method Controller::logged_in() ~ APPPATH/classes/controller/page.php [ 403 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2013-01-15 18:07:57 --- ERROR: ErrorException [ 1 ]: Call to undefined method Controller::logged_in() ~ APPPATH/classes/controller/page.php [ 403 ]
2013-01-15 18:07:57 --- STRACE: ErrorException [ 1 ]: Call to undefined method Controller::logged_in() ~ APPPATH/classes/controller/page.php [ 403 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2013-01-15 18:07:58 --- ERROR: ErrorException [ 1 ]: Call to undefined method Controller::logged_in() ~ APPPATH/classes/controller/page.php [ 403 ]
2013-01-15 18:07:58 --- STRACE: ErrorException [ 1 ]: Call to undefined method Controller::logged_in() ~ APPPATH/classes/controller/page.php [ 403 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2013-01-15 18:07:59 --- ERROR: ErrorException [ 1 ]: Call to undefined method Controller::logged_in() ~ APPPATH/classes/controller/page.php [ 403 ]
2013-01-15 18:07:59 --- STRACE: ErrorException [ 1 ]: Call to undefined method Controller::logged_in() ~ APPPATH/classes/controller/page.php [ 403 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2013-01-15 18:08:01 --- ERROR: ErrorException [ 1 ]: Call to undefined method Controller::logged_in() ~ APPPATH/classes/controller/page.php [ 403 ]
2013-01-15 18:08:01 --- STRACE: ErrorException [ 1 ]: Call to undefined method Controller::logged_in() ~ APPPATH/classes/controller/page.php [ 403 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2013-01-15 18:08:02 --- ERROR: ErrorException [ 1 ]: Call to undefined method Controller::logged_in() ~ APPPATH/classes/controller/page.php [ 403 ]
2013-01-15 18:08:02 --- STRACE: ErrorException [ 1 ]: Call to undefined method Controller::logged_in() ~ APPPATH/classes/controller/page.php [ 403 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2013-01-15 18:08:03 --- ERROR: ErrorException [ 1 ]: Call to undefined method Controller::logged_in() ~ APPPATH/classes/controller/page.php [ 403 ]
2013-01-15 18:08:03 --- STRACE: ErrorException [ 1 ]: Call to undefined method Controller::logged_in() ~ APPPATH/classes/controller/page.php [ 403 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2013-01-15 18:08:03 --- ERROR: ErrorException [ 1 ]: Call to undefined method Controller::logged_in() ~ APPPATH/classes/controller/page.php [ 403 ]
2013-01-15 18:08:03 --- STRACE: ErrorException [ 1 ]: Call to undefined method Controller::logged_in() ~ APPPATH/classes/controller/page.php [ 403 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2013-01-15 18:08:04 --- ERROR: ErrorException [ 1 ]: Call to undefined method Controller::logged_in() ~ APPPATH/classes/controller/page.php [ 403 ]
2013-01-15 18:08:04 --- STRACE: ErrorException [ 1 ]: Call to undefined method Controller::logged_in() ~ APPPATH/classes/controller/page.php [ 403 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2013-01-15 18:08:05 --- ERROR: ErrorException [ 1 ]: Call to undefined method Controller::logged_in() ~ APPPATH/classes/controller/page.php [ 403 ]
2013-01-15 18:08:05 --- STRACE: ErrorException [ 1 ]: Call to undefined method Controller::logged_in() ~ APPPATH/classes/controller/page.php [ 403 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2013-01-15 18:08:11 --- ERROR: ErrorException [ 1 ]: Call to undefined method Controller::logged_in() ~ APPPATH/classes/controller/page.php [ 403 ]
2013-01-15 18:08:11 --- STRACE: ErrorException [ 1 ]: Call to undefined method Controller::logged_in() ~ APPPATH/classes/controller/page.php [ 403 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2013-01-15 18:13:44 --- ERROR: HTTP_Exception_404 [ 404 ]: The requested URL page/keyword was not found on this server. ~ SYSPATH/classes/kohana/request/client/internal.php [ 111 ]
2013-01-15 18:13:44 --- STRACE: HTTP_Exception_404 [ 404 ]: The requested URL page/keyword was not found on this server. ~ SYSPATH/classes/kohana/request/client/internal.php [ 111 ]
--
#0 /home/jason/www/common/kohana/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#1 /home/jason/www/common/kohana/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#2 /home/jason/www/rta.casa.net/index.php(114): Kohana_Request->execute()
#3 {main}
2013-01-15 18:29:39 --- ERROR: ErrorException [ 1 ]: Call to undefined method Controller::logged_in() ~ APPPATH/classes/controller/page.php [ 437 ]
2013-01-15 18:29:39 --- STRACE: ErrorException [ 1 ]: Call to undefined method Controller::logged_in() ~ APPPATH/classes/controller/page.php [ 437 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2013-01-15 18:29:57 --- ERROR: ErrorException [ 1 ]: Call to undefined method Controller::logged_in() ~ APPPATH/classes/controller/page.php [ 437 ]
2013-01-15 18:29:57 --- STRACE: ErrorException [ 1 ]: Call to undefined method Controller::logged_in() ~ APPPATH/classes/controller/page.php [ 437 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2013-01-15 20:08:50 --- ERROR: ErrorException [ 1 ]: Class name must be a valid object or a string ~ APPPATH/classes/controller/corrections.php [ 61 ]
2013-01-15 20:08:50 --- STRACE: ErrorException [ 1 ]: Class name must be a valid object or a string ~ APPPATH/classes/controller/corrections.php [ 61 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2013-01-15 20:15:18 --- ERROR: ErrorException [ 2 ]: Illegal offset type ~ APPPATH/classes/controller/corrections.php [ 71 ]
2013-01-15 20:15:18 --- STRACE: ErrorException [ 2 ]: Illegal offset type ~ APPPATH/classes/controller/corrections.php [ 71 ]
--
#0 /home/jason/www/rta.casa.net/application/classes/controller/corrections.php(71): Kohana_Core::error_handler(2, 'Illegal offset ...', '/home/jason/www...', 71, Array)
#1 [internal function]: Controller_Corrections->action_estates()
#2 /home/jason/www/common/kohana/system/classes/kohana/request/client/internal.php(116): ReflectionMethod->invoke(Object(Controller_Corrections))
#3 /home/jason/www/common/kohana/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#4 /home/jason/www/common/kohana/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#5 /home/jason/www/rta.casa.net/index.php(114): Kohana_Request->execute()
#6 {main}
2013-01-15 20:28:49 --- ERROR: ErrorException [ 2 ]: Missing argument 1 for Mongo_Collection::skip(), called in /home/jason/www/rta.casa.net/application/classes/controller/corrections.php on line 19 and defined ~ MODPATH/mongodb/classes/mongo/collection.php [ 368 ]
2013-01-15 20:28:49 --- STRACE: ErrorException [ 2 ]: Missing argument 1 for Mongo_Collection::skip(), called in /home/jason/www/rta.casa.net/application/classes/controller/corrections.php on line 19 and defined ~ MODPATH/mongodb/classes/mongo/collection.php [ 368 ]
--
#0 /home/jason/www/common/kohana/modules/mongodb/classes/mongo/collection.php(368): Kohana_Core::error_handler(2, 'Missing argumen...', '/home/jason/www...', 368, Array)
#1 /home/jason/www/rta.casa.net/application/classes/controller/corrections.php(19): Mongo_Collection->skip()
#2 [internal function]: Controller_Corrections->action_pages()
#3 /home/jason/www/common/kohana/system/classes/kohana/request/client/internal.php(116): ReflectionMethod->invoke(Object(Controller_Corrections))
#4 /home/jason/www/common/kohana/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#5 /home/jason/www/common/kohana/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#6 /home/jason/www/rta.casa.net/index.php(114): Kohana_Request->execute()
#7 {main}
2013-01-15 23:08:38 --- DEBUG: No session found
2013-01-15 23:08:38 --- DEBUG: Continue Invoked