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
        <script type="text/javascript" src="//<?php echo RT::COM_SERVER ?>/js/jquery.min.js"></script>
        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>
        <script type="text/javascript" src="//<?php echo RT::COM_SERVER ?>/js/knockout.js"></script>
        <script type="text/javascript" src="//<?php echo RT::COM_SERVER ?>/js/knockout.mapping-latest.js"></script>
        <script type="text/javascript" src="/js/growl.js"></script>
        <style type="text/css">
            *{font-family: 'Droid Sans', sans-serif important; border-radius: 1px !important;}body{background-image:url('/images/subtle_stripes.png')}.white{background:#fff;border:1px solid #eee;position:relative}.content-area{padding:10px;box-shadow:1px 1px 1px #ddd inset;border-radius:2px;margin-top: 10px;}
            #leftFixedBar{position:fixed;background-color:#fff;height:100%;top:0px;width:70px;left:0;bottom:0;border-right:1px solid #bbb;}
            #leftFixedBar > ul > li > a{display:block;text-align: center;margin:1px auto;}
            #leftFixedBar > ul > li{margin:1px auto;}
            #leftFixedBar > ul > li.active > a{margin-right:-1px}
            .inline > div {display:inline-block;vertical-align: middle; padding:2px;}
            .navbar-menu{position:relative;top:0;right:0;left:0;margin-bottom:10px;}
        </style>
    </head>
    <body>
        <div id="leftFixedBar">
            <ul class="nav nav-pills nav-stacked">
                <li data-bind="css:{'active': isUser}"><a href="#clean"><i class="icon-user icon-3x"></i><div>Users</div></a></li>
                <li data-bind="css:{'active': isJob}"><a href="#jobs"><i class="icon-tasks icon-3x"></i><div>Jobs</div></a></li>
                <li data-bind="css:{'active': isLog}"><a href="#logs"><i class="icon-file-alt icon-3x"></i><div>Logs</div></a></li>
            </ul>
        </div>
        <div class="container-fluid clearfix" style="border-left:1px solid #eee;margin-left:71px">
            <div class="row-fluid clearfix">
                <div class="span12" style="margin-top:10px">
                    <div class="row-fluid" id="clean" data-bind="visible: app.isUser">
                        <div class="span12">
                            <div class="white content-area">
                                <div class="navbar navbar-menu">
                                    <div class="navbar-inner">
                                        <div class="brand pull-left span2">Users</div>
                                        <form class="navbar-search span6">
                                            <input type="text" class="search-query span12" data-bind="value:userField" placeholder="Enter user id, username or email" >
                                        </form>
                                        <ul class="nav span3 pull-right">
                                            <li><button class="btn btn-info btn-small" title="Reset User" data-bind="click:resetUser"><i class="icon-retweet icon-2x" data-bind="css:{'icon-spin': isResetting}"></i></button></li>
                                            <li><button class="btn btn-danger btn-small" title="Delete User" data-bind="click:deleteUser"><i class="icon-trash icon-2x" data-bind="css:{'icon-spin': isDeleting}"></i></button></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid" id="jobs" data-bind="visible: app.isJob">
                        <div class="span12">
                            <div class="white content-area">
                                <div class="navbar navbar-menu">
                                    <div class="navbar-inner">
                                        <div class="brand pull-left span2">Jobs</div>
                                        <form class="navbar-search span6">
                                            <input type="text" class="search-query span12"  data-bind="" placeholder="Enter job id" >
                                        </form>
                                        <ul class="nav span3 pull-right">
                                            <li><button class="btn btn-small" title="Run Email Job" data-bind="click:runEmail"><i class="icon-envelope icon-2x" data-bind="css:{'icon-spin':isRunningEmail}"></i></li>
                                            <li><button class="btn btn-info btn-small" title="ReQueue Jobs" data-bind="click:reQueueJobs"><i class="icon-retweet icon-2x"></i></li>
                                            <li><button class="btn btn-inverse btn-small" title="Refresh Jobs" data-bind="click:refresh"><i class="icon-refresh icon-2x" data-bind="css:{'icon-spin':isRefreshing}"></i></button></li>
                                        </ul>
                                    </div>
                                </div>
                                <ul data-bind="foreach:dataRows" class="nav nav-list" style="margin-top:50px">
                                    <li>
                                        <div class="row-fluid">
                                            <div class="span8">
                                                <div class="span3">
                                                    <label>Time: <small data-bind="text:time"></small>
                                                    </label>
                                                </div>
                                                <div class="span2">
                                                    <label>Status: <small class="badge" data-bind="text:$data.status,
                                                                          css:{
                                                                          'badge-success': $data.status() === 0,
                                                                          'badge-warning': $data.status() === 1,
                                                                          'badge-error': $data.status() === 2,
                                                                          }"></small>
                                                    </label>
                                                </div>
                                                <div class="span2">
                                                    <label>Processing?: <small data-bind="text:$data.in_progress() ? 'Yes' : 'No', css:{'text-warning':in_progress, 'text-success':!in_progress()}"></small></label>
                                                </div>
                                                <div class="span2">
                                                    <label>Single?: <small data-bind="text:$data.is_single() ? 'Yes' : 'No', css:{'text-warning':is_single, 'text-success':!is_single()}"></small></label>
                                                </div>
                                            </div>
                                            <div class="span4">
                                                <div class="btn-toolbar clearfix" style="float:right;margin-top:0">
                                                    <div class="btn-group clearfix">
                                                        <a href="#" class="btn btn-small" data-bind="click:$parent.hide">
                                                            <i class="icon-eye-close" data-bind=""></i>&nbsp;<span class="hidden-phone hidden-tablet">Hide</span>
                                                        </a>
                                                        <a href="#" class="btn btn-small btn-danger" data-bind="click:$parent.trash">
                                                            <i class="icon-trash" data-bind=""></i>&nbsp;<span class="hidden-phone hidden-tablet">Delete</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row-fluid" data-bind="attr:{id: $data._id}" style="display:none;">
                                            <div class="span12">
                                                <pre data-bind="text:ko.toJSON(messages, null, 2)"></pre>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="divider"></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid" id="logs" data-bind="visible: app.isLog">
                        <div class="span12">
                            <div class="white content-area">
                                <div class="navbar navbar-menu">
                                    <div class="navbar-inner">
                                        <div class="brand pull-left span2">Logs</div>
                                        <form class="navbar-search span6">
                                            <input type="text" class="search-query span12"  data-bind="" placeholder="Enter log id" >
                                        </form>
                                        <ul class="nav span3 pull-right">
                                            <li><button class="btn btn-danger btn-small" title="Delete Logs" data-bind="click:deleteAll"><i class="icon-trash icon-2x"></i></button></li>
                                            <li><button class="btn btn-inverse btn-small" title="Refresh Logs" data-bind="click:refresh,disable:isLoading"><i class="icon-refresh icon-2x" data-bind="css:{'icon-spin':isRefreshing}"></i></button></li>
                                        </ul>
                                    </div>
                                </div>
                                <ul data-bind="foreach:dataRows" class="nav nav-list" style="margin-top:50px">
                                    <li>
                                        <div class="row-fluid">
                                            <div class="span6">
                                                <small data-bind="text:time"></small>
                                            </div>
                                            <div class="span6">
                                                <div class="btn-toolbar clearfix" style="float:right;margin-top:0">
                                                    <div class="btn-group clearfix">
                                                        <a href="#" class="btn btn-small" data-bind="click:$parent.hide">
                                                            <i class="icon-eye-close" data-bind=""></i>&nbsp;<span class="hidden-phone hidden-tablet">Hide</span>
                                                        </a>
                                                        <a href="#" class="btn btn-small btn-danger" data-bind="click:$parent.trash">
                                                            <i class="icon-trash" data-bind=""></i>&nbsp;<span class="hidden-phone hidden-tablet">Delete</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row-fluid" data-bind="attr:{id: $data._id}">
                                            <div class="span12">
                                                <pre data-bind="html:$data.body"></pre>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="divider"></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script type="text/javascript">
        var queue = null;
        function App() {
            var self = this, h = window.location.hash;
            self.hash = ko.observable(h);
            self.isUser = ko.computed(function() {
                return self.hash() === '#clean';
            });
            self.isJob = ko.computed(function() {
                return self.hash() === '#jobs';
            });
            self.isLog = ko.computed(function() {
                return self.hash() === '#logs';
            });
            window.onhashchange = function() {
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
                    url: '/admin/deleteuser/',
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
                    url: '/admin/resetuser/',
                    type: 'post',
                    data: {search: t.userField()},
                    beforeSend: function() {
                        t.isResetting(!0);
                    }
                })
                        .done(function(r) {
                    $.bootstrapGrowl("User reset!");
                })
                        .always(function() {
                    return t.isResetting(!1) && t.userField(null);
                }) || false;
            };
        }

        function Jobs(a) {
            var c = $('.collapse'), t = c.find('table'), self = this;
            self.app = a;
            self.limit = ko.observable(10);
            self.page = ko.observable(1);
            self.dataRows = ko.observableArray([]);
            self.totalRows = ko.observable(0);
            self.isLoading = ko.observable(false);
            self.isRunningEmail = ko.observable(false);
            self.isRefreshing = ko.observable(false);
            self.load = function() {
                return self.isLoading(true) && $.get("/admin/jobs", {
                    limit: self.limit(),
                    skip: self.page() * self.limit(),
                    page: self.page()
                }).done(function(r) {
                    var arr = ko.mapping.fromJS(r.data);
                    self.dataRows(GetObs(arr));
                    self.totalRows(r.total);

                }).always(function() {
                    return self.isLoading(false)
                });
            }
            self.trash = function(d) {
                return $.post('/admin/deletejob/' + d._id(), function() {
                    return self.load();
                });
            };
            self.hide = function(d) {
                $('#' + d._id()).slideToggle('fast');
            }
            self.toggleTable = function(d, e) {
                e.stopPropagation();
                $(e.currentTarget).find("table").slideToggle();
            }
            self.reQueueJobs = function() {
                return $.get('/admin/resetjobs', self.refreshJobs);
            }
            self.refreshJobs = function(v) {
                var queue = setTimeout(self.refreshJobs, 3e3);

                self.isRefreshing() && self.load().always(queue) || clearTimeout(queue);
            };
            self.refresh = function(d, e){
                return self.isRefreshing(!self.isRefreshing());
            }
            self.reloop = function() {
                queue = setTimeout(self.reloop, 3e3);

                return self.isRefreshing() && self.load().always(queue) || clearTimeout(queue);
            }
            self.isRefreshing.subscribe(self.reloop)
            self.runEmail = function(){
                self.isRunningEmail(true);
                $.get('http://queue.jjpc.net/email').always(function(){self.isRunningEmail(false);})
            };
        }

        function Logs(a) {
            var self = this;
            self.app = a;
            self.limit = ko.observable(10);
            self.page = ko.observable(1);
            self.dataRows = ko.observableArray([]);
            self.totalRows = ko.observable(0);
            self.isLoading = ko.observable(false);
            self.isRefreshing = ko.observable(false);
            self.load = function() {
                return self.isLoading(true) && $.get("/admin/logs", {
                    limit: self.limit(),
                    skip: self.page() * self.limit(),
                    page: self.page()
                }).done(function(r) {
                    var arr = ko.mapping.fromJS(r.data);
                    self.dataRows(GetObs(arr));
                    self.totalRows(r.total);

                }).always(function() {
                    return self.isLoading(false)
                });
            }
            self.deleteAll = function() {
                return $.post('/admin/deletealllogs/', function() {
                    return self.load();
                });
            };
            self.refresh = function(d, e){
                return self.isRefreshing(!self.isRefreshing());
            }
            self.reloop = function() {
                queue = setTimeout(self.reloop, 3e3);

                return self.isRefreshing() && self.load() || clearTimeout(queue);
            };
            self.isRefreshing.subscribe(self.reloop);
            self.hide = function(d) {
                    $('#' + d._id()).slideToggle('fast');
                }
            self.trash = function(d) {
                return $.post('/admin/deletelog/' + d._id(), function() {
                    return self.load();
                });
            };
        }
        ;

        function Log(data) {
            return {
                _id: ko.observable(data._id),
                body: ko.observable(data.body),
                time: ko.observable(data.time)
            };
        }
        ;

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
            var jobs = new Jobs(app);
            ko.applyBindings(jobs, document.getElementById('jobs'));
            jobs.load();

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
        ko.observableArray.fn["pushAll"] = function(items) {
            this.push.apply(this, items);
        };
        GetObs = function(p) {
            return ko.isObservable(p) ? p() : p;
        }
        $.fn.exists = function() {
            return this.length !== 0;
        }
    </script>
</html>