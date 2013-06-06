/**
 * @package knockout-postbox
 * @link https://github.com/rniemeyer/knockout-postbox
 * @author Ryan Niemeyer
 */
!function(d) {
    "function" === typeof require && "object" === typeof exports && "object" === typeof module ? d(require("knockout"), exports) : "function" === typeof define && define.amd ? define(["knockout", "exports"], d) : d(ko, ko.postbox = {})
}(function(d, c, j) {
    var f, g;
    d.subscribable.call(c);
    c.topicCache = {};
    c.serializer = d.toJSON;
    c.publish = function(a, b) {
        a && (c.topicCache[a] = {value: b, serialized: c.serializer(b)}, c.notifySubscribers(b, a))
    };
    g = c.subscribe;
    c.subscribe = function(a, b, e) {
        if (a)
            return g.call(c, b, e, a)
    };
    c.defaultComparer = function(a,
            b) {
        return b && c.serializer(a) === b.serialized
    };
    d.subscribable.fn.publishOn = function(a, b, e) {
        var d;
        a && ("function" === typeof b ? e = b : d = b, e = e || c.defaultComparer, f.call(this, a, "publishOn"), this.postboxSubs[a].publishOn = this.subscribe(function(b) {
            e.call(this, b, c.topicCache[a]) || c.publish(a, b)
        }, this), d || c.publish(a, this()));
        return this
    };
    f = function(a, b) {
        var c = this.postboxSubs = this.postboxSubs || {};
        c[a] = c[a] || {};
        c[a][b] && c[a][b].dispose()
    };
    d.subscribable.fn.stopPublishingOn = function(a) {
        f.call(this, a, "publishOn");
        return this
    };
    d.subscribable.fn.subscribeTo = function(a, b, e) {
        var h, g = this;
        "function" === typeof b ? e = b : h = b;
        a && d.isWriteableObservable(this) && (f.call(this, a, "subscribeTo"), b = function(a) {
            g(e ? e.call(g, a) : a)
        }, this.postboxSubs[a].subscribeTo = c.subscribe(a, b), h && (a = c.topicCache[a], a !== j && b(a.value)));
        return this
    };
    d.subscribable.fn.unsubscribeFrom = function(a) {
        f.call(this, a, "subscribeTo");
        return this
    };
    d.subscribable.fn.syncWith = function(a, b, c, d) {
        this.subscribeTo(a, b).publishOn(a, c, d);
        return this
    };
    d.postbox = c
});


var queue = null;
function App() {
    var self = this, w = window;
    self.hash = ko.observable(w.location.hash).publishOn('hashChanged');
    self.isUser = ko.computed(function() {
        return self.hash() === '#clean';
    });
    self.isJob = ko.computed(function() {
        return self.hash() === '#jobs';
    });
    self.isLog = ko.computed(function() {
        return self.hash() === '#logs';
    });

    w.onhashchange = function() {
        self.hash(w.location.hash);
    }
}

function Clean() {
    this.visible = ko.observable().subscribeTo("hashChanged", function(v) {
        return v === "#clean";
    });
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

function Jobs() {
    var c = $('.collapse'), t = c.find('table'), self = this;
    self.visible = ko.observable().subscribeTo("hashChanged", function(v) {
        return v === "#jobs";
    });
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
    };
    self.toggleTable = function(d, e) {
        e.stopPropagation();
        $(e.currentTarget).find("table").slideToggle();
    };
    self.reQueueJobs = function() {
        return $.get('/admin/resetjobs', self.refreshJobs);
    };
    self.refreshJobs = function(v) {
        var queue = setTimeout(self.refreshJobs, 3e3);

        self.isRefreshing() && self.load().always(queue) || clearTimeout(queue);
    };
    self.refresh = function(d, e) {
        return self.isRefreshing(!self.isRefreshing());
    };
    self.reloop = function() {
        queue = setTimeout(self.reloop, 3e3);

        return self.isRefreshing() && self.load().always(queue) || clearTimeout(queue);
    };
    self.isRefreshing.subscribe(self.reloop);
    self.runEmail = function() {
        self.isRunningEmail(true);
        $.get('http://queue.jjpc.net/email').always(function() {
            self.isRunningEmail(false);
        })
    };
}

function Logs() {
    var self = this;
    self.visible = ko.observable().subscribeTo("hashChanged", function(v) {
        return v === "#logs";
    });
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
    };
    self.deleteAll = function() {
        return $.post('/admin/deletealllogs/', function() {
            return self.load();
        });
    };
    self.refresh = function(d, e) {
        return self.isRefreshing(!self.isRefreshing());
    };
    self.reloop = function() {
        queue = setTimeout(self.reloop, 3e3);

        return self.isRefreshing() && self.load() || clearTimeout(queue);
    };
    self.isRefreshing.subscribe(self.reloop);
    self.hide = function(d) {
        $('#' + d._id()).slideToggle('fast');
    };
    self.trash = function(d) {
        return $.post('/admin/deletelog/' + d._id(), function() {
            return self.load();
        });
    };
}

jQuery(document).ready(function($) {
    // variables
    var app = new App, jobs = new Jobs, logs = new Logs;

    /**
     * Leftbar
     *
     */
    ko.applyBindings(app, document.getElementById('leftFixedBar'));

    /**
     * User(s)
     */
    ko.applyBindings(new Clean, document.getElementById('clean'));

    /**
     * Jobs
     */
    ko.applyBindings(jobs, document.getElementById('jobs'));jobs.load();

    /**
     * Logs
     */
    ko.applyBindings(logs, document.getElementById('logs'));logs.load();
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