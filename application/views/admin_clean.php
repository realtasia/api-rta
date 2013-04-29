<!DOCTYPE html>
<html>
    <head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# realtasia: http://ogp.me/ns/fb/realtasia#">
        <?php if (isset($meta) && is_array($meta)): ?>
            <title><?php echo \Arr::get($meta, 'title') . ' - '; ?>Realtasia Singapore</title>
            <?php echo \Arr::get($meta, 'meta'); ?>
        <?php endif; ?>
        <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
        <meta name="viewport" content="initial-scale=1">
        <link rel="stylesheet" type="text/css" href="http://<?php echo RT::COM_SERVER ?>/css/final/css/jquery-ui-boostrap.css" />
        <link rel="stylesheet" type="text/css" href="/css/font-awesome.min.css" />
        <link href='http://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'>
        <script type="text/javascript" src="http://<?php echo RT::COM_SERVER ?>/js/jquery.min.js"></script>
        <script type="text/javascript" src="http://<?php echo RT::COM_SERVER ?>/js/knockout.js"></script>
        <script type="text/javascript" src="/js/growl.js"></script>
        <style type="text/css">
            *{font-family: 'Droid Sans', sans-serif important;}
        </style>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span6">
                    <h3>User Stuff</h3>
                    <hr style="margin-top: 0">
                    <form>
                        <input type="text" data-bind="value:userField" placeholder="Enter user id, username or email" class="span12"><br>
                    </form>
                    <div class="btn-toolbar">
                        <div class="btn-group">
                            <button class="btn" disabled="disabled" data-bind="click:resetUser"><i class="icon-retweet" data-bind="css:{'icon-spin': isResetting}"></i>&nbsp;Reset</button>
                            <button class="btn btn-danger" data-bind="click:deleteUser"><i class="icon-trash" data-bind="css:{'icon-spin': isDeleting}"></i>&nbsp;Delete</button>
                        </div>
                    </div>
                </div>
                <div class="span6"></div>
            </div>
        </div>
    </body>
    <script type="text/javascript">
        function Clean() {
            this.isResetting = ko.observable(!1);
            this.isDeleting = ko.observable(!1);
            this.userField = ko.observable(null);
            this.deleteUser = function() {
                var t = this;
                return t.userField() && !t.isDeleting() && $.ajax({
                    url: '/clean/deleteuser/',
                    type: 'post',
                    data: {search : t.userField()},
                    beforeSend: function() {
                        t.isDeleting(!0);
                    }
                })
                .done(function(r) {
            $.bootstrapGrowl("User deleted!");
        })
                .always(function() {
                    return t.isDeleting(!1) && t.userField(null);
                }) || false;
            };
            this.resetUser = function() {
                var t = this;
                return t.userField() && !t.isResetting() && $.ajax({
                    url: '/clean/resetuser/',
                    type: 'post',
                    data: {search : t.userField()},
                    beforeSend: function() {
                        t.isDeleting(!0);
                    }
                })
                .done(function(r) {
            $.bootstrapGrowl("User reset!");
        })
                .always(function() {
                    return t.isDeleting(!1) && t.userField(null);
                }) || false;
            };
        }
        jQuery(document).ready(function($){
            ko.applyBindings(new Clean());
        });
    </script>
</html>