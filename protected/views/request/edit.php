<?php
    $createTime = DateTime::createFromFormat('Y-m-d H:i:s', $request->request_create_time);
    $extraInfo = $request->extraInfo();

    $steps = $request->product->productSteps;
    usort($steps, function($a, $b) {
        if($a->product_step_order == $b->product_step_order)
        {
            return 0;
        }

        return ($a->product_step_order < $b->product_step_order) ? -1 : 1;
    });

    $activeStep = reset($steps);
?>
<div class="wrapper-content">
    <div class="row">
        <div class="col-lg-6 text-center">
            <div class="ibox">
                <div class="ibox-title">
                    <h5 style="float: right;">המסמך</h5>
                    <i class="fa fa-file pull-left"></i>
                </div>
                <div class="ibox-content" id="document-ibox-content" style="overflow-y: auto;">
                    <textarea id="document"></textarea>
                </div>
                <div class="ibox-footer">
                    <div class="pull-left btn btn-danger btn-sm" id="document-restore-button">ברירת מחדל <i class="fa fa-refresh"></i></div>
                    <div class="pull-right btn btn-primary btn-sm" id="document-save-button">שמור <i class="fa fa-check"></i></div>
                    <div class="pull-right btn btn-danger btn-sm export-button m-r-xs" data-format="pdf" title="Export to PDF" tooltip><i class="fa fa-file-pdf-o"></i></div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="row">
                <div class="col-lg-4">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5 style="float: right;">נוצר בתאריך</h5>
                            <i class="fa fa-clock-o"></i>
                        </div>
                        <div class="ibox-content" dir="rtl">
                            <h1 class="no-margins"><?php echo $createTime->format('d/m/Y'); ?></h1>
                            <small>בשעה <?php echo $createTime->format('H:i:s'); ?></small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5 style="float: right;">סטטוס</h5>
                            <i class="fa fa-pencil cp" id="update-status-button" tooltip title="שינוי סטטוס"></i>
                        </div>
                        <div class="ibox-content" style="height: 87px;">
                            <h1 class="no-margins" id="status-title" dir="rtl"><?php echo GxHtml::encode($request->requestStatus->request_status_name); ?></h1>
                            <?php echo GxHtml::dropDownList('', $request->request_status_id, GxHtml::encodeEx(GxHtml::listDataEx(RequestStatus::model()->findAll())), array('class' => 'form-control hidden', 'id' => 'status')); ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5 style="float: right;">מוצר</h5>
                            <i class="fa fa-shopping-basket"></i>
                        </div>
                        <div class="ibox-content" dir="rtl" style="height: 87px;">
                            <h1 class="no-margins"><?php echo GxHtml::encode($request->product->product_name); ?></h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ibox">
                <div class="ibox-title">
                    <h5 id="step-progress" style="direction: rtl;"></h5>
                    <h5 id="step-name" style="float: right;"><?php echo GxHtml::encode($activeStep->product_step_name); ?></h5>
                </div>
                <div class="ibox-content" id="steps-ibox-content" style="overflow-y: scroll;">
                    <form id="form" class="text-right pull-right m-b-sm">
                        <div id="product-steps">
                            <?php
                            for($i = 0; $i < count($steps); $i++)
                            {
                                $step = $steps[$i];
                                $nextStep = isset($steps[$i + 1]) ? $steps[$i + 1] : null;
                                $prevStep = isset($steps[$i - 1]) ? $steps[$i - 1] : null;

                                $active = $step->getPrimaryKey() == $activeStep->getPrimaryKey();

                                echo '<div class="product-step ' . ($active ? 'active' : '') . '" data-step-id="' . $step->getPrimaryKey() . '" data-next-id="' . ($nextStep ? $nextStep->getPrimaryKey() : '') . '" data-prev-id="' . ($prevStep ? $prevStep->getPrimaryKey() : '') . '">';
                                $this->renderPartial($step->product_step_view);
                                echo '</div>';
                            }
                            ?>
                        </div>
                    </form>
                    <div class="clearfix"></div>
                </div>
                <div class="ibox-footer text-center">
                    <div class="pull-left btn btn-primary btn-sm" id="next-button"><i class="fa fa-chevron-left"></i></div>
                    <div class="pull-right btn btn-primary btn-sm" id="prev-button"><i class="fa fa-chevron-right"></i></div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="ibox">
                <div class="ibox-title">
                    <div class="btn btn-success btn-xs" id="add-files-button">הוספת קובץ <i class="fa fa-upload"></i></div>
                    <h5 class="pull-right">ניהול קבצים מצורפים</h5>
                </div>
                <div class="ibox-content" style="height: 225px; overflow-y: auto;">
                    <ul id="files" class="todo-list"></ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        var $statusTitle = $('#status-title');
        var $updateStatusButton = $('#update-status-button');
        var $status = $('#status');
        var $stepName = $('#step-name');
        var $productSteps = $('#product-steps');
        var $nextButton = $('#next-button');
        var $prevButton = $('#prev-button');
        var request = <?php echo $request ? json_encode($request->toArray()) : 'null'; ?>;
        var steps = <?php echo json_encode(array_map(function($step) { return $step->toArray(); }, $steps)); ?>;
        var defaultActiveStep = <?php echo json_encode($activeStep->toArray()); ?>;
        var $form = $('#form');
        var $stepProgress = $('#step-progress');
        var $stepsIboxContent = $('#steps-ibox-content');
        var $documentIboxContent = $('#document-ibox-content');
        var $documentSaveButton = $('#document-save-button');
        var $documentRestoreButton = $('#document-restore-button');
        var $document = $('#document');
        var $files = $('#files');
        var files = [];
        var $addFilesButton = $('#add-files-button');

        $(window).on('resize', onWindowResize);
        onWindowResize();
        function onWindowResize()
        {
            $stepsIboxContent.css('height', ($(window).height() - 705) + 'px');
            $documentIboxContent.css('height', ($(window).height() - 245) + 'px');
            $document.css('height', ($(window).height() - 400) + 'px');
        }

        tinymce.init({
            selector: "#document",
            plugins: [
                "advlist autolink link lists charmap hr anchor pagebreak spellchecker",
                "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                "save table contextmenu directionality template paste textcolor colorpicker"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor table",
            init_instance_callback: function (editor) {
                request.documentHtml && editor.setContent(request.documentHtml);

                $documentSaveButton.on('click', function() {
                    if($documentSaveButton.hasClass('disabled'))
                    {
                        return;
                    }

                    $documentSaveButton.addClass('disabled');

                    apiRequest(config.api.urls.request.update, 'POST', {
                        request_id: request.id,
                        document_html: editor.getContent(),
                    }, function(response) {
                        $documentSaveButton.removeClass('disabled');
                    });
                });

                $documentRestoreButton.on('click', function() {
                    if($documentRestoreButton.hasClass('disabled'))
                    {
                        return;
                    }

                    if(!confirm('Are you sure?'))
                    {
                        return;
                    }

                    $documentRestoreButton.addClass('disabled');

                    apiRequest(config.api.urls.request.restoreDocument, 'POST', {
                        request_id: request.id,
                    }, function(response) {
                        if(response.status == 'OK')
                        {
                            editor.setContent(response.body.request.documentHtml);
                        }

                        $documentRestoreButton.removeClass('disabled');
                    });
                });
            },
        });

        request && fillData($form, request.fields);

        $form.find('.recurring').each(function() {
            var $recurring = $(this);
            var items = request ? request.fields[$recurring.attr('data-name')] : [];

            items = items || [];
            $recurring.parents('.recurring-sample').length == 0 && setRecurring($recurring, items); //only top level recurring
        });

        function setRecurring($recurring, items)
        {
            $recurring.find('.recurring-add-button[data-for="' + $recurring.attr('data-name') + '"]').remove();
            var $items = $('<div class="recurring-items"></div>');
            var $sample = $recurring.find('.recurring-sample[data-for="' + $recurring.attr('data-name') + '"]').after($items).detach();

            if(items.length)
            {
                for(var i = 0; i < items.length; i++)
                {
                    $items.append(formatItem(items[i]));
                }
            }

            function formatItem(data)
            {
                data = data || {};
                var $clone = $sample.clone().removeClass('recurring-sample');

                fillData($clone, data);

                $clone.find('.recurring').each(function() {
                    var $inner = $(this);
                    var innerItems = data[$inner.attr('data-name')];
                    $inner.parents('.recurring-sample').length == 0 && setRecurring($inner, innerItems || []);
                });

                return $clone;
            }
        }

        $form.find(':input').attr('readonly', 'readonly');
        $form.find(':checkbox, select').attr('disabled', 'disabled');

        $form.find('.section-group').each(function() {
            var $sectionGroup = $(this);
            $sectionGroup.find('.section').first().before($sectionGroup.find('.section').last());
        });

        $updateStatusButton.on('click', function() {
            $updateStatusButton.addClass('hidden');
            $status.removeClass('hidden');
            $statusTitle.addClass('hidden');
        });

        $status.on('change', function() {
            $status.addClass('disabled');

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

                $status.removeClass('disabled').addClass('hidden');
                $statusTitle.removeClass('hidden');
                $updateStatusButton.removeClass('hidden');
                $statusTitle.html($status.find(':selected').text());
            });
        });

        $nextButton.on('click', function() {
            if($nextButton.hasClass('disabled'))
            {
                return;
            }

            var $curStep = $productSteps.find('.product-step.active');
            setStep($productSteps.find('.product-step[data-step-id="' + $curStep.attr('data-next-id') + '"]'));
        });

        $prevButton.on('click', function() {
            if($prevButton.hasClass('disabled'))
            {
                return;
            }

            var $curStep = $productSteps.find('.product-step.active');
            setStep($productSteps.find('.product-step[data-step-id="' + $curStep.attr('data-prev-id') + '"]'));
        });

        setStep($productSteps.find('.product-step[data-step-id="' + defaultActiveStep.id + '"]'));
        function setStep($step)
        {
            var step = steps.filter(function(step) { return step.id == $step.attr('data-step-id') })[0];

            $productSteps.find('.product-step').removeClass('active');
            $step.addClass('active');

            $stepName.html(htmlEncode(step.name));
            $stepProgress.html(step.order + ' מתוך ' + steps.length);
            var isFirstStep = step.order == 1;
            var isLastStep = step.order == steps.length;

            isFirstStep ? $prevButton.addClass('disabled') : $prevButton.removeClass('disabled');
            isLastStep ? $nextButton.addClass('disabled') : $nextButton.removeClass('disabled');
        }

        $('.export-button').on('click', function() {
            var $form = $('<form method="POST" class="hidden" action="<?php echo Yii::app()->createUrl('api/request/export'); ?>"></form>');
            $form.append('<input type="hidden" name="request_id" value="' + request.id + '" />');
            $form.append('<input type="hidden" name="login_token" value="' + auth.getUser().loginToken + '" />');
            $form.append('<input type="hidden" name="format" value="' + $(this).attr('data-format') + '" />');
            $form.appendTo($('body')).submit();
        });

        function fillData($container, data)
        {
            $.each(data, function(key, value) {
                if(typeof(value) == 'object')
                {
                    $container.find('.checkbox-list[data-name="' + key + '"]').each(function() {
                        var $checkboxList = $(this);

                        $checkboxList.find('span').each(function() {
                            var $span = $(this);
                            if(value.contains($span.text()))
                            {
                                $span.parents('label').find('input').prop('checked', true);
                            }
                        });
                    });

                    $container.find('.upload-area[data-name="' + key + '"]').each(function() {
                        var $uploadArea = $(this);
                        var $files = $uploadArea.find('.files');

                        $.each(value, function(key, file) {
                            $files.append('<div>' + htmlEncode(file.name) + '</div>');
                            files.push(file);
                        });
                    });
                }
                else
                {
                    var $input = $container.find(':input[name="' + key + '"]');
                    if($input.attr('type') == 'checkbox')
                    {
                        parseInt(value) == 1 && $input.prop('checked', true);
                    }
                    else
                    {
                        $input.val(value);
                    }
                }
            });
        }

        $addFilesButton.on('click', function() {
            if($addFilesButton.hasClass('disabled'))
            {
                return;
            }

            fileBrowser.browse(function($file) {
                if(!$file)
                {
                    return;
                }

                $addFilesButton.addClass('disabled');
                $addFilesButton.find('i').addClass('fa-spin fa-spinner').removeClass('fa-upload');

                uploader.uploadFile($file, function(file) {
                    $addFilesButton.removeClass('disabled').find('i').removeClass('fa-spin fa-spinner').addClass('fa-upload');
                    $files.append(formatFile(file));
                    updateFiles();
                }, function(errors) {
                    $addFilesButton.removeClass('disabled').find('i').removeClass('fa-spin fa-spinner').addClass('fa-upload');
                });
            });
        });

        if(request.extraInfo && request.extraInfo.fileInfos)
        {
            var sorted = request.extraInfo.fileInfos.sort(function(a, b) {
                return a.order - b.order;
            });

            $.each(sorted, function(key, info) {
                $files.append(formatFile(info.file, info));
            });

            var newFiles = files.filter(function(file) {
                return sorted.map(function(info) { return info.file.id; }).indexOf(file.id) == -1;
            });

            $.each(newFiles, function(key, file) {
                $files.append(formatFile(file));
            });

            newFiles.length && updateFiles();
        }
        else
        {
            $.each(files, function(key, file) {
                $files.append(formatFile(file));
            });

            updateFiles();
        }

        $files.sortable({
            update: function(event, ui) {
                updateFiles();
            },
        });

        function formatFile(file, info)
        {
            var html = '';
            var typeLabel = '';
            var typeClass = '';

            if(file.contentType.match(/.(jpg|jpeg|png|gif)$/i))
            {
                typeLabel = 'Image';
                typeClass = 'success';
            }
            else if(file.contentType == 'application/pdf')
            {
                typeLabel = 'PDF';
                typeClass = 'danger';
            }
            else
            {
                typeLabel = 'Not Supported';
                typeClass = 'default';
            }

            html += '<li class="file">';
            html += '   <i class="fa fa-download cp download-button"></i>';
            html += '   <span class="m-l-xs">' + htmlEncode(file.name) + '</span>';
            html += '   <div class="label label-' + typeClass + '">' + typeLabel + '</div>';
            html += '   <div class="checkbox checkbox-primary checkbox-inline pull-right">';
            html += '       <input class="attach-checkbox" id="file-checkbox-' + file.id + '" type="checkbox" ' + (info && parseInt(info.attached) ? 'checked="checked"' : '') + '>';
            html += '       <label for="file-checkbox-' + file.id + '"></label>';
            html += '   </div>';
            html += '</li>';

            var $file = $(html);
            var $attachCheckbox = $file.find('.attach-checkbox');
            var $downloadButton = $file.find('.download-button');

            $downloadButton.on('click', function(e) {
                var $form = $('<form method="POST" class="hidden" action="<?php echo Yii::app()->createUrl('api/file/download'); ?>"></form>');
                $form.append('<input type="hidden" name="file_id" value="' + file.id + '" />');
                $form.append('<input type="hidden" name="login_token" value="' + auth.getUser().loginToken + '" />');
                $form.appendTo($('body')).submit();
            });

            $attachCheckbox.on('change', function() {
                updateFiles();
            });

            $file.data('file', file);
            return $file;
        }

        function updateFiles()
        {
            var files = [];
            var order = 1;

            $files.find('.file').each(function() {
                files.push({
                    file: $(this).data('file'),
                    order: order++,
                    attached: $(this).find('.attach-checkbox').is(':checked') ? 1 : 0,
                });
            });

            apiRequest(config.api.urls.request.updateExtraInfo, 'POST', {
                request_id: request.id,
                key: 'fileInfos',
                value: files
            });
        }
    });
</script>