<?php
    $steps = $product->productSteps;
    usort($steps, function($a, $b) {
        if($a->product_step_order == $b->product_step_order)
        {
            return 0;
        }

        return ($a->product_step_order < $b->product_step_order) ? -1 : 1;
    });

    $activeStep = reset($steps);

    if(isset($_REQUEST['sid']))
    {
        $activeSteps = array_filter($steps, function($step) {
            return $step->getPrimaryKey() == $_REQUEST['sid'];
        });

        $activeStep = count($activeSteps) ? reset($activeSteps) : $activeStep;
    }

    $stepsCount = count($steps);
?>
<div id="product-page">
    <div id="top-bar-space"></div>
    <section id="banner" style="background: url('<?php echo $product->product_image_url; ?>');">
        <div class="container">
            <div class="space"></div>
            <div class="text">
                <div class="header"><?php echo GxHtml::encode($product->product_name); ?></div>
                <div class="sub-header"><?php echo nl2br(GxHtml::encode($product->product_description)); ?></div>
                <br /><br />
                <div id="price">מחיר עריכת ההסכם: <?php echo GxHtml::encode(number_format($product->product_price)); ?> ש"ח בלבד</div>
            </div>
        </div>
    </section>
    <section id="steps-section">
        <div class="container">
            <div class="steps-container">
                <div class="steps">
                    <div class="step">
                        <div class="circle">3</div>
                        <div class="text">שלם ושלח לעריכה</div>
                    </div>
                    <div class="step active">
                        <div class="circle">2</div>
                        <div class="text">מילוי השאלון</div>
                    </div>
                    <div class="step">
                        <div class="circle">1</div>
                        <div class="text">בחירת מוצר</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="container no-h-padding">
            <div class="text-right">
                <form id="form">
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
                    <div id="finish-buttons">
                        <div id="finish-area">
                            <div>
                                <label>
                                    <input id="agree-terms" type="checkbox" />
                                    אני מסכים ל<a target="_blank" href="<?php echo Yii::app()->createUrl('public/terms'); ?>">תנאי השימוש</a>
                                </label>
                            </div>
                            <div class="button" id="finish-button">סיום ומעבר לתשלום</div>
                        </div>
                        <div class="button" id="save-button">שמור והמשך במועד אחר</div>
                    </div>
                </form>
                <div id="guide">
                    <div id="step-number" class="step-number"></div>
                    <div id="step-name" class="step-name"></div>
                    <div class="text-center">
                        <div class="progress-bar">
                            <div id="progress" class="progress"></div>
                            <div id="progress-text" class="text"></div>
                        </div>
                    </div>
                    <div id="step-help-text" class="help-text"><?php echo nl2br(GxHtml::encode($activeStep->product_step_help_text)); ?></div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div id="bottom-sep"></div>
            <div id="nav-buttons">
                <div id="next-button">הבא ></div>
                <div id="prev-button">< הקודם</div>
                <div class="clearfix"></div>
            </div>
        </div>
    </section>
</div>

<script>
    $(function() {
        var $form = $('#form');
        var $productSteps = $('#product-steps');
        var $stepNumber = $('#step-number');
        var $stepName = $('#step-name');
        var $progress = $('#progress');
        var $progressText = $('#progress-text');
        var $stepHelpText = $('#step-help-text');
        var $nextButton = $('#next-button');
        var $prevButton = $('#prev-button');
        var $saveButton = $('#save-button');
        var $finishButton = $('#finish-button');
        var $finishArea = $('#finish-area');
        var $agreeTerms = $('#agree-terms');
        var concurrentUploads = 0;

        var steps = <?php echo json_encode(array_map(function($step) { return $step->toArray(); }, $steps)); ?>;
        var defaultActiveStep = <?php echo json_encode($activeStep->toArray()); ?>;
        var requestHash = '<?php echo $request ? $request->request_hash : ''; ?>';
        var request = <?php echo $request ? json_encode($request->toArray()) : 'null'; ?>;

        request && fillData($form, request.fields);

        $nextButton.on('click', function() {
            if($nextButton.hasClass('disabled'))
            {
                return;
            }

            if(concurrentUploads > 0)
            {
                showError('נא להמתין לסיום העלאת הקבצים')
            }
            else
            {
                updateCurrentStep(function($curStep) {
                    setStep($productSteps.find('.product-step[data-step-id="' + $curStep.attr('data-next-id') + '"]'));
                });
            }
        });

        $finishButton.on('click', function() {
            if($finishButton.hasClass('disabled'))
            {
                return;
            }

            if(concurrentUploads > 0)
            {
                showError('נא להמתין לסיום העלאת הקבצים')
            }
            else
            {
                if($agreeTerms.is(':checked'))
                {
                    updateCurrentStep(function() {
                        window.location = "<?php echo Yii::app()->createUrl('pay'); ?>/" + requestHash;
                    });
                }
                else
                {
                    addErrorStyle($agreeTerms);
                    showError('חובה לסמן הסכמה לתנאים');
                }
            }
        });

        $prevButton.on('click', function() {
            if($prevButton.hasClass('disabled'))
            {
                return;
            }

            var $curStep = $productSteps.find('.active');
            var $prevStep = $productSteps.find('.product-step[data-step-id="' + $curStep.attr('data-prev-id') + '"]');

            if(concurrentUploads > 0)
            {
                showError('נא להמתין לסיום העלאת הקבצים')
            }
            else
            {
                setStep($prevStep);
            }
        });

        $saveButton.on('click', function() {
            if($saveButton.hasClass('disabled'))
            {
                return;
            }

            if(concurrentUploads > 0)
            {
                showError('נא להמתין לסיום העלאת הקבצים')
            }
            else
            {
                updateCurrentStep(function($curStep) {
                    $curStep.attr('data-prev-id') && $prevButton.removeClass('disabled');

                    alert('Data saved. what now?');
                });
            }
        });

        setStep($productSteps.find('.product-step[data-step-id="' + defaultActiveStep.id + '"]'));
        function setStep($step)
        {
            var step = steps.filter(function(step) { return step.id == $step.attr('data-step-id') })[0];

            $productSteps.find('.product-step').removeClass('active');
            $step.addClass('active');

            $stepNumber.html('שלב ' + step.order + ':');
            $stepName.html(htmlEncode(step.name));

            var percentComplete = parseInt(100 / steps.length * (step.order - 1));
            $progress.css('width', percentComplete + '%');
            $progressText.html(percentComplete + '%');

            $stepHelpText.html(htmlEncode(step.helpText));

            setQueryParam('sid', step.id);

            var isFirstStep = step.order == 1;
            var isLastStep = step.order == steps.length;

            isFirstStep ? $prevButton.addClass('disabled') : $prevButton.removeClass('disabled');
            isLastStep ? $nextButton.hide() : $nextButton.show();
            isLastStep ? $finishArea.show() : $finishArea.hide();
        }

        function validateStep($step)
        {
            var errors = [];
            removeErrorStyle($agreeTerms);

            $step.find(':input').each(function() {
                var $field = $(this);
                var value = $field.val();
                var validationsText = $field.attr('data-validations');
                var validations = validationsText ? validationsText.split(' ') : [];

                removeErrorStyle($field);

                if(validations.length)
                {
                    var fieldHasErrors = false;

                    $.each(validations, function(key, validation) {
                        if(!fieldHasErrors)
                        {
                            if($field.attr('type') == 'checkbox')
                            {
                                if(validation == 'required' && !$field.is(':checked'))
                                {
                                    errors.push('חובה לסמן את השדות המסומנים באדום');
                                    addErrorStyle($field);
                                    fieldHasErrors = true;
                                }
                            }
                            else
                            {
                                if(validation == 'required' && value == '')
                                {
                                    errors.push('חובה למלא את השדות המסומנים באדום');
                                    addErrorStyle($field);
                                    fieldHasErrors = true;
                                }
                                else if(validation == 'id' && !validateId(value))
                                {
                                    errors.push("מספר תעודת זהות לא תקין");
                                    addErrorStyle($field);
                                    fieldHasErrors = true;
                                }
                                else if(validation == 'email' && !validateEmail(value))
                                {
                                    errors.push('האימייל שהוכנס אינו תקין');
                                    addErrorStyle($field);
                                    fieldHasErrors = true;
                                }
                                if(validation == 'fullName' && value.trim() != '' && value.trim().split(' ').length < 2)
                                {
                                    errors.push('השם שהוכנס אינו מלא');
                                    addErrorStyle($field);
                                    fieldHasErrors = true;
                                }
                            }
                        }
                    });
                }

                if($field.attr('type') == 'number')
                {
                    var min = parseInt($field.attr('min'));
                    var max = parseInt($field.attr('max'));

                    if(isNaN(value))
                    {
                        errors.push($field.attr('placeholder') + ' חייב להיות מספר');
                        addErrorStyle($field);
                    }
                    else if(!isNaN(min) && parseInt(value) < min)
                    {
                        errors.push($field.attr('placeholder') + ' חייב להיות גדול מ-' + numberFormat(min));
                        addErrorStyle($field);
                    }
                    else if(!isNaN(max) && parseInt(value) > max)
                    {
                        errors.push($field.attr('placeholder') + ' חייב להיות קטן מ-' + numberFormat(max));
                        addErrorStyle($field);
                    }
                }
            });

            return errors;
        }

        function addErrorStyle($field)
        {
            if($field.is('input[type="checkbox"]'))
            {
                $field.parents('label').addClass('error');
            }
            else
            {
                $field.addClass('error');
            }
        }

        function removeErrorStyle($field)
        {
            if($field.is('input[type="checkbox"]'))
            {
                $field.parents('label').removeClass('error');
            }
            else
            {
                $field.removeClass('error');
            }
        }

        function showError(text)
        {
            alert(text);
        }

        $form.find(':input[data-validations]').each(function() {
            var $field = $(this);
            var validationsText = $field.attr('data-validations');
            var validations = validationsText ? validationsText.split(' ') : [];
            var placeholder = $field.attr('placeholder');

            if(validations.indexOf('required') != -1 && placeholder)
            {
                $field.attr('placeholder', '*' + placeholder);
            }
        });

        function updateCurrentStep(onComplete)
        {
            var $curStep = $productSteps.find('.active');
            var errors = validateStep($curStep);
            if(errors.length)
            {
                $.each(errors.unique(), function(key, error) {
                    showError(error);
                });
            }
            else
            {
                $nextButton.addClass('disabled');
                $prevButton.addClass('disabled');
                $finishButton.addClass('disabled');
                $saveButton.addClass('disabled');

                if(requestHash)
                {
                    update();
                }
                else
                {
                    apiRequest(config.api.urls.request.create, 'POST', {
                        product_id: '<?php echo $product->getPrimaryKey(); ?>',
                    }, function(response) {
                        setQueryParam('rh', response.body.request.hash);
                        requestHash = response.body.request.hash;
                        update();
                    });
                }

                function update()
                {
                    var fields = containerToData($curStep);

                    apiRequest(config.api.urls.request.updateFields, 'POST', {
                        request_hash: requestHash,
                        fields: fields,
                    }, function(response) {
                        if(response.status == 'OK')
                        {
                            onComplete && onComplete($curStep);
                        }
                        else
                        {
                            if(response.status == 'INVALID_DISCOUNT_CODE')
                            {
                                showError('קוד המנוי שהוזן אינו תקין');
                                addErrorStyle($form.find(':input[name="discountCode"]'));
                            }
                            else
                            {
                                showError('אירעה שגיאה');
                            }
                        }

                        $nextButton.removeClass('disabled');
                        $finishButton.removeClass('disabled');
                        $saveButton.removeClass('disabled');
                    });
                }
            }
        }

        $form.find('.recurring').each(function() {
            var $recurring = $(this);
            var items = request ? request.fields[$recurring.attr('data-name')] : [];

            items = items || [];
            $recurring.parents('.recurring-sample').length == 0 && setRecurring($recurring, items); //only top level recurring
        });

        function setRecurring($recurring, items)
        {
            var $addButton = $recurring.find('.recurring-add-button[data-for="' + $recurring.attr('data-name') + '"]');
            var $items = $('<div class="recurring-items"></div>');
            var $sample = $recurring.find('.recurring-sample[data-for="' + $recurring.attr('data-name') + '"]').after($items).detach();
            var limit = parseInt($recurring.attr('data-limit'));
            var itemsCount = 0;

            $sample.find(':input').val('');

            if(items.length)
            {
                for(var i = 0; i < items.length; i++)
                {
                    itemsCount++;
                    $items.append(formatItem(items[i]));
                }
            }
            else
            {
                for(var i = 0; i < parseInt($recurring.attr('data-default') || 1); i++)
                {
                    itemsCount++;
                    $items.append(formatItem());
                }
            }

            $addButton.off().on('click', function() {
                if($addButton.hasClass('disabled'))
                {
                    return;
                }

                itemsCount++;
                $items.append(formatItem());
                limit && itemsCount >= limit && $addButton.addClass('disabled');
            });

            function formatItem(data)
            {
                data = data || {};
                var $clone = $sample.clone().removeClass('recurring-sample');
                $clone.addClass('recurring-item').on('click', '.recurring-remove-button[data-for="' + $recurring.attr('data-name') + '"]', function() {
                    itemsCount--;
                    $clone.remove();
                    limit && itemsCount < limit && $addButton.removeClass('disabled');
                });

                fillData($clone, data);
                setEvents($clone);

                $clone.find('.recurring').each(function() {
                    var $inner = $(this);
                    var innerItems = data[$inner.attr('data-name')];
                    $inner.parents('.recurring-sample').length == 0 && setRecurring($inner, innerItems || []);
                });

                return $clone;
            }
        }

        function containerToData($container)
        {
            var fields = {};
            var $clone = $container.clone(true);

            $clone.find('.recurring').remove();

            $clone.find(':input').each(function() {
                var $field = $(this);

                if($field.attr('name'))
                {
                    if($field.attr('type') == 'checkbox')
                    {
                        fields[$field.attr('name')] = $field.is(':checked') ? 1 : 0;
                    }
                    else
                    {
                        fields[$field.attr('name')] = $container.find(':input[data-id="' + $field.attr('data-id') + '"]').val();
                    }
                }
            });

            $clone.find('.checkbox-list').each(function() {
                var $checkboxList = $(this);
                var values = $checkboxList.find(':input:checked').map(function() {
                    return $(this).parents('label').find('span').text();
                }).get();

                fields[$checkboxList.attr('data-name')] = values.length ? values : '';
            });

            $clone.find('.upload-area').each(function() {
                var $uploadArea = $(this);
                var files = $uploadArea.find('.file').map(function() {
                    return $(this).data('file');
                }).get();

                fields[$uploadArea.attr('data-name')] = files.length ? files : '';
            });

            $container.find('.recurring').each(function() {
                var $recurring = $(this);
                var $items = $recurring.find('.recurring-item[data-for="' + $recurring.attr('data-name') + '"]');
                var items = $items.map(function() {
                    return containerToData($(this));
                }).get();

                fields[$recurring.attr('data-name')] = items.length ? items : '';
            });

            return fields;
        }

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
                            $files.append(formatFile(file));
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

        setEvents($form);
        function setEvents($container)
        {
            $container.find(':input[data-toggles]').each(function() {
                var $checkbox = $(this);
                var $container = $checkbox.parents('.recurring-item');

                if($container.length == 0)
                {
                    $container = $checkbox.parents('.product-step');
                }

                var $toggle = $container.find('.' + $checkbox.attr('data-toggles'));

                $checkbox.on('change', function() {
                    $checkbox.is(':checked') ? ($toggle.slideDown() && $toggle.focus()) : $toggle.slideUp();
                });

                $checkbox.is(':checked') ? $toggle.show() : $toggle.hide();
            });

            $container.find(':input[data-placeholder]').each(function() {
                var $input = $(this);
                var $container = $input.parents('.recurring-item');

                if($container.length == 0)
                {
                    $container = $input.parents('.product-step');
                }

                var $input2 = $container.find(':input[name="' + $input.attr('data-placeholder') + '"]');

                $input2.on('change', function() {
                    $input.attr('placeholder', $input2.find(':selected').text());
                });

                $input.attr('placeholder', $input2.find(':selected').text());
            });

            $container.find('.dp').each(function() {
                var $input = $(this);
                $input.datepicker({ maxDate: $input.hasClass('past-only') ? new Date() : null });
            });

            $container.find(':input').each(function() {
                $(this).attr('data-id', guid());
            });

            $container.find('.upload-area').each(function() {
                var $uploadArea = $(this);
                var $files = $uploadArea.find('.files');
                var $uploadButton = $uploadArea.find('.upload-button');
                var limit = $uploadArea.attr('data-limit');

                $uploadButton.off().on('click', function() {
                    if(limit && $files.find('.file, .pending').length >= limit)
                    {
                        if(limit == 1)
                        {
                            showError('לא ניתן להעלות יותר מקובץ אחד');
                        }
                        else
                        {
                            showError('לא ניתן להעלות יותר מ-' + limit + ' קבצים');
                        }

                        return;
                    }

                    fileBrowser.browse(function($file) {
                        if(!$file)
                        {
                            return;
                        }

                        var $pending = $('<div class="pending"><i class="fa fa-spinner fa-spin"></i> ' + 'מעלה קובץ...' + ' <span class="text"></span> <a class="cp cancel-button" >ביטול</a></div>');
                        var $pendingText = $pending.find('.text');
                        var cancelled = false;

                        $pending.on('click', '.delete-button', function() {
                            $pending.remove();
                        });

                        $pending.on('click', '.cancel-button', function() {
                            $pending.remove();
                            concurrentUploads--;
                            cancelled = true;
                        });

                        $files.append($pending);
                        concurrentUploads++;

                        uploader.uploadFile($file, function(file) {
                            if(!cancelled)
                            {
                                $pending.before(formatFile(file)).remove();
                                concurrentUploads--;
                            }
                        }, function(errors) {
                            if(!cancelled)
                            {
                                $pending.html('<i class="fa fa-remove delete-button cp"></i> העלאת קובץ נכשלה');
                                concurrentUploads--;
                            }
                        }, function(percentage) {
                            $pendingText.html(parseInt(percentage) + '%');
                        });
                    });
                });
            });
        }

        function formatFile(file)
        {
            var html = '';

            html += '<div class="file" data-file-id="' + file.id + '">';
            html += '   <i class="fa fa-remove delete-button cp m-r-5"></i>';
            html +=     htmlEncode(file.name) + ' <i class="fa fa-file"></i>';
            html += '</div>';

            var $file = $(html);
            var $deleteButton = $file.find('.delete-button');
            $deleteButton.on('click', function() {
                $file.remove();
            });

            $file.data('file', file);
            return $file;
        }
    });
</script>