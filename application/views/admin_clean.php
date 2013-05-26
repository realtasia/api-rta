<!DOCTYPE html>
<html>
    <head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# realtasia: http://ogp.me/ns/fb/realtasia#">
        <?php if (isset($meta) && is_array($meta)): ?>
            <title><?php echo \Arr::get($meta, 'title') . ' - '; ?>Realtasia Singapore</title>
            <?php echo \Arr::get($meta, 'meta'); ?>
        <?php endif; ?>
        <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
        <meta name="viewport" content="initial-scale=1">
        <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="/css/admin-boostrap.min.css" />
        <link rel="stylesheet" type="text/css" href="/css/font-awesome.min.css" />
        <link href='http://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'>
        <script type="text/javascript" src="//<?php echo RT::COM_SERVER ?>/js/jquery.min.js"></script>
        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>
        <script type="text/javascript" src="//<?php echo RT::COM_SERVER ?>/js/knockout.js"></script>
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/js-signals/0.8.1/js-signals.min.js"></script>
        <script type="text/javascript" src="/js/hasher.js"></script>
        <script type="text/javascript" src="/js/growl.js"></script>
        <style type="text/css">
            *{font-family: 'Droid Sans', sans-serif important; border-radius: 1px !important;}body{background:#ddd}.white{background:#fff;border:1px solid #eee;}.content-area{padding:10px;box-shadow:1px 1px 1px #ddd inset;border-radius:2px;margin-top: 10px;}
            #leftFixedBar{position:fixed;background-color:#fff;height:100%;top:0px;width:100px;left:0;bottom:0;border-right:1px solid #bbb;}
            #leftFixedBar > ul.nav > li > a{display:block;text-align: center}
            #leftFixedBar > ul.nav > li.active > a{margin-right:-1px}
            .inline > div {display:inline-block;vertical-align: middle; padding:2px;}
        </style>
    </head>
    <body>
        <div id="leftFixedBar">
            <ul class="nav nav-pills nav-stacked">
                <li data-bind="css:{'active': isUser}"><a href="#clean"><i class="icon-user icon-4x"></i><div>Users</div></a></li>
                <li data-bind="css:{'active': isJob}"><a href="#jobs"><i class="icon-tasks icon-4x"></i><div>Jobs</div></a></li>
                <li data-bind="css:{'active': isLog}"><a href="#logs"><i class="icon-file-alt icon-4x"></i><div>Logs</div></a></li>
            </ul>
        </div>
        <div class="container-fluid clearfix" style="border-left:1px solid #eee;margin-left:101px">
            <div class="row-fluid clearfix" style="margin-top:10px;">
                <div class="span12" style="margin-top:10px">
                    <div class="row-fluid" id="clean" data-bind="visible: app.isUser">
                        <div class="span12">
                            <div class="white content-area">
                                <h3>User</h3>
                                <form>
                                    <input type="text" data-bind="value:userField" placeholder="Enter user id, username or email" class="span12"><br>
                                    <div class="btn-toolbar">
                                        <div class="btn-group">
                                            <button class="btn btn-small" disabled="disabled" data-bind="click:resetUser"><i class="icon-retweet" data-bind="css:{'icon-spin': isResetting}"></i>&nbsp;<span class="hidden-phone hidden-tablet">Reset</span></button>
                                            <button class="btn btn-small btn-danger" data-bind="click:deleteUser"><i class="icon-trash" data-bind="css:{'icon-spin': isDeleting}"></i>&nbsp;<span class="hidden-phone hidden-tablet">Delete</span></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid" id="jobs" data-bind="visible: app.isJob">
                        <div class="span12">
                            <div class="white content-area">
                                <h3>Jobs</h3>
                                <div class="btn-toolbar">
                                    <div class="btn-group">
                                        <a href="#" class="btn btn-info" href="/clean/resetjobs" data-bind="click:reQueueJobs"><i class="icon-reply-all"></i>&nbsp;<span class="hidden-phone hidden-tablet">Requeue Jobs</span></a>
                                        <a href="#" class="btn btn-inverse" href="/clean/index" data-bind="click:refreshJobs"><i class="icon-refresh"></i>&nbsp;<span class="hidden-phone hidden-tablet">Refresh Jobs</span></a>
                                    </div>
                                </div>
                                <div style="padding:10px; margin:0 auto;" id="job-tables">
                                    <?php echo $jobs ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid" id="logs" data-bind="visible: app.isLog">
                        <div class="span12">
                            <div class="white content-area">
                                <h3>Logs</h3>
                                <ul data-bind="foreach:dataRows" class="nav" style="margin-left:0">
                                    <li>
                                        <div class="row-fluid">
                                            <div class="span6">
                                                <p data-bind="text:$data._id"></p>
                                            </div>
                                            <div class="span6">
                                                <div class="btn-toolbar clearfix" style="float:right;margin-top:0">
                                                    <div class="btn-group clearfix">
                                                        <button class="btn btn-mini" data-bind="click:hide">
                                                            <i class="icon-eye-close" data-bind=""></i>&nbsp;<span class="hidden-phone hidden-tablet">Hide</span>
                                                        </button>
                                                        <button class="btn btn-mini btn-danger" data-bind="click:trash">
                                                            <i class="icon-trash" data-bind=""></i>&nbsp;<span class="hidden-phone hidden-tablet">Delete</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row-fluid" data-bind="attr:{id: $data._id}">
                                            <div class="span12">
                                                <pre data-bind="text:$data.body"></pre>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script type="text/javascript">

        function App(){
            var self = this, h = window.location.hash;
            self.hash = ko.observable(h);
            self.isUser = ko.computed(function(){return self.hash() === '#clean';});
            self.isJob = ko.computed(function(){return self.hash() === '#jobs';});
            self.isLog = ko.computed(function(){return self.hash() === '#logs';});
            window.onhashchange = function(){
                self.hash(location.hash);
                console.log(self.hash())
            }
        }
        function Clean(a) {
            this.app = a;
            this.isResetting = ko.observable(!1);
            this.isDeleting = ko.observable(!1);
            this.userField = ko.observable(null);
            this.deleteUser = function() {
                var t = this;
                return t.userField() && !t.isDeleting() && $.ajax({
                    url: '/clean/deleteuser/',
                    type: 'post',
                    data: {search: t.userField()},
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
                    data: {search: t.userField()},
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

        function Jobs(a) {
            var c = $('.collapse'), t = c.find('table'), self = this;
            this.app = a;
            this.toggleTable = function(d, e) {
                e.stopPropagation();
                $(e.currentTarget).find("table").slideToggle();
            }
            this.reQueueJobs = function() {
                return $.get('/clean/resetjobs', self.refreshJobs);
            }
            this.refreshJobs = function() {
                var container = $('#job-tables');
                $.ajax({
                    beforeSend: function() {
                        container.css('opacity', .5);
                    },
                    url: '/clean/refreshjobs'
                }).done(function(r) {
                    container.slideUp('fast').empty().append(r).fadeIn('fast');
                }).always(function() {
                    container.css('opacity', 1);
                    container.find('.collapse > td > table').hide()
                });
            }
        }

        function Logs(a) {
            var self = this;
            self.app = a;
            self.limit = ko.observable(10);
            self.page = ko.observable(0);
            self.dataRows = ko.observableArray([]);
            self.totalRows = ko.observable(0);
            self.load = function(){
                return $.get("/clean/logs", {
                    limit: self.limit(),
                    skip: self.page() * self.limit(),
                    page: self.page(),
                }).done(function(r) {
                    var arr = [];
                    ko.utils.arrayForEach(r.data, function(i) {
                        return arr.push(new Log(i));
                    });
                    self.totalRows(r.total);
                    self.dataRows(arr);
                });
            }
        };

        function Log(data) {
            return {
                _id: ko.observable(data._id),
                body: ko.observable(data.body),
                hide: function(d){
                    $('#'+d._id()).slideToggle('fast');
                },
                trash: function(d){
                    return true;
                }
            };
        };
        ;

        $.fn.exists = function() {
            return this.length !== 0;
        }
        jQuery(document).ready(function($) {
            /**
             * Leftbar
             *
             */
            var app = new App;
            ko.applyBindings(app, document.getElementById('leftFixedBar'));

            /**
             * User(s)
             */
            ko.applyBindings(new Clean(app), document.getElementById('clean'));

            /**
             * Jobs
             */
            ko.applyBindings(new Jobs(app), document.getElementById('jobs'));

            /**
             * Used in the Jobs view to shrink initial table created by PHP
             * (with JSON data).
             *
             * @type string css class for collapsible tables
             * @todo can be improved. Server side logic best implemented locally
             * */
            var t = $('.collapse').find('table');
            t && t.exists() && t.hide();


            /**
             * Logs
             */
            var logs = new Logs(app);
            ko.applyBindings(logs, document.getElementById('logs'));
            logs.load();
        });
    </script>
</html>