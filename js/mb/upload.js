var uploader = new Uploader();
var imageBrowser = new ImageBrowser();
var fileBrowser = new FileBrowser();

function Uploader()
{
    var _self = this;

    if(platformChecker.isMobile())
    {
        _self.uploadImage = function(imageUri, onSuccess, onError, onProgress) {
            var options = new FileUploadOptions();
            options.fileKey = "file";
            options.fileName = imageUri.substr(imageUri.lastIndexOf('/') + 1);
            options.mimeType = "image/jpeg";
            options.chunkedMode = false;

            var fileTransfer = new FileTransfer();
            fileTransfer.upload(imageUri, config.api.urls.upload.image + '?login_token=' + auth.getUser().loginToken, function(data) {
                var response = JSON.parse(data.response);
                if(response.status == 'OK')
                {
                    onSuccess && onSuccess(response.body.image);
                }
                else
                {
                    onError && onError(response.body.error);
                }
            }, function(error) {
                defaultErrorHandler(null, null, null);
            }, options);

            var percentage = 0;
            fileTransfer.onprogress = function(e) {
                if(e.lengthComputable)
                {
                    percentage = e.loaded / e.total * 100;
                }
                else
                {
                    percentage++;
                }

                onProgress && onProgress(Math.min(percentage, 100));
            };
        };
    }
    else //upload via browser
    {
        _self.uploadImage = function($file, onSuccess, onError, onProgress, params) {
            var imageData = $file instanceof Blob ? $file : $file.prop("files")[0];
            var formData = new FormData();

            if(params)
            {
                $.each(params, function(key, value) {
                    formData.append(key, value);
                });
            }

            formData.append("file", imageData);

            $.ajax({
                url: config.api.urls.file.uploadImage + '?login_token=' + auth.getUser().loginToken,
                type: "POST",
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                success: function(data) {
                    var response = JSON.parse(data);
                    if(response.status == 'OK')
                    {
                        onSuccess && onSuccess(response.body.image);
                    }
                    else
                    {
                        onError && onError(response.body.errors);
                    }
                },
                error: defaultErrorHandler,
                xhr: function() {
                    var xhr = $.ajaxSettings.xhr();

                    var percentage = 0;
                    xhr.upload.onprogress = function(e) {
                        if(e.lengthComputable)
                        {
                            percentage = e.loaded / e.total * 100;
                        }
                        else
                        {
                            percentage++;
                        }

                        onProgress && onProgress(Math.min(percentage, 100));
                    };

                    return xhr;
                }
            });
        };
    }

    _self.uploadFile = function($file, onSuccess, onError, onProgress, params) {
        var fileData = $file.prop("files")[0];
        var formData = new FormData();

        if(params)
        {
            $.each(params, function(key, value) {
                formData.append(key, value);
            });
        }

        formData.append("file", fileData);

        $.ajax({
            url: config.api.urls.file.create + '?login_token=' + auth.getUser().loginToken,
            type: "POST",
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            success: function(data) {
                var response = JSON.parse(data);
                if(response.status == 'OK')
                {
                    onSuccess && onSuccess(response.body.file);
                }
                else
                {
                    onError && onError(response.body.errors);
                }
            },
            error: defaultErrorHandler,
            xhr: function() {
                var xhr = $.ajaxSettings.xhr();

                var percentage = 0;
                xhr.upload.onprogress = function(e) {
                    if(e.lengthComputable)
                    {
                        percentage = e.loaded / e.total * 100;
                    }
                    else
                    {
                        percentage++;
                    }

                    onProgress && onProgress(Math.min(percentage, 100));
                };

                return xhr;
            }
        });
    };
}

function ImageBrowser()
{
    var _self = this;

    if(platformChecker.isMobile())
    {
        _self.fromCamera = function(onSelect) {
            _self.browse(navigator.camera.PictureSourceType.CAMERA, onSelect);
        };

        _self.fromGallery = function(onSelect) {
            _self.browse(navigator.camera.PictureSourceType.PHOTOLIBRARY, onSelect);
        };

        _self.browse = function(sourceType, onSelect) {
            navigator.camera.getPicture(function(imageUri) {
                onSelect(imageUri);
            }, function(error) {
                onSelect(null);
            }, {
                quality: 50,
                destinationType: navigator.camera.DestinationType.FILE_URI,
                sourceType: sourceType
            });
        };
    }
    else //browse via browser
    {
        _self.fromGallery = function(onSelect) {
            var $file = $('<input type="file" accept="image/*" />');
            $file.css({ 'position': 'fixed', 'top':  '-1000px' });
            $file[0].click();

            $file.on('change', function() { onSelect($file.val() ? $file : null); });
        };
    }
}

function FileBrowser()
{
    var _self = this;

    _self.browse = function(onSelect) {
        var $file = $('<input type="file" />');
        $file.css({ 'position': 'fixed', 'top':  '-1000px' });
        $file[0].click();

        $file.on('change', function() { onSelect($file.val() ? $file : null); });
    };
}

function imageCropper(image, fullImage, onCrop, options)
{
    options = options || {};
    options.title = options.title || 'Crop Image';
    options.public = options.public || 0;
    options.aspectRatio = options.aspectRatio || 1;

    if(image && fullImage)
    {
        onUpload(fullImage);
    }
    else
    {
        imageBrowser.fromGallery(function($file) {
            $file && uploadFullImage($file);
        });
    }

    function uploadFullImage($file)
    {
        var $modal = modal('Uploading Image...');
        var $modalBody = $modal.find('.modal-body').empty();

        var html = '';

        html += '<div>';
        html += '   <div class="progress m-b-none">';
        html += '       <div style="width: 0%;" class="progress-bar progress-bar-success"></div>';
        html += '   </div>';
        html += '</div>';

        $modalBody.html(html);
        $modal.modal();

        var $progressBar = $modalBody.find('.progress-bar');

        uploader.uploadImage($file, function(fullImage) {
            $modal.modal('hide');
            onUpload(fullImage);
        }, function(error) {
            showToast('error', 'Your image cannot be uploaded.');
            $modal.modal('hide');
        }, function(percentage) {
            $progressBar.css('width', percentage + '%');
        }, { public: options.public });
    }

    function onUpload(fullImage)
    {
        var $modal = modal(options.title);
        var $modalBody = $modal.find('.modal-body').empty();
        var $modalFooter = $modal.find('.modal-footer');

        $('<div class="text-center"><i class="fa fa-spinner fa-spin fa-2x"></i></div>').appendTo($modalBody);
        var $cropperContainer = $('<div></div>');
        var $cropper = $('<img src="' + fullImage.url + '" />').css('max-width', '100%').appendTo($cropperContainer);

        $modalFooter.find('.close-button').addClass('pull-left');
        $modal.modal();

        $modal.on('hidden.bs.modal', function() { $cropper.cropper('destroy'); });

        var $newImageButton = $('<button type="button" class="btn btn-success pull-right disabled"><i class="fa fa-upload"></i> New Image</button>').on('click', function() {
            if($newImageButton.hasClass('disabled'))
            {
                return;
            }

            imageBrowser.fromGallery(function($file) {
                $modal.modal('hide');
                $file && uploadFullImage($file);
            });
        });

        var $rotateButton = $('<button type="button" class="btn btn-success disabled"><i class="fa fa-rotate-left"></i></button>').on('click', function() {
            if($rotateButton.hasClass('disabled'))
            {
                return;
            }

            $cropper.cropper('rotate', '-90');
        });

        var $uploadCroppedButton = $('<button type="button" class="btn btn-primary pull-right disabled"><i class="fa fa-check"></i> Crop & Save</button>').on('click', function() {
            if($uploadCroppedButton.hasClass('disabled'))
            {
                return;
            }

            $newImageButton.addClass('disabled');
            $uploadCroppedButton.addClass('disabled');
            $rotateButton.addClass('disabled');
            $uploadCroppedButton.html('<i class="fa fa-spinner fa-spin"></i> Cropping... 100%');

            var cropBox = $cropper.cropper('getCropBoxData');
            var data = $cropper.cropper('getData');
            cropBox.rotate = data.rotate;

            var contentType = 'image/jpeg';
            var base64Url = $cropper.cropper('getCroppedCanvas').toDataURL(contentType);

            apiRequest(config.api.urls.upload.base64Image, 'POST', {
                url: base64Url,
                content_type: contentType,
                public: options.public,
            }, function(response) {
                $modal.modal('hide');
                onCrop && onCrop(response.body.image, fullImage, cropBox);
            });
        }).appendTo($modalFooter);

        $newImageButton.appendTo($modalFooter);
        $rotateButton.appendTo($modalFooter);

        $modal.on('shown.bs.modal', function() {
            $modalBody.html($cropperContainer);
            $cropper.cropper({
                aspectRatio: options.aspectRatio,
                zoomable: false,
                built: function() {
                    $uploadCroppedButton.removeClass('disabled');
                    $newImageButton.removeClass('disabled');
                    $rotateButton.removeClass('disabled');

                    fullImage.cropBox && $cropper.cropper('setCropBoxData', {
                        left: parseFloat(fullImage.cropBox.left),
                        top: parseFloat(fullImage.cropBox.top),
                        width: parseFloat(fullImage.cropBox.width),
                        height: parseFloat(fullImage.cropBox.height),
                    });

                    fullImage.cropBox && $cropper.cropper('setData', {
                        rotate: parseFloat(fullImage.cropBox.rotate),
                    });
                }
            });
        });
    }
}