// jQuery.XDomainRequest.js
// Author: Jason Moon - @JSONMOON
// IE8+
if (!jQuery.support.cors && window.XDomainRequest) {
    var httpRegEx = /^https?:\/\//i;
    var getOrPostRegEx = /^get|post$/i;
    var sameSchemeRegEx = new RegExp('^' + location.protocol, 'i');
    var jsonRegEx = /\/json/i;
    var xmlRegEx = /\/xml/i;

    // ajaxTransport exists in jQuery 1.5+
    jQuery.ajaxTransport('text html xml json', function(options, userOptions, jqXHR) {
        // XDomainRequests must be: asynchronous, GET or POST methods, HTTP or HTTPS protocol, and same scheme as calling page
        if (options.crossDomain && options.async && getOrPostRegEx.test(options.type) && httpRegEx.test(userOptions.url) && sameSchemeRegEx.test(userOptions.url)) {
            var xdr = null;
            var userType = (userOptions.dataType || '').toLowerCase();
            return {
                send: function(headers, complete) {
                    xdr = new XDomainRequest();
                    if (/^\d+$/.test(userOptions.timeout)) {
                        xdr.timeout = userOptions.timeout;
                    }
                    xdr.ontimeout = function() {
                        complete(500, 'timeout');
                    };
                    xdr.onload = function() {
                        var allResponseHeaders = 'Content-Length: ' + xdr.responseText.length + '\r\nContent-Type: ' + xdr.contentType;
                        var status = {
                            code: 200,
                            message: 'success'
                        };
                        var responses = {
                            text: xdr.responseText
                        };
                        /*
                         if (userType === 'html') {
                         responses.html = xdr.responseText;
                         } else
                         */
                        try {
                            if ((userType === 'json') || ((userType !== 'text') && jsonRegEx.test(xdr.contentType))) {
                                try {
                                    responses.json = $.parseJSON(xdr.responseText);
                                } catch (e) {
                                    status.code = 500;
                                    status.message = 'parseerror';
                                    //throw 'Invalid JSON: ' + xdr.responseText;
                                }
                            } else if ((userType === 'xml') || ((userType !== 'text') && xmlRegEx.test(xdr.contentType))) {
                                var doc = new ActiveXObject('Microsoft.XMLDOM');
                                doc.async = false;
                                try {
                                    doc.loadXML(xdr.responseText);
                                } catch (e) {
                                    doc = undefined;
                                }
                                if (!doc || !doc.documentElement || doc.getElementsByTagName('parsererror').length) {
                                    status.code = 500;
                                    status.message = 'parseerror';
                                    throw 'Invalid XML: ' + xdr.responseText;
                                }
                                responses.xml = doc;
                            }
                        } catch (parseMessage) {
                            throw parseMessage;
                        } finally {
                            complete(status.code, status.message, responses, allResponseHeaders);
                        }
                    };
                    xdr.onerror = function() {
                        complete(500, 'error', {
                            text: xdr.responseText
                        });
                    };
                    var postData = (userOptions.data && $.param(userOptions.data)) || '';
                    xdr.open(options.type, options.url);
                    xdr.send(postData);
                },
                abort: function() {
                    if (xdr) {
                        xdr.abort();
                    }
                }
            };
        }
    });
}

function Param(key, val)
{
    this.key = ko.observable(key);
    this.value = ko.observable(val);
}

function viewModel() {
    var self = this;

    this.uri = ko.observable('');
    this.name = ko.observable();
    this.val = ko.observable();
    this.params = ko.observableArray();

    this.addParam = function(data, event)
    {
        if (event.keyCode == '13') {
            if (self.name().length > 0 && self.val().length > 0) {
                toAdd = new Param(self.name(), self.val());

                self.params.remove(function(item) {
                    return item.key() === self.name();
                });

                self.params.unshift(toAdd);

            }
        }
        if (event.keyCode == '46') {
            console.log(data.key)
            self.params.remove(function(item) {
                return item.key() === data.name();
            });
        }
    };
    this.selectLoad = function(key, val, data, event)
    {
        self.name(key);
        self.val(val);
        jQuery(event.currentTarget).parent().find('.selected').removeClass('selected')
        jQuery(event.currentTarget).addClass('selected')
        jQuery('input.val').focus();
    };
    this.query = function(data, event)
    {
        var btn = jQuery(event.target), pars = {};

        ko.utils.arrayForEach(this.params(), function(item) {
            pars[item.key()] = item.value();
        });
        var settings = {
            type : btn.text()
        };

        return new Load(settings, pars, self);
    }

    this.authClient = function()
    {
        var params = {
            client_id: 123456789,
            redirect_uri: 'http://jjpc.net',
            grant_type: 'client_credentials'
        }
        self.uri('/auth');
        return new Load('POST', params, self);
    }

    this.authUser = function()
    {
        var params = {
            client_id: 123456789,
            username: 'two.user',
            password: 'two',
            redirect_uri: 'http://jjpc.net',
            grant_type: 'password'
        }

        self.uri('/auth');
        return new Load('POST', params, self);
    }

    this.authAdmin = function()
    {
        var params = {
            client_id: 123456789,
            username: 'admin',
            password: 'one',
            redirect_uri: 'http://jjpc.net',
            grant_type: 'password'
        }
        self.uri('/auth');
        return new Load('POST', params, self);
    }
}

Load = function(settings, params, model)
{
    var dest = jQuery('#results'), token = null;

    if (model.uri().length === 0) {
        return false;
    }

    var domain = 'http://rta.jjpc.net';
    if (location.host.indexOf('casa') > 1)
    {
        domain = 'http://rta.casa.net';
    }

    $.ajax({
        url: domain + model.uri(),
        type: settings.type,
        crossDomain: true,
        dataType: 'json',
        data: params,
        success: function(response, textStatus, jqXHR) {

            dest.html(parseValue(response));

            if (response.data && response.data['access_token']) {
                token = response.data['access_token'];
            }
            if (response.session && response.session['access_token']) {
                token = response.session['access_token'];
            }
            if (token) {
                var toAdd = new Param('access_token', token);
                model.params.remove(function(item) {
                    return item.key() === 'access_token';
                });
                model.params.unshift(toAdd);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            var eob = {
                status: 'error',
                data: {
                    title: 'access_denied',
                    message: 'The resource owner or authorization server or browser denied the request.'
                }
            };
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
            dest.text(JSON.stringify(eob, undefined, 3));
        }
    });
}

jQuery(document).ready(function($) {
    ko.applyBindings(new viewModel());
});

