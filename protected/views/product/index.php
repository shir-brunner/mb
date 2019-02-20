<div class="wrapper-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Products</h5>
                    <div class="ibox-tools">
                        <a title="New Product" tooltip id="create-product-button"><i class="fa fa-plus text-success"></i></a>
                    </div>
                </div>
                <div class="ibox-content">
                    <table class="table table-striped m-b-none" id="products-table">
                        <thead>
                        <tr>
                            <th>Edit</th>
                            <th>Name</th>
                            <th>Category</th>
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
        var $productsTable = $('#products-table');
        var $productsTbody = $productsTable.find('tbody');
        var $createProductButton = $('#create-product-button');

        loadProducts();
        function loadProducts()
        {
            $productsTbody.html();

            apiRequest(config.api.urls.product.all, 'GET', {}, function(response) {
                $.each(response.body.products, function(key, product) {
                    $productsTbody.append(formatProduct(product));
                });
            });
        }

        function formatProduct(product)
        {
            var html = '';

            html += '<tr>';
            html += '   <td><a href="<?php echo Yii::app()->createUrl('product/edit'); ?>/' + product.id + '" class="btn btn-xs btn-success" tooltip title="Edit Product"><i class="fa fa-pencil"></i></a></td>';
            html += '   <td>' + htmlEncode(product.name) + '</td>';
            html += '   <td>' + htmlEncode(product.category.name) + '</td>';
            html += '   <td><i class="fa ' + (product.published ? 'fa-check text-navy' : 'fa-remove text-danger') + '"></i></td>';
            html += '   <td><div class="btn btn-xs btn-danger delete-button" tooltip title="Delete Product"><i class="fa fa-trash"></i></div></td>';
            html += '</tr>';

            var $product = $(html);
            var $deleteButton = $product.find('.delete-button');
            $deleteButton.on('click', function() {
                if(confirm('Delete product?'))
                {
                    apiRequest(config.api.urls.product.delete, 'POST', { product_id: product.id });

                    $deleteButton.tooltip('hide');
                    $product.remove();
                }
            });

            return $product;
        }

        $createProductButton.on('click', function() {
            var $modal = modal('New Product');
            var $modalBody = $modal.find('.modal-body').empty();
            var $modalFooter = $modal.find('.modal-footer');

            var html = '';

            html += '<div class="form-horizontal">';
            html += '   <div class="form-group" field-name="product_name">';
            html += '       <div class="col-xs-3 text-right">';
            html += '           <label class="control-label">Name</label>';
            html += '       </div>';
            html += '       <div class="col-xs-8">';
            html += '           <input type="text" class="form-control name" />';
            html += '       </div>';
            html += '   </div>';
            html += '   <div class="form-group" field-name="category_id">';
            html += '       <div class="col-xs-3 text-right">';
            html += '           <label class="control-label">Category</label>';
            html += '       </div>';
            html += '       <div class="col-xs-8">';
            html += '           <select class="form-control category">';

            <?php
                foreach(Category::model()->findAll() as $category)
                {
                    echo "html += '<option value=\"" . $category->getPrimaryKey() . "\">" . GxHtml::encode($category->category_name) . "</option>';";
                }
            ?>

            html += '           </select>';
            html += '       </div>';
            html += '   </div>';
            html += '   <div class="form-group" field-name="product_price">';
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
            html += '   <div class="form-group" field-name="product_description">';
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
            var $category = $modalBody.find('.category');

            var createButtonText = '<i class="fa fa-check"></i> Create';
            var $createButton = $('<button type="button" class="btn btn-primary">' + createButtonText + '</button>').on('click', function() {
                if($createButton.hasClass('disabled'))
                {
                    return;
                }

                $createButton.addClass('disabled').html('<i class="fa fa-spin fa-spinner"></i> Create');
                $modalBody.find('.form-group').removeClass('has-error');

                apiRequest(config.api.urls.product.create, 'POST', {
                    name: $name.val(),
                    description: $description.val(),
                    price: $price.val(),
                    category_id: $category.val()
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
                        window.location = '<?php echo Yii::app()->createUrl('product/edit'); ?>/' + response.body.product.id;
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