<div class="wrapper-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Requests</h5>
                </div>
                <div class="ibox-content">
                    <table class="table table-striped m-b-none" id="requests-table">
                        <thead>
                        <tr>
                            <th>Edit</th>
                            <th>User</th>
                            <th>Product</th>
                            <th>Status</th>
                            <th>Created On</th>
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
        var $requestsTable = $('#requests-table');
        var $requestsTbody = $requestsTable.find('tbody');

        loadRequests();
        function loadRequests()
        {
            $requestsTbody.html();

            apiRequest(config.api.urls.request.all, 'GET', {}, function(response) {
                $.each(response.body.requests, function(key, request) {
                    $requestsTbody.append(formatRequest(request));
                });
            });
        }

        function formatRequest(request)
        {
            var html = '';

            html += '<tr>';
            html += '   <td><a href="<?php echo Yii::app()->createUrl('request/edit'); ?>/' + request.id + '" class="btn btn-xs btn-success" tooltip title="Edit Request"><i class="fa fa-pencil"></i></a></td>';
            html += '   <td>' + (request.user ? htmlEncode(request.user.firstName + ' ' + request.user.lastName) : '') + '</td>';
            html += '   <td>' + (request.product ? htmlEncode(request.product.name) : '') + '</td>';
            html += '   <td>' + htmlEncode(request.status.name) + '</td>';
            html += '   <td>' + request.createTime.ymdHisToDate().toLocal().toDmyHis() + '</td>';
            html += '   <td><div class="btn btn-xs btn-danger delete-button" tooltip title="Delete Request"><i class="fa fa-trash"></i></div></td>';
            html += '</tr>';

            var $request = $(html);
            var $deleteButton = $request.find('.delete-button');
            $deleteButton.on('click', function() {
                if(confirm('Delete request?'))
                {
                    apiRequest(config.api.urls.request.delete, 'POST', { request_id: request.id });

                    $deleteButton.tooltip('hide');
                    $request.remove();
                }
            });

            return $request;
        }
    });
</script>