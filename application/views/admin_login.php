<!DOCTYPE html>
<html>
    <head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# realtasia: http://ogp.me/ns/fb/realtasia#">
        <?php if (isset($meta) && is_array($meta)): ?>
            <title><?php echo \Arr::get($meta, 'title') . ' - '; ?>Realtasia Singapore</title>
            <?php echo \Arr::get($meta, 'meta'); ?>
        <?php endif; ?>
        <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
        <meta name="viewport" content="initial-scale=1">
        <link rel="stylesheet" type="text/css" href="/css/admin-boostrap.min.css" />
        <link rel="stylesheet" type="text/css" href="/css/bootstrap-responsive.min.css" />
        <link rel="stylesheet" type="text/css" href="/css/font-awesome.min.css" />
        <link href='http://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" type="text/css" href="/css/admin.css" />
        <style type="text/css">
            *{border-radius:3px !important;font-size:15pt !important;} input.text{height:40px;text-shadow:1px 1px 1px #fff;}h6{text-shadow:1px 1px 1px #ddd;}
        </style>

        <script type="text/javascript" src="//<?php echo RT::COM_SERVER ?>/js/jquery.min.js"></script>
        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>
        <script type="text/javascript" src="//<?php echo RT::COM_SERVER ?>/js/knockout.js"></script>
        <script type="text/javascript" src="//<?php echo RT::COM_SERVER ?>/js/knockout.mapping-latest.js"></script>
    </head>
    <body>
        <div class="container" style="max-width:350px;margin-top:10px">
            <div class="container-fluid">
                <div class="row-fluid">
                    <div class="span12">
                        <div style="border: 1px solid #eee; background:#fefefe;padding:20px;box-shadow:1px 1px 8px #000">
                            <img src="http://static2.realtasia.com.sg/realtlogo5.png"  style="margin-bottom:10px;">
                            <form class="form">
                                <h6 style="padding:5px">Username</h6>
                                <input type="text" class="text span12" name="username" id="username" placeholder="Email/Username">
                                <h6 style="padding:5px">Password</h6>
                                <input type="password" class="text span12" name="username" id="username">
                                <button class="btn btn-primary btn-large" style="margin-top:10px;"><i class="icon-signin"></i>&nbsp;Sign In</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>