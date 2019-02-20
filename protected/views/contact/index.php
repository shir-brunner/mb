<div class="wrapper-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Contacts</h5>
                </div>
                <div class="ibox-content">
                    <table class="table table-striped m-b-none" id="contacts-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Message</th>
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
        var $contactsTable = $('#contacts-table');
        var $contactsTbody = $contactsTable.find('tbody');

        loadContacts();
        function loadContacts()
        {
            $contactsTbody.html();

            apiRequest(config.api.urls.contact.all, 'GET', {}, function(response) {
                $.each(response.body.contacts, function(key, contact) {
                    $contactsTbody.append(formatContact(contact));
                });
            });
        }

        function formatContact(contact)
        {
            var html = '';

            html += '<tr>';
            html += '   <td>' + htmlEncode(contact.name) + '</td>';
            html += '   <td>' + htmlEncode(contact.email) + '</td>';
            html += '   <td>' + htmlEncode(contact.phone) + '</td>';
            html += '   <td>' + htmlEncode(contact.message) + '</td>';
            html += '   <td>' + contact.createTime.ymdHisToDate().toDmyHis() + '</td>';
            html += '   <td><div class="btn btn-xs btn-danger delete-button" tooltip title="Delete Contact"><i class="fa fa-trash"></i></div></td>';
            html += '</tr>';

            var $contact = $(html);
            var $deleteButton = $contact.find('.delete-button');
            $deleteButton.on('click', function() {
                if(confirm('Delete contact?'))
                {
                    apiRequest(config.api.urls.contact.delete, 'POST', { contact_id: contact.id });

                    $deleteButton.tooltip('hide');
                    $contact.remove();
                }
            });

            return $contact;
        }
    });
</script>