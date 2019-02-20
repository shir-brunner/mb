<?php
    $createTime = DateTime::createFromFormat('Y-m-d H:i:s', $request->request_create_time);
    $extraInfo = $request->extraInfo();
?>
<div class="wrapper-content">
    <div class="row">
        <div class="col-lg-6">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Fields</h5>
                </div>

                <div class="ibox-content">
                    <div class="form-horizontal">
                        <?php

                        ?>
                    </div>
                </div>
                <div class="ibox-footer">
                    <div class="btn btn-sm btn-success pull-right export-button" format="docx" tooltip title="Export To Word"><i class="fa fa-file-word-o"></i></div>
                    <div class="btn btn-sm btn-danger pull-right export-button m-r-xs" format="pdf" tooltip title="Export To PDF"><i class="fa fa-file-pdf-o"></i></div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Request Info</h5>
                </div>
                <div class="ibox-content">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label class="col-md-2 control-label">User Name</label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input class="form-control" readonly value="<?php echo $request->user ? $request->user->getFullName() : ''; ?>" />
                                    <div class="input-group-addon gray-bg"><i class="fa fa-user"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">User Email</label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input class="form-control" readonly value="<?php echo $request->user ? $request->user->user_email : ''; ?>" />
                                    <div class="input-group-addon gray-bg"><i class="fa fa-envelope"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Product</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" readonly value="<?php echo GxHtml::encode($request->product->product_name); ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Created On</label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input class="form-control" readonly value="<?php echo $createTime->format('d/m/Y H:i:s'); ?>" />
                                    <div class="input-group-addon gray-bg"><i class="fa fa-clock-o"></i></div>
                                </div>
                            </div>
                        </div>
                        <?php if(isset($extraInfo['discount'])) { ?>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Discount Code</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" readonly value="<?php echo GxHtml::encode($extraInfo['discount']['code']); ?> (<?php echo $extraInfo['discount']['percent']; ?>%)" />
                                        <div class="input-group-addon gray-bg"><i class="fa fa-tag"></i></div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <hr />
                        <div class="form-group">
                            <label class="col-md-2 control-label">Status</label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <?php echo GxHtml::dropDownList('', $request->request_status_id, GxHtml::encodeEx(GxHtml::listDataEx(RequestStatus::model()->findAll())), array('class' => 'form-control', 'id' => 'status')); ?>
                                    <div class="input-group-btn">
                                        <div class="btn btn-primary" id="save-status-button"><i class="fa fa-check"></i> Save</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Document</label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <div class="form-control">
                                        <span id="file-name" file-id="<?php echo $request->file_id; ?>"><?php echo $request->file ? ('<i class="glyphicon glyphicon-file"></i> ' . GxHtml::encode($request->file->file_name)) : ''; ?></span>
                                    </div>
                                    <span class="input-group-addon btn btn-default" id="select-file-button">
                                        Select File
                                    </span>
                                    <span class="input-group-addon btn btn-default <?php echo $request->file ? '' : 'hidden'; ?>" id="download-file-button" tooltip title="Download">
                                        <i class="fa fa-download"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        var $selectFileButton = $('#select-file-button');
        var $downloadFileButton = $('#download-file-button');
        var $fileName = $('#file-name');
        var $saveStatusButton = $('#save-status-button');
        var $status = $('#status');

        $selectFileButton.on('click', function() {
            fileBrowser.browse(function($file) {
                if(!$file)
                {
                    return;
                }

                uploader.uploadFile($file, function(file) {
                    apiRequest(config.api.urls.request.update, 'POST', {
                        request_id: '<?php echo $request->getPrimaryKey(); ?>',
                        file_id: file.id,
                    }, function(response) {
                        $fileName.html('<i class="glyphicon glyphicon-file"></i> ' + htmlEncode(file.name));
                        $fileName.attr('file-id', file.id);
                        $selectFileButton.html('Select File');
                    });
                }, function(errors) {
                    $selectFileButton.html('Select File');
                }, function(percentage) {
                    $selectFileButton.html('Uploading... ' + percentage + '%');
                });
            });
        });

        $downloadFileButton.on('click', function() {
            var $form = $('<form method="POST" class="hidden" action="<?php echo Yii::app()->createUrl('api/file/download'); ?>"></form>');
            $form.append('<input type="hidden" name="file_id" value="' + $fileName.attr('file-id') + '" />');
            $form.append('<input type="hidden" name="login_token" value="' + auth.getUser().loginToken + '" />');
            $form.appendTo($('body')).submit();
        });

        $('.export-button').on('click', function() {
            var $form = $('<form method="POST" class="hidden" action="<?php echo Yii::app()->createUrl('api/request/preview'); ?>"></form>');
            $form.append('<input type="hidden" name="request_id" value="<?php echo $request->getPrimaryKey(); ?>" />');
            $form.append('<input type="hidden" name="login_token" value="' + auth.getUser().loginToken + '" />');
            $form.append('<input type="hidden" name="format" value="' + $(this).attr('format') + '" />');
            $form.appendTo($('body')).submit();
        });

        $('.field').on('input', function() {
            var $field = $(this);
            var field = JSON.parse($field.attr('field-data'));

            var $saveIcon = $field.parents('.form-group').find('.save-icon');

            $saveIcon.addClass('fa-spin fa-spinner').removeClass('fa-check fa-remove');

            clearTimeout($field.data('saveTimeout'));
            $field.data('saveTimeout', setTimeout(function() {
                var fields = {};
                fields[field.id] = $field.val();

                apiRequest(config.api.urls.request.updateFields, 'POST', {
                    request_hash: '<?php echo $request->request_hash; ?>',
                    fields: fields
                }, function(response) {
                    if(response.status == 'ERROR')
                    {
                        $.each(response.body.errors, function(key, error) {
                            showToast('error', error);
                        });

                        $saveIcon.addClass('fa-remove').removeClass('fa-spin fa-spinner fa-check');
                    }
                    else if(response.status == 'OK')
                    {
                        $saveIcon.addClass('fa-check').removeClass('fa-spin fa-spinner fa-remove');
                    }
                });
            }, 3000));
        });

        $('.checkbox-field').on('change', function() {
            var $field = $(this);
            var field = JSON.parse($field.attr('field-data'));
            var fields = {};
            fields[field.id] = $field.is(':checked') ? 1 : 0;

            apiRequest(config.api.urls.request.updateFields, 'POST', {
                request_hash: '<?php echo $request->request_hash; ?>',
                fields: fields
            }, function(response) {
                if(response.status == 'ERROR')
                {
                    $.each(response.body.errors, function(key, error) {
                        showToast('error', error);
                    });
                }
            });
        });

        $saveStatusButton.on('click', function() {
            if($saveStatusButton.hasClass('disabled'))
            {
                return;
            }

            $saveStatusButton.addClass('disabled');
            $saveStatusButton.find('i').addClass('fa-spin fa-spinner').removeClass('fa-check');

            apiRequest(config.api.urls.request.update, 'POST', {
                request_id: '<?php echo $request->getPrimaryKey(); ?>',
                request_status_id: $status.val(),
            }, function(response) {
                if(response.status == 'ERROR')
                {
                    $.each(response.body.errors, function(key, error) {
                        showToast('error', error);
                    });
                }

                $saveStatusButton.removeClass('disabled');
                $saveStatusButton.find('i').addClass('fa-check').removeClass('fa-spin fa-spinner');
            });
        });
    });
</script>