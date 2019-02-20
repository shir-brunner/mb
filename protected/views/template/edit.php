<div class="wrapper-content">
    <div class="row">
        <div class="col-lg-7">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Template Info</h5>
                </div>
                <div class="ibox-content">
                    <div class="form-horizontal" id="edit-template-form">
                        <div class="form-group" field-name="template_name">
                            <label class="col-md-2 control-label">Name</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="name" value="<?php echo GxHtml::encode($template->template_name); ?>" />
                            </div>
                        </div>
                        <div class="form-group" field-name="template_description">
                            <label class="col-md-2 control-label">Description</label>
                            <div class="col-md-8">
                                <textarea class="form-control" id="description" style="height: 150px;"><?php echo GxHtml::encode($template->template_description); ?></textarea>
                            </div>
                        </div>
                        <div class="form-group" field-name="template_price">
                            <label class="col-md-2 control-label">Price</label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="number" class="form-control" id="price" value="<?php echo GxHtml::encode($template->template_price); ?>" />
                                    <div class="input-group-addon"><i class="fa fa-shekel"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" field-name="file_id">
                            <label class="col-md-2 control-label">Document</label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <div class="form-control">
                                        <span id="file-name" file-id="<?php echo $template->file_id; ?>"><?php echo $template->file ? ('<i class="glyphicon glyphicon-file"></i> ' . GxHtml::encode($template->file->file_name)) : ''; ?></span>
                                    </div>
                                    <span class="input-group-addon btn btn-default" id="select-file-button">
                                        Select File
                                    </span>
                                    <span class="input-group-addon btn btn-default <?php echo $template->file ? '' : 'hidden'; ?>" id="download-file-button" tooltip title="Download">
                                        <i class="fa fa-download"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" field-name="template_published">
                            <label class="col-md-2 control-label"></label>
                            <div class="col-md-8">
                                <div class="checkbox checkbox-primary">
                                    <input type="checkbox" id="published" <?php echo $template->template_published ? 'checked' : ''; ?> />
                                    <label for="published">Published</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ibox-footer">
                    <div class="btn btn-primary btn-sm pull-right" id="template-save-button"><i class="fa fa-check"></i> Save</div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Fields</h5>
                </div>
                <div class="ibox-content">
                    <table id="fields" class="table table-striped m-b-none">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Step</th>
                            <th>Update</th>
                            <th>Sort</th>
                            <th>Delete</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="ibox-footer">
                    <div class="btn btn-success btn-sm pull-left" id="create-field-button"><i class="fa fa-plus"></i> New Field</div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Image</h5>
                </div>
                <div class="ibox-content" style="min-height: 200px;">
                    <img id="template-image" class="<?php echo $template->template_image_url ? '' : 'hidden'; ?>" src="<?php echo $template->template_image_url ? $template->template_image_url : ''; ?>" />
                </div>
                <div class="ibox-footer">
                    <div class="btn btn-success btn-sm pull-left" id="upload-image-button"><i class="fa fa-upload"></i> Upload Image</div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="ibox">
                        <div class="ibox-title">
                            <h5>Icon</h5>
                        </div>
                        <div class="ibox-content" style="min-height: 200px;">
                            <img id="template-icon" class="<?php echo $template->template_icon_url ? '' : 'hidden'; ?>" src="<?php echo $template->template_icon_url ? $template->template_icon_url : ''; ?>" />
                        </div>
                        <div class="ibox-footer">
                            <div class="btn btn-success btn-sm pull-left" id="upload-icon-button"><i class="fa fa-upload"></i> Upload Image</div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="ibox">
                        <div class="ibox-title">
                            <h5>Hover Icon</h5>
                        </div>
                        <div class="ibox-content" style="min-height: 200px;">
                            <img id="template-hover-icon" class="<?php echo $template->template_hover_icon_url ? '' : 'hidden'; ?>" src="<?php echo $template->template_hover_icon_url ? $template->template_hover_icon_url : ''; ?>" />
                        </div>
                        <div class="ibox-footer">
                            <div class="btn btn-success btn-sm pull-left" id="upload-hover-icon-button"><i class="fa fa-upload"></i> Upload Image</div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ibox hidden" id="validation-ibox">
                <div class="ibox-title">
                    <h5>Validation Warnings</h5>
                </div>
                <div class="ibox-content">
                    <ul class="list-group clear-list m-b-none" id="validation-warnings"></ul>
                </div>
            </div>
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Manage Discounts</h5>
                </div>
                <div class="ibox-content">
                    <table class="table table-bordered table-striped table-hover m-b-none" id="discounts">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Percent</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="ibox-footer">
                    <div class="btn btn-success btn-sm pull-left" id="new-discount-button"><i class="fa fa-plus"></i> New Discount</div>
                    <div class="btn btn-primary btn-sm pull-right" id="save-discounts-button"><i class="fa fa-check"></i> Save Discounts</div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        var extraInfo = <?php echo empty($template->template_extra_info) ? '{}' : $template->template_extra_info; ?>;

        var $editTemplateForm = $('#edit-template-form');
        var $templateSaveButton = $('#template-save-button');
        var $name = $('#name');
        var $selectFileButton = $('#select-file-button');
        var $downloadFileButton = $('#download-file-button');
        var $fileName = $('#file-name');
        var $description = $('#description');
        var $price = $('#price');
        var $published = $('#published');
        var $createFieldButton = $('#create-field-button');

        var $uploadImageButton = $('#upload-image-button');
        var $templateImage = $('#template-image');

        var $uploadIconButton = $('#upload-icon-button');
        var $templateIcon = $('#template-icon');

        var $uploadHoverIconButton = $('#upload-hover-icon-button');
        var $templateHoverIcon = $('#template-hover-icon');

        var $discounts = $('#discounts');
        var $discountsTbody = $discounts.find('tbody');
        var $newDiscountButton = $('#new-discount-button');
        var $saveDiscountsButton = $('#save-discounts-button');
        var $validationIbox = $('#validation-ibox');
        var $validationWarnings = $('#validation-warnings');

        var fields = <?php echo json_encode(array_map(function($field) { return $field->toArray(); }, $template->fields)); ?>;

        var $fields = $('#fields');
        var $fieldsTbody = $fields.find('tbody');
        var updateOrdersTimeout = null;

        var maxOrder = fields.map(function(field) {
            return field.order;
        }).max();

        fields = fields.sort(function(a, b) { return (a.order || maxOrder) - (b.order || maxOrder); });

        $.each(fields, function(key, field) {
            $fieldsTbody.append(formatField(field));
        });

        function formatField(field)
        {
            var html = '';

            html += '<tr field-id="' + field.id + '">';
            html += '   <td>' + htmlEncode(field.name) + '</td>';
            html += '   <td>' + htmlEncode(field.step) + '</td>';

            html += '   <td>';
            html += '       <div class="btn btn-xs btn-success update-button" tooltip title="Update Field"><i class="fa fa-pencil"></i></div>';

            if(field.type.id == <?php echo FieldType::SELECT; ?> ||
                field.type.id == <?php echo FieldType::CHOICE; ?> ||
                field.type.id == <?php echo FieldType::CHECK_BOX; ?> ||
                field.type.id == <?php echo FieldType::NUMBER; ?>)
            {
                html += '   <div class="btn btn-xs btn-success options-button" tooltip title="Edit Options"><i class="fa fa-cog"></i></div>';
            }

            html += '   </td>';

            html += '   <td>';
            html += '       <div class="down-button btn btn-xs btn-primary"><i class="fa fa-chevron-down cp"></i></div>';
            html += '       <div class="up-button btn btn-xs btn-primary"><i class="fa fa-chevron-up cp"></i></div>';
            html += '   </td>';
            html += '   <td><div class="btn btn-xs btn-danger delete-button"><i class="fa fa-trash"></i></div></td>';
            html += '</tr>';

            var $field = $(html);
            var $deleteButton = $field.find('.delete-button');
            $deleteButton.on('click', function() {
                $field.remove();
                validateTemplate();

                apiRequest(config.api.urls.field.delete, 'POST', {
                    field_id: field.id
                });
            });

            var $upButton = $field.find('.up-button');
            $upButton.on('click', function() {
                $field.prev().before($field);
                updateFieldOrders();
            });

            var $downButton = $field.find('.down-button');
            $downButton.on('click', function() {
                $field.next().after($field);
                updateFieldOrders();
            });

            var $optionsButton = $field.find('.options-button');
            $optionsButton.on('click', function() {
                if(field.type.id == <?php echo FieldType::SELECT; ?> || field.type.id == <?php echo FieldType::CHOICE; ?>)
                {
                    editOptions($field, field);
                }
                else if(field.type.id == <?php echo FieldType::NUMBER; ?>)
                {
                    editNumberOptions($field, field);
                }
                else if(field.type.id == <?php echo FieldType::CHECK_BOX; ?>)
                {
                    editCheckBoxOptions($field, field);
                }
            });

            var $updateButton = $field.find('.update-button');
            $updateButton.on('click', function() {
                editField(field, $field);
            });

            $field.data('field', field);

            return $field;
        }

        function updateFieldOrders()
        {
            clearTimeout(updateOrdersTimeout);
            updateOrdersTimeout = setTimeout(function() {
                var orders = {};
                var order = 1;

                $fields.find('tr').each(function() {
                    orders[$(this).attr('field-id')] = order++;
                });

                apiRequest(config.api.urls.field.changeOrder, 'POST', {
                    orders: orders
                });
            }, 1000);
        }

        $templateSaveButton.on('click', function() {
            if($templateSaveButton.hasClass('disabled'))
            {
                return;
            }

            $templateSaveButton.addClass('disabled');
            $templateSaveButton.find('i').addClass('fa-spinner fa-spin').removeClass('fa-check');

            $editTemplateForm.find('.form-group').removeClass('has-error');

            apiRequest(config.api.urls.template.update, 'POST', {
                template_id: '<?php echo $template->getPrimaryKey(); ?>',
                name: $name.val(),
                description: $description.val(),
                price: $price.val(),
                published: $published.is(':checked') ? 1 : 0,
                file_id: $fileName.attr('file-id'),
            }, function(response) {
                if(response.status == 'OK')
                {
                    $templateSaveButton.removeClass('btn-warning').addClass('btn-primary');
                }
                else if(response.status == 'ERROR')
                {
                    $.each(response.body.errors, function(key, error) {
                        $editTemplateForm.find('.form-group[field-name="' + key + '"]').addClass('has-error');
                        showToast('error', error);
                    });
                }
                else
                {
                    defaultErrorHandler();
                }

                $templateSaveButton.find('i').removeClass('fa-spinner fa-spin').addClass('fa-check');
                $templateSaveButton.removeClass('disabled');
            });
        });

        $editTemplateForm.find(':input').on('input', function() {
            $templateSaveButton.addClass('btn-warning').removeClass('btn-primary');
        });

        $createFieldButton.on('click', function() {
            editField();
        });

        function editField(field, $field)
        {
            var $modal = modal(field ? 'Update Field' : 'New Field');
            var $modalBody = $modal.find('.modal-body').empty();
            var $modalFooter = $modal.find('.modal-footer');

            var html = '';

            html += '<div class="form-horizontal">';
            html += '   <div class="form-group" field-name="field_name">';
            html += '       <div class="col-xs-3 text-right">';
            html += '           <label class="control-label">Name</label>';
            html += '       </div>';
            html += '       <div class="col-xs-8">';
            html += '           <input type="text" class="form-control name" />';
            html += '       </div>';
            html += '   </div>';
            html += '   <div class="form-group" field-name="field_type_id">';
            html += '       <div class="col-xs-3 text-right">';
            html += '           <label class="control-label">Type</label>';
            html += '       </div>';
            html += '       <div class="col-xs-8">';
            html += '           <select class="form-control type">';

            <?php
                foreach(FieldType::model()->findAll() as $fieldType)
                {
                    echo "html += '<option value=\"" . $fieldType->getPrimaryKey() . "\">" . $fieldType->field_type_name . "</option>';";
                }
            ?>

            html += '           </select>';
            html += '       </div>';
            html += '   </div>';
            html += '   <div class="form-group" field-name="field_help_text">';
            html += '       <div class="col-xs-3 text-right">';
            html += '           <label class="control-label">Help Text</label>';
            html += '       </div>';
            html += '       <div class="col-xs-8">';
            html += '           <input type="text" class="form-control help-text" />';
            html += '       </div>';
            html += '   </div>';
            html += '   <div class="form-group" field-name="field_step">';
            html += '       <div class="col-xs-3 text-right">';
            html += '           <label class="control-label">Step</label>';
            html += '       </div>';
            html += '       <div class="col-xs-8">';
            html += '           <input type="text" class="form-control step" />';
            html += '       </div>';
            html += '   </div>';
            html += '   <div class="form-group">';
            html += '       <div class="col-xs-3 text-right">';
            html += '           <label class="control-label"></label>';
            html += '       </div>';
            html += '       <div class="col-xs-8">';
            html += '           <div class="checkbox checkbox-primary">';
            html += '               <input type="checkbox" class="required" id="required" />';
            html += '               <label for="required">Required</label>';
            html += '           </div>';
            html += '       </div>';
            html += '   </div>';
            html += '</div>';

            $modalBody.append(html);

            var $name = $modalBody.find('.name');
            var $type = $modalBody.find('.type');
            var $helpText = $modalBody.find('.help-text');
            var $required = $modalBody.find('.required');
            var $step = $modalBody.find('.step');

            if(field)
            {
                $name.val(field.name);
                $type.val(field.type.id);
                $helpText.val(field.helpText);
                $step.val(field.step);
                field.required && $required.prop('checked', true);
            }

            $type.on('change', function() {
                if($type.val() == <?php echo FieldType::USER_EMAIL; ?> ||
                    $type.val() == <?php echo FieldType::USER_FIRST_NAME; ?> ||
                    $type.val() == <?php echo FieldType::USER_LAST_NAME; ?>)
                {
                    $required.prop('checked', true);
                    $required.attr('disabled', 'disabled');
                }
                else
                {
                    $required.removeAttr('disabled');
                }
            }).trigger('change');

            var createButtonText = '<i class="fa fa-check"></i> ' + (field ? 'Save' : 'Create');
            var $createButton = $('<button type="button" class="btn btn-primary">' + createButtonText + '</button>').on('click', function() {
                if($createButton.hasClass('disabled'))
                {
                    return;
                }

                $createButton.addClass('disabled').html('<i class="fa fa-spin fa-spinner"></i> Create');
                $modalBody.find('.form-group').removeClass('has-error');

                apiRequest(config.api.urls.field[field ? 'update' : 'create'], 'POST', {
                    field_id: field ? field.id : null,
                    name: $name.val(),
                    field_type_id: $type.val(),
                    help_text: $helpText.val(),
                    required: $required.is(':checked') ? 1 : 0,
                    step: $step.val(),
                    template_id: '<?php echo $template->getPrimaryKey(); ?>',
                }, function(response) {
                    if(response.status == 'ERROR')
                    {
                        $.each(response.body.errors, function(key, error) {
                            showToast('error', error);
                            $modalBody.find('.form-group[field-name="' + key + '"]').addClass('has-error');
                        });

                        $createButton.removeClass('disabled').html(createButtonText);
                    }
                    else if(response.status == 'OK')
                    {
                        $modal.modal('hide');

                        if($field)
                        {
                            $field.before(formatField(response.body.field)).remove();
                        }
                        else
                        {
                            $fields.append(formatField(response.body.field));
                        }

                        validateTemplate();
                    }
                    else
                    {
                        defaultErrorHandler();
                    }
                });
            }).appendTo($modalFooter);

            $modal.on('shown.bs.modal', function() {
                !field && $name.focus();
            });

            $modal.modal();
        }

        function editOptions($field, field)
        {
            var $modal = modal('Edit Options');
            var $modalBody = $modal.find('.modal-body').empty();
            var $modalFooter = $modal.find('.modal-footer');

            var html = '';
            html += '<div class="form-horizontal options" style="min-height: 300px;"></div>';
            $modalBody.append(html);

            var $options = $modalBody.find('.options');

            var saveButtonText = '<i class="fa fa-check"></i> Save';
            var $saveButton = $('<button type="button" class="btn btn-primary">' + saveButtonText + '</button>').on('click', function() {
                if($saveButton.hasClass('disabled'))
                {
                    return;
                }

                $saveButton.addClass('disabled').html('<i class="fa fa-spin fa-spinner"></i> Save');
                $modalBody.find('.form-group').removeClass('has-error');

                apiRequest(config.api.urls.field.updateExtraInfo, 'POST', {
                    field_id: field.id,
                    key: 'options',
                    value: $options.find('.option').map(function() {
                        return $(this).find('input').val();
                    }).get().filter(function(val) { return val; })
                }, function(response) {
                    if(response.status == 'OK')
                    {
                        $modal.modal('hide');
                        $field.before(formatField(response.body.field)).remove();
                    }
                    else
                    {
                        defaultErrorHandler();
                    }
                });
            }).appendTo($modalFooter);

            $('<div class="btn btn-success pull-left"><i class="fa fa-plus"></i> New Option</div>').on('click', function() {
                $options.append(formatOption());
            }).appendTo($modalFooter);

            $modal.modal();
        }

        function formatOption(option)
        {
            var html = '';

            html += '   <div class="form-group option">';
            html += '       <div class="col-xs-12">';
            html += '           <div class="input-group">';
            html += '               <input type="text" class="form-control" value="' + htmlEncode(option) + '" />';
            html += '               <div class="input-group-btn">';
            html += '                   <div class="btn btn-danger delete-button"><i class="fa fa-trash"></i></div>';
            html += '               </div>';
            html += '           </div>'
            html += '       </div>';
            html += '   </div>';

            var $option = $(html);
            $option.on('click', '.delete-button', function() {
                $option.remove();
            });

            return $option;
        }

        function editNumberOptions($field, field)
        {
            var $modal = modal('Number Options');
            var $modalBody = $modal.find('.modal-body').empty();
            var $modalFooter = $modal.find('.modal-footer');

            var html = '';

            html += '<div class="form-horizontal">';
            html += '   <div class="form-group">';
            html += '       <div class="col-xs-3 text-right">';
            html += '           <label class="control-label">Min</label>';
            html += '       </div>';
            html += '       <div class="col-xs-8">';
            html += '           <input type="number" class="form-control min" value="' + (field.extraInfo && field.extraInfo.range ? field.extraInfo.range.min : '') + '" />';
            html += '       </div>';
            html += '   </div>';
            html += '   <div class="form-group">';
            html += '       <div class="col-xs-3 text-right">';
            html += '           <label class="control-label">Max</label>';
            html += '       </div>';
            html += '       <div class="col-xs-8">';
            html += '           <input type="number" class="form-control max" value="' + (field.extraInfo && field.extraInfo.range ? field.extraInfo.range.max : '') + '" />';
            html += '       </div>';
            html += '   </div>';
            html += '</div>';

            $modalBody.append(html);

            var $min = $modalBody.find('.min');
            var $max = $modalBody.find('.max');

            var saveButtonText = '<i class="fa fa-check"></i> Save';
            var $saveButton = $('<button type="button" class="btn btn-primary">' + saveButtonText + '</button>').on('click', function() {
                if($saveButton.hasClass('disabled'))
                {
                    return;
                }

                $saveButton.addClass('disabled').html('<i class="fa fa-spin fa-spinner"></i> Save');
                $modalBody.find('.form-group').removeClass('has-error');

                apiRequest(config.api.urls.field.updateExtraInfoBatch, 'POST', {
                    field_id: field.id,
                    batch: [
                        { key: 'range', value: { min: $min.val(), max: $max.val() } },
                    ]
                }, function(response) {
                    if(response.status == 'OK')
                    {
                        $modal.modal('hide');
                        $field.before(formatField(response.body.field)).remove();
                        validateTemplate();
                    }
                    else
                    {
                        defaultErrorHandler();
                    }
                });
            }).appendTo($modalFooter);

            $modal.modal();
        }

        $selectFileButton.on('click', function() {
            fileBrowser.browse(function($file) {
                if(!$file)
                {
                    return;
                }

                $templateSaveButton.addClass('disabled');

                uploader.uploadFile($file, function(file) {
                    $fileName.html('<i class="glyphicon glyphicon-file"></i> ' + htmlEncode(file.name));
                    $fileName.attr('file-id', file.id);
                    $selectFileButton.html('Select File');
                    $templateSaveButton.removeClass('disabled');
                }, function(errors) {
                    $selectFileButton.html('Select File');
                    $templateSaveButton.removeClass('disabled');
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

        extraInfo.discounts && $.each(extraInfo.discounts, function(key, discount) {
            $discountsTbody.append(formatDiscount(discount));
        });

        $newDiscountButton.on('click', function() {
            $discountsTbody.append(formatDiscount());
        });

        $saveDiscountsButton.on('click', function() {
            if($saveDiscountsButton.hasClass('disabled'))
            {
                return;
            }

            var valid = true;
            var discounts = $discountsTbody.find('.discount').map(function() {
                return {
                    code: $(this).find('.code').val(),
                    percent: $(this).find('.percent').val(),
                };
            }).get().filter(function(discount) {
                if(isNaN(discount.percent))
                {
                    showToast('error', 'Discount Percent must be numeric');
                    valid = false;
                    return false;
                }
                else if(parseInt(discount.percent) < 1)
                {
                    showToast('error', 'Discount Percent must be greater than 0');
                    valid = false;
                    return false;
                }
                else if(parseInt(discount.percent) > 99)
                {
                    showToast('error', 'Discount Percent must be less than 100');
                    valid = false;
                    return false;
                }

                return discount.code && discount.percent;
            });

            if(!valid)
            {
                return;
            }

            $saveDiscountsButton.addClass('disabled');
            $saveDiscountsButton.find('i').addClass('fa-spin fa-spinner').removeClass('fa-check');

            apiRequest(config.api.urls.template.updateExtraInfo, 'POST', {
                template_id: '<?php echo $template->getPrimaryKey(); ?>',
                key: 'discounts',
                value: discounts,
            }, function(response) {
                $saveDiscountsButton.removeClass('disabled');
                $saveDiscountsButton.find('i').addClass('fa-check').removeClass('fa-spin fa-spinner');
            });
        });

        function formatDiscount(discount)
        {
            var html = '';

            html += '<tr class="discount">';
            html += '   <td><input type="text" class="form-control code" value="' + (discount ? htmlEncode(discount.code) : '') + '" /></td>';
            html += '   <td>';
            html += '       <div class="input-group">';
            html += '           <input type="number" class="form-control percent" value="' + (discount ? htmlEncode(discount.percent) : '') + '" max="100" min="0" />';
            html += '           <div class="input-group-addon"><i class="fa fa-percent"></i></div>';
            html += '       </div>';
            html += '   </td>';
            html += '   <td class="text-center"><div class="btn btn-danger delete-button" tooltip title="Delete Discount"><i class="fa fa-remove"></i></div></td>';
            html += '</tr>';

            var $discount = $(html);
            var $deleteButton = $discount.find('.delete-button');
            $deleteButton.on('click', function() {
                $deleteButton.tooltip('hide');
                $discount.remove();
            });

            return $discount;
        }

        function editCheckBoxOptions($field, field)
        {
            var $modal = modal('Check Box Options');
            var $modalBody = $modal.find('.modal-body').empty();
            var $modalFooter = $modal.find('.modal-footer');

            var html = '';

            html += '<div class="form-horizontal">';
            html += '   <div class="form-group">';
            html += '       <div class="col-xs-3 text-right">';
            html += '           <label class="control-label">Checked Value</label>';
            html += '       </div>';
            html += '       <div class="col-xs-8">';
            html += '           <input type="number" class="form-control checked-value" value="' + (field.extraInfo ? htmlEncode(field.extraInfo.checkedValue) : '') + '" />';
            html += '       </div>';
            html += '   </div>';
            html += '   <div class="form-group">';
            html += '       <div class="col-xs-3 text-right">';
            html += '           <label class="control-label">Unchecked Value</label>';
            html += '       </div>';
            html += '       <div class="col-xs-8">';
            html += '           <input type="number" class="form-control unchecked-value" value="' + (field.extraInfo ? htmlEncode(field.extraInfo.uncheckedValue) : '') + '" />';
            html += '       </div>';
            html += '   </div>';
            html += '   <div class="form-group">';
            html += '       <div class="col-xs-3 text-right">';
            html += '           <label class="control-label">Charge Amount</label>';
            html += '       </div>';
            html += '       <div class="col-xs-8">';
            html += '           <div class="input-group">';
            html += '               <input type="number" class="form-control charge-amount" value="' + (field.extraInfo && field.extraInfo.chargeAmount ? field.extraInfo.chargeAmount : '') + '" />';
            html += '               <div class="input-group-addon"><i class="fa fa-shekel"></i></div>';
            html += '           </div>';
            html += '           <div class="help-block"><i class="fa fa-info-circle"></i> Charge this amount when this check box is selected</div>';
            html += '       </div>';
            html += '   </div>';
            html += '   <hr />';
            html += '   <div class="form-group">';
            html += '       <div class="col-xs-3 text-right">';
            html += '           <label class="control-label"></label>';
            html += '       </div>';
            html += '       <div class="col-xs-8">';
            html += '           <div class="checkbox checkbox-primary">';
            html += '               <input type="checkbox" class="add-fee" id="add-fee" />';
            html += '               <label for="add-fee">Charge fee when this check box is selected</label>';
            html += '           </div>';
            html += '       </div>';
            html += '   </div>';
            html += '   <div class="fee-container">';
            html += '       <div class="form-group">';
            html += '           <div class="col-xs-3 text-right">';
            html += '               <label class="control-label">Fee Type</label>';
            html += '           </div>';
            html += '           <div class="col-xs-8">';
            html += '               <select class="form-control fee-type">';
            html += '                   <option value="percent">Percent</option>"';
            html += '                   <option value="fixed">Fixed</option>"';
            html += '               </select>';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="form-group">';
            html += '           <div class="col-xs-3 text-right">';
            html += '               <label class="control-label">Fee Amount</label>';
            html += '           </div>';
            html += '           <div class="col-xs-8">';
            html += '               <div class="input-group">';
            html += '                   <input type="number" class="form-control fee-amount" value="' + (field.extraInfo && field.extraInfo.feeAmount ? field.extraInfo.feeAmount : '') + '" />';
            html += '                   <div class="input-group-addon"><i class="fa fee-type-icon"></i></div>';
            html += '               </div>';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="fee-fields-container">';
            html += '           <div class="form-group">';
            html += '               <div class="col-xs-3 text-right">';
            html += '                   <label class="control-label">Fee Fields</label>';
            html += '               </div>';
            html += '               <div class="col-xs-8">';
            html += '                   <div class="fee-fields"></div>';
            html += '               </div>';
            html += '           </div>';
            html += '       </div>';
            html += '   </div>';
            html += '</div>';

            $modalBody.append(html);

            var $checkedValue = $modalBody.find('.checked-value');
            var $uncheckedValue = $modalBody.find('.unchecked-value');
            var $chargeAmount = $modalBody.find('.charge-amount');
            var $feeContainer = $modalBody.find('.fee-container').hide();
            var $addFee = $modalBody.find('.add-fee');
            var $feeType = $modalBody.find('.fee-type');
            var $feeTypeIcon = $modalBody.find('.fee-type-icon');
            var $feeAmount = $modalBody.find('.fee-amount');
            var $feeFieldsContainer = $modalBody.find('.fee-fields-container').hide();
            var $feeFields = $modalBody.find('.fee-fields');

            if(field.extraInfo)
            {
                field.extraInfo.addFee && $feeContainer.show() && $addFee.prop('checked', true);
                $feeType.val(field.extraInfo.feeType || 'percent');

                if(field.extraInfo.feeType == 'fixed')
                {
                    $feeTypeIcon.addClass('fa-shekel').removeClass('fa-percent');
                }
                else
                {
                    $feeTypeIcon.addClass('fa-percent').removeClass('fa-shekel');
                    $feeFieldsContainer.show();
                }

                $chargeAmount.val(field.extraInfo.chargeAmount || '');
                $feeAmount.val(field.extraInfo.feeAmount || '');
            }

            $('#fields tbody tr').each(function() {
                var otherField = $(this).data('field');
                if(otherField.type.id == <?php echo FieldType::NUMBER; ?>)
                {
                    var html = '';
                    var checked = field.extraInfo && field.extraInfo.feeFields && field.extraInfo.feeFields.contains(otherField.name);

                    html += '<div class="checkbox checkbox-success">';
                    html += '   <input type="checkbox" ' + (checked ? 'checked="checked"' : '') + ' id="fee-field-' + otherField.id + '" />';
                    html += '   <label for="fee-field-' + otherField.id + '">' + htmlEncode(otherField.name) + '</label>';
                    html += '</div>';

                    $feeFields.append(html);
                }
            });

            $addFee.on('change', function() {
                $addFee.is(':checked') ? $feeContainer.slideDown() : $feeContainer.slideUp();
            });

            $feeType.on('change', function() {
                if($feeType.val() == 'fixed')
                {
                    $feeTypeIcon.addClass('fa-shekel').removeClass('fa-percent');
                    $feeFieldsContainer.slideUp();
                }
                else
                {
                    $feeTypeIcon.addClass('fa-percent').removeClass('fa-shekel');
                    $feeFieldsContainer.slideDown();
                }
            });

            var saveButtonText = '<i class="fa fa-check"></i> Save';
            var $saveButton = $('<button type="button" class="btn btn-primary">' + saveButtonText + '</button>').on('click', function() {
                if($saveButton.hasClass('disabled'))
                {
                    return;
                }

                $saveButton.addClass('disabled').html('<i class="fa fa-spin fa-spinner"></i> Save');

                var feeFields = [];

                $feeFields.find('input[type="checkbox"]').each(function() {
                    var $checkbox = $(this);
                    if($checkbox.is(':checked'))
                    {
                        var $label = $feeFields.find('label[for="' + $checkbox.attr('id') + '"]');
                        feeFields.push($label.text());
                    }
                });

                apiRequest(config.api.urls.field.updateExtraInfoBatch, 'POST', {
                    field_id: field.id,
                    batch: [
                        { key: 'chargeAmount', value: $chargeAmount.val() },
                        { key: 'addFee', value: $addFee.is(':checked') ? 1 : 0 },
                        { key: 'feeType', value: $feeType.val() },
                        { key: 'feeAmount', value: $feeAmount.val() },
                        { key: 'checkedValue', value: $checkedValue.val() },
                        { key: 'uncheckedValue', value: $uncheckedValue.val() },
                        { key: 'feeFields', value: feeFields },
                    ],
                }, function(response) {
                    if(response.status == 'OK')
                    {
                        $modal.modal('hide');
                        $field.before(formatField(response.body.field)).remove();
                        validateTemplate();
                    }
                    else
                    {
                        defaultErrorHandler();
                    }
                });
            }).appendTo($modalFooter);

            $modal.modal();
        }

        validateTemplate();
        function validateTemplate()
        {
            $validationWarnings.empty();

            var fields = $('#fields tr').map(function() {
                return $(this).data('field');
            }).get();

            var warnings = [];

            !$templateImage.attr('src') && warnings.push('No image was uploaded.');
            !$templateIcon.attr('src') && warnings.push('No icon was uploaded.');
            !$templateHoverIcon.attr('src') && warnings.push('No hover icon was uploaded.');

            var userEmailFields = fields.filter(function(field) { return field.type.id == <?php echo FieldType::USER_EMAIL; ?>; });
            if(userEmailFields.length == 0)
            {
                warnings.push('User Email field is not defined.');
            }
            else if(userEmailFields.length > 1)
            {
                warnings.push('Only 1 User Email field is effective (found ' + userEmailFields.length + ').');
            }

            var userFirstNameFields = fields.filter(function(field) { return field.type.id == <?php echo FieldType::USER_FIRST_NAME; ?>; });
            if(userFirstNameFields.length == 0)
            {
                warnings.push('User First Name field is not defined.');
            }
            else if(userFirstNameFields.length > 1)
            {
                warnings.push('Only 1 User First Name field is effective (found ' + userFirstNameFields.length + ').');
            }

            var userLastNameFields = fields.filter(function(field) { return field.type.id == <?php echo FieldType::USER_LAST_NAME; ?>; });
            if(userLastNameFields.length == 0)
            {
                warnings.push('User Last Name field is not defined.');
            }
            else if(userLastNameFields.length > 1)
            {
                warnings.push('Only 1 User Last Name field is effective (found ' + userLastNameFields.length + ').');
            }

            var fieldsWithStep = fields.filter(function(field) { return field.step; });
            var fieldsWithoutStep = fields.filter(function(field) { return !field.step; });

            if(fieldsWithStep.length && fieldsWithoutStep.length)
            {
                warnings.push('Some fields does not have steps (' + fieldsWithoutStep.length + ')');
            }

            var c = 1;
            $.each(warnings, function(key, warning) {
                var html = '';

                html += '<li class="list-group-item ' + (c == 1 ? 'fist-item' : '') + '">';
                html += '   <i class="fa fa-exclamation-triangle text-warning"></i> ' + htmlEncode(warning);
                html += '</li>';

                c++;
                $validationWarnings.append(html);
            });

            warnings.length == 0 ? $validationIbox.addClass('hidden') : $validationIbox.removeClass('hidden');
        }

        $uploadImageButton.on('click', function() {
            if($uploadImageButton.hasClass('disabled'))
            {
                return;
            }

            imageBrowser.fromGallery(function($file) {
                if(!$file)
                {
                    return;
                }

                $uploadImageButton.addClass('disabled');

                uploader.uploadImage($file, function(image) {
                    apiRequest(config.api.urls.template.update, 'POST', {
                        template_id: '<?php echo $template->getPrimaryKey(); ?>',
                        image_url: image.url,
                    }, function(response) {
                        if(response.status == 'OK')
                        {
                            $uploadImageButton.html('<i class="fa fa-upload"></i> Upload Image').removeClass('disabled');
                            $templateImage.attr('src', image.url).removeClass('hidden');
                        }
                        else if(response.status == 'ERROR')
                        {
                            $.each(response.body.errors, function(key, error) {
                                showToast('error', error);
                            });
                        }
                        else
                        {
                            defaultErrorHandler();
                        }

                        $uploadImageButton.html('<i class="fa fa-upload"></i> Upload Image').removeClass('disabled');
                        validateTemplate();
                    });
                }, function(errors) {
                    $uploadImageButton.html('<i class="fa fa-upload"></i> Upload Image').removeClass('disabled');
                    $.each(errors, function(key, error) {
                        showToast('error', error);
                    });
                    validateTemplate();
                }, function(percentage) {
                    $uploadImageButton.html('<i class="fa fa-spin fa-spinner"></i> Uploading... (' + percentage + '%)');
                });
            });
        });

        $uploadIconButton.on('click', function() {
            if($uploadIconButton.hasClass('disabled'))
            {
                return;
            }

            imageBrowser.fromGallery(function($file) {
                if(!$file)
                {
                    return;
                }

                $uploadIconButton.addClass('disabled');

                uploader.uploadImage($file, function(image) {
                    apiRequest(config.api.urls.template.update, 'POST', {
                        template_id: '<?php echo $template->getPrimaryKey(); ?>',
                        icon_url: image.url,
                    }, function(response) {
                        if(response.status == 'OK')
                        {
                            $uploadIconButton.html('<i class="fa fa-upload"></i> Upload Image').removeClass('disabled');
                            $templateIcon.attr('src', image.url).removeClass('hidden');
                        }
                        else if(response.status == 'ERROR')
                        {
                            $.each(response.body.errors, function(key, error) {
                                showToast('error', error);
                            });
                        }
                        else
                        {
                            defaultErrorHandler();
                        }

                        $uploadIconButton.html('<i class="fa fa-upload"></i> Upload Image').removeClass('disabled');
                        validateTemplate();
                    });
                }, function(errors) {
                    $uploadIconButton.html('<i class="fa fa-upload"></i> Upload Image').removeClass('disabled');
                    $.each(errors, function(key, error) {
                        showToast('error', error);
                    });
                    validateTemplate();
                }, function(percentage) {
                    $uploadIconButton.html('<i class="fa fa-spin fa-spinner"></i> Uploading... (' + percentage + '%)');
                });
            });
        });

        $uploadHoverIconButton.on('click', function() {
            if($uploadHoverIconButton.hasClass('disabled'))
            {
                return;
            }

            imageBrowser.fromGallery(function($file) {
                if(!$file)
                {
                    return;
                }

                $uploadHoverIconButton.addClass('disabled');

                uploader.uploadImage($file, function(image) {
                    apiRequest(config.api.urls.template.update, 'POST', {
                        template_id: '<?php echo $template->getPrimaryKey(); ?>',
                        hover_icon_url: image.url,
                    }, function(response) {
                        if(response.status == 'OK')
                        {
                            $uploadHoverIconButton.html('<i class="fa fa-upload"></i> Upload Image').removeClass('disabled');
                            $templateHoverIcon.attr('src', image.url).removeClass('hidden');
                        }
                        else if(response.status == 'ERROR')
                        {
                            $.each(response.body.errors, function(key, error) {
                                showToast('error', error);
                            });
                        }
                        else
                        {
                            defaultErrorHandler();
                        }

                        $uploadHoverIconButton.html('<i class="fa fa-upload"></i> Upload Image').removeClass('disabled');
                        validateTemplate();
                    });
                }, function(errors) {
                    $uploadHoverIconButton.html('<i class="fa fa-upload"></i> Upload Image').removeClass('disabled');
                    $.each(errors, function(key, error) {
                        showToast('error', error);
                    });
                    validateTemplate();
                }, function(percentage) {
                    $uploadHoverIconButton.html('<i class="fa fa-spin fa-spinner"></i> Uploading... (' + percentage + '%)');
                });
            });
        });
    });
</script>