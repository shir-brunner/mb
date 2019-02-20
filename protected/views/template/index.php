<div class="wrapper-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Templates</h5>
                    <div class="ibox-tools">
                        <a title="New Template" tooltip id="create-template-button"><i class="fa fa-plus text-success"></i></a>
                    </div>
                </div>
                <div class="ibox-content">
                    <table class="table table-striped m-b-none" id="templates-table">
                        <thead>
                            <tr>
                                <th>Edit</th>
                                <th>Name</th>
                                <th>Set</th>
                                <th>Published</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        var $templatesTable = $('#templates-table');
        var $templatesTbody = $templatesTable.find('tbody');
        var $createTemplateButton = $('#create-template-button');

        loadTemplates();
        function loadTemplates()
        {
            $templatesTbody.html();

            apiRequest(config.api.urls.template.all, 'GET', {}, function(response) {
                $.each(response.body.templates, function(key, template) {
                    $templatesTbody.append(formatTemplate(template));
                });
            });
        }

        function formatTemplate(template)
        {
            var html = '';

            html += '<tr>';
            html += '   <td><a href="<?php echo Yii::app()->createUrl('template/edit'); ?>/' + template.id + '" class="btn btn-xs btn-success" tooltip title="Edit Template"><i class="fa fa-pencil"></i></a></td>';
            html += '   <td>' + htmlEncode(template.name) + '</td>';
            html += '   <td>' + htmlEncode(template.set.name) + '</td>';
            html += '   <td><i class="fa ' + (template.published ? 'fa-check text-navy' : 'fa-remove text-danger') + '"></i></td>';
            html += '   <td><div class="btn btn-xs btn-danger delete-button" tooltip title="Delete Template"><i class="fa fa-trash"></i></div></td>';
            html += '</tr>';

            var $template = $(html);
            var $deleteButton = $template.find('.delete-button');
            $deleteButton.on('click', function() {
                if(confirm('Delete template?'))
                {
                    apiRequest(config.api.urls.template.delete, 'POST', { template_id: template.id });

                    $deleteButton.tooltip('hide');
                    $template.remove();
                }
            });

            return $template;
        }

        $createTemplateButton.on('click', function() {
            var $modal = modal('New Template');
            var $modalBody = $modal.find('.modal-body').empty();
            var $modalFooter = $modal.find('.modal-footer');

            var html = '';

            html += '<div class="form-horizontal">';
            html += '   <div class="form-group" field-name="template_name">';
            html += '       <div class="col-xs-3 text-right">';
            html += '           <label class="control-label">Name</label>';
            html += '       </div>';
            html += '       <div class="col-xs-8">';
            html += '           <input type="text" class="form-control name" />';
            html += '       </div>';
            html += '   </div>';
            html += '   <div class="form-group" field-name="template_set_id">';
            html += '       <div class="col-xs-3 text-right">';
            html += '           <label class="control-label">Set</label>';
            html += '       </div>';
            html += '       <div class="col-xs-8">';
            html += '           <select class="form-control template-set">';

            <?php
                foreach(TemplateSet::model()->findAll() as $templateSet)
                {
                    echo "html += '<option value=\"" . $templateSet->getPrimaryKey() . "\">" . $templateSet->template_set_name . "</option>';";
                }
            ?>

            html += '           </select>';
            html += '       </div>';
            html += '   </div>';
            html += '   <div class="form-group" field-name="template_price">';
            html += '       <div class="col-xs-3 text-right">';
            html += '           <label class="control-label">Price</label>';
            html += '       </div>';
            html += '       <div class="col-xs-8">';
            html += '           <div class="input-group">';
            html += '               <input type="number" class="form-control price" />';
            html += '               <div class="input-group-addon"><i class="fa fa-shekel"></i></div>';
            html += '           </div>';
            html += '       </div>';
            html += '   </div>';
            html += '   <div class="form-group" field-name="template_description">';
            html += '       <div class="col-xs-3 text-right">';
            html += '           <label class="control-label">Description</label>';
            html += '       </div>';
            html += '       <div class="col-xs-8">';
            html += '           <textarea class="form-control description"></textarea>';
            html += '       </div>';
            html += '   </div>';
            html += '</div>';

            $modalBody.append(html);

            var $name = $modalBody.find('.name');
            var $description = $modalBody.find('.description');
            var $price = $modalBody.find('.price');
            var $templateSet = $modalBody.find('.template-set');

            var createButtonText = '<i class="fa fa-check"></i> Create';
            var $createButton = $('<button type="button" class="btn btn-primary">' + createButtonText + '</button>').on('click', function() {
                if($createButton.hasClass('disabled'))
                {
                    return;
                }

                $createButton.addClass('disabled').html('<i class="fa fa-spin fa-spinner"></i> Create');
                $modalBody.find('.form-group').removeClass('has-error');

                apiRequest(config.api.urls.template.create, 'POST', {
                    name: $name.val(),
                    description: $description.val(),
                    price: $price.val(),
                    template_set_id: $templateSet.val()
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
                        window.location = '<?php echo Yii::app()->createUrl('template/edit'); ?>/' + response.body.template.id;
                    }
                    else
                    {
                        defaultErrorHandler();
                    }
                });
            }).appendTo($modalFooter);

            $modal.on('shown.bs.modal', function() {
                $name.focus();
            });

            $modal.modal();
        });
    });
</script>