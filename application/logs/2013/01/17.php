<?php defined('SYSPATH') OR die('No direct script access.'); ?>

2013-01-17 23:17:45 --- ERROR: ErrorException [ 4096 ]: Argument 2 passed to Guzzle\Service\Client::getCommand() must be of the type array, string given, called in phar:///usr/share/php/AWSSDKforPHP/aws.phar/vendor/guzzle/guzzle/src/Guzzle/Service/Client.php on line 93 and defined ~ phar:///usr/share/php/AWSSDKforPHP/aws.phar/vendor/guzzle/guzzle/src/Guzzle/Service/Client.php [ 121 ]
2013-01-17 23:17:45 --- STRACE: ErrorException [ 4096 ]: Argument 2 passed to Guzzle\Service\Client::getCommand() must be of the type array, string given, called in phar:///usr/share/php/AWSSDKforPHP/aws.phar/vendor/guzzle/guzzle/src/Guzzle/Service/Client.php on line 93 and defined ~ phar:///usr/share/php/AWSSDKforPHP/aws.phar/vendor/guzzle/guzzle/src/Guzzle/Service/Client.php [ 121 ]
--
#0 phar:///usr/share/php/AWSSDKforPHP/aws.phar/vendor/guzzle/guzzle/src/Guzzle/Service/Client.php(121): Kohana_Core::error_handler(4096, 'Argument 2 pass...', 'phar:///usr/sha...', 121, Array)
#1 phar:///usr/share/php/AWSSDKforPHP/aws.phar/vendor/guzzle/guzzle/src/Guzzle/Service/Client.php(93): Guzzle\Service\Client->getCommand('Delete_all_obje...', 'static2.realtas...')
#2 phar:///usr/share/php/AWSSDKforPHP/aws.phar/src/Aws/Common/Client/AbstractClient.php(102): Guzzle\Service\Client->__call('Delete_all_obje...', Array)
#3 /home/jason/www/rta.casa.net/application/classes/controller/post.php(737): Aws\Common\Client\AbstractClient->__call('delete_all_obje...', Array)
#4 /home/jason/www/rta.casa.net/application/classes/controller/post.php(737): Aws\S3\S3Client->delete_all_object_versions('static2.realtas...', '/a45f67e189eacf...')
#5 /home/jason/www/rta.casa.net/application/classes/controller/post.php(134): Controller_Post::delete_attachment(Array)
#6 [internal function]: Controller_Post->action_delete()
#7 /home/jason/www/common/kohana/system/classes/kohana/request/client/internal.php(116): ReflectionMethod->invoke(Object(Controller_Post))
#8 /home/jason/www/common/kohana/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#9 /home/jason/www/common/kohana/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#10 /home/jason/www/rta.casa.net/index.php(115): Kohana_Request->execute()
#11 {main}
2013-01-17 23:28:58 --- ERROR: Guzzle\Service\Exception\ValidationException [ 0 ]: Validation errors: [Bucket] is a required string
[Key] is a required string ~ phar:///usr/share/php/AWSSDKforPHP/aws.phar/vendor/guzzle/guzzle/src/Guzzle/Service/Command/AbstractCommand.php [ 376 ]
2013-01-17 23:28:58 --- STRACE: Guzzle\Service\Exception\ValidationException [ 0 ]: Validation errors: [Bucket] is a required string
[Key] is a required string ~ phar:///usr/share/php/AWSSDKforPHP/aws.phar/vendor/guzzle/guzzle/src/Guzzle/Service/Command/AbstractCommand.php [ 376 ]
--
#0 phar:///usr/share/php/AWSSDKforPHP/aws.phar/vendor/guzzle/guzzle/src/Guzzle/Service/Command/AbstractCommand.php(272): Guzzle\Service\Command\AbstractCommand->validate()
#1 phar:///usr/share/php/AWSSDKforPHP/aws.phar/vendor/guzzle/guzzle/src/Guzzle/Service/Client.php(218): Guzzle\Service\Command\AbstractCommand->prepare()
#2 phar:///usr/share/php/AWSSDKforPHP/aws.phar/vendor/guzzle/guzzle/src/Guzzle/Service/Command/AbstractCommand.php(167): Guzzle\Service\Client->execute(Object(Guzzle\Service\Command\OperationCommand))
#3 phar:///usr/share/php/AWSSDKforPHP/aws.phar/vendor/guzzle/guzzle/src/Guzzle/Service/Command/AbstractCommand.php(218): Guzzle\Service\Command\AbstractCommand->execute()
#4 phar:///usr/share/php/AWSSDKforPHP/aws.phar/vendor/guzzle/guzzle/src/Guzzle/Service/Client.php(93): Guzzle\Service\Command\AbstractCommand->getResult()
#5 phar:///usr/share/php/AWSSDKforPHP/aws.phar/src/Aws/Common/Client/AbstractClient.php(102): Guzzle\Service\Client->__call('DeleteObject', Array)
#6 /home/jason/www/rta.casa.net/application/classes/controller/post.php(741): Aws\Common\Client\AbstractClient->__call('deleteObject', Array)
#7 /home/jason/www/rta.casa.net/application/classes/controller/post.php(741): Aws\S3\S3Client->deleteObject(Array)
#8 /home/jason/www/rta.casa.net/application/classes/controller/post.php(134): Controller_Post::delete_attachment(Array)
#9 [internal function]: Controller_Post->action_delete()
#10 /home/jason/www/common/kohana/system/classes/kohana/request/client/internal.php(116): ReflectionMethod->invoke(Object(Controller_Post))
#11 /home/jason/www/common/kohana/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#12 /home/jason/www/common/kohana/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#13 /home/jason/www/rta.casa.net/index.php(115): Kohana_Request->execute()
#14 {main}
2013-01-17 23:33:15 --- ERROR: Guzzle\Service\Exception\ValidationException [ 0 ]: Validation errors: [Objects][0] must be of type object ~ phar:///usr/share/php/AWSSDKforPHP/aws.phar/vendor/guzzle/guzzle/src/Guzzle/Service/Command/AbstractCommand.php [ 376 ]
2013-01-17 23:33:15 --- STRACE: Guzzle\Service\Exception\ValidationException [ 0 ]: Validation errors: [Objects][0] must be of type object ~ phar:///usr/share/php/AWSSDKforPHP/aws.phar/vendor/guzzle/guzzle/src/Guzzle/Service/Command/AbstractCommand.php [ 376 ]
--
#0 phar:///usr/share/php/AWSSDKforPHP/aws.phar/vendor/guzzle/guzzle/src/Guzzle/Service/Command/AbstractCommand.php(272): Guzzle\Service\Command\AbstractCommand->validate()
#1 phar:///usr/share/php/AWSSDKforPHP/aws.phar/vendor/guzzle/guzzle/src/Guzzle/Service/Client.php(218): Guzzle\Service\Command\AbstractCommand->prepare()
#2 phar:///usr/share/php/AWSSDKforPHP/aws.phar/vendor/guzzle/guzzle/src/Guzzle/Service/Command/AbstractCommand.php(167): Guzzle\Service\Client->execute(Object(Guzzle\Service\Command\OperationCommand))
#3 phar:///usr/share/php/AWSSDKforPHP/aws.phar/vendor/guzzle/guzzle/src/Guzzle/Service/Command/AbstractCommand.php(218): Guzzle\Service\Command\AbstractCommand->execute()
#4 phar:///usr/share/php/AWSSDKforPHP/aws.phar/vendor/guzzle/guzzle/src/Guzzle/Service/Client.php(93): Guzzle\Service\Command\AbstractCommand->getResult()
#5 phar:///usr/share/php/AWSSDKforPHP/aws.phar/src/Aws/Common/Client/AbstractClient.php(102): Guzzle\Service\Client->__call('DeleteObjects', Array)
#6 /home/jason/www/rta.casa.net/application/classes/controller/post.php(741): Aws\Common\Client\AbstractClient->__call('deleteObjects', Array)
#7 /home/jason/www/rta.casa.net/application/classes/controller/post.php(741): Aws\S3\S3Client->deleteObjects(Array)
#8 /home/jason/www/rta.casa.net/application/classes/controller/post.php(134): Controller_Post::delete_attachment(Array)
#9 [internal function]: Controller_Post->action_delete()
#10 /home/jason/www/common/kohana/system/classes/kohana/request/client/internal.php(116): ReflectionMethod->invoke(Object(Controller_Post))
#11 /home/jason/www/common/kohana/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#12 /home/jason/www/common/kohana/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#13 /home/jason/www/rta.casa.net/index.php(115): Kohana_Request->execute()
#14 {main}
2013-01-17 23:33:59 --- ERROR: Guzzle\Service\Exception\ValidationException [ 0 ]: Validation errors: [Objects][Key] must be of type object ~ phar:///usr/share/php/AWSSDKforPHP/aws.phar/vendor/guzzle/guzzle/src/Guzzle/Service/Command/AbstractCommand.php [ 376 ]
2013-01-17 23:33:59 --- STRACE: Guzzle\Service\Exception\ValidationException [ 0 ]: Validation errors: [Objects][Key] must be of type object ~ phar:///usr/share/php/AWSSDKforPHP/aws.phar/vendor/guzzle/guzzle/src/Guzzle/Service/Command/AbstractCommand.php [ 376 ]
--
#0 phar:///usr/share/php/AWSSDKforPHP/aws.phar/vendor/guzzle/guzzle/src/Guzzle/Service/Command/AbstractCommand.php(272): Guzzle\Service\Command\AbstractCommand->validate()
#1 phar:///usr/share/php/AWSSDKforPHP/aws.phar/vendor/guzzle/guzzle/src/Guzzle/Service/Client.php(218): Guzzle\Service\Command\AbstractCommand->prepare()
#2 phar:///usr/share/php/AWSSDKforPHP/aws.phar/vendor/guzzle/guzzle/src/Guzzle/Service/Command/AbstractCommand.php(167): Guzzle\Service\Client->execute(Object(Guzzle\Service\Command\OperationCommand))
#3 phar:///usr/share/php/AWSSDKforPHP/aws.phar/vendor/guzzle/guzzle/src/Guzzle/Service/Command/AbstractCommand.php(218): Guzzle\Service\Command\AbstractCommand->execute()
#4 phar:///usr/share/php/AWSSDKforPHP/aws.phar/vendor/guzzle/guzzle/src/Guzzle/Service/Client.php(93): Guzzle\Service\Command\AbstractCommand->getResult()
#5 phar:///usr/share/php/AWSSDKforPHP/aws.phar/src/Aws/Common/Client/AbstractClient.php(102): Guzzle\Service\Client->__call('DeleteObjects', Array)
#6 /home/jason/www/rta.casa.net/application/classes/controller/post.php(741): Aws\Common\Client\AbstractClient->__call('deleteObjects', Array)
#7 /home/jason/www/rta.casa.net/application/classes/controller/post.php(741): Aws\S3\S3Client->deleteObjects(Array)
#8 /home/jason/www/rta.casa.net/application/classes/controller/post.php(134): Controller_Post::delete_attachment(Array)
#9 [internal function]: Controller_Post->action_delete()
#10 /home/jason/www/common/kohana/system/classes/kohana/request/client/internal.php(116): ReflectionMethod->invoke(Object(Controller_Post))
#11 /home/jason/www/common/kohana/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#12 /home/jason/www/common/kohana/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#13 /home/jason/www/rta.casa.net/index.php(115): Kohana_Request->execute()
#14 {main}
2013-01-17 23:51:07 --- DEBUG: No session found
2013-01-17 23:51:07 --- DEBUG: Continue Invoked
2013-01-17 23:51:12 --- DEBUG: No session found
2013-01-17 23:51:12 --- DEBUG: Continue Invoked
2013-01-17 23:52:51 --- DEBUG: No session found
2013-01-17 23:52:51 --- DEBUG: Continue Invoked
2013-01-17 23:53:10 --- DEBUG: No session found
2013-01-17 23:53:10 --- DEBUG: Continue Invoked
2013-01-17 23:53:29 --- DEBUG: No session found
2013-01-17 23:53:29 --- DEBUG: Continue Invoked
2013-01-17 23:54:04 --- DEBUG: No session found
2013-01-17 23:54:04 --- DEBUG: Continue Invoked
2013-01-17 23:54:25 --- DEBUG: No session found
2013-01-17 23:54:25 --- DEBUG: Continue Invoked
2013-01-17 23:54:36 --- DEBUG: No session found
2013-01-17 23:54:36 --- DEBUG: Continue Invoked
2013-01-17 23:54:44 --- DEBUG: No session found
2013-01-17 23:54:44 --- DEBUG: Continue Invoked
2013-01-17 23:54:53 --- DEBUG: No session found
2013-01-17 23:54:53 --- DEBUG: Continue Invoked
2013-01-17 23:56:12 --- DEBUG: Access Denied invoked because no valid user
2013-01-17 23:56:16 --- DEBUG: Access Denied invoked because no valid user
2013-01-17 23:56:16 --- DEBUG: Access Denied invoked because no valid user
2013-01-17 23:56:52 --- DEBUG: Access Denied in check_password
2013-01-17 23:56:59 --- ERROR: MongoCursorException [ 10113 ]: localhost:27017: bad hint ~ MODPATH/mongodb/classes/mongo/collection.php [ 1029 ]
2013-01-17 23:56:59 --- STRACE: MongoCursorException [ 10113 ]: localhost:27017: bad hint ~ MODPATH/mongodb/classes/mongo/collection.php [ 1029 ]
--
#0 /home/jason/www/common/kohana/modules/mongodb/classes/mongo/collection.php(1029): MongoCursor->next()
#1 /home/jason/www/rta.casa.net/application/classes/model/user/collection.php(150): Mongo_Collection->getNext()
#2 /home/jason/www/rta.casa.net/application/classes/controller/user.php(812): Model_User_Collection->search(Array)
#3 /home/jason/www/rta.casa.net/application/classes/controller/user.php(102): Controller_User->search()
#4 [internal function]: Controller_User->action_index()
#5 /home/jason/www/common/kohana/system/classes/kohana/request/client/internal.php(116): ReflectionMethod->invoke(Object(Controller_User))
#6 /home/jason/www/common/kohana/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#7 /home/jason/www/common/kohana/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#8 /home/jason/www/rta.casa.net/index.php(115): Kohana_Request->execute()
#9 {main}
2013-01-17 23:57:08 --- DEBUG: Access Denied in check_password
2013-01-17 23:57:25 --- ERROR: MongoCursorException [ 10113 ]: localhost:27017: bad hint ~ MODPATH/mongodb/classes/mongo/collection.php [ 1029 ]
2013-01-17 23:57:25 --- STRACE: MongoCursorException [ 10113 ]: localhost:27017: bad hint ~ MODPATH/mongodb/classes/mongo/collection.php [ 1029 ]
--
#0 /home/jason/www/common/kohana/modules/mongodb/classes/mongo/collection.php(1029): MongoCursor->next()
#1 /home/jason/www/rta.casa.net/application/classes/model/user/collection.php(150): Mongo_Collection->getNext()
#2 /home/jason/www/rta.casa.net/application/classes/controller/user.php(812): Model_User_Collection->search(Array)
#3 /home/jason/www/rta.casa.net/application/classes/controller/user.php(102): Controller_User->search()
#4 [internal function]: Controller_User->action_index()
#5 /home/jason/www/common/kohana/system/classes/kohana/request/client/internal.php(116): ReflectionMethod->invoke(Object(Controller_User))
#6 /home/jason/www/common/kohana/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#7 /home/jason/www/common/kohana/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#8 /home/jason/www/rta.casa.net/index.php(115): Kohana_Request->execute()
#9 {main}
2013-01-17 23:57:33 --- ERROR: MongoCursorException [ 10113 ]: localhost:27017: bad hint ~ MODPATH/mongodb/classes/mongo/collection.php [ 1029 ]
2013-01-17 23:57:33 --- STRACE: MongoCursorException [ 10113 ]: localhost:27017: bad hint ~ MODPATH/mongodb/classes/mongo/collection.php [ 1029 ]
--
#0 /home/jason/www/common/kohana/modules/mongodb/classes/mongo/collection.php(1029): MongoCursor->next()
#1 /home/jason/www/rta.casa.net/application/classes/model/user/collection.php(150): Mongo_Collection->getNext()
#2 /home/jason/www/rta.casa.net/application/classes/controller/user.php(812): Model_User_Collection->search(Array)
#3 /home/jason/www/rta.casa.net/application/classes/controller/user.php(102): Controller_User->search()
#4 [internal function]: Controller_User->action_index()
#5 /home/jason/www/common/kohana/system/classes/kohana/request/client/internal.php(116): ReflectionMethod->invoke(Object(Controller_User))
#6 /home/jason/www/common/kohana/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#7 /home/jason/www/common/kohana/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#8 /home/jason/www/rta.casa.net/index.php(115): Kohana_Request->execute()
#9 {main}