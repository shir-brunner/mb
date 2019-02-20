var prod = false;
var baseAdminUrl = prod ? 'http://ec2-52-15-94-49.us-east-2.compute.amazonaws.com/mb' : 'http://localhost/mb';
var baseApiUrl = baseAdminUrl + '/api';

var config = {
    api: {
        urls: {
            product: {
                all: baseApiUrl + '/product/all',
                create: baseApiUrl + '/product/create',
                update: baseApiUrl + '/product/update',
                delete: baseApiUrl + '/product/delete',
                updateExtraInfo: baseApiUrl + '/product/updateExtraInfo',
            },
            contact: {
                all: baseApiUrl + '/contact/all',
                delete: baseApiUrl + '/contact/delete',
            },
            request: {
                all: baseApiUrl + '/request/all',
                update: baseApiUrl + '/request/update',
                delete: baseApiUrl + '/request/delete',
                updateFields: baseApiUrl + '/request/updateFields',
                restoreDocument: baseApiUrl + '/request/restoreDocument',
                updateExtraInfo: baseApiUrl + '/request/updateExtraInfo',
            },
            file: {
                create: baseApiUrl + '/file/create',
                delete: baseApiUrl + '/file/delete',
                uploadImage: baseApiUrl + '/file/uploadImage',
            },
            productStep: {
                create: baseApiUrl + '/productStep/create',
                update: baseApiUrl + '/productStep/update',
                delete: baseApiUrl + '/productStep/delete',
                changeOrder: baseApiUrl + '/productStep/changeOrder',
            }
        },
    },
};