<?php
    $steps = array_reverse(array_filter(array_unique(array_map(function($field) {
        return $field->field_step;
    }, $template->fields))));

    $fields = $template->fields;
    usort($fields, function($a, $b) {
        if($a->field_order == $b->field_order)
        {
            return 0;
        }

        return ($a->field_order < $b->field_order) ? -1 : 1;
    });

    $extraInfo = empty($template->template_extra_info) ? array() : json_decode($template->template_extra_info, true);
?>
<section class="alternate-2">
    <div class="container">
        <div class="heading-title text-center <?php echo empty($steps) ? '' : 'mb-80'; ?>">
            <h3><?php echo GxHtml::encode($template->template_name); ?></h3>
        </div>

        <?php if(!empty($steps)) { ?>
            <ul class="process-steps nav nav-justified" id="step-nav">
                <?php
                $c = count($steps);
                foreach($steps as $step)
                {
                    $active = $c == 1;

                    echo '<li class="nav-item">';
                    echo '  <a step="' . $c . '" class="no-cp nav-link ' . ($active ? 'active' : '') . '">' . $c-- . '</a>';
                    echo '  <h5>' . GxHtml::encode($step) . '</h5>';
                    echo '</li>';
                }
                ?>
            </ul>
        <?php } ?>
    </div>
</section>

<section>
    <div class="container">
        <p><?php echo nl2br($template->template_description); ?></p>
        <form>
            <div class="tab-content mt-60">
                <?php
                    if(empty($steps))
                    {
                        $steps[] = '';
                    }

                    $c = count($steps);
                    foreach($steps as $step)
                    {
                        $active = $c == 1;
                        echo '<div class="step tab-pane ' . ($active ? 'active' : '') . '" step="' . $c . '">';
                        echo '<h1>' . $step . '</h1>';
                        echo '    <fieldset>';

                        $stepFields = array_filter($fields, function($field) use ($step) {
                            return $field->field_step == $step;
                        });

                        $i = 0;
                        foreach($stepFields as $field)
                        {
                            if($field->isFullRow())
                            {
                                if($i == 1)
                                {
                                    echo '<div class="col-md-4"></div>';
                                    echo '<div class="col-md-4"></div>';
                                    echo '</div>';
                                    $i = 0;
                                }
                                else if($i == 2)
                                {
                                    echo '<div class="col-md-4"></div>';
                                    echo '</div>';
                                    $i = 0;
                                }
                            }

                            if($i == 0)
                            {
                                echo '<div class="row">';
                            }

                            echo '<div class="col-md-' . ($field->isFullRow() ? '12' : '4') . '">';
                            echo $field->toHtml($request ? $request->getFieldValue($field->getPrimaryKey()) : null);
                            echo '</div>';

                            $i++;

                            if($field->isFullRow())
                            {
                                echo '</div>';
                                $i = 0;
                            }

                            if($i == 3)
                            {
                                echo '</div>';
                                $i = 0;
                            }
                        }

                        echo '    </fieldset>';

                        if($c == count($steps))
                        {
                            if(isset($extraInfo['discounts']) && is_array($extraInfo['discounts']) && count($extraInfo['discounts']))
                            {
                                echo '<div class="row">';
                                echo '    <div class="col-lg-2">';
                                echo '          <label for="discount-code">קוד הנחה</label>';
                                echo '          <input type="password" id="discount-code" class="form-control" />';
                                echo '    </div>';
                                echo '</div>';
                            }

                            echo '    <div class="btn btn-primary cp next-button"><i class="fa fa-check"></i> סיים ושלח</div>';
                        }
                        else
                        {
                            echo '    <div class="btn btn-primary cp next-button"><i class="fa fa-chevron-left"></i> המשך</div>';
                        }

                        if($c != 1)
                        {
                            echo '    <div class="btn btn-default cp float-right prev-button">חזור <i class="fa fa-chevron-right"></i></div>';
                        }

                        echo '</div>';

                        $c--;
                    }
                ?>
            </div>
        </form>
    </div>
</section>

<script>
    $(function() {
        var $stepNav = $('#step-nav');
        var requestHash = '<?php echo $request ? $request->request_hash : ''; ?>';
        var queryStep = getQueryParam('s');
        var $discountCode = $('#discount-code');

        queryStep && setStep(queryStep);

        $('.next-button').on('click', function() {
            var $step = $(this).parents('.step');
            var $nextButton = $(this);
            var $prevButton = $step.find('.prev-button');

            if($nextButton.hasClass('disabled'))
            {
                return;
            }

            if(validateStep($step))
            {
                var $i = $nextButton.find('i');
                var iClass = $i.attr('class');

                $nextButton.addClass('disabled');
                $prevButton.addClass('disabled');
                $i.addClass('fa-spin fa-spinner').removeClass('fa-chevron-left fa-check');

                if(requestHash)
                {
                    updateFields();
                }
                else
                {
                    apiRequest(config.api.urls.request.create, 'POST', {
                        template_id: '<?php echo $template->getPrimaryKey(); ?>',
                    }, function(response) {
                        setQueryParam('h', response.body.request.hash);
                        requestHash = response.body.request.hash;
                        updateFields();
                    });
                }

                function updateFields()
                {
                    var fields = {};
                    $step.find('.field').each(function() {
                        var $field = $(this);
                        var field = JSON.parse($field.attr('field-data'));

                        if(field.type.id == <?php echo FieldType::CHECK_BOX; ?>)
                        {
                            fields[field.id] = $field.is(':checked') ? 1 : 0;
                        }
                        else
                        {
                            fields[field.id] = $field.val();
                        }
                    });

                    var isLastStep = $step.is(':first-child'); //":first-child" because we're right to left

                    apiRequest(config.api.urls.request.updateFields, 'POST', {
                        request_hash: requestHash,
                        fields: fields,
                        discount_code: isLastStep ? $discountCode.val() : '',
                    }, function(response) {
                        if(response.status == 'OK')
                        {
                            if(isLastStep) //is last step
                            {
                                window.location = "<?php echo Yii::app()->createUrl('pay'); ?>/" + requestHash;
                            }
                            else
                            {
                                setStep(parseInt($step.attr('step')) + 1);
                                $nextButton.removeClass('disabled');
                                $prevButton.removeClass('disabled');
                                $i.attr('class', iClass);
                            }
                        }
                        else
                        {
                            if(response.status == 'INVALID_DISCOUNT_CODE')
                            {
                                addErrorStyle($discountCode);
                                showToast('error', 'קוד הנחה לא תקין');
                            }
                            else
                            {
                                showError('אירעה שגיאה, אנא נסה שנית');
                            }

                            $nextButton.removeClass('disabled');
                            $prevButton.removeClass('disabled');
                            $i.attr('class', iClass);
                        }
                    });
                }
            }
        });

        $('.prev-button').on('click', function() {
            if($(this).hasClass('disabled'))
            {
                return;
            }

            var $step = $(this).parents('.step');
            setStep(parseInt($step.attr('step')) - 1);
        });

        function setStep(num)
        {
            var $currentStep = $('.step.active');
            $currentStep.removeClass('active');

            var $newStep = $('.step[step="' + num + '"]');
            $newStep.addClass('active');

            if($newStep.length == 0)
            {
                setStep(1);
                return;
            }

            $stepNav.find('.nav-link').each(function() {
                if(parseInt($(this).attr('step')) > num)
                {
                    $(this).removeClass('active');
                }
                else
                {
                    $(this).addClass('active');
                }
            });

            setQueryParam('s', num);
        }

        function validateStep($step)
        {
            var hasErrors = false;
            removeErrorStyle($discountCode);

            $step.find('.field').each(function() {
                var $field = $(this);
                var field = JSON.parse($field.attr('field-data'));
                var value = $field.val();

                removeErrorStyle($field);

                if(field.type.id == <?php echo FieldType::CHECK_BOX; ?>)
                {
                    if(field.required && !$field.is(':checked'))
                    {
                        showError('חובה לסמן את השדות המודגשים באדום');
                        addErrorStyle($field);
                        hasErrors = true;
                    }
                }
                else if(field.required && value == '')
                {
                    showError("חובה למלא " + field.name);
                    addErrorStyle($field);
                    hasErrors = true;
                }
                else if(value != '')
                {
                    if(field.type.id == <?php echo FieldType::ID; ?> && !validateId($field.val()))
                    {
                        showError("מספר תעודת זהות לא תקין");
                        addErrorStyle($field);
                        hasErrors = true;
                    }
                    else if(field.type.id == <?php echo FieldType::EMAIL; ?> || field.type.id == <?php echo FieldType::USER_EMAIL; ?>)
                    {
                        if(!validateEmail($field.val()))
                        {
                            showError("המייל שהוכנס אינו תקין");
                            addErrorStyle($field);
                            hasErrors = true;
                        }
                    }
                    else if(field.type.id == <?php echo FieldType::NUMBER; ?>)
                    {
                        if(isNaN(value))
                        {
                            showError(field.name + ' חייב להיות מספר');
                            addErrorStyle($field);
                            hasErrors = true;
                        }
                        else if(field.extraInfo && field.extraInfo.range && !isNaN(field.extraInfo.range.min) && parseInt(value) < parseInt(field.extraInfo.range.min))
                        {
                            showError(field.name + ' חייב להיות גדול מ-' + field.extraInfo.range.min);
                            addErrorStyle($field);
                            hasErrors = true;
                        }
                        else if(field.extraInfo && field.extraInfo.range && !isNaN(field.extraInfo.range.max) && parseInt(value) > parseInt(field.extraInfo.range.max))
                        {
                            showError(field.name + ' חייב להיות קטן מ-' + field.extraInfo.range.max);
                            addErrorStyle($field);
                            hasErrors = true;
                        }
                    }
                }
            });

            return !hasErrors;
        }

        function addErrorStyle($field)
        {
            if($field.is('input[type="checkbox"]'))
            {
                $field.parents('label').css('color', '#bf6464');
            }
            else
            {
                $field.css('border', '#bf6464 1px solid');
            }
        }

        function removeErrorStyle($field)
        {
            if($field.is('input[type="checkbox"]'))
            {
                $field.parents('label').css('color', '#404040');
            }
            else
            {
                $field.css('border', '#ddd 2px solid');
            }
        }

        function showError(text)
        {
            _toastr(text, "bottom-left", "error", false);
        }
    });
</script>