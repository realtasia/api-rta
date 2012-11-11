var domain = 'http://rta.jjpc.net', common = 'http://jjpc.net/common/js', app;
if (location.host.indexOf('casa') > 1)
{
    domain = 'http://rta.casa.net';
    common = 'http://casa.net/common/js';
}

function File(id, name, size)
{
    this.id = id;
    this.size = size;
    this.src = ko.observable(false);
    this.name = ko.observable(name || 'Description...');
    this.queued = ko.observable(true);
    this.uploading = ko.observable(false);
    this.uploaded = ko.observable(false);
    this.progress = ko.observable(0);
}

function Lib()
{
    var self = this;
    this.files = ko.observableArray([]);
    this.fadeIn = function(element, index, data){
        $(element).fadeIn('slow');
    };
    this.fadeOut = function(element, index, data){
        $(element).fadeOut('slow');
    };
    this.isDroppable = ko.observable(false);
    this.droppable = ko.computed(function(){
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
            {title: "Image files", extensions: "jpg,gif,png"},
            {title: "Zip files", extensions: "zip"}
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
                var uploading = (up.state === plupload.STARTED) ? true : false;

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

    uploader.bind('Init', function(up, params){
        if(!!FileReader && !((params.runtime === "flash") || (params.runtime === "silverlight")))
        {
            lib.isDroppable(true);
        }
    })

    uploader.init();

    $('.deleteFile').live('click', function(e){
        e.preventDefault();
        uploader.splice($(this).id, 1);
        return lib.files.remove(ko.dataFor(this))
    })

//
//    uploader.bind('Error', function(up, err) {
//        $('#filelist').append("<div>Error: " + err.code +
//                ", Message: " + err.message +
//                (err.file ? ", File: " + err.file.name : "") +
//                "</div>"
//                );
//
//        up.refresh(); // Reposition Flash/Silverlight
//    });
//
//    uploader.bind('FileUploaded', function(up, file) {
//        $('#' + file.id + " b").html("100%");
//    });
})