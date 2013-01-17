<?php defined('SYSPATH') OR die('No direct script access.'); ?>

2013-01-16 01:20:15 --- DEBUG: Access Denied invoked because no valid user
2013-01-16 01:21:29 --- DEBUG: Access Denied invoked because no valid user
2013-01-16 01:21:48 --- DEBUG: Access Denied invoked because no valid user
2013-01-16 01:29:48 --- DEBUG: Access Denied invoked because no valid user
2013-01-16 01:29:51 --- DEBUG: Access Denied invoked because no valid user
2013-01-16 01:29:51 --- DEBUG: Access Denied invoked because no valid user
2013-01-16 01:30:36 --- ERROR: ErrorException [ 2048 ]: Only variables should be passed by reference ~ APPPATH/classes/controller/user.php [ 942 ]
2013-01-16 01:30:36 --- STRACE: ErrorException [ 2048 ]: Only variables should be passed by reference ~ APPPATH/classes/controller/user.php [ 942 ]
--
#0 /home/jason/www/rta.casa.net/application/classes/controller/user.php(942): Kohana_Core::error_handler(2048, 'Only variables ...', '/home/jason/www...', 942, Array)
#1 /home/jason/www/rta.casa.net/application/classes/controller/user.php(143): Controller_User->beta_interest()
#2 [internal function]: Controller_User->action_update()
#3 /home/jason/www/common/kohana/system/classes/kohana/request/client/internal.php(116): ReflectionMethod->invoke(Object(Controller_User))
#4 /home/jason/www/common/kohana/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#5 /home/jason/www/common/kohana/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#6 /home/jason/www/rta.casa.net/index.php(114): Kohana_Request->execute()
#7 {main}
2013-01-16 01:36:53 --- DEBUG: Access Denied invoked because no valid user
2013-01-16 01:47:36 --- DEBUG: Access Denied invoked because no valid user
2013-01-16 01:47:40 --- DEBUG: Access Denied invoked because no valid user
2013-01-16 01:47:40 --- DEBUG: Access Denied invoked because no valid user
2013-01-16 01:47:48 --- DEBUG: Access Denied invoked because no valid user
2013-01-16 01:47:48 --- DEBUG: Access Denied invoked because no valid user
2013-01-16 01:48:22 --- ERROR: ErrorException [ 2048 ]: Only variables should be passed by reference ~ APPPATH/classes/controller/user.php [ 942 ]
2013-01-16 01:48:22 --- STRACE: ErrorException [ 2048 ]: Only variables should be passed by reference ~ APPPATH/classes/controller/user.php [ 942 ]
--
#0 /home/jason/www/rta.casa.net/application/classes/controller/user.php(942): Kohana_Core::error_handler(2048, 'Only variables ...', '/home/jason/www...', 942, Array)
#1 /home/jason/www/rta.casa.net/application/classes/controller/user.php(143): Controller_User->beta_interest()
#2 [internal function]: Controller_User->action_update()
#3 /home/jason/www/common/kohana/system/classes/kohana/request/client/internal.php(116): ReflectionMethod->invoke(Object(Controller_User))
#4 /home/jason/www/common/kohana/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#5 /home/jason/www/common/kohana/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#6 /home/jason/www/rta.casa.net/index.php(114): Kohana_Request->execute()
#7 {main}
2013-01-16 01:52:47 --- DEBUG: Access Denied invoked because no valid user
2013-01-16 01:52:50 --- DEBUG: Access Denied invoked because no valid user
2013-01-16 01:52:50 --- DEBUG: Access Denied invoked because no valid user
2013-01-16 04:51:20 --- ERROR: MongoCursorException [ 10113 ]: localhost:27017: bad hint ~ MODPATH/mongodb/classes/mongo/collection.php [ 1029 ]
2013-01-16 04:51:20 --- STRACE: MongoCursorException [ 10113 ]: localhost:27017: bad hint ~ MODPATH/mongodb/classes/mongo/collection.php [ 1029 ]
--
#0 /home/jason/www/common/kohana/modules/mongodb/classes/mongo/collection.php(1029): MongoCursor->next()
#1 /home/jason/www/rta.casa.net/application/classes/model/user/collection.php(169): Mongo_Collection->getNext()
#2 /home/jason/www/rta.casa.net/application/classes/controller/user.php(811): Model_User_Collection->search(Array, '50f59780d0729dd...')
#3 /home/jason/www/rta.casa.net/application/classes/controller/user.php(102): Controller_User->search()
#4 [internal function]: Controller_User->action_index()
#5 /home/jason/www/common/kohana/system/classes/kohana/request/client/internal.php(116): ReflectionMethod->invoke(Object(Controller_User))
#6 /home/jason/www/common/kohana/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#7 /home/jason/www/common/kohana/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#8 /home/jason/www/rta.casa.net/index.php(114): Kohana_Request->execute()
#9 {main}
2013-01-16 04:51:37 --- DEBUG: Error invoked in MODPATH/classes/sms. invalid number
2013-01-16 04:51:37 --- ERROR: MongoCursorException [ 10113 ]: localhost:27017: bad hint ~ MODPATH/mongodb/classes/mongo/collection.php [ 1029 ]
2013-01-16 04:51:37 --- STRACE: MongoCursorException [ 10113 ]: localhost:27017: bad hint ~ MODPATH/mongodb/classes/mongo/collection.php [ 1029 ]
--
#0 /home/jason/www/common/kohana/modules/mongodb/classes/mongo/collection.php(1029): MongoCursor->next()
#1 /home/jason/www/rta.casa.net/application/classes/model/user/collection.php(169): Mongo_Collection->getNext()
#2 /home/jason/www/rta.casa.net/application/classes/controller/testpad.php(662): Model_User_Collection->search(Array)
#3 [internal function]: Controller_Testpad->action_index()
#4 /home/jason/www/common/kohana/system/classes/kohana/request/client/internal.php(116): ReflectionMethod->invoke(Object(Controller_Testpad))
#5 /home/jason/www/common/kohana/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/jason/www/common/kohana/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/jason/www/rta.casa.net/index.php(114): Kohana_Request->execute()
#8 {main}
2013-01-16 04:57:32 --- DEBUG: Error invoked in MODPATH/classes/sms. invalid number
2013-01-16 04:57:56 --- DEBUG: Error invoked in MODPATH/classes/sms. invalid number
2013-01-16 10:09:03 --- ERROR: ErrorException [ 2 ]: include({
    &quot;status&quot;: &quot;success&quot;,
    &quot;session&quot;: {
        &quot;_id&quot;: &quot;50f5c00fc6af886d5be78dc9&quot;,
        &quot;user_id&quot;: &quot;50f59780d0729dd50c000038&quot;,
        &quot;code&quot;: null,
        &quot;access_token&quot;: &quot;fe2ce944c9a68390ac1b0823ee84d608a47f69e1&quot;,
        &quot;expires_in&quot;: 5259488,
        &quot;grant_type&quot;: &quot;password&quot;,
        &quot;stage&quot;: &quot;granted&quot;,
        &quot;modified&quot;: 1358282767,
        &quot;state&quot;: &quot;afbecf3c3adb7c8262fd8a07d23b15166309f0c5&quot;
    },
    &quot;data&quot;: [
        {
            &quot;_id&quot;: &quot;50ed6ff0b90fb8e80c000003&quot;,
            &quot;attachments&quot;: [

            ],
            &quot;content&quot;: null,
            &quot;counts&quot;: {
                &quot;attachments&quot;: 0,
                &quot;checkins&quot;: 0,
                &quot;events&quot;: 0,
                &quot;likers&quot;: 0,
                &quot;listings&quot;: 0,
                &quot;posts&quot;: 0,
                &quot;subscribers&quot;: 0,
                &quot;members&quot;: 0,
                &quot;testimonials&quot;: 0
            },
            &quot;created&quot;: 1357737968,
            &quot;keywords&quot;: [
                &quot;lorong g telok kurau&quot;,
                &quot;suites twenty two&quot;,
                &quot;katong&quot;
            ],
            &quot;likers&quot;: [

            ],
            &quot;modified&quot;: 1358238569,
            &quot;name&quot;: &quot;Suites Twenty Two&quot;,
            &quot;parent&quot;: {
                &quot;_id&quot;: &quot;50ed251ab90fb8c81700003b&quot;,
                &quot;name&quot;: &quot;Katong&quot;,
                &quot;uri&quot;: &quot;\/location\/estate\/katong&quot;,
                &quot;type&quot;: &quot;location&quot;
            },
            &quot;privacy&quot;: {
                &quot;is&quot;: &quot;PUBLIC&quot;
            },
            &quot;sector&quot;: [

            ],
            &quot;slug&quot;: &quot;suites-twenty-two&quot;,
            &quot;stats&quot;: [

            ],
            &quot;status&quot;: &quot;active&quot;,
            &quot;subscribers&quot;: [

            ],
            &quot;subtype&quot;: &quot;Private Apartments&quot;,
            &quot;type&quot;: &quot;location&quot;,
            &quot;uri&quot;: &quot;\/location\/private-apartments\/suites-twenty-two&quot;,
            &quot;user&quot;: {
                &quot;_id&quot;: &quot;50b7620ab90fb8e884000009&quot;,
                &quot;name&quot;: &quot;Jason Joe&quot;
            },
            &quot;is_owner&quot;: false,
            &quot;is_page_owner&quot;: false,
            &quot;connections&quot;: {
                &quot;likers&quot;: &quot;http:\/\/rta.jjpc.net\/page\/likers\/50ed6ff0b90fb8e80c000003&quot;,
                &quot;feed&quot;: &quot;http:\/\/rta.jjpc.net\/page\/feed\/50ed6ff0b90fb8e80c000003&quot;,
                &quot;subscribers&quot;: &quot;http:\/\/rta.jjpc.net\/page\/subscribers\/50ed6ff0b90fb8e80c000003&quot;,
                &quot;testimonials&quot;: &quot;http:\/\/rta.jjpc.net\/page\/testimonials\/50ed6ff0b90fb8e80c000003&quot;,
                &quot;pictures&quot;: &quot;http:\/\/rta.jjpc.net\/page\/pictures\/50ed6ff0b90fb8e80c000003&quot;
            },
            &quot;interactions&quot;: {
                &quot;like&quot;: false,
                &quot;subscribe&quot;: false
            },
            &quot;actions&quot;: {
                &quot;like&quot;: &quot;http:\/\/rta.jjpc.net\/page\/like\/50ed6ff0b90fb8e80c000003&quot;,
                &quot;unlike&quot;: &quot;http:\/\/rta.jjpc.net\/page\/unlike\/50ed6ff0b90fb8e80c000003&quot;,
                &quot;subscribe&quot;: &quot;http:\/\/rta.jjpc.net\/page\/subscribe\/50ed6ff0b90fb8e80c000003&quot;,
                &quot;unsubscribe&quot;: &quot;http:\/\/rta.jjpc.net\/page\/unsubscribe\/50ed6ff0b90fb8e80c000003&quot;
            }
        },
        {
            &quot;_id&quot;: &quot;50ed6fcab90fb8e80c000002&quot;,
            &quot;attachments&quot;: [

            ],
            &quot;content&quot;: null,
            &quot;counts&quot;: {
                &quot;attachments&quot;: 0,
                &quot;checkins&quot;: 0,
                &quot;events&quot;: 0,
                &quot;likers&quot;: 0,
                &quot;listings&quot;: 0,
                &quot;posts&quot;: 0,
                &quot;subscribers&quot;: 0,
                &quot;members&quot;: 0,
                &quot;testimonials&quot;: 0
            },
            &quot;created&quot;: 1357737930,
            &quot;keywords&quot;: [
                &quot;tembeling road&quot;,
                &quot;studios@tembeling&quot;,
                &quot;katong&quot;
            ],
            &quot;likers&quot;: [

            ],
            &quot;modified&quot;: 1358238569,
            &quot;name&quot;: &quot;Studios@Tembeling&quot;,
            &quot;parent&quot;: {
                &quot;_id&quot;: &quot;50ed251ab90fb8c81700003b&quot;,
                &quot;name&quot;: &quot;Katong&quot;,
                &quot;uri&quot;: &quot;\/location\/estate\/katong&quot;,
                &quot;type&quot;: &quot;location&quot;
            },
            &quot;privacy&quot;: {
                &quot;is&quot;: &quot;PUBLIC&quot;
            },
            &quot;sector&quot;: [

            ],
            &quot;slug&quot;: &quot;studiostembeling&quot;,
            &quot;stats&quot;: [

            ],
            &quot;status&quot;: &quot;active&quot;,
            &quot;subscribers&quot;: [

            ],
            &quot;subtype&quot;: &quot;Private Apartments&quot;,
            &quot;type&quot;: &quot;location&quot;,
            &quot;uri&quot;: &quot;\/location\/private-apartments\/studiostembeling&quot;,
            &quot;user&quot;: {
                &quot;_id&quot;: &quot;50b7620ab90fb8e884000009&quot;,
                &quot;name&quot;: &quot;Jason Joe&quot;
            },
            &quot;is_owner&quot;: false,
            &quot;is_page_owner&quot;: false,
            &quot;connections&quot;: {
                &quot;likers&quot;: &quot;http:\/\/rta.jjpc.net\/page\/likers\/50ed6fcab90fb8e80c000002&quot;,
                &quot;feed&quot;: &quot;http:\/\/rta.jjpc.net\/page\/feed\/50ed6fcab90fb8e80c000002&quot;,
                &quot;subscribers&quot;: &quot;http:\/\/rta.jjpc.net\/page\/subscribers\/50ed6fcab90fb8e80c000002&quot;,
                &quot;testimonials&quot;: &quot;http:\/\/rta.jjpc.net\/page\/testimonials\/50ed6fcab90fb8e80c000002&quot;,
                &quot;pictures&quot;: &quot;http:\/\/rta.jjpc.net\/page\/pictures\/50ed6fcab90fb8e80c000002&quot;
            },
            &quot;interactions&quot;: {
                &quot;like&quot;: false,
                &quot;subscribe&quot;: false
            },
            &quot;actions&quot;: {
                &quot;like&quot;: &quot;http:\/\/rta.jjpc.net\/page\/like\/50ed6fcab90fb8e80c000002&quot;,
                &quot;unlike&quot;: &quot;http:\/\/rta.jjpc.net\/page\/unlike\/50ed6fcab90fb8e80c000002&quot;,
                &quot;subscribe&quot;: &quot;http:\/\/rta.jjpc.net\/page\/subscribe\/50ed6fcab90fb8e80c000002&quot;,
                &quot;unsubscribe&quot;: &quot;http:\/\/rta.jjpc.net\/page\/unsubscribe\/50ed6fcab90fb8e80c000002&quot;
            }
        },
        {
            &quot;_id&quot;: &quot;50ed6dd2b90fb8f818000005&quot;,
            &quot;attachments&quot;: [

            ],
            &quot;content&quot;: null,
            &quot;counts&quot;: {
                &quot;attachments&quot;: 0,
                &quot;checkins&quot;: 0,
                &quot;events&quot;: 0,
                &quot;likers&quot;: 0,
                &quot;listings&quot;: 0,
                &quot;posts&quot;: 0,
                &quot;subscribers&quot;: 0,
                &quot;members&quot;: 0,
                &quot;testimonials&quot;: 0
            },
            &quot;created&quot;: 1357737426,
            &quot;keywords&quot;: [
                &quot;hillview terrace&quot;,
                &quot;natura@hillview&quot;,
                &quot;bukit panjang&quot;
            ],
            &quot;likers&quot;: [

            ],
            &quot;modified&quot;: 1358238569,
            &quot;name&quot;: &quot;Natura@Hillview&quot;,
            &quot;parent&quot;: {
                &quot;_id&quot;: &quot;50ed2719b90fb8c81700004c&quot;,
                &quot;name&quot;: &quot;Bukit Panjang&quot;,
                &quot;uri&quot;: &quot;\/location\/estate\/bukit-panjang&quot;,
                &quot;type&quot;: &quot;location&quot;
            },
            &quot;privacy&quot;: {
                &quot;is&quot;: &quot;PUBLIC&quot;
            },
            &quot;sector&quot;: [

            ],
            &quot;slug&quot;: &quot;naturahillview&quot;,
            &quot;stats&quot;: [

            ],
            &quot;status&quot;: &quot;active&quot;,
            &quot;subscribers&quot;: [

            ],
            &quot;subtype&quot;: &quot;Private Apartments&quot;,
            &quot;type&quot;: &quot;location&quot;,
            &quot;uri&quot;: &quot;\/location\/private-apartments\/naturahillview&quot;,
            &quot;user&quot;: {
                &quot;_id&quot;: &quot;50b7620ab90fb8e884000009&quot;,
                &quot;name&quot;: &quot;Jason Joe&quot;
            },
            &quot;is_owner&quot;: false,
            &quot;is_page_owner&quot;: false,
            &quot;connections&quot;: {
                &quot;likers&quot;: &quot;http:\/\/rta.jjpc.net\/page\/likers\/50ed6dd2b90fb8f818000005&quot;,
                &quot;feed&quot;: &quot;http:\/\/rta.jjpc.net\/page\/feed\/50ed6dd2b90fb8f818000005&quot;,
                &quot;subscribers&quot;: &quot;http:\/\/rta.jjpc.net\/page\/subscribers\/50ed6dd2b90fb8f818000005&quot;,
                &quot;testimonials&quot;: &quot;http:\/\/rta.jjpc.net\/page\/testimonials\/50ed6dd2b90fb8f818000005&quot;,
                &quot;pictures&quot;: &quot;http:\/\/rta.jjpc.net\/page\/pictures\/50ed6dd2b90fb8f818000005&quot;
            },
            &quot;interactions&quot;: {
                &quot;like&quot;: false,
                &quot;subscribe&quot;: false
            },
            &quot;actions&quot;: {
                &quot;like&quot;: &quot;http:\/\/rta.jjpc.net\/page\/like\/50ed6dd2b90fb8f818000005&quot;,
                &quot;unlike&quot;: &quot;http:\/\/rta.jjpc.net\/page\/unlike\/50ed6dd2b90fb8f818000005&quot;,
                &quot;subscribe&quot;: &quot;http:\/\/rta.jjpc.net\/page\/subscribe\/50ed6dd2b90fb8f818000005&quot;,
                &quot;unsubscribe&quot;: &quot;http:\/\/rta.jjpc.net\/page\/unsubscribe\/50ed6dd2b90fb8f818000005&quot;
            }
        },
        {
            &quot;_id&quot;: &quot;50ed6ce8b90fb8f818000002&quot;,
            &quot;attachments&quot;: [

            ],
            &quot;content&quot;: null,
            &quot;counts&quot;: {
                &quot;attachments&quot;: 0,
                &quot;checkins&quot;: 0,
                &quot;events&quot;: 0,
                &quot;likers&quot;: 0,
                &quot;listings&quot;: 0,
                &quot;posts&quot;: 0,
                &quot;subscribers&quot;: 0,
                &quot;members&quot;: 0,
                &quot;testimonials&quot;: 0
            },
            &quot;created&quot;: 1357737192,
            &quot;keywords&quot;: [
                &quot;lew lian vale&quot;,
                &quot;jade towers&quot;,
                &quot;hougang (paya lebar)&quot;
            ],
            &quot;likers&quot;: [

            ],
            &quot;modified&quot;: 1358238569,
            &quot;name&quot;: &quot;Jade Towers&quot;,
            &quot;parent&quot;: {
                &quot;_id&quot;: &quot;50ed25c4b90fb8c817000041&quot;,
                &quot;name&quot;: &quot;Hougang (Paya Lebar)&quot;,
                &quot;uri&quot;: &quot;\/location\/estate\/hougang-paya-lebar&quot;,
                &quot;type&quot;: &quot;location&quot;
            },
            &quot;privacy&quot;: {
                &quot;is&quot;: &quot;PUBLIC&quot;
            },
            &quot;sector&quot;: [

            ],
            &quot;slug&quot;: &quot;jade-towers&quot;,
            &quot;stats&quot;: [

            ],
            &quot;status&quot;: &quot;active&quot;,
            &quot;subscribers&quot;: [

            ],
            &quot;subtype&quot;: &quot;Private Apartments&quot;,
            &quot;type&quot;: &quot;location&quot;,
            &quot;uri&quot;: &quot;\/location\/private-apartments\/jade-towers&quot;,
            &quot;user&quot;: {
                &quot;_id&quot;: &quot;50b7620ab90fb8e884000009&quot;,
                &quot;name&quot;: &quot;Jason Joe&quot;
            },
            &quot;is_owner&quot;: false,
            &quot;is_page_owner&quot;: false,
            &quot;connections&quot;: {
                &quot;likers&quot;: &quot;http:\/\/rta.jjpc.net\/page\/likers\/50ed6ce8b90fb8f818000002&quot;,
                &quot;feed&quot;: &quot;http:\/\/rta.jjpc.net\/page\/feed\/50ed6ce8b90fb8f818000002&quot;,
                &quot;subscribers&quot;: &quot;http:\/\/rta.jjpc.net\/page\/subscribers\/50ed6ce8b90fb8f818000002&quot;,
                &quot;testimonials&quot;: &quot;http:\/\/rta.jjpc.net\/page\/testimonials\/50ed6ce8b90fb8f818000002&quot;,
                &quot;pictures&quot;: &quot;http:\/\/rta.jjpc.net\/page\/pictures\/50ed6ce8b90fb8f818000002&quot;
            },
            &quot;interactions&quot;: {
                &quot;like&quot;: false,
                &quot;subscribe&quot;: false
            },
            &quot;actions&quot;: {
                &quot;like&quot;: &quot;http:\/\/rta.jjpc.net\/page\/like\/50ed6ce8b90fb8f818000002&quot;,
                &quot;unlike&quot;: &quot;http:\/\/rta.jjpc.net\/page\/unlike\/50ed6ce8b90fb8f818000002&quot;,
                &quot;subscribe&quot;: &quot;http:\/\/rta.jjpc.net\/page\/subscribe\/50ed6ce8b90fb8f818000002&quot;,
                &quot;unsubscribe&quot;: &quot;http:\/\/rta.jjpc.net\/page\/unsubscribe\/50ed6ce8b90fb8f818000002&quot;
            }
        },
        {
            &quot;_id&quot;: &quot;50ed6ca3b90fb8f818000001&quot;,
            &quot;attachments&quot;: [

            ],
            &quot;content&quot;: null,
            &quot;counts&quot;: {
                &quot;attachments&quot;: 0,
                &quot;checkins&quot;: 0,
                &quot;events&quot;: 0,
                &quot;likers&quot;: 0,
                &quot;listings&quot;: 0,
                &quot;posts&quot;: 0,
                &quot;subscribers&quot;: 0,
                &quot;members&quot;: 0,
                &quot;testimonials&quot;: 0
            },
            &quot;created&quot;: 1357737123,
            &quot;keywords&quot;: [
                &quot;leonie hill&quot;,
                &quot;horizon towers west&quot;,
                &quot;orchard&quot;
            ],
            &quot;likers&quot;: [

            ],
            &quot;modified&quot;: 1358238569,
            &quot;name&quot;: &quot;Horizon Towers West&quot;,
            &quot;parent&quot;: {
                &quot;_id&quot;: &quot;50ed2361b90fb8c81700002f&quot;,
                &quot;name&quot;: &quot;Orchard&quot;,
                &quot;uri&quot;: &quot;\/location\/estate\/orchard&quot;,
                &quot;type&quot;: &quot;location&quot;
            },
            &quot;privacy&quot;: {
                &quot;is&quot;: &quot;PUBLIC&quot;
            },
            &quot;sector&quot;: [

            ],
            &quot;slug&quot;: &quot;horizon-towers-west&quot;,
            &quot;stats&quot;: [

            ],
            &quot;status&quot;: &quot;active&quot;,
            &quot;subscribers&quot;: [

            ],
            &quot;subtype&quot;: &quot;Private Apartments&quot;,
            &quot;type&quot;: &quot;location&quot;,
            &quot;uri&quot;: &quot;\/location\/private-apartments\/horizon-towers-west&quot;,
            &quot;user&quot;: {
                &quot;_id&quot;: &quot;50b7620ab90fb8e884000009&quot;,
                &quot;name&quot;: &quot;Jason Joe&quot;
            },
            &quot;is_owner&quot;: false,
            &quot;is_page_owner&quot;: false,
            &quot;connections&quot;: {
                &quot;likers&quot;: &quot;http:\/\/rta.jjpc.net\/page\/likers\/50ed6ca3b90fb8f818000001&quot;,
                &quot;feed&quot;: &quot;http:\/\/rta.jjpc.net\/page\/feed\/50ed6ca3b90fb8f818000001&quot;,
                &quot;subscribers&quot;: &quot;http:\/\/rta.jjpc.net\/page\/subscribers\/50ed6ca3b90fb8f818000001&quot;,
                &quot;testimonials&quot;: &quot;http:\/\/rta.jjpc.net\/page\/testimonials\/50ed6ca3b90fb8f818000001&quot;,
                &quot;pictures&quot;: &quot;http:\/\/rta.jjpc.net\/page\/pictures\/50ed6ca3b90fb8f818000001&quot;
            },
            &quot;interactions&quot;: {
                &quot;like&quot;: false,
                &quot;subscribe&quot;: false
            },
            &quot;actions&quot;: {
                &quot;like&quot;: &quot;http:\/\/rta.jjpc.net\/page\/like\/50ed6ca3b90fb8f818000001&quot;,
                &quot;unlike&quot;: &quot;http:\/\/rta.jjpc.net\/page\/unlike\/50ed6ca3b90fb8f818000001&quot;,
                &quot;subscribe&quot;: &quot;http:\/\/rta.jjpc.net\/page\/subscribe\/50ed6ca3b90fb8f818000001&quot;,
                &quot;unsubscribe&quot;: &quot;http:\/\/rta.jjpc.net\/page\/unsubscribe\/50ed6ca3b90fb8f818000001&quot;
            }
        },
        {
            &quot;_id&quot;: &quot;50ed6c96b90fb8f818000000&quot;,
            &quot;attachments&quot;: [

            ],
            &quot;content&quot;: null,
            &quot;counts&quot;: {
                &quot;attachments&quot;: 0,
                &quot;checkins&quot;: 0,
                &quot;events&quot;: 0,
                &quot;likers&quot;: 0,
                &quot;listings&quot;: 0,
                &quot;posts&quot;: 0,
                &quot;subscribers&quot;: 0,
                &quot;members&quot;: 0,
                &quot;testimonials&quot;: 0
            },
            &quot;created&quot;: 1357737110,
            &quot;keywords&quot;: [
                &quot;leonie hill road&quot;,
                &quot;horizon towers east&quot;,
                &quot;orchard&quot;
            ],
            &quot;likers&quot;: [

            ],
            &quot;modified&quot;: 1358238569,
            &quot;name&quot;: &quot;Horizon Towers East&quot;,
            &quot;parent&quot;: {
                &quot;_id&quot;: &quot;50ed2361b90fb8c81700002f&quot;,
                &quot;name&quot;: &quot;Orchard&quot;,
                &quot;uri&quot;: &quot;\/location\/estate\/orchard&quot;,
                &quot;type&quot;: &quot;location&quot;
            },
            &quot;privacy&quot;: {
                &quot;is&quot;: &quot;PUBLIC&quot;
            },
            &quot;sector&quot;: [

            ],
            &quot;slug&quot;: &quot;horizon-towers-east&quot;,
            &quot;stats&quot;: [

            ],
            &quot;status&quot;: &quot;active&quot;,
            &quot;subscribers&quot;: [

            ],
            &quot;subtype&quot;: &quot;Private Apartments&quot;,
            &quot;type&quot;: &quot;location&quot;,
            &quot;uri&quot;: &quot;\/location\/private-apartments\/horizon-towers-east&quot;,
            &quot;user&quot;: {
                &quot;_id&quot;: &quot;50b7620ab90fb8e884000009&quot;,
                &quot;name&quot;: &quot;Jason Joe&quot;
            },
            &quot;is_owner&quot;: false,
            &quot;is_page_owner&quot;: false,
            &quot;connections&quot;: {
                &quot;likers&quot;: &quot;http:\/\/rta.jjpc.net\/page\/likers\/50ed6c96b90fb8f818000000&quot;,
                &quot;feed&quot;: &quot;http:\/\/rta.jjpc.net\/page\/feed\/50ed6c96b90fb8f818000000&quot;,
                &quot;subscribers&quot;: &quot;http:\/\/rta.jjpc.net\/page\/subscribers\/50ed6c96b90fb8f818000000&quot;,
                &quot;testimonials&quot;: &quot;http:\/\/rta.jjpc.net\/page\/testimonials\/50ed6c96b90fb8f818000000&quot;,
                &quot;pictures&quot;: &quot;http:\/\/rta.jjpc.net\/page\/pictures\/50ed6c96b90fb8f818000000&quot;
            },
            &quot;interactions&quot;: {
                &quot;like&quot;: false,
                &quot;subscribe&quot;: false
            },
            &quot;actions&quot;: {
                &quot;like&quot;: &quot;http:\/\/rta.jjpc.net\/page\/like\/50ed6c96b90fb8f818000000&quot;,
                &quot;unlike&quot;: &quot;http:\/\/rta.jjpc.net\/page\/unlike\/50ed6c96b90fb8f818000000&quot;,
                &quot;subscribe&quot;: &quot;http:\/\/rta.jjpc.net\/page\/subscribe\/50ed6c96b90fb8f818000000&quot;,
                &quot;unsubscribe&quot;: &quot;http:\/\/rta.jjpc.net\/page\/unsubscribe\/50ed6c96b90fb8f818000000&quot;
            }
        },
        {
            &quot;_id&quot;: &quot;50ec0a5ab90fb8c817000006&quot;,
            &quot;attachments&quot;: [

            ],
            &quot;content&quot;: null,
            &quot;counts&quot;: {
                &quot;attachments&quot;: 0,
                &quot;checkins&quot;: 0,
                &quot;events&quot;: 0,
                &quot;likers&quot;: 0,
                &quot;listings&quot;: 0,
                &quot;posts&quot;: 0,
                &quot;subscribers&quot;: 0,
                &quot;members&quot;: 0,
                &quot;testimonials&quot;: 0
            },
            &quot;keywords&quot;: [
                &quot;cairnhill rise&quot;,
                &quot;the cairnhill&quot;,
                &quot;condominium&quot;,
                &quot;orchard&quot;
            ],
            &quot;likers&quot;: [

            ],
            &quot;modified&quot;: 1358238568,
            &quot;name&quot;: &quot;The Cairnhill&quot;,
            &quot;parent&quot;: {
                &quot;_id&quot;: &quot;50ed2361b90fb8c81700002f&quot;,
                &quot;name&quot;: &quot;Orchard&quot;,
                &quot;uri&quot;: &quot;\/location\/estate\/orchard&quot;,
                &quot;type&quot;: &quot;location&quot;
            },
            &quot;privacy&quot;: {
                &quot;is&quot;: &quot;PUBLIC&quot;
            },
            &quot;property_type&quot;: &quot;Condominium&quot;,
            &quot;sector&quot;: &quot;22&quot;,
            &quot;sectors&quot;: [

            ],
            &quot;slug&quot;: &quot;the-cairnhill&quot;,
            &quot;stats&quot;: [

            ],
            &quot;status&quot;: &quot;active&quot;,
            &quot;subscribers&quot;: [

            ],
            &quot;subtype&quot;: &quot;Private Apartments&quot;,
            &quot;type&quot;: &quot;location&quot;,
            &quot;uri&quot;: &quot;\/location\/private-apartments\/the-cairnhill&quot;,
            &quot;user&quot;: {
                &quot;_id&quot;: &quot;50b7620ab90fb8e884000009&quot;,
                &quot;name&quot;: &quot;Jason Joe&quot;
            },
            &quot;created&quot;: 1357646426,
            &quot;is_owner&quot;: false,
            &quot;is_page_owner&quot;: false,
            &quot;connections&quot;: {
                &quot;likers&quot;: &quot;http:\/\/rta.jjpc.net\/page\/likers\/50ec0a5ab90fb8c817000006&quot;,
                &quot;feed&quot;: &quot;http:\/\/rta.jjpc.net\/page\/feed\/50ec0a5ab90fb8c817000006&quot;,
                &quot;subscribers&quot;: &quot;http:\/\/rta.jjpc.net\/page\/subscribers\/50ec0a5ab90fb8c817000006&quot;,
                &quot;testimonials&quot;: &quot;http:\/\/rta.jjpc.net\/page\/testimonials\/50ec0a5ab90fb8c817000006&quot;,
                &quot;pictures&quot;: &quot;http:\/\/rta.jjpc.net\/page\/pictures\/50ec0a5ab90fb8c817000006&quot;
            },
            &quot;interactions&quot;: {
                &quot;like&quot;: false,
                &quot;subscribe&quot;: false
            },
            &quot;actions&quot;: {
                &quot;like&quot;: &quot;http:\/\/rta.jjpc.net\/page\/like\/50ec0a5ab90fb8c817000006&quot;,
                &quot;unlike&quot;: &quot;http:\/\/rta.jjpc.net\/page\/unlike\/50ec0a5ab90fb8c817000006&quot;,
                &quot;subscribe&quot;: &quot;http:\/\/rta.jjpc.net\/page\/subscribe\/50ec0a5ab90fb8c817000006&quot;,
                &quot;unsubscribe&quot;: &quot;http:\/\/rta.jjpc.net\/page\/unsubscribe\/50ec0a5ab90fb8c817000006&quot;
            }
        },
        {
            &quot;_id&quot;: &quot;50ec09c3b90fb8c817000003&quot;,
            &quot;attachments&quot;: [

            ],
            &quot;content&quot;: null,
            &quot;counts&quot;: {
                &quot;attachments&quot;: 0,
                &quot;checkins&quot;: 0,
                &quot;events&quot;: 0,
                &quot;likers&quot;: 0,
                &quot;listings&quot;: 0,
                &quot;posts&quot;: 0,
                &quot;subscribers&quot;: 0,
                &quot;members&quot;: 0,
                &quot;testimonials&quot;: 0
            },
            &quot;keywords&quot;: [
                &quot;whitley road&quot;,
                &quot;whitley villas&quot;,
                &quot;thomson (newton)&quot;
            ],
            &quot;likers&quot;: [

            ],
            &quot;modified&quot;: 1358238568,
            &quot;name&quot;: &quot;Whitley Villas&quot;,
            &quot;parent&quot;: {
                &quot;_id&quot;: &quot;50ed2423b90fb8c817000033&quot;,
                &quot;name&quot;: &quot;Thomson (Newton)&quot;,
                &quot;uri&quot;: &quot;\/location\/estate\/thomson-newton&quot;,
                &quot;type&quot;: &quot;location&quot;
            },
            &quot;privacy&quot;: {
                &quot;is&quot;: &quot;PUBLIC&quot;
            },
            &quot;sector&quot;: &quot;29&quot;,
            &quot;sectors&quot;: [

            ],
            &quot;slug&quot;: &quot;whitley-villas&quot;,
            &quot;stats&quot;: [

            ],
            &quot;status&quot;: &quot;active&quot;,
            &quot;subscribers&quot;: [

            ],
            &quot;subtype&quot;: &quot;Private Apartments&quot;,
            &quot;type&quot;: &quot;location&quot;,
            &quot;uri&quot;: &quot;\/location\/private-apartments\/whitley-villas&quot;,
            &quot;user&quot;: {
                &quot;_id&quot;: &quot;50b7620ab90fb8e884000009&quot;,
                &quot;name&quot;: &quot;Jason Joe&quot;
            },
            &quot;created&quot;: 1357646275,
            &quot;is_owner&quot;: false,
            &quot;is_page_owner&quot;: false,
            &quot;connections&quot;: {
                &quot;likers&quot;: &quot;http:\/\/rta.jjpc.net\/page\/likers\/50ec09c3b90fb8c817000003&quot;,
                &quot;feed&quot;: &quot;http:\/\/rta.jjpc.net\/page\/feed\/50ec09c3b90fb8c817000003&quot;,
                &quot;subscribers&quot;: &quot;http:\/\/rta.jjpc.net\/page\/subscribers\/50ec09c3b90fb8c817000003&quot;,
                &quot;testimonials&quot;: &quot;http:\/\/rta.jjpc.net\/page\/testimonials\/50ec09c3b90fb8c817000003&quot;,
                &quot;pictures&quot;: &quot;http:\/\/rta.jjpc.net\/page\/pictures\/50ec09c3b90fb8c817000003&quot;
            },
            &quot;interactions&quot;: {
                &quot;like&quot;: false,
                &quot;subscribe&quot;: false
            },
            &quot;actions&quot;: {
                &quot;like&quot;: &quot;http:\/\/rta.jjpc.net\/page\/like\/50ec09c3b90fb8c817000003&quot;,
                &quot;unlike&quot;: &quot;http:\/\/rta.jjpc.net\/page\/unlike\/50ec09c3b90fb8c817000003&quot;,
                &quot;subscribe&quot;: &quot;http:\/\/rta.jjpc.net\/page\/subscribe\/50ec09c3b90fb8c817000003&quot;,
                &quot;unsubscribe&quot;: &quot;http:\/\/rta.jjpc.net\/page\/unsubscribe\/50ec09c3b90fb8c817000003&quot;
            }
        },
        {
            &quot;_id&quot;: &quot;50ec07fab90fb82017000003&quot;,
            &quot;attachments&quot;: [

            ],
            &quot;content&quot;: null,
            &quot;counts&quot;: {
                &quot;attachments&quot;: 0,
                &quot;checkins&quot;: 0,
                &quot;events&quot;: 0,
                &quot;likers&quot;: 0,
                &quot;listings&quot;: 0,
                &quot;posts&quot;: 0,
                &quot;subscribers&quot;: 0,
                &quot;members&quot;: 0,
                &quot;testimonials&quot;: 0
            },
            &quot;keywords&quot;: [
                &quot;bencoolen street&quot;,
                &quot;bencoolen house&quot;,
                &quot;bugis&quot;
            ],
            &quot;likers&quot;: [

            ],
            &quot;modified&quot;: 1358238567,
            &quot;name&quot;: &quot;Bencoolen House&quot;,
            &quot;parent&quot;: {
                &quot;_id&quot;: &quot;50ed22efb90fb8c81700002b&quot;,
                &quot;name&quot;: &quot;Bugis&quot;,
                &quot;uri&quot;: &quot;\/location\/estate\/bugis&quot;,
                &quot;type&quot;: &quot;location&quot;
            },
            &quot;privacy&quot;: {
                &quot;is&quot;: &quot;PUBLIC&quot;
            },
            &quot;sector&quot;: &quot;18&quot;,
            &quot;sectors&quot;: [

            ],
            &quot;slug&quot;: &quot;bencoolen-house&quot;,
            &quot;stats&quot;: [

            ],
            &quot;status&quot;: &quot;active&quot;,
            &quot;subscribers&quot;: [

            ],
            &quot;subtype&quot;: &quot;Private Apartments&quot;,
            &quot;type&quot;: &quot;location&quot;,
            &quot;uri&quot;: &quot;\/location\/private-apartments\/bencoolen-house&quot;,
            &quot;user&quot;: {
                &quot;_id&quot;: &quot;50b7620ab90fb8e884000009&quot;,
                &quot;name&quot;: &quot;Jason Joe&quot;
            },
            &quot;created&quot;: 1357645818,
            &quot;is_owner&quot;: false,
            &quot;is_page_owner&quot;: false,
            &quot;connections&quot;: {
                &quot;likers&quot;: &quot;http:\/\/rta.jjpc.net\/page\/likers\/50ec07fab90fb82017000003&quot;,
                &quot;feed&quot;: &quot;http:\/\/rta.jjpc.net\/page\/feed\/50ec07fab90fb82017000003&quot;,
                &quot;subscribers&quot;: &quot;http:\/\/rta.jjpc.net\/page\/subscribers\/50ec07fab90fb82017000003&quot;,
                &quot;testimonials&quot;: &quot;http:\/\/rta.jjpc.net\/page\/testimonials\/50ec07fab90fb82017000003&quot;,
                &quot;pictures&quot;: &quot;http:\/\/rta.jjpc.net\/page\/pictures\/50ec07fab90fb82017000003&quot;
            },
            &quot;interactions&quot;: {
                &quot;like&quot;: false,
                &quot;subscribe&quot;: false
            },
            &quot;actions&quot;: {
                &quot;like&quot;: &quot;http:\/\/rta.jjpc.net\/page\/like\/50ec07fab90fb82017000003&quot;,
                &quot;unlike&quot;: &quot;http:\/\/rta.jjpc.net\/page\/unlike\/50ec07fab90fb82017000003&quot;,
                &quot;subscribe&quot;: &quot;http:\/\/rta.jjpc.net\/page\/subscribe\/50ec07fab90fb82017000003&quot;,
                &quot;unsubscribe&quot;: &quot;http:\/\/rta.jjpc.net\/page\/unsubscribe\/50ec07fab90fb82017000003&quot;
            }
        },
        {
            &quot;_id&quot;: &quot;50e9473fb90fb8c81400028e&quot;,
            &quot;attachments&quot;: [

            ],
            &quot;content&quot;: null,
            &quot;counts&quot;: {
                &quot;attachments&quot;: 0,
                &quot;checkins&quot;: 0,
                &quot;events&quot;: 0,
                &quot;likers&quot;: 0,
                &quot;listings&quot;: 0,
                &quot;posts&quot;: 0,
                &quot;subscribers&quot;: 0,
                &quot;members&quot;: 0,
                &quot;testimonials&quot;: 0
            },
            &quot;keywords&quot;: [
                &quot;hamilton road&quot;,
                &quot;emerald hill road&quot;,
                &quot;killiney road&quot;,
                &quot;lengkok merak&quot;,
                &quot;jalan novena&quot;,
                &quot;barker road&quot;,
                &quot;telok kurau road&quot;,
                &quot;lorong stangee&quot;,
                &quot;lorong l telok kurau&quot;,
                &quot;jalan limau bali&quot;,
                &quot;residence 66&quot;,
                &quot;sengkang&quot;
            ],
            &quot;likers&quot;: [

            ],
            &quot;modified&quot;: 1358238567,
            &quot;name&quot;: &quot;Residence 66&quot;,
            &quot;parent&quot;: {
                &quot;_id&quot;: &quot;50ed25d7b90fb8c817000042&quot;,
                &quot;name&quot;: &quot;Sengkang&quot;,
                &quot;uri&quot;: &quot;\/location\/estate\/sengkang&quot;,
                &quot;type&quot;: &quot;location&quot;
            },
            &quot;privacy&quot;: {
                &quot;is&quot;: &quot;PUBLIC&quot;
            },
            &quot;sector&quot;: &quot;54&quot;,
            &quot;slug&quot;: &quot;residence-66&quot;,
            &quot;stats&quot;: [

            ],
            &quot;status&quot;: &quot;active&quot;,
            &quot;subscribers&quot;: [

            ],
            &quot;subtype&quot;: &quot;Private Apartments&quot;,
            &quot;type&quot;: &quot;location&quot;,
            &quot;uri&quot;: &quot;\/location\/private-apartments\/residence-66&quot;,
            &quot;user&quot;: {
                &quot;_id&quot;: &quot;50b7620ab90fb8e884000009&quot;,
                &quot;name&quot;: &quot;Jason Nathan&quot;
            },
            &quot;created&quot;: 1357465407,
            &quot;is_owner&quot;: false,
            &quot;is_page_owner&quot;: false,
            &quot;connections&quot;: {
                &quot;likers&quot;: &quot;http:\/\/rta.jjpc.net\/page\/likers\/50e9473fb90fb8c81400028e&quot;,
                &quot;feed&quot;: &quot;http:\/\/rta.jjpc.net\/page\/feed\/50e9473fb90fb8c81400028e&quot;,
                &quot;subscribers&quot;: &quot;http:\/\/rta.jjpc.net\/page\/subscribers\/50e9473fb90fb8c81400028e&quot;,
                &quot;testimonials&quot;: &quot;http:\/\/rta.jjpc.net\/page\/testimonials\/50e9473fb90fb8c81400028e&quot;,
                &quot;pictures&quot;: &quot;http:\/\/rta.jjpc.net\/page\/pictures\/50e9473fb90fb8c81400028e&quot;
            },
            &quot;interactions&quot;: {
                &quot;like&quot;: false,
                &quot;subscribe&quot;: false
            },
            &quot;actions&quot;: {
                &quot;like&quot;: &quot;http:\/\/rta.jjpc.net\/page\/like\/50e9473fb90fb8c81400028e&quot;,
                &quot;unlike&quot;: &quot;http:\/\/rta.jjpc.net\/page\/unlike\/50e9473fb90fb8c81400028e&quot;,
                &quot;subscribe&quot;: &quot;http:\/\/rta.jjpc.net\/page\/subscribe\/50e9473fb90fb8c81400028e&quot;,
                &quot;unsubscribe&quot;: &quot;http:\/\/rta.jjpc.net\/page\/unsubscribe\/50e9473fb90fb8c81400028e&quot;
            }
        },
        {
            &quot;_id&quot;: &quot;50e9473fb90fb8c81400028a&quot;,
            &quot;attachments&quot;: [

            ],
            &quot;content&quot;: null,
            &quot;counts&quot;: {
                &quot;attachments&quot;: 0,
                &quot;checkins&quot;: 0,
                &quot;events&quot;: 0,
                &quot;likers&quot;: 0,
                &quot;listings&quot;: 0,
                &quot;posts&quot;: 0,
                &quot;subscribers&quot;: 0,
                &quot;members&quot;: 0,
                &quot;testimonials&quot;: 0
            },
            &quot;keywords&quot;: [
                &quot;jalan bukit merah&quot;,
                &quot;mar thoma road&quot;,
                &quot;lorong 35 geylang&quot;,
                &quot;lorong marzuki&quot;,
                &quot;regal 35&quot;,
                &quot;kembangan (kaki bukit)&quot;
            ],
            &quot;likers&quot;: [

            ],
            &quot;modified&quot;: 1358238567,
            &quot;name&quot;: &quot;Regal 35&quot;,
            &quot;parent&quot;: {
                &quot;_id&quot;: &quot;50ed2509b90fb8c81700003a&quot;,
                &quot;name&quot;: &quot;Kembangan (Kaki Bukit)&quot;,
                &quot;uri&quot;: &quot;\/location\/estate\/kembangan-kaki-bukit&quot;,
                &quot;type&quot;: &quot;location&quot;
            },
            &quot;privacy&quot;: {
                &quot;is&quot;: &quot;PUBLIC&quot;
            },
            &quot;sector&quot;: &quot;41&quot;,
            &quot;slug&quot;: &quot;regal-35&quot;,
            &quot;stats&quot;: [

            ],
            &quot;status&quot;: &quot;active&quot;,
            &quot;subscribers&quot;: [

            ],
            &quot;subtype&quot;: &quot;Private Apartments&quot;,
            &quot;type&quot;: &quot;location&quot;,
            &quot;uri&quot;: &quot;\/location\/private-apartments\/regal-35&quot;,
            &quot;user&quot;: {
                &quot;_id&quot;: &quot;50b7620ab90fb8e884000009&quot;,
                &quot;name&quot;: &quot;Jason Nathan&quot;
            },
            &quot;created&quot;: 1357465407,
            &quot;is_owner&quot;: false,
            &quot;is_page_owner&quot;: false,
            &quot;connections&quot;: {
                &quot;likers&quot;: &quot;http:\/\/rta.jjpc.net\/page\/likers\/50e9473fb90fb8c81400028a&quot;,
                &quot;feed&quot;: &quot;http:\/\/rta.jjpc.net\/page\/feed\/50e9473fb90fb8c81400028a&quot;,
                &quot;subscribers&quot;: &quot;http:\/\/rta.jjpc.net\/page\/subscribers\/50e9473fb90fb8c81400028a&quot;,
                &quot;testimonials&quot;: &quot;http:\/\/rta.jjpc.net\/page\/testimonials\/50e9473fb90fb8c81400028a&quot;,
                &quot;pictures&quot;: &quot;http:\/\/rta.jjpc.net\/page\/pictures\/50e9473fb90fb8c81400028a&quot;
            },
            &quot;interactions&quot;: {
                &quot;like&quot;: false,
                &quot;subscribe&quot;: false
            },
            &quot;actions&quot;: {
                &quot;like&quot;: &quot;http:\/\/rta.jjpc.net\/page\/like\/50e9473fb90fb8c81400028a&quot;,
                &quot;unlike&quot;: &quot;http:\/\/rta.jjpc.net\/page\/unlike\/50e9473fb90fb8c81400028a&quot;,
                &quot;subscribe&quot;: &quot;http:\/\/rta.jjpc.net\/page\/subscribe\/50e9473fb90fb8c81400028a&quot;,
                &quot;unsubscribe&quot;: &quot;http:\/\/rta.jjpc.net\/page\/unsubscribe\/50e9473fb90fb8c81400028a&quot;
            }
        }
    ]
}): failed to open stream: Invalid argument ~ APPPATH/classes/controller.php [ 35 ]
2013-01-16 10:09:03 --- STRACE: ErrorException [ 2 ]: include({
    &quot;status&quot;: &quot;success&quot;,
    &quot;session&quot;: {
        &quot;_id&quot;: &quot;50f5c00fc6af886d5be78dc9&quot;,
        &quot;user_id&quot;: &quot;50f59780d0729dd50c000038&quot;,
        &quot;code&quot;: null,
        &quot;access_token&quot;: &quot;fe2ce944c9a68390ac1b0823ee84d608a47f69e1&quot;,
        &quot;expires_in&quot;: 5259488,
        &quot;grant_type&quot;: &quot;password&quot;,
        &quot;stage&quot;: &quot;granted&quot;,
        &quot;modified&quot;: 1358282767,
        &quot;state&quot;: &quot;afbecf3c3adb7c8262fd8a07d23b15166309f0c5&quot;
    },
    &quot;data&quot;: [
        {
            &quot;_id&quot;: &quot;50ed6ff0b90fb8e80c000003&quot;,
            &quot;attachments&quot;: [

            ],
            &quot;content&quot;: null,
            &quot;counts&quot;: {
                &quot;attachments&quot;: 0,
                &quot;checkins&quot;: 0,
                &quot;events&quot;: 0,
                &quot;likers&quot;: 0,
                &quot;listings&quot;: 0,
                &quot;posts&quot;: 0,
                &quot;subscribers&quot;: 0,
                &quot;members&quot;: 0,
                &quot;testimonials&quot;: 0
            },
            &quot;created&quot;: 1357737968,
            &quot;keywords&quot;: [
                &quot;lorong g telok kurau&quot;,
                &quot;suites twenty two&quot;,
                &quot;katong&quot;
            ],
            &quot;likers&quot;: [

            ],
            &quot;modified&quot;: 1358238569,
            &quot;name&quot;: &quot;Suites Twenty Two&quot;,
            &quot;parent&quot;: {
                &quot;_id&quot;: &quot;50ed251ab90fb8c81700003b&quot;,
                &quot;name&quot;: &quot;Katong&quot;,
                &quot;uri&quot;: &quot;\/location\/estate\/katong&quot;,
                &quot;type&quot;: &quot;location&quot;
            },
            &quot;privacy&quot;: {
                &quot;is&quot;: &quot;PUBLIC&quot;
            },
            &quot;sector&quot;: [

            ],
            &quot;slug&quot;: &quot;suites-twenty-two&quot;,
            &quot;stats&quot;: [

            ],
            &quot;status&quot;: &quot;active&quot;,
            &quot;subscribers&quot;: [

            ],
            &quot;subtype&quot;: &quot;Private Apartments&quot;,
            &quot;type&quot;: &quot;location&quot;,
            &quot;uri&quot;: &quot;\/location\/private-apartments\/suites-twenty-two&quot;,
            &quot;user&quot;: {
                &quot;_id&quot;: &quot;50b7620ab90fb8e884000009&quot;,
                &quot;name&quot;: &quot;Jason Joe&quot;
            },
            &quot;is_owner&quot;: false,
            &quot;is_page_owner&quot;: false,
            &quot;connections&quot;: {
                &quot;likers&quot;: &quot;http:\/\/rta.jjpc.net\/page\/likers\/50ed6ff0b90fb8e80c000003&quot;,
                &quot;feed&quot;: &quot;http:\/\/rta.jjpc.net\/page\/feed\/50ed6ff0b90fb8e80c000003&quot;,
                &quot;subscribers&quot;: &quot;http:\/\/rta.jjpc.net\/page\/subscribers\/50ed6ff0b90fb8e80c000003&quot;,
                &quot;testimonials&quot;: &quot;http:\/\/rta.jjpc.net\/page\/testimonials\/50ed6ff0b90fb8e80c000003&quot;,
                &quot;pictures&quot;: &quot;http:\/\/rta.jjpc.net\/page\/pictures\/50ed6ff0b90fb8e80c000003&quot;
            },
            &quot;interactions&quot;: {
                &quot;like&quot;: false,
                &quot;subscribe&quot;: false
            },
            &quot;actions&quot;: {
                &quot;like&quot;: &quot;http:\/\/rta.jjpc.net\/page\/like\/50ed6ff0b90fb8e80c000003&quot;,
                &quot;unlike&quot;: &quot;http:\/\/rta.jjpc.net\/page\/unlike\/50ed6ff0b90fb8e80c000003&quot;,
                &quot;subscribe&quot;: &quot;http:\/\/rta.jjpc.net\/page\/subscribe\/50ed6ff0b90fb8e80c000003&quot;,
                &quot;unsubscribe&quot;: &quot;http:\/\/rta.jjpc.net\/page\/unsubscribe\/50ed6ff0b90fb8e80c000003&quot;
            }
        },
        {
            &quot;_id&quot;: &quot;50ed6fcab90fb8e80c000002&quot;,
            &quot;attachments&quot;: [

            ],
            &quot;content&quot;: null,
            &quot;counts&quot;: {
                &quot;attachments&quot;: 0,
                &quot;checkins&quot;: 0,
                &quot;events&quot;: 0,
                &quot;likers&quot;: 0,
                &quot;listings&quot;: 0,
                &quot;posts&quot;: 0,
                &quot;subscribers&quot;: 0,
                &quot;members&quot;: 0,
                &quot;testimonials&quot;: 0
            },
            &quot;created&quot;: 1357737930,
            &quot;keywords&quot;: [
                &quot;tembeling road&quot;,
                &quot;studios@tembeling&quot;,
                &quot;katong&quot;
            ],
            &quot;likers&quot;: [

            ],
            &quot;modified&quot;: 1358238569,
            &quot;name&quot;: &quot;Studios@Tembeling&quot;,
            &quot;parent&quot;: {
                &quot;_id&quot;: &quot;50ed251ab90fb8c81700003b&quot;,
                &quot;name&quot;: &quot;Katong&quot;,
                &quot;uri&quot;: &quot;\/location\/estate\/katong&quot;,
                &quot;type&quot;: &quot;location&quot;
            },
            &quot;privacy&quot;: {
                &quot;is&quot;: &quot;PUBLIC&quot;
            },
            &quot;sector&quot;: [

            ],
            &quot;slug&quot;: &quot;studiostembeling&quot;,
            &quot;stats&quot;: [

            ],
            &quot;status&quot;: &quot;active&quot;,
            &quot;subscribers&quot;: [

            ],
            &quot;subtype&quot;: &quot;Private Apartments&quot;,
            &quot;type&quot;: &quot;location&quot;,
            &quot;uri&quot;: &quot;\/location\/private-apartments\/studiostembeling&quot;,
            &quot;user&quot;: {
                &quot;_id&quot;: &quot;50b7620ab90fb8e884000009&quot;,
                &quot;name&quot;: &quot;Jason Joe&quot;
            },
            &quot;is_owner&quot;: false,
            &quot;is_page_owner&quot;: false,
            &quot;connections&quot;: {
                &quot;likers&quot;: &quot;http:\/\/rta.jjpc.net\/page\/likers\/50ed6fcab90fb8e80c000002&quot;,
                &quot;feed&quot;: &quot;http:\/\/rta.jjpc.net\/page\/feed\/50ed6fcab90fb8e80c000002&quot;,
                &quot;subscribers&quot;: &quot;http:\/\/rta.jjpc.net\/page\/subscribers\/50ed6fcab90fb8e80c000002&quot;,
                &quot;testimonials&quot;: &quot;http:\/\/rta.jjpc.net\/page\/testimonials\/50ed6fcab90fb8e80c000002&quot;,
                &quot;pictures&quot;: &quot;http:\/\/rta.jjpc.net\/page\/pictures\/50ed6fcab90fb8e80c000002&quot;
            },
            &quot;interactions&quot;: {
                &quot;like&quot;: false,
                &quot;subscribe&quot;: false
            },
            &quot;actions&quot;: {
                &quot;like&quot;: &quot;http:\/\/rta.jjpc.net\/page\/like\/50ed6fcab90fb8e80c000002&quot;,
                &quot;unlike&quot;: &quot;http:\/\/rta.jjpc.net\/page\/unlike\/50ed6fcab90fb8e80c000002&quot;,
                &quot;subscribe&quot;: &quot;http:\/\/rta.jjpc.net\/page\/subscribe\/50ed6fcab90fb8e80c000002&quot;,
                &quot;unsubscribe&quot;: &quot;http:\/\/rta.jjpc.net\/page\/unsubscribe\/50ed6fcab90fb8e80c000002&quot;
            }
        },
        {
            &quot;_id&quot;: &quot;50ed6dd2b90fb8f818000005&quot;,
            &quot;attachments&quot;: [

            ],
            &quot;content&quot;: null,
            &quot;counts&quot;: {
                &quot;attachments&quot;: 0,
                &quot;checkins&quot;: 0,
                &quot;events&quot;: 0,
                &quot;likers&quot;: 0,
                &quot;listings&quot;: 0,
                &quot;posts&quot;: 0,
                &quot;subscribers&quot;: 0,
                &quot;members&quot;: 0,
                &quot;testimonials&quot;: 0
            },
            &quot;created&quot;: 1357737426,
            &quot;keywords&quot;: [
                &quot;hillview terrace&quot;,
                &quot;natura@hillview&quot;,
                &quot;bukit panjang&quot;
            ],
            &quot;likers&quot;: [

            ],
            &quot;modified&quot;: 1358238569,
            &quot;name&quot;: &quot;Natura@Hillview&quot;,
            &quot;parent&quot;: {
                &quot;_id&quot;: &quot;50ed2719b90fb8c81700004c&quot;,
                &quot;name&quot;: &quot;Bukit Panjang&quot;,
                &quot;uri&quot;: &quot;\/location\/estate\/bukit-panjang&quot;,
                &quot;type&quot;: &quot;location&quot;
            },
            &quot;privacy&quot;: {
                &quot;is&quot;: &quot;PUBLIC&quot;
            },
            &quot;sector&quot;: [

            ],
            &quot;slug&quot;: &quot;naturahillview&quot;,
            &quot;stats&quot;: [

            ],
            &quot;status&quot;: &quot;active&quot;,
            &quot;subscribers&quot;: [

            ],
            &quot;subtype&quot;: &quot;Private Apartments&quot;,
            &quot;type&quot;: &quot;location&quot;,
            &quot;uri&quot;: &quot;\/location\/private-apartments\/naturahillview&quot;,
            &quot;user&quot;: {
                &quot;_id&quot;: &quot;50b7620ab90fb8e884000009&quot;,
                &quot;name&quot;: &quot;Jason Joe&quot;
            },
            &quot;is_owner&quot;: false,
            &quot;is_page_owner&quot;: false,
            &quot;connections&quot;: {
                &quot;likers&quot;: &quot;http:\/\/rta.jjpc.net\/page\/likers\/50ed6dd2b90fb8f818000005&quot;,
                &quot;feed&quot;: &quot;http:\/\/rta.jjpc.net\/page\/feed\/50ed6dd2b90fb8f818000005&quot;,
                &quot;subscribers&quot;: &quot;http:\/\/rta.jjpc.net\/page\/subscribers\/50ed6dd2b90fb8f818000005&quot;,
                &quot;testimonials&quot;: &quot;http:\/\/rta.jjpc.net\/page\/testimonials\/50ed6dd2b90fb8f818000005&quot;,
                &quot;pictures&quot;: &quot;http:\/\/rta.jjpc.net\/page\/pictures\/50ed6dd2b90fb8f818000005&quot;
            },
            &quot;interactions&quot;: {
                &quot;like&quot;: false,
                &quot;subscribe&quot;: false
            },
            &quot;actions&quot;: {
                &quot;like&quot;: &quot;http:\/\/rta.jjpc.net\/page\/like\/50ed6dd2b90fb8f818000005&quot;,
                &quot;unlike&quot;: &quot;http:\/\/rta.jjpc.net\/page\/unlike\/50ed6dd2b90fb8f818000005&quot;,
                &quot;subscribe&quot;: &quot;http:\/\/rta.jjpc.net\/page\/subscribe\/50ed6dd2b90fb8f818000005&quot;,
                &quot;unsubscribe&quot;: &quot;http:\/\/rta.jjpc.net\/page\/unsubscribe\/50ed6dd2b90fb8f818000005&quot;
            }
        },
        {
            &quot;_id&quot;: &quot;50ed6ce8b90fb8f818000002&quot;,
            &quot;attachments&quot;: [

            ],
            &quot;content&quot;: null,
            &quot;counts&quot;: {
                &quot;attachments&quot;: 0,
                &quot;checkins&quot;: 0,
                &quot;events&quot;: 0,
                &quot;likers&quot;: 0,
                &quot;listings&quot;: 0,
                &quot;posts&quot;: 0,
                &quot;subscribers&quot;: 0,
                &quot;members&quot;: 0,
                &quot;testimonials&quot;: 0
            },
            &quot;created&quot;: 1357737192,
            &quot;keywords&quot;: [
                &quot;lew lian vale&quot;,
                &quot;jade towers&quot;,
                &quot;hougang (paya lebar)&quot;
            ],
            &quot;likers&quot;: [

            ],
            &quot;modified&quot;: 1358238569,
            &quot;name&quot;: &quot;Jade Towers&quot;,
            &quot;parent&quot;: {
                &quot;_id&quot;: &quot;50ed25c4b90fb8c817000041&quot;,
                &quot;name&quot;: &quot;Hougang (Paya Lebar)&quot;,
                &quot;uri&quot;: &quot;\/location\/estate\/hougang-paya-lebar&quot;,
                &quot;type&quot;: &quot;location&quot;
            },
            &quot;privacy&quot;: {
                &quot;is&quot;: &quot;PUBLIC&quot;
            },
            &quot;sector&quot;: [

            ],
            &quot;slug&quot;: &quot;jade-towers&quot;,
            &quot;stats&quot;: [

            ],
            &quot;status&quot;: &quot;active&quot;,
            &quot;subscribers&quot;: [

            ],
            &quot;subtype&quot;: &quot;Private Apartments&quot;,
            &quot;type&quot;: &quot;location&quot;,
            &quot;uri&quot;: &quot;\/location\/private-apartments\/jade-towers&quot;,
            &quot;user&quot;: {
                &quot;_id&quot;: &quot;50b7620ab90fb8e884000009&quot;,
                &quot;name&quot;: &quot;Jason Joe&quot;
            },
            &quot;is_owner&quot;: false,
            &quot;is_page_owner&quot;: false,
            &quot;connections&quot;: {
                &quot;likers&quot;: &quot;http:\/\/rta.jjpc.net\/page\/likers\/50ed6ce8b90fb8f818000002&quot;,
                &quot;feed&quot;: &quot;http:\/\/rta.jjpc.net\/page\/feed\/50ed6ce8b90fb8f818000002&quot;,
                &quot;subscribers&quot;: &quot;http:\/\/rta.jjpc.net\/page\/subscribers\/50ed6ce8b90fb8f818000002&quot;,
                &quot;testimonials&quot;: &quot;http:\/\/rta.jjpc.net\/page\/testimonials\/50ed6ce8b90fb8f818000002&quot;,
                &quot;pictures&quot;: &quot;http:\/\/rta.jjpc.net\/page\/pictures\/50ed6ce8b90fb8f818000002&quot;
            },
            &quot;interactions&quot;: {
                &quot;like&quot;: false,
                &quot;subscribe&quot;: false
            },
            &quot;actions&quot;: {
                &quot;like&quot;: &quot;http:\/\/rta.jjpc.net\/page\/like\/50ed6ce8b90fb8f818000002&quot;,
                &quot;unlike&quot;: &quot;http:\/\/rta.jjpc.net\/page\/unlike\/50ed6ce8b90fb8f818000002&quot;,
                &quot;subscribe&quot;: &quot;http:\/\/rta.jjpc.net\/page\/subscribe\/50ed6ce8b90fb8f818000002&quot;,
                &quot;unsubscribe&quot;: &quot;http:\/\/rta.jjpc.net\/page\/unsubscribe\/50ed6ce8b90fb8f818000002&quot;
            }
        },
        {
            &quot;_id&quot;: &quot;50ed6ca3b90fb8f818000001&quot;,
            &quot;attachments&quot;: [

            ],
            &quot;content&quot;: null,
            &quot;counts&quot;: {
                &quot;attachments&quot;: 0,
                &quot;checkins&quot;: 0,
                &quot;events&quot;: 0,
                &quot;likers&quot;: 0,
                &quot;listings&quot;: 0,
                &quot;posts&quot;: 0,
                &quot;subscribers&quot;: 0,
                &quot;members&quot;: 0,
                &quot;testimonials&quot;: 0
            },
            &quot;created&quot;: 1357737123,
            &quot;keywords&quot;: [
                &quot;leonie hill&quot;,
                &quot;horizon towers west&quot;,
                &quot;orchard&quot;
            ],
            &quot;likers&quot;: [

            ],
            &quot;modified&quot;: 1358238569,
            &quot;name&quot;: &quot;Horizon Towers West&quot;,
            &quot;parent&quot;: {
                &quot;_id&quot;: &quot;50ed2361b90fb8c81700002f&quot;,
                &quot;name&quot;: &quot;Orchard&quot;,
                &quot;uri&quot;: &quot;\/location\/estate\/orchard&quot;,
                &quot;type&quot;: &quot;location&quot;
            },
            &quot;privacy&quot;: {
                &quot;is&quot;: &quot;PUBLIC&quot;
            },
            &quot;sector&quot;: [

            ],
            &quot;slug&quot;: &quot;horizon-towers-west&quot;,
            &quot;stats&quot;: [

            ],
            &quot;status&quot;: &quot;active&quot;,
            &quot;subscribers&quot;: [

            ],
            &quot;subtype&quot;: &quot;Private Apartments&quot;,
            &quot;type&quot;: &quot;location&quot;,
            &quot;uri&quot;: &quot;\/location\/private-apartments\/horizon-towers-west&quot;,
            &quot;user&quot;: {
                &quot;_id&quot;: &quot;50b7620ab90fb8e884000009&quot;,
                &quot;name&quot;: &quot;Jason Joe&quot;
            },
            &quot;is_owner&quot;: false,
            &quot;is_page_owner&quot;: false,
            &quot;connections&quot;: {
                &quot;likers&quot;: &quot;http:\/\/rta.jjpc.net\/page\/likers\/50ed6ca3b90fb8f818000001&quot;,
                &quot;feed&quot;: &quot;http:\/\/rta.jjpc.net\/page\/feed\/50ed6ca3b90fb8f818000001&quot;,
                &quot;subscribers&quot;: &quot;http:\/\/rta.jjpc.net\/page\/subscribers\/50ed6ca3b90fb8f818000001&quot;,
                &quot;testimonials&quot;: &quot;http:\/\/rta.jjpc.net\/page\/testimonials\/50ed6ca3b90fb8f818000001&quot;,
                &quot;pictures&quot;: &quot;http:\/\/rta.jjpc.net\/page\/pictures\/50ed6ca3b90fb8f818000001&quot;
            },
            &quot;interactions&quot;: {
                &quot;like&quot;: false,
                &quot;subscribe&quot;: false
            },
            &quot;actions&quot;: {
                &quot;like&quot;: &quot;http:\/\/rta.jjpc.net\/page\/like\/50ed6ca3b90fb8f818000001&quot;,
                &quot;unlike&quot;: &quot;http:\/\/rta.jjpc.net\/page\/unlike\/50ed6ca3b90fb8f818000001&quot;,
                &quot;subscribe&quot;: &quot;http:\/\/rta.jjpc.net\/page\/subscribe\/50ed6ca3b90fb8f818000001&quot;,
                &quot;unsubscribe&quot;: &quot;http:\/\/rta.jjpc.net\/page\/unsubscribe\/50ed6ca3b90fb8f818000001&quot;
            }
        },
        {
            &quot;_id&quot;: &quot;50ed6c96b90fb8f818000000&quot;,
            &quot;attachments&quot;: [

            ],
            &quot;content&quot;: null,
            &quot;counts&quot;: {
                &quot;attachments&quot;: 0,
                &quot;checkins&quot;: 0,
                &quot;events&quot;: 0,
                &quot;likers&quot;: 0,
                &quot;listings&quot;: 0,
                &quot;posts&quot;: 0,
                &quot;subscribers&quot;: 0,
                &quot;members&quot;: 0,
                &quot;testimonials&quot;: 0
            },
            &quot;created&quot;: 1357737110,
            &quot;keywords&quot;: [
                &quot;leonie hill road&quot;,
                &quot;horizon towers east&quot;,
                &quot;orchard&quot;
            ],
            &quot;likers&quot;: [

            ],
            &quot;modified&quot;: 1358238569,
            &quot;name&quot;: &quot;Horizon Towers East&quot;,
            &quot;parent&quot;: {
                &quot;_id&quot;: &quot;50ed2361b90fb8c81700002f&quot;,
                &quot;name&quot;: &quot;Orchard&quot;,
                &quot;uri&quot;: &quot;\/location\/estate\/orchard&quot;,
                &quot;type&quot;: &quot;location&quot;
            },
            &quot;privacy&quot;: {
                &quot;is&quot;: &quot;PUBLIC&quot;
            },
            &quot;sector&quot;: [

            ],
            &quot;slug&quot;: &quot;horizon-towers-east&quot;,
            &quot;stats&quot;: [

            ],
            &quot;status&quot;: &quot;active&quot;,
            &quot;subscribers&quot;: [

            ],
            &quot;subtype&quot;: &quot;Private Apartments&quot;,
            &quot;type&quot;: &quot;location&quot;,
            &quot;uri&quot;: &quot;\/location\/private-apartments\/horizon-towers-east&quot;,
            &quot;user&quot;: {
                &quot;_id&quot;: &quot;50b7620ab90fb8e884000009&quot;,
                &quot;name&quot;: &quot;Jason Joe&quot;
            },
            &quot;is_owner&quot;: false,
            &quot;is_page_owner&quot;: false,
            &quot;connections&quot;: {
                &quot;likers&quot;: &quot;http:\/\/rta.jjpc.net\/page\/likers\/50ed6c96b90fb8f818000000&quot;,
                &quot;feed&quot;: &quot;http:\/\/rta.jjpc.net\/page\/feed\/50ed6c96b90fb8f818000000&quot;,
                &quot;subscribers&quot;: &quot;http:\/\/rta.jjpc.net\/page\/subscribers\/50ed6c96b90fb8f818000000&quot;,
                &quot;testimonials&quot;: &quot;http:\/\/rta.jjpc.net\/page\/testimonials\/50ed6c96b90fb8f818000000&quot;,
                &quot;pictures&quot;: &quot;http:\/\/rta.jjpc.net\/page\/pictures\/50ed6c96b90fb8f818000000&quot;
            },
            &quot;interactions&quot;: {
                &quot;like&quot;: false,
                &quot;subscribe&quot;: false
            },
            &quot;actions&quot;: {
                &quot;like&quot;: &quot;http:\/\/rta.jjpc.net\/page\/like\/50ed6c96b90fb8f818000000&quot;,
                &quot;unlike&quot;: &quot;http:\/\/rta.jjpc.net\/page\/unlike\/50ed6c96b90fb8f818000000&quot;,
                &quot;subscribe&quot;: &quot;http:\/\/rta.jjpc.net\/page\/subscribe\/50ed6c96b90fb8f818000000&quot;,
                &quot;unsubscribe&quot;: &quot;http:\/\/rta.jjpc.net\/page\/unsubscribe\/50ed6c96b90fb8f818000000&quot;
            }
        },
        {
            &quot;_id&quot;: &quot;50ec0a5ab90fb8c817000006&quot;,
            &quot;attachments&quot;: [

            ],
            &quot;content&quot;: null,
            &quot;counts&quot;: {
                &quot;attachments&quot;: 0,
                &quot;checkins&quot;: 0,
                &quot;events&quot;: 0,
                &quot;likers&quot;: 0,
                &quot;listings&quot;: 0,
                &quot;posts&quot;: 0,
                &quot;subscribers&quot;: 0,
                &quot;members&quot;: 0,
                &quot;testimonials&quot;: 0
            },
            &quot;keywords&quot;: [
                &quot;cairnhill rise&quot;,
                &quot;the cairnhill&quot;,
                &quot;condominium&quot;,
                &quot;orchard&quot;
            ],
            &quot;likers&quot;: [

            ],
            &quot;modified&quot;: 1358238568,
            &quot;name&quot;: &quot;The Cairnhill&quot;,
            &quot;parent&quot;: {
                &quot;_id&quot;: &quot;50ed2361b90fb8c81700002f&quot;,
                &quot;name&quot;: &quot;Orchard&quot;,
                &quot;uri&quot;: &quot;\/location\/estate\/orchard&quot;,
                &quot;type&quot;: &quot;location&quot;
            },
            &quot;privacy&quot;: {
                &quot;is&quot;: &quot;PUBLIC&quot;
            },
            &quot;property_type&quot;: &quot;Condominium&quot;,
            &quot;sector&quot;: &quot;22&quot;,
            &quot;sectors&quot;: [

            ],
            &quot;slug&quot;: &quot;the-cairnhill&quot;,
            &quot;stats&quot;: [

            ],
            &quot;status&quot;: &quot;active&quot;,
            &quot;subscribers&quot;: [

            ],
            &quot;subtype&quot;: &quot;Private Apartments&quot;,
            &quot;type&quot;: &quot;location&quot;,
            &quot;uri&quot;: &quot;\/location\/private-apartments\/the-cairnhill&quot;,
            &quot;user&quot;: {
                &quot;_id&quot;: &quot;50b7620ab90fb8e884000009&quot;,
                &quot;name&quot;: &quot;Jason Joe&quot;
            },
            &quot;created&quot;: 1357646426,
            &quot;is_owner&quot;: false,
            &quot;is_page_owner&quot;: false,
            &quot;connections&quot;: {
                &quot;likers&quot;: &quot;http:\/\/rta.jjpc.net\/page\/likers\/50ec0a5ab90fb8c817000006&quot;,
                &quot;feed&quot;: &quot;http:\/\/rta.jjpc.net\/page\/feed\/50ec0a5ab90fb8c817000006&quot;,
                &quot;subscribers&quot;: &quot;http:\/\/rta.jjpc.net\/page\/subscribers\/50ec0a5ab90fb8c817000006&quot;,
                &quot;testimonials&quot;: &quot;http:\/\/rta.jjpc.net\/page\/testimonials\/50ec0a5ab90fb8c817000006&quot;,
                &quot;pictures&quot;: &quot;http:\/\/rta.jjpc.net\/page\/pictures\/50ec0a5ab90fb8c817000006&quot;
            },
            &quot;interactions&quot;: {
                &quot;like&quot;: false,
                &quot;subscribe&quot;: false
            },
            &quot;actions&quot;: {
                &quot;like&quot;: &quot;http:\/\/rta.jjpc.net\/page\/like\/50ec0a5ab90fb8c817000006&quot;,
                &quot;unlike&quot;: &quot;http:\/\/rta.jjpc.net\/page\/unlike\/50ec0a5ab90fb8c817000006&quot;,
                &quot;subscribe&quot;: &quot;http:\/\/rta.jjpc.net\/page\/subscribe\/50ec0a5ab90fb8c817000006&quot;,
                &quot;unsubscribe&quot;: &quot;http:\/\/rta.jjpc.net\/page\/unsubscribe\/50ec0a5ab90fb8c817000006&quot;
            }
        },
        {
            &quot;_id&quot;: &quot;50ec09c3b90fb8c817000003&quot;,
            &quot;attachments&quot;: [

            ],
            &quot;content&quot;: null,
            &quot;counts&quot;: {
                &quot;attachments&quot;: 0,
                &quot;checkins&quot;: 0,
                &quot;events&quot;: 0,
                &quot;likers&quot;: 0,
                &quot;listings&quot;: 0,
                &quot;posts&quot;: 0,
                &quot;subscribers&quot;: 0,
                &quot;members&quot;: 0,
                &quot;testimonials&quot;: 0
            },
            &quot;keywords&quot;: [
                &quot;whitley road&quot;,
                &quot;whitley villas&quot;,
                &quot;thomson (newton)&quot;
            ],
            &quot;likers&quot;: [

            ],
            &quot;modified&quot;: 1358238568,
            &quot;name&quot;: &quot;Whitley Villas&quot;,
            &quot;parent&quot;: {
                &quot;_id&quot;: &quot;50ed2423b90fb8c817000033&quot;,
                &quot;name&quot;: &quot;Thomson (Newton)&quot;,
                &quot;uri&quot;: &quot;\/location\/estate\/thomson-newton&quot;,
                &quot;type&quot;: &quot;location&quot;
            },
            &quot;privacy&quot;: {
                &quot;is&quot;: &quot;PUBLIC&quot;
            },
            &quot;sector&quot;: &quot;29&quot;,
            &quot;sectors&quot;: [

            ],
            &quot;slug&quot;: &quot;whitley-villas&quot;,
            &quot;stats&quot;: [

            ],
            &quot;status&quot;: &quot;active&quot;,
            &quot;subscribers&quot;: [

            ],
            &quot;subtype&quot;: &quot;Private Apartments&quot;,
            &quot;type&quot;: &quot;location&quot;,
            &quot;uri&quot;: &quot;\/location\/private-apartments\/whitley-villas&quot;,
            &quot;user&quot;: {
                &quot;_id&quot;: &quot;50b7620ab90fb8e884000009&quot;,
                &quot;name&quot;: &quot;Jason Joe&quot;
            },
            &quot;created&quot;: 1357646275,
            &quot;is_owner&quot;: false,
            &quot;is_page_owner&quot;: false,
            &quot;connections&quot;: {
                &quot;likers&quot;: &quot;http:\/\/rta.jjpc.net\/page\/likers\/50ec09c3b90fb8c817000003&quot;,
                &quot;feed&quot;: &quot;http:\/\/rta.jjpc.net\/page\/feed\/50ec09c3b90fb8c817000003&quot;,
                &quot;subscribers&quot;: &quot;http:\/\/rta.jjpc.net\/page\/subscribers\/50ec09c3b90fb8c817000003&quot;,
                &quot;testimonials&quot;: &quot;http:\/\/rta.jjpc.net\/page\/testimonials\/50ec09c3b90fb8c817000003&quot;,
                &quot;pictures&quot;: &quot;http:\/\/rta.jjpc.net\/page\/pictures\/50ec09c3b90fb8c817000003&quot;
            },
            &quot;interactions&quot;: {
                &quot;like&quot;: false,
                &quot;subscribe&quot;: false
            },
            &quot;actions&quot;: {
                &quot;like&quot;: &quot;http:\/\/rta.jjpc.net\/page\/like\/50ec09c3b90fb8c817000003&quot;,
                &quot;unlike&quot;: &quot;http:\/\/rta.jjpc.net\/page\/unlike\/50ec09c3b90fb8c817000003&quot;,
                &quot;subscribe&quot;: &quot;http:\/\/rta.jjpc.net\/page\/subscribe\/50ec09c3b90fb8c817000003&quot;,
                &quot;unsubscribe&quot;: &quot;http:\/\/rta.jjpc.net\/page\/unsubscribe\/50ec09c3b90fb8c817000003&quot;
            }
        },
        {
            &quot;_id&quot;: &quot;50ec07fab90fb82017000003&quot;,
            &quot;attachments&quot;: [

            ],
            &quot;content&quot;: null,
            &quot;counts&quot;: {
                &quot;attachments&quot;: 0,
                &quot;checkins&quot;: 0,
                &quot;events&quot;: 0,
                &quot;likers&quot;: 0,
                &quot;listings&quot;: 0,
                &quot;posts&quot;: 0,
                &quot;subscribers&quot;: 0,
                &quot;members&quot;: 0,
                &quot;testimonials&quot;: 0
            },
            &quot;keywords&quot;: [
                &quot;bencoolen street&quot;,
                &quot;bencoolen house&quot;,
                &quot;bugis&quot;
            ],
            &quot;likers&quot;: [

            ],
            &quot;modified&quot;: 1358238567,
            &quot;name&quot;: &quot;Bencoolen House&quot;,
            &quot;parent&quot;: {
                &quot;_id&quot;: &quot;50ed22efb90fb8c81700002b&quot;,
                &quot;name&quot;: &quot;Bugis&quot;,
                &quot;uri&quot;: &quot;\/location\/estate\/bugis&quot;,
                &quot;type&quot;: &quot;location&quot;
            },
            &quot;privacy&quot;: {
                &quot;is&quot;: &quot;PUBLIC&quot;
            },
            &quot;sector&quot;: &quot;18&quot;,
            &quot;sectors&quot;: [

            ],
            &quot;slug&quot;: &quot;bencoolen-house&quot;,
            &quot;stats&quot;: [

            ],
            &quot;status&quot;: &quot;active&quot;,
            &quot;subscribers&quot;: [

            ],
            &quot;subtype&quot;: &quot;Private Apartments&quot;,
            &quot;type&quot;: &quot;location&quot;,
            &quot;uri&quot;: &quot;\/location\/private-apartments\/bencoolen-house&quot;,
            &quot;user&quot;: {
                &quot;_id&quot;: &quot;50b7620ab90fb8e884000009&quot;,
                &quot;name&quot;: &quot;Jason Joe&quot;
            },
            &quot;created&quot;: 1357645818,
            &quot;is_owner&quot;: false,
            &quot;is_page_owner&quot;: false,
            &quot;connections&quot;: {
                &quot;likers&quot;: &quot;http:\/\/rta.jjpc.net\/page\/likers\/50ec07fab90fb82017000003&quot;,
                &quot;feed&quot;: &quot;http:\/\/rta.jjpc.net\/page\/feed\/50ec07fab90fb82017000003&quot;,
                &quot;subscribers&quot;: &quot;http:\/\/rta.jjpc.net\/page\/subscribers\/50ec07fab90fb82017000003&quot;,
                &quot;testimonials&quot;: &quot;http:\/\/rta.jjpc.net\/page\/testimonials\/50ec07fab90fb82017000003&quot;,
                &quot;pictures&quot;: &quot;http:\/\/rta.jjpc.net\/page\/pictures\/50ec07fab90fb82017000003&quot;
            },
            &quot;interactions&quot;: {
                &quot;like&quot;: false,
                &quot;subscribe&quot;: false
            },
            &quot;actions&quot;: {
                &quot;like&quot;: &quot;http:\/\/rta.jjpc.net\/page\/like\/50ec07fab90fb82017000003&quot;,
                &quot;unlike&quot;: &quot;http:\/\/rta.jjpc.net\/page\/unlike\/50ec07fab90fb82017000003&quot;,
                &quot;subscribe&quot;: &quot;http:\/\/rta.jjpc.net\/page\/subscribe\/50ec07fab90fb82017000003&quot;,
                &quot;unsubscribe&quot;: &quot;http:\/\/rta.jjpc.net\/page\/unsubscribe\/50ec07fab90fb82017000003&quot;
            }
        },
        {
            &quot;_id&quot;: &quot;50e9473fb90fb8c81400028e&quot;,
            &quot;attachments&quot;: [

            ],
            &quot;content&quot;: null,
            &quot;counts&quot;: {
                &quot;attachments&quot;: 0,
                &quot;checkins&quot;: 0,
                &quot;events&quot;: 0,
                &quot;likers&quot;: 0,
                &quot;listings&quot;: 0,
                &quot;posts&quot;: 0,
                &quot;subscribers&quot;: 0,
                &quot;members&quot;: 0,
                &quot;testimonials&quot;: 0
            },
            &quot;keywords&quot;: [
                &quot;hamilton road&quot;,
                &quot;emerald hill road&quot;,
                &quot;killiney road&quot;,
                &quot;lengkok merak&quot;,
                &quot;jalan novena&quot;,
                &quot;barker road&quot;,
                &quot;telok kurau road&quot;,
                &quot;lorong stangee&quot;,
                &quot;lorong l telok kurau&quot;,
                &quot;jalan limau bali&quot;,
                &quot;residence 66&quot;,
                &quot;sengkang&quot;
            ],
            &quot;likers&quot;: [

            ],
            &quot;modified&quot;: 1358238567,
            &quot;name&quot;: &quot;Residence 66&quot;,
            &quot;parent&quot;: {
                &quot;_id&quot;: &quot;50ed25d7b90fb8c817000042&quot;,
                &quot;name&quot;: &quot;Sengkang&quot;,
                &quot;uri&quot;: &quot;\/location\/estate\/sengkang&quot;,
                &quot;type&quot;: &quot;location&quot;
            },
            &quot;privacy&quot;: {
                &quot;is&quot;: &quot;PUBLIC&quot;
            },
            &quot;sector&quot;: &quot;54&quot;,
            &quot;slug&quot;: &quot;residence-66&quot;,
            &quot;stats&quot;: [

            ],
            &quot;status&quot;: &quot;active&quot;,
            &quot;subscribers&quot;: [

            ],
            &quot;subtype&quot;: &quot;Private Apartments&quot;,
            &quot;type&quot;: &quot;location&quot;,
            &quot;uri&quot;: &quot;\/location\/private-apartments\/residence-66&quot;,
            &quot;user&quot;: {
                &quot;_id&quot;: &quot;50b7620ab90fb8e884000009&quot;,
                &quot;name&quot;: &quot;Jason Nathan&quot;
            },
            &quot;created&quot;: 1357465407,
            &quot;is_owner&quot;: false,
            &quot;is_page_owner&quot;: false,
            &quot;connections&quot;: {
                &quot;likers&quot;: &quot;http:\/\/rta.jjpc.net\/page\/likers\/50e9473fb90fb8c81400028e&quot;,
                &quot;feed&quot;: &quot;http:\/\/rta.jjpc.net\/page\/feed\/50e9473fb90fb8c81400028e&quot;,
                &quot;subscribers&quot;: &quot;http:\/\/rta.jjpc.net\/page\/subscribers\/50e9473fb90fb8c81400028e&quot;,
                &quot;testimonials&quot;: &quot;http:\/\/rta.jjpc.net\/page\/testimonials\/50e9473fb90fb8c81400028e&quot;,
                &quot;pictures&quot;: &quot;http:\/\/rta.jjpc.net\/page\/pictures\/50e9473fb90fb8c81400028e&quot;
            },
            &quot;interactions&quot;: {
                &quot;like&quot;: false,
                &quot;subscribe&quot;: false
            },
            &quot;actions&quot;: {
                &quot;like&quot;: &quot;http:\/\/rta.jjpc.net\/page\/like\/50e9473fb90fb8c81400028e&quot;,
                &quot;unlike&quot;: &quot;http:\/\/rta.jjpc.net\/page\/unlike\/50e9473fb90fb8c81400028e&quot;,
                &quot;subscribe&quot;: &quot;http:\/\/rta.jjpc.net\/page\/subscribe\/50e9473fb90fb8c81400028e&quot;,
                &quot;unsubscribe&quot;: &quot;http:\/\/rta.jjpc.net\/page\/unsubscribe\/50e9473fb90fb8c81400028e&quot;
            }
        },
        {
            &quot;_id&quot;: &quot;50e9473fb90fb8c81400028a&quot;,
            &quot;attachments&quot;: [

            ],
            &quot;content&quot;: null,
            &quot;counts&quot;: {
                &quot;attachments&quot;: 0,
                &quot;checkins&quot;: 0,
                &quot;events&quot;: 0,
                &quot;likers&quot;: 0,
                &quot;listings&quot;: 0,
                &quot;posts&quot;: 0,
                &quot;subscribers&quot;: 0,
                &quot;members&quot;: 0,
                &quot;testimonials&quot;: 0
            },
            &quot;keywords&quot;: [
                &quot;jalan bukit merah&quot;,
                &quot;mar thoma road&quot;,
                &quot;lorong 35 geylang&quot;,
                &quot;lorong marzuki&quot;,
                &quot;regal 35&quot;,
                &quot;kembangan (kaki bukit)&quot;
            ],
            &quot;likers&quot;: [

            ],
            &quot;modified&quot;: 1358238567,
            &quot;name&quot;: &quot;Regal 35&quot;,
            &quot;parent&quot;: {
                &quot;_id&quot;: &quot;50ed2509b90fb8c81700003a&quot;,
                &quot;name&quot;: &quot;Kembangan (Kaki Bukit)&quot;,
                &quot;uri&quot;: &quot;\/location\/estate\/kembangan-kaki-bukit&quot;,
                &quot;type&quot;: &quot;location&quot;
            },
            &quot;privacy&quot;: {
                &quot;is&quot;: &quot;PUBLIC&quot;
            },
            &quot;sector&quot;: &quot;41&quot;,
            &quot;slug&quot;: &quot;regal-35&quot;,
            &quot;stats&quot;: [

            ],
            &quot;status&quot;: &quot;active&quot;,
            &quot;subscribers&quot;: [

            ],
            &quot;subtype&quot;: &quot;Private Apartments&quot;,
            &quot;type&quot;: &quot;location&quot;,
            &quot;uri&quot;: &quot;\/location\/private-apartments\/regal-35&quot;,
            &quot;user&quot;: {
                &quot;_id&quot;: &quot;50b7620ab90fb8e884000009&quot;,
                &quot;name&quot;: &quot;Jason Nathan&quot;
            },
            &quot;created&quot;: 1357465407,
            &quot;is_owner&quot;: false,
            &quot;is_page_owner&quot;: false,
            &quot;connections&quot;: {
                &quot;likers&quot;: &quot;http:\/\/rta.jjpc.net\/page\/likers\/50e9473fb90fb8c81400028a&quot;,
                &quot;feed&quot;: &quot;http:\/\/rta.jjpc.net\/page\/feed\/50e9473fb90fb8c81400028a&quot;,
                &quot;subscribers&quot;: &quot;http:\/\/rta.jjpc.net\/page\/subscribers\/50e9473fb90fb8c81400028a&quot;,
                &quot;testimonials&quot;: &quot;http:\/\/rta.jjpc.net\/page\/testimonials\/50e9473fb90fb8c81400028a&quot;,
                &quot;pictures&quot;: &quot;http:\/\/rta.jjpc.net\/page\/pictures\/50e9473fb90fb8c81400028a&quot;
            },
            &quot;interactions&quot;: {
                &quot;like&quot;: false,
                &quot;subscribe&quot;: false
            },
            &quot;actions&quot;: {
                &quot;like&quot;: &quot;http:\/\/rta.jjpc.net\/page\/like\/50e9473fb90fb8c81400028a&quot;,
                &quot;unlike&quot;: &quot;http:\/\/rta.jjpc.net\/page\/unlike\/50e9473fb90fb8c81400028a&quot;,
                &quot;subscribe&quot;: &quot;http:\/\/rta.jjpc.net\/page\/subscribe\/50e9473fb90fb8c81400028a&quot;,
                &quot;unsubscribe&quot;: &quot;http:\/\/rta.jjpc.net\/page\/unsubscribe\/50e9473fb90fb8c81400028a&quot;
            }
        }
    ]
}): failed to open stream: Invalid argument ~ APPPATH/classes/controller.php [ 35 ]
--
#0 /home/jason/www/rta.casa.net/application/classes/controller.php(35): Kohana_Core::error_handler(2, 'include({?    &...', '/home/jason/www...', 35, Array)
#1 /home/jason/www/rta.casa.net/application/classes/controller.php(35): Controller::after()
#2 /home/jason/www/rta.casa.net/application/classes/controller/abstract.php(227): Controller->after()
#3 [internal function]: Controller_Abstract->after()
#4 /home/jason/www/common/kohana/system/classes/kohana/request/client/internal.php(119): ReflectionMethod->invoke(Object(Controller_Page))
#5 /home/jason/www/common/kohana/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/jason/www/common/kohana/system/classes/kohana/request.php(1154): Kohana_Request_Client->execute(Object(Request))
#7 /home/jason/www/rta.casa.net/index.php(114): Kohana_Request->execute()
#8 {main}