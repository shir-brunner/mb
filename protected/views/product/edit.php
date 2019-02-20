<div class="wrapper-content">
    <div class="row">
        <div class="col-lg-7">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Product Info</h5>
                </div>
                <div class="ibox-content">
                    <div class="form-horizontal" id="edit-product-form">
                        <div class="form-group" field-name="product_name">
                            <label class="col-md-2 control-label">Name</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="name" value="<?php echo GxHtml::encode($product->product_name); ?>" />
                            </div>
                        </div>
                        <div class="form-group" field-name="product_description">
                            <label class="col-md-2 control-label">Description</label>
                            <div class="col-md-8">
                                <textarea class="form-control" id="description" style="height: 150px;"><?php echo GxHtml::encode($product->product_description); ?></textarea>
                            </div>
                        </div>
                        <div class="form-group" field-name="product_price">
                            <label class="col-md-2 control-label">Price</label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="number" class="form-control" id="price" value="<?php echo GxHtml::encode($product->product_price); ?>" />
                                    <div class="input-group-addon"><i class="fa fa-shekel"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" field-name="product_class_name">
                            <label class="col-md-2 control-label">Class Name</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="class-name" value="<?php echo GxHtml::encode($product->product_class_name); ?>" />
                            </div>
                        </div>
                        <div class="form-group" field-name="product_published">
                            <label class="col-md-2 control-label"></label>
                            <div class="col-md-8">
                                <div class="checkbox checkbox-primary">
                                    <input type="checkbox" id="published" <?php echo $product->product_published ? 'checked' : ''; ?> />
                                    <label for="published">Published</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ibox-footer">
                    <a href="<?php echo Yii::app()->createUrl('public/product', array('id' => $product->getPrimaryKey())); ?>"><div class="btn btn-danger btn-sm pull-left"><i class="fa fa-eye"></i> View Product</div></a>
                    <div class="btn btn-primary btn-sm pull-right" id="product-save-button"><i class="fa fa-check"></i> Save</div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="ibox">
                        <div class="ibox-title">
                            <h5>Steps</h5>
                        </div>
                        <div class="ibox-content">
                            <ul id="steps" class="todo-list"></ul>
                        </div>
                        <div class="ibox-footer">
                            <div class="btn btn-success btn-sm pull-left" id="create-step-button"><i class="fa fa-plus"></i> New Step</div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
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
        <div class="col-lg-5">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Image</h5>
                </div>
                <div class="ibox-content" style="min-height: 200px;">
                    <img id="product-image" class="<?php echo $product->product_image_url ? '' : 'hidden'; ?>" src="<?php echo $product->product_image_url ? $product->product_image_url : ''; ?>" />
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
                            <img id="product-icon" class="<?php echo $product->product_icon_url ? '' : 'hidden'; ?>" src="<?php echo $product->product_icon_url ? $product->product_icon_url : ''; ?>" />
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
                            <img id="product-hover-icon" class="<?php echo $product->product_hover_icon_url ? '' : 'hidden'; ?>" src="<?php echo $product->product_hover_icon_url ? $product->product_hover_icon_url : ''; ?>" />
                        </div>
                        <div class="ibox-footer">
                            <div class="btn btn-success btn-sm pull-left" id="upload-hover-icon-button"><i class="fa fa-upload"></i> Upload Image</div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        var extraInfo = <?php echo empty($product->product_extra_info) ? '{}' : $product->product_extra_info; ?>;

        var $editProductForm = $('#edit-product-form');
        var $productSaveButton = $('#product-save-button');
        var $name = $('#name');
        var $description = $('#description');
        var $price = $('#price');
        var $published = $('#published');

        var $uploadImageButton = $('#upload-image-button');
        var $productImage = $('#product-image');

        var $uploadIconButton = $('#upload-icon-button');
        var $productIcon = $('#product-icon');

        var $uploadHoverIconButton = $('#upload-hover-icon-button');
        var $productHoverIcon = $('#product-hover-icon');

        var $discounts = $('#discounts');
        var $discountsTbody = $discounts.find('tbody');
        var $newDiscountButton = $('#new-discount-button');
        var $saveDiscountsButton = $('#save-discounts-button');

        var $steps = $('#steps');
        var $createStepButton = $('#create-step-button');

        var $className = $('#class-name');

        $productSaveButton.on('click', function() {
            if($productSaveButton.hasClass('disabled'))
            {
                return;
            }

            $productSaveButton.addClass('disabled');
            $productSaveButton.find('i').addClass('fa-spinner fa-spin').removeClass('fa-check');

            $editProductForm.find('.form-group').removeClass('has-error');

            apiRequest(config.api.urls.product.update, 'POST', {
                product_id: '<?php echo $product->getPrimaryKey(); ?>',
                name: $name.val(),
                description: $description.val(),
                price: $price.val(),
                published: $published.is(':checked') ? 1 : 0,
                class_name: $className.val(),
            }, function(response) {
                if(response.status == 'OK')
                {
                    $productSaveButton.removeClass('btn-warning').addClass('btn-primary');
                }
                else if(response.status == 'ERROR')
                {
                    $.each(response.body.errors, function(key, error) {
                        $editProductForm.find('.form-group[field-name="' + key + '"]').addClass('has-error');
                        showToast('error', error);
                    });
                }
                else
                {
                    defaultErrorHandler();
                }

                $productSaveButton.find('i').removeClass('fa-spinner fa-spin').addClass('fa-check');
                $productSaveButton.removeClass('disabled');
            });
        });

        $editProductForm.find(':input').on('input', function() {
            $productSaveButton.addClass('btn-warning').removeClass('btn-primary');
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

            apiRequest(config.api.urls.product.updateExtraInfo, 'POST', {
                product_id: '<?php echo $product->getPrimaryKey(); ?>',
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
                    apiRequest(config.api.urls.product.update, 'POST', {
                        product_id: '<?php echo $product->getPrimaryKey(); ?>',
                        image_url: image.url,
                    }, function(response) {
                        if(response.status == 'OK')
                        {
                            $uploadImageButton.html('<i class="fa fa-upload"></i> Upload Image').removeClass('disabled');
                            $productImage.attr('src', image.url).removeClass('hidden');
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
                    });
                }, function(errors) {
                    $uploadImageButton.html('<i class="fa fa-upload"></i> Upload Image').removeClass('disabled');
                    $.each(errors, function(key, error) {
                        showToast('error', error);
                    });
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
                    apiRequest(config.api.urls.product.update, 'POST', {
                        product_id: '<?php echo $product->getPrimaryKey(); ?>',
                        icon_url: image.url,
                    }, function(response) {
                        if(response.status == 'OK')
                        {
                            $uploadIconButton.html('<i class="fa fa-upload"></i> Upload Image').removeClass('disabled');
                            $productIcon.attr('src', image.url).removeClass('hidden');
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
                    });
                }, function(errors) {
                    $uploadIconButton.html('<i class="fa fa-upload"></i> Upload Image').removeClass('disabled');
                    $.each(errors, function(key, error) {
                        showToast('error', error);
                    });
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
                    apiRequest(config.api.urls.product.update, 'POST', {
                        product_id: '<?php echo $product->getPrimaryKey(); ?>',
                        hover_icon_url: image.url,
                    }, function(response) {
                        if(response.status == 'OK')
                        {
                            $uploadHoverIconButton.html('<i class="fa fa-upload"></i> Upload Image').removeClass('disabled');
                            $productHoverIcon.attr('src', image.url).removeClass('hidden');
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
                    });
                }, function(errors) {
                    $uploadHoverIconButton.html('<i class="fa fa-upload"></i> Upload Image').removeClass('disabled');
                    $.each(errors, function(key, error) {
                        showToast('error', error);
                    });
                }, function(percentage) {
                    $uploadHoverIconButton.html('<i class="fa fa-spin fa-spinner"></i> Uploading... (' + percentage + '%)');
                });
            });
        });

        var steps = <?php echo json_encode(array_map(function($productStep) { return $productStep->toArray(); }, $product->productSteps)); ?>;
        var maxOrder = steps.map(function(step) {
            return step.order;
        }).max();

        steps = steps.sort(function(a, b) { return (a.order || maxOrder) - (b.order || maxOrder); });

        $.each(steps, function(key, step) {
            $steps.append(formatStep(step));
        });

        $steps.sortable({
            update: function(event, ui) {
                updateStepOrders();
            },
        });

        function updateStepOrders()
        {
            var orders = {};
            var order = 1;

            $steps.find('.step').each(function() {
                orders[$(this).data('step').id] = order++;
            });

            apiRequest(config.api.urls.productStep.changeOrder, 'POST', {
                orders: orders
            });
        }

        $createStepButton.on('click', function() {
            editStep();
        });

        function formatStep(step)
        {
            var html = '';

            html += '<li class="step">';
            html += '   <span class="m-l-xs">' + htmlEncode(step.name) + '</span>';
            html += '   <span class="edit-button cp pull-left" tooltip title="Edit Step"><i class="fa fa-pencil"></i></span>';
            html += '   <span class="pull-right delete-button cp" tooltip title="Delete Step"><i class="fa fa-trash"></i></span>';
            html += '</li>';

            var $step = $(html);

            var $deleteButton = $step.find('.delete-button');
            $deleteButton.on('click', function() {
                apiRequest(config.api.urls.productStep.delete, 'POST', {
                    product_step_id: step.id,
                });
                $deleteButton.tooltip('hide');
                $step.remove();

                updateStepOrders();
            });

            var $editButton = $step.find('.edit-button');
            $editButton.on('click', function() {
                editStep(step, $step);
            });

            $step.data('step', step);
            return $step;
        }

        function editStep(step, $step)
        {
            var $modal = modal(step ? 'Edit Step' : 'New Step');
            var $modalBody = $modal.find('.modal-body').empty();
            var $modalFooter = $modal.find('.modal-footer');

            var html = '';

            html += '<div class="form-horizontal">';
            html += '   <div class="form-group" field-name="product_step_name">';
            html += '       <div class="col-xs-3 text-right">';
            html += '           <label class="control-label">Name</label>';
            html += '       </div>';
            html += '       <div class="col-xs-8">';
            html += '           <input type="text" class="form-control name" />';
            html += '       </div>';
            html += '   </div>';
            html += '   <div class="form-group" field-name="product_step_view">';
            html += '       <div class="col-xs-3 text-right">';
            html += '           <label class="control-label">View</label>';
            html += '       </div>';
            html += '       <div class="col-xs-8">';
            html += '           <input type="text" class="form-control view" />';
            html += '       </div>';
            html += '   </div>';
            html += '   <div class="form-group" field-name="product_step_help_text">';
            html += '       <div class="col-xs-3 text-right">';
            html += '           <label class="control-label">Help Text</label>';
            html += '       </div>';
            html += '       <div class="col-xs-8">';
            html += '           <textarea class="form-control help-text"></textarea>';
            html += '       </div>';
            html += '   </div>';
            html += '</div>';

            $modalBody.append(html);

            var $name = $modalBody.find('.name');
            var $view = $modalBody.find('.view');
            var $helpText = $modalBody.find('.help-text');

            if(step)
            {
                $name.val(step.name);
                $view.val(step.view);
                $helpText.val(step.helpText);
            }

            var createButtonText = '<i class="fa fa-check"></i> ' + (step ? 'Save' : 'Create');
            var $createButton = $('<button type="button" class="btn btn-primary">' + createButtonText + '</button>').on('click', function() {
                if($createButton.hasClass('disabled'))
                {
                    return;
                }

                $createButton.addClass('disabled').html('<i class="fa fa-spin fa-spinner"></i> Create');
                $modalBody.find('.form-group').removeClass('has-error');

                apiRequest(config.api.urls.productStep[step ? 'update' : 'create'], 'POST', {
                    product_step_id: step ? step.id : null,
                    product_id: '<?php echo $product->getPrimaryKey(); ?>',
                    name: $name.val(),
                    view: $view.val(),
                    help_text: $helpText.val(),
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

                        if($step)
                        {
                            $step.before(formatStep(response.body.step)).remove();
                        }
                        else
                        {
                            $steps.append(formatStep(response.body.step));
                            updateStepOrders();
                        }
                    }
                    else
                    {
                        defaultErrorHandler();
                    }
                });
            }).appendTo($modalFooter);

            $modal.on('shown.bs.modal', function() {
                !step && $name.focus();
            });

            $modal.modal();
        }
    });
</script>