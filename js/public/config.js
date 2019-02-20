var prod = false;
var baseAdminUrl = prod ? 'http://ec2-52-15-94-49.us-east-2.compute.amazonaws.com/mb' : 'http://localhost/mb';
var baseApiUrl = baseAdminUrl + '/api';

var config = {
    api: {
        urls: {
            request: {
                create: baseApiUrl + '/request/create',
                updateFields: baseApiUrl + '/request/updateFields',
            },
            contact: {
                create: baseApiUrl + '/contact/create',
            },
            file: {
                create: baseApiUrl + '/file/create',
            },
        },
    },
};