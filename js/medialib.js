var domain = 'http://rta.jjpc.net', common = 'http://jjpc.net/common/js', app;
if (location.host.indexOf('casa') > 1)
{
    domain = 'http://rta.casa.net';
    common = 'http://casa.net/common/js';
}

function File(id, name, size)
{
    var self = this;
    this.id = id || null;
    this.size = size || null;
    this.src = ko.observable(false);
    this.name = ko.observable(name || 'Description...');
    this.queued = ko.observable(true);
    this.uploading = ko.observable(false);
    this.uploaded = ko.observable(false);
    this.isHovering = ko.observable(false);
    this.editing = ko.observable(false);
    this.stopEditing = function(item, event)
    {
        item.editing(false);
    }
    this.edit = function(item, event)
    {
        item.editing(true);
    }

    this.progress = ko.observable(0);
}

function Lib()
{
    var self = this;
    this.files = ko.observableArray([]);
    this.isDroppable = ko.observable(false);
    this.droppable = ko.computed(function() {
        return this.isDroppable() && this.files().length === 0;
    }, this)
}

$(document).ready(function() {
    var lib = new Lib();

    ko.applyBindings(lib);

    function log() {
        var str = "";

        plupload.each(arguments, function(arg) {
            var row = "";

            if (typeof(arg) != "string") {
                plupload.each(arg, function(value, key) {
                    // Convert items in File objects to human readable form
                    if (arg instanceof plupload.File) {
                        // Convert status to human readable
                        switch (value) {
                            case plupload.QUEUED:
                                value = 'QUEUED';
                                break;

                            case plupload.UPLOADING:
                                value = 'UPLOADING';
                                break;

                            case plupload.FAILED:
                                value = 'FAILED';
                                break;

                            case plupload.DONE:
                                value = 'DONE';
                                break;
                        }
                    }

                    if (typeof(value) != "function") {
                        row += (row ? ', ' : '') + key + '=' + value;
                    }
                });

                str += row + " ";
            } else {
                str += arg + " ";
            }
        });

        $('#log').append(str + "\n");
    }

    var uploader = new plupload.Uploader({
        runtimes: 'gears,html5,flash,silverlight,browserplus',
        browse_button: 'pickfiles',
        container: 'thumbnails',
        drop_element: 'droppable',
        max_file_size: '10mb',
        url: '/upload.php',
        flash_swf_url: common + '/plupload/js/plupload.flash.swf',
        silverlight_xap_url: common + '/plupload/js/plupload.silverlight.xap',
        filters: [
            {title: "Images", extensions: "jpg,gif,png"},
            {title: "Documents", extensions: "zip"}
        ],
        //resize: {width: 320, height: 240, quality: 90},
        // PreInit events, bound before any internal events
// Post init events, bound after the internal events
        init: {
            Refresh: function(up) {
                // Called when upload shim is moved
                log('[Refresh]');
            },
            StateChanged: function(up) {
                var uploading = (up.state === plupload.STARTED);

                ko.utils.arrayForEach(lib.files(), function(item) {
                    item.uploading(uploading);
                });
            },
            QueueChanged: function(up) {
                // Called when the files in queue are changed by adding/removing files
                //log('[QueueChanged]');
            },
            UploadProgress: function(up, file) {
                var found = ko.utils.arrayFirst(lib.files(), function(item) {
                    return file.id === item.id;
                });

                found.progress(file.percent);
            },
            FilesAdded: function(up, files) {
                ko.utils.arrayForEach(files, function(file) {
                    lib.files.push(new File(file.id, file.name, plupload.formatSize(file.size)));
                });
                up.refresh(); // Reposition Flash/Silverlight
                uploader.start();
            },
            FilesRemoved: function(up, files) {
                // Called when files where removed from queue
                //log('[FilesRemoved]');

                plupload.each(files, function(file) {
                    log('  File:', file);
                });
            },
            FileUploaded: function(up, file, info) {
                // Called when a file has finished uploading
                log('[FileUploaded] File:', file, "Info:", info);
                var found = ko.utils.arrayFirst(lib.files(), function(item) {
                    return file.id === item.id;
                });

                found.uploaded(true);
                found.uploading(false);
                found.queued(false);
                found.src(domain + '/uploads/' + file.name);
            },
            ChunkUploaded: function(up, file, info) {
                // Called when a file chunk has finished uploading
                log('[ChunkUploaded] File:', file, "Info:", info);
            },
            Error: function(up, args) {
                // Called when a error has occured
                log('[error] ', args);
            }
        }
    });

    uploader.bind('Init', function(up, params) {
        if (!!FileReader && !((params.runtime === "flash") || (params.runtime === "silverlight")))
        {
            lib.isDroppable(true);
        }
    })

    uploader.init();

    $("#thumbnails").delegate(".deleteFile", "click", function() {
        //retrieve the context
        var file = ko.dataFor(this);
        if (file.uploaded())
        {
            log("File uploaded. Send request to server for deletion");
        }
        else
        {
            log("Removing file " + file.id + " from queue");
            uploader.splice(file.id, 1)
            lib.files.remove(file);
        }

        return false;
    });
    $(".imageItem").live({
        mouseenter: function() {
            ko.dataFor(this).isHovering(true);
        }, mouseleave: function() {
            ko.dataFor(this).isHovering(false);
        }
    });

    lib.files.push(new File());
    lib.files.push(new File());
    lib.files.push(new File());

});

var ENTER_KEY = 13;

// a custom binding to handle the enter key (could go in a separate library)
ko.bindingHandlers.enterKey = {
    init: function(element, valueAccessor, allBindingsAccessor, data) {
        var wrappedHandler, newValueAccessor;

        // wrap the handler with a check for the enter key
        wrappedHandler = function(data, event) {
            if (event.keyCode === ENTER_KEY) {
                valueAccessor().call(this, data, event);
            }
        };

        // create a valueAccessor with the options that we would want to pass to the event binding
        newValueAccessor = function() {
            return {
                keyup: wrappedHandler
            };
        };

        // call the real event binding's init function
        ko.bindingHandlers.event.init(element, newValueAccessor, allBindingsAccessor, data);
    }
};

// wrapper to hasfocus that also selects text and applies focus async
ko.bindingHandlers.selectAndFocus = {
    init: function(element, valueAccessor, allBindingsAccessor) {
        ko.bindingHandlers.hasfocus.init(element, valueAccessor, allBindingsAccessor);
        ko.utils.registerEventHandler(element, 'focus', function() {
            element.focus()
            element.select();
        });
    },
    update: function(element, valueAccessor) {
        ko.utils.unwrapObservable(valueAccessor()); // for dependency
        // ensure that element is visible before trying to focus
        setTimeout(function() {
            ko.bindingHandlers.hasfocus.update(element, valueAccessor);
        }, 0);
    }
};
