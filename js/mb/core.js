var dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
var shortDayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
var monthNames = ["January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December"
];
var shortMonthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
    "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
];

var explainedItems = [];

const DATE_FORMAT_DMY = 1;
const DATE_FORMAT_MDY = 2;

const TIME_FORMAT_MILITARY_TIME = 1;
const TIME_FORMAT_AM_PM = 2;

const UNIT_SYSTEM_IMPERIAL = 1;
const UNIT_SYSTEM_METRIC = 2;

const EVENT_STATUS_PENDING_APPROVAL = 1;
const EVENT_STATUS_SCHEDULED = 2;

var toastCount = 0;

var defaultErrorHandler = function(jqXHR, textStatus, errorThrown) {
    if(errorThrown != 'abort')
    {
        showToast('info', 'We were unable to retrieve some information, please refresh your page.');
    }
};

function showToast(toastType, text, options)
{
    var title = options == undefined || options.title == undefined ? '' : options.title;

    toastr.options = {
        closeButton: true,
        debug: false,
        progressBar: true,
        preventDuplicates: false,
        positionClass: 'toast-bottom-right',
        onclick: options == undefined || options.onclick == undefined ? null : options.onclick,
    };

    $("#toastrOptions").text("Command: toastr["
        + toastType
        + "](\""
        + text
        + (title ? "\", \"" + title : '')
        + "\")\n\ntoastr.options = "
        + JSON.stringify(toastr.options, null, 2)
    );

    var $toast = toastr[toastType](text, title); // Wire up an event handler to a button in the toast, if it exists
}

function apiRequest(url, method, data, onComplete, onError)
{
    data = data || {};
    var loginToken = null;
    var authUser = auth ? auth.getUser() : null;

    if(authUser)
    {
        loginToken = auth.getUser().loginToken;
    }

    return $.ajax({
        url: url + '?login_token=' + loginToken,
        method: method,
        cache: false,
        data: data,
        success: function(data) {
            var response = typeof(data) == 'object' ? data : $.parseJSON(data);

            onComplete && onComplete(response);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            defaultErrorHandler(jqXHR, textStatus, errorThrown);
            onError && typeof onError == 'function' && onError(jqXHR, textStatus, errorThrown);
        },
    });
}

function guid()
{
    function s4() {
        return Math.floor((1 + Math.random()) * 0x10000)
            .toString(16)
            .substring(1);
    }
    return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
        s4() + '-' + s4() + s4() + s4();
}

function htmlEncode(value)
{
    return $('<div/>').text(value).html();
}

function htmlEncodeWithNewLines(value)
{
    return htmlEncode(value.trim().replaceAll("\n", '{{newline}}')).replaceAll('{{newline}}', "<br>");
}

function escapeRegExp(str)
{
    return str.replace(/([.*+?^=!:${}()|\[\]\/\\])/g, "\\$1");
}

String.prototype.replaceAll = function(find, replace) {
    return this.replace(new RegExp(escapeRegExp(find), 'g'), replace);
};

function htmlDecode(value)
{
    return $('<div/>').html(value).text();
}

function zeroPadTime(num)
{
    return num >= 10 ? num.toString() : (0 + num.toString());
}

function datesEqual(date1, date2)
{
    if(!date1 || !date2)
    {
        return false;
    }

    var date1Formatted = date1.getFullYear().toString() + date1.getMonth().toString() + date1.getDate().toString();
    var date2Formatted = date2.getFullYear().toString() + date2.getMonth().toString() + date2.getDate().toString();

    return date1Formatted == date2Formatted;
}

function daysDiff(date1, date2)
{
    var timeDiff = Math.abs(date2.getTime() - date1.getTime());
    return Math.ceil(timeDiff / (1000 * 3600 * 24)) - 1;
}

function secondsDiff(date1, date2)
{
    var timeDiff = Math.abs(date2.getTime() - date1.getTime());
    return Math.ceil(timeDiff / 1000);
}

Date.prototype.clone = function() {
    return new Date(this.valueOf());
};

Date.prototype.toHi = function() {
    return zeroPadTime(this.getHours()) + ':' + zeroPadTime(this.getMinutes());
};

Date.prototype.toStartOfHour = function() {
    var ymd = this.getFullYear() + '-' + zeroPadTime(this.getMonth() + 1) + '-' + zeroPadTime(this.getDate());
    return (ymd + ' ' + zeroPadTime(this.getHours()) + ':00:00').ymdHisToDate();
};

Date.prototype.toStartOfDay = function() {
    var ymd = this.getFullYear() + '-' + zeroPadTime(this.getMonth() + 1) + '-' + zeroPadTime(this.getDate());
    return (ymd + ' 00:00:00').ymdHisToDate();
};

Date.prototype.toEndOfDay = function() {
    var ymd = this.getFullYear() + '-' + zeroPadTime(this.getMonth() + 1) + '-' + zeroPadTime(this.getDate());
    return (ymd + ' 23:59:59').ymdHisToDate();
};

Date.prototype.toStartOfMonth = function() {
    return (this.getFullYear() + '-' + zeroPadTime(this.getMonth() + 1) + '-01').ymdToDate();
};

Date.prototype.toEndOfMonth = function() {
    return (this.getFullYear() + '-' + zeroPadTime(this.getMonth() + 1) + this.daysInMonth()).ymdToDate();
};

Date.prototype.daysInMonth = function() {
    return new Date(this.getFullYear(), this.getMonth() + 1, 0).getDate();
};

Date.prototype.between = function(fromDate, toDate) {
    var timestamp = this.getTime();
    return timestamp >= fromDate.getTime() && timestamp < toDate.getTime();
};

Date.prototype.toYmd = function() {
    return this.getFullYear() + '-' + (zeroPadTime(this.getMonth() + 1)) + '-' + zeroPadTime(this.getDate());
};

Date.prototype.toYmdHis = function() {
    return this.toYmd() + ' ' + zeroPadTime(this.getHours()) + ':' + zeroPadTime(this.getMinutes()) + ':' + zeroPadTime(this.getSeconds());
};

Date.prototype.toYmdHi = function() {
    return this.toYmd() + ' ' + zeroPadTime(this.getHours()) + ':' + zeroPadTime(this.getMinutes());
};

Date.prototype.toDmy = function() {
    return zeroPadTime(this.getDate()) + '/' + (zeroPadTime(this.getMonth() + 1)) + '/' + this.getFullYear();
};

Date.prototype.toDmyHis = function() {
    return this.toDmy() + ' ' + zeroPadTime(this.getHours()) + ':' + zeroPadTime(this.getMinutes()) + ':' + zeroPadTime(this.getSeconds());
};

Date.prototype.toDmyHi = function() {
    return this.toDmy() + ' ' + (zeroPadTime(this.getHours()) + ':' + zeroPadTime(this.getMinutes())).formatTime();
};

Date.prototype.toMdy = function() {
    return (zeroPadTime(this.getMonth() + 1)) + '/' + zeroPadTime(this.getDate()) + '/' + this.getFullYear();
};

Date.prototype.toMdyHis = function() {
    return this.toMdy() + ' ' + zeroPadTime(this.getHours()) + ':' + zeroPadTime(this.getMinutes()) + ':' + zeroPadTime(this.getSeconds());
};

Date.prototype.toMdyHi = function() {
    return this.toMdy() + ' ' + (zeroPadTime(this.getHours()) + ':' + zeroPadTime(this.getMinutes())).formatTime();
};

Date.prototype.toDm = function() {
    return zeroPadTime(this.getDate()) + '/' + zeroPadTime(this.getMonth() + 1);
};

Date.prototype.toMd = function() {
    return zeroPadTime(this.getMonth() + 1) + '/' + zeroPadTime(this.getDate());
};

Date.prototype.toFjy = function(short) {
    var monthName = short ? shortMonthNames[this.getMonth()] : monthNames[this.getMonth()];
    return monthName + ' ' + this.getDate() + ', ' + this.getFullYear();
};

Date.prototype.isToday = function() {
    return datesEqual(this, new Date());
};

Date.prototype.isYesterday = function() {
    return datesEqual(this, (new Date()).yesterday());
};

Date.prototype.tomorrow = function() {
    var tomorrow = new Date(this.valueOf());
    tomorrow.setDate(this.getDate() + 1);
    return tomorrow;
};

Date.prototype.yesterday = function() {
    var yesterday = new Date(this.valueOf());
    yesterday.setDate(this.getDate() - 1);
    return yesterday;
};

Date.prototype.subtractDays = function(days) {
    this.setDate(this.getDate() - parseInt(days));
    return this;
};

Date.prototype.addDays = function(days) {
    this.setDate(this.getDate() + parseInt(days));
    return this;
};

Date.prototype.addMinutes = function(minutes) {
    this.setMinutes(this.getMinutes() + parseInt(minutes));
    return this;
};

Date.prototype.addHours = function(hours) {
    this.setHours(this.getHours() + parseInt(hours));
    return this;
};

Date.prototype.subtractMonths = function(months) {
    this.setMonth(this.getMonth() - months);
    return this;
};

Date.prototype.addMonths = function(months) {
    this.setMonth(this.getMonth() + months);
    return this;
};

Date.prototype.nextDay = function() {
    var date = new Date(this.valueOf());
    date.setDate(date.getDate() + 1);
    return date;
};

Date.prototype.prevDay = function() {
    var date = new Date(this.valueOf());
    date.setDate(date.getDate() - 1);
    return date;
};

Date.prototype.lastWeek = function() {
    var date = new Date(this.valueOf());
    date.setDate(date.getDate() - 7);
    return date;
};

Date.prototype.nextWeek = function() {
    var date = new Date(this.valueOf());
    date.setDate(date.getDate() + 7);
    return date;
};

Date.prototype.lastMonth = function() {
    var date = new Date(this.valueOf());
    date.setMonth(date.getMonth() - 1);
    return date;
};

Date.prototype.lastYear = function() {
    var date = new Date(this.valueOf());
    date.setFullYear(date.getFullYear() - 1);
    return date;
};

Date.prototype.nextYear = function() {
    var date = new Date(this.valueOf());
    date.setFullYear(date.getFullYear() + 1);
    return date;
};

Date.prototype.nextMonth = function() {
    var date = new Date(this.valueOf());
    date.setMonth(date.getMonth() + 1);
    return date;
};

Date.prototype.lastSunday = function() {
    if(this.getDay() == 0) //already sunday
    {
        return new Date(this.valueOf());
    }

    var date = new Date();
    date.setDate(this.getDate() - this.getDay());
    return date;
};

Date.prototype.nextSunday = function() {
    return this.lastSunday().addDays(7);
};

Date.prototype.sameYearAs = function(date) {
    return this.getFullYear() == date.getFullYear();
};

Date.prototype.justNow = function() {
    return secondsDiff(this, new Date()) < 60;
};

Date.prototype.toLocal = function() {
    var timeZoneOffset = (new Date().getTimezoneOffset() / 60) * -1;
    return this.clone().addHours(timeZoneOffset);
};

Date.prototype.toTimeZone = function(offset) {
    return this.clone().addHours(offset);
};

Date.prototype.toUtcFromTimeZone = function(offset) {
    return this.clone().addHours(offset * -1);
};

Date.prototype.toUtc = function() {
    return new Date(this.getTime() + (this.getTimezoneOffset() * 60000));
};

Date.prototype.toHowLongAgo = function(showTime, short) {
    showTime = showTime || false;
    short = short || false;
    var now = new Date();

    if(this.getTime() > now.getTime()) //somewhen in the future...
    {
        var secondsAhead = secondsDiff(this, now);
        var minutesAhead = parseInt(secondsAhead / 60);
        var hoursAhead = parseInt(minutesAhead / 60);
        var daysAhead = parseInt(Math.ceil(hoursAhead / 24));
        var weeksAhead = parseInt(daysAhead / 7);
        var yearsAhead = parseInt(weeksAhead / 52);

        if(minutesAhead < 60)
        {
            if(short)
            {
                return minutesAhead == 1 ? '1 min' : (minutesAhead + ' mins');
            }
            return minutesAhead == 1 ? 'In 1 minute' : ('In ' + minutesAhead + ' minutes');
        }

        if(hoursAhead < 24)
        {
            if(short)
            {
                return hoursAhead == 1 ? '1 hr' : (hoursAhead + ' hrs');
            }
            return hoursAhead == 1 ? 'In 1 hour' : ('In ' + hoursAhead + ' hours');
        }

        if(daysAhead < 7)
        {
            if(short)
            {
                return daysAhead + 'd';
            }
            return daysAhead == 1 ? 'Tomorrow' : ('In ' + daysAhead + ' days');
        }

        if(weeksAhead < 52)
        {
            if(short)
            {
                return weeksAhead == 1 ? '1 week' : (weeksAhead + ' weeks');
            }
            return weeksAhead == 1 ? 'In 1 week' : ('In ' + weeksAhead + ' weeks');
        }

        if(short)
        {
            return yearsAhead + 'yr';
        }
        return yearsAhead == 1 ? 'In 1 year' : ('In ' + yearsAhead + ' years');
    }

    if(showTime && this.justNow())
    {
        return 'Just now';
    }

    if(!showTime && this.isToday())
    {
        return showTime ? 'Today at ' + this.getHours() + ':' + this.getMinutes() : 'Today';
    }

    if(!showTime && this.isYesterday())
    {
        return showTime ? 'Yesterday at ' + this.getHours() + ':' + this.getMinutes() : 'Yesterday';
    }

    var secondsAgo = secondsDiff(this, now);
    var minutesAgo = parseInt(secondsAgo / 60);
    var hoursAgo = parseInt(minutesAgo / 60);
    var daysAgo = parseInt(Math.ceil(hoursAgo / 24));
    var weeksAgo = parseInt(daysAgo / 7);
    var yearsAgo = parseInt(weeksAgo / 52);

    if(minutesAgo < 60)
    {
        if(short)
        {
            return minutesAgo == 1 ? '1 min' : (minutesAgo + ' mins');
        }
        return minutesAgo == 1 ? '1 minute ago' : (minutesAgo + ' minutes ago');
    }

    if(hoursAgo < 24)
    {
        if(short)
        {
            return hoursAgo == 1 ? '1 hr' : (hoursAgo + ' hrs');
        }
        return hoursAgo == 1 ? '1 hour ago' : (hoursAgo + ' hours ago');
    }

    if(daysAgo < 7)
    {
        if(short)
        {
            return daysAgo + 'd';
        }
        return daysAgo == 1 ? 'Yesterday' : (daysAgo + ' days ago');
    }

    if(weeksAgo < 52)
    {
        if(short)
        {
            return weeksAgo == 1 ? '1 week' : (weeksAgo + ' weeks');
        }
        return weeksAgo == 1 ? '1 week ago' : (weeksAgo + ' weeks ago');
    }

    if(short)
    {
        return yearsAgo + 'yr';
    }
    return yearsAgo == 1 ? '1 year ago' : (yearsAgo + ' years ago');
};

Date.prototype.toGoogleFormat = function() {
    return this.getFullYear()  + (zeroPadTime(this.getMonth() + 1)) +  zeroPadTime(this.getDate()) + 'T' + zeroPadTime(this.getHours()) + zeroPadTime(this.getMinutes()) + '00Z';
};

String.prototype.spaceBeforeCapitals = function() {
    return this.replace(/([A-Z])/g, ' $1').trim();
};

String.prototype.ymdToDate = function() {
    var thisString = this.toString().trim();
    var spaceIndex = thisString.indexOf(' ');
    var str = spaceIndex == -1 ? thisString : thisString.substr(0, spaceIndex); //remove everything after the space (usually the time), as in "Y-m-d H:i:s"
    var parts = str.split("-");
    return new Date(parts[0], parts[1] - 1, parts[2]);
};

String.prototype.dmyToDate = function() {
    var thisString = this.toString().trim();
    var spaceIndex = thisString.indexOf(' ');
    var str = spaceIndex == -1 ? thisString : thisString.substr(0, spaceIndex); //remove everything after the space (usually the time), as in "d/m/Y H:i:s"
    var parts = str.split("/");
    return new Date(parts[2], parts[1] - 1, parts[0]);
};

String.prototype.mdyToDate = function() {
    var thisString = this.toString().trim();
    var spaceIndex = thisString.indexOf(' ');
    var str = spaceIndex == -1 ? thisString : thisString.substr(0, spaceIndex); //remove everything after the space (usually the time), as in "d/m/Y H:i:s"
    var parts = str.split("/");
    return new Date(parts[2], parts[0] - 1, parts[1]);
};

String.prototype.ymdHisToDate = function() {
    var thisString = this.toString().trim();
    var spaceIndex = thisString.indexOf(' ');
    var leftPart = thisString.substr(0, spaceIndex).trim();
    var parts = leftPart.split("-");

    var year = parts[0];
    var month = parts[1] - 1;
    var day = parts[2];

    var rightPart = thisString.substr(spaceIndex).trim();
    parts = rightPart.split(':');
    var hour = parts[0];
    var minutes = parts[1];
    var seconds = parts[2];

    return new Date(year, month, day, hour, minutes, seconds);
};

String.prototype.ymdHiToDate = function() {
    var thisString = this.toString().trim();
    var spaceIndex = thisString.indexOf(' ');
    var leftPart = thisString.substr(0, spaceIndex).trim();
    var parts = leftPart.split("-");

    var year = parts[0];
    var month = parts[1] - 1;
    var day = parts[2];

    var rightPart = thisString.substr(spaceIndex).trim();
    parts = rightPart.split(':');
    var hour = parts[0];
    var minutes = parts[1];

    return new Date(year, month, day, hour, minutes);
};

String.prototype.capitalizeFirstLetter = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
};

String.prototype.capitalize = function() {
    return this.replace(/(?:^|\s)\S/g, function(a) { return a.toUpperCase(); });
};

String.prototype.labelize = function() {
    return this.spaceBeforeCapitals().capitalizeFirstLetter();
};

String.prototype.pluralize = function() {
    return pluralize.plural(this);
};

Number.prototype.toFixedDown = function(digits) {
    var re = new RegExp("(\\d+\\.\\d{" + digits + "})(\\d)"),
        m = this.toString().match(re);
    return m ? parseFloat(m[1]) : this.valueOf();
};

Number.prototype.roundDecimal = function() {
    return Math.round(this * 100) / 100;
};

Array.prototype.max = function() {
    return Math.max.apply(null, this);
};

Array.prototype.min = function() {
    return Math.min.apply(null, this);
};

Array.prototype.contains = function(value) {
    return this.indexOf(value) != -1;
};

Array.prototype.firstElements = function(howMany) {
    return this.slice(0, howMany);
};

var platformChecker = {
    isAndroid: function() {
        return navigator.userAgent.match(/Android/i);
    },
    isBlackBerry: function() {
        return navigator.userAgent.match(/BlackBerry/i);
    },
    isIos: function() {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
    },
    isOpera: function() {
        return navigator.userAgent.match(/Opera Mini/i);
    },
    isWindows: function() {
        return navigator.userAgent.match(/IEMobile/i) || navigator.userAgent.match(/WPDesktop/i);
    },
    isMobile: function() {
        return this.isAndroid() || this.isBlackBerry() || this.isIos() || this.isOpera() || this.isWindows();
    },
    getPlatform: function() {
        if(this.isAndroid()) return 'Android';
        if(this.isBlackBerry()) return 'BlackBerry';
        if(this.isIos()) return 'iOS';
        if(this.isOpera()) return 'Opera';

        var isOpera = (!!window.opr && !!opr.addons) || !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;
        if(isOpera) return 'Opera';

        var isFirefox = typeof InstallTrigger !== 'undefined';
        if(isFirefox) return 'Firefox';

        var isSafari = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0 || (function (p) { return p.toString() === "[object SafariRemoteNotification]"; })(!window['safari'] || safari.pushNotification);
        if(isSafari) return 'Safari';

        var isIE = /*@cc_on!@*/false || !!document.documentMode;
        if(isIE) return 'IE';

        var isEdge = !isIE && !!window.StyleMedia;
        if(isEdge) return 'Edge';

        var isChrome = !!window.chrome && !!window.chrome.webstore;
        if(isChrome) return 'Chrome';

        if(this.isWindows()) return 'Windows';

        return 'Unknown';
    }
};

$(document).ready(function() {
    explainedItems = JSON.parse(localStorage.getItem('explainedItems')) || [];

    $.fn.hasClassOrChildOf = function(selector) {
        return $(this).hasClass(selector) || $(this).parents('.' + selector).length;
    };

    $.fn.getText = function() {
        var $element = $('<div>' + $(this).html() + '</div>');
        var text = $element.children('div').map(function() {
            return $(this).text();
        }).get().join('\n');
        $element.children('div').remove();
        return $element.text() + '\n' + text;
    };

    $.fn.updateTooltip = function(text) {
        return $(this).tooltip('hide').attr('data-original-title', text).tooltip('fixTitle');
    };

    $.fn.scrolledToBottom = function(offsetFromBottom) {
        offsetFromBottom = offsetFromBottom || 0;
        return $(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight - offsetFromBottom;
    };

    $.fn.hasScrollBar = function() {
        return $(this)[0].scrollHeight > $(this).height();
    };

    $.fn.scrolledToTop = function() {
        return $(this).scrollTop() == 0;
    };

    $.fn.scrollToBottom = function() {
        $(this).scrollTop(function() { return this.scrollHeight; });
    };

    $.fn.scrollToTop = function() { $(this).scrollTop(0); };

    $.fn.isScrolledIntoView = function() {
        var docViewTop = $(window).scrollTop();
        var docViewBottom = docViewTop + $(window).height();

        var elemTop = $(this).offset().top;
        var elemBottom = elemTop + $(this).height();

        return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
    };

    $.fn.fadeOutAndRemove = function() {
        $(this).fadeOut(function() {
            $(this).remove();
        });
    };

    $.fn.quickClientCreator = function(contact, options) {
        options = options || {};

        var $viewDetails = $(this);
        $viewDetails.popover({
            container: 'body',
            html: true,
            placement: 'top',
            content: function() {
                var html = '';
                var registerButtonClass = 'fa-user-plus';

                html += '<div>';
                html += '   <h4>' + htmlEncode(contact.name.capitalize()) + '</h4>';
                html += '   <p>';
                html += '       <i class="fa fa-envelope"></i> ' + contact.email;
                if(contact.mobile)
                {
                    html += '       <br /><i class="fa fa-phone"></i> ' + contact.mobile;
                }
                html += '   </p>';
                html += '   <span class="btn btn-success btn-sm btn-block register-button"><i class="fa ' + registerButtonClass + '"></i> Register ' + htmlEncode(contact.firstName.capitalize()) + ' as your client</span>';
                html += '</div>';

                var $content = $(html);
                var $registerButton = $content.find('.register-button');

                $registerButton.on('click', function() {
                    if($registerButton.hasClass('disabled'))
                    {
                        return;
                    }

                    $registerButton.find('i').addClass('fa-spin fa-spinner').removeClass(registerButtonClass);
                    $registerButton.addClass('disabled');

                    apiRequest(config.api.urls.client.create, 'POST', { contact_id: contact.id }, function(response) {
                        if(response.status == 'OK')
                        {
                            refreshUserState(function() {
                                $viewDetails.popover('hide');

                                var client = response.body.client;
                                successMessage(client);
                                options.success && options.success(client);
                            });
                        }
                        else
                        {
                            $viewDetails.popover('hide');
                            createClientDialog(contact, function(client) {
                                successMessage(client);
                                options.success && options.success(client);
                            });
                        }

                        function successMessage(client)
                        {
                            showToast('success', htmlEncode(client.user.name.capitalize()) + ' has been registered as your client.', {
                                onclick: function() {
                                    window.location = adminUrls.client.profile + '/' + client.id;
                                },
                            });
                        }
                    });
                });

                return $content;
            }
        });
    };

    $.fn.registerClientButton = function(contact, options) {
        options = options || {};
        var $registerButton = $(this);
        var $registerButtonIcon = $(this).find('i');

        $registerButton.off('click').on('click', function() {
            if($registerButton.hasClass('disabled'))
            {
                return;
            }

            $registerButtonIcon.addClass('fa-spin fa-spinner').removeClass('fa-user-plus');
            $registerButton.addClass('disabled');

            if(contact)
            {
                apiRequest(config.api.urls.client.create, 'POST', { contact_id: contact.id }, function(response) {
                    if(response.status == 'OK')
                    {
                        refreshUserState(function() {
                            var client = response.body.client;
                            successMessage(client);
                            options.success && options.success(client);
                        });
                    }
                    else
                    {
                        onContactFailed();
                    }
                });
            }
            else
            {
                onContactFailed();
            }

            function onContactFailed()
            {
                $registerButtonIcon.removeClass('fa-spin fa-spinner').addClass('fa-user-plus');
                $registerButton.removeClass('disabled');

                if(options.autoCreateFailed)
                {
                    options.autoCreateFailed(showDialog);
                }
                else
                {
                    showDialog();
                }

                function showDialog()
                {
                    createClientDialog(contact, function(client) {
                        successMessage(client);
                        options.success && options.success(client, true);
                    });
                }
            }

            function successMessage(client)
            {
                showToast('success', htmlEncode(client.user.name.capitalize()) + ' has been registered as your client.', {
                    onclick: function() {
                        window.location = adminUrls.client.profile + '/' + client.id;
                    },
                });
            }
        });
    };

    $.fn.updatedAnimation = function(options, onComplete, onIconShow, onFadeStart) {
        if(typeof(options) == 'function')
        {
            onComplete = options;
            options = {};
        }

        var $overlay = $('<div><i class="fa ' + (options.icon || 'fa-check') + '"></i></div>');
        $overlay.css({
            'top': '0px',
            'left': '0px',
            'position': 'absolute',
            'width': '100%',
            'height': '100%',
            'background': options.background || 'rgb(26, 179, 148)',
            'color': 'white',
            'z-index': 1,
            'text-align': 'center',
            'font-size': '70px',
            'padding-top': options.paddingTop || '30px',
            'display': 'none',
            'opacity': 0.4
        }).appendTo($(this)).fadeIn(function() {
            onIconShow && onIconShow();
            setTimeout(function() {
                onFadeStart && onFadeStart();
                $overlay.fadeOut(function() {
                    $overlay.remove();
                    onComplete && onComplete();
                });
            }, 400);
        });
    };

    $.fn.explain = function(options) {
        return;
        if($('.explained').length != 0 || $(this).length == 0)
        {
            return;
        }

        var timeout = $(this).data('timeout');

        if(options == 'refresh')
        {
            $('.backdrops').remove();
            $(this).each(function() {
                $(this).popover('destroy');
                var explainOptions = $(this).data('explain-options');
                $(this).explain(explainOptions);
            });

            clearTimeout(timeout);
            return;
        }
        else if(options == 'remove')
        {
            $('.backdrops').remove();
            $(this).each(function() {
                $(this).popover('destroy');
                $(this).removeClass('explained');
            });

            enableScroll();
            clearTimeout(timeout);
            return;
        }

        options = options || {};

        if(explained(options.storageKey))
        {
            options.onClose && options.onClose(false);
            return;
        }

        options.placement = options.placement || 'top';
        options.content = options.content || '';

        if(!options.storageKey)
        {
            throw '"storageKey" is required when using Explain.';
        }

        var $explained = $(this).first();
        $explained.data('explain-options', options);

        if(!$explained.isScrolledIntoView())
        {
            $(window).on('scroll.explain', function() {
                if($explained.parents('html').length == 0) //element has been removed before had the chance to be explained
                {
                    $(window).off('scroll.explain');
                    return;
                }

                if($explained.isScrolledIntoView())
                {
                    setTimeout(function() {
                        options.placement = 'top';
                        $explained.explain(options);
                    }, 300);

                    $(window).off('scroll.explain');
                }
            });
            return;
        }

        $(this).data('timeout', setTimeout(function() {
            var rectMargin = options.rectMargin || 0;
            var $backdrops = $('<div class="backdrops"></div>');
            var offset = $explained.offset();
            var height = $explained.innerHeight();
            var width = $explained.innerWidth();
            var scrollTop = $(window).scrollTop();

            var $topBackdrop = $('<div class="tour-backdrop"></div>').css({
                'top': 0,
                'left': 0,
                'width': '100%',
                'height': offset.top - rectMargin - scrollTop,
            });

            var $bottomBackdrop = $('<div class="tour-backdrop"></div>').css({
                'top': offset.top + height + rectMargin - scrollTop,
                'left': 0,
                'width': '100%',
                'height': $(window).height() - offset.top - height - rectMargin + scrollTop,
            });

            var $leftBackdrop = $('<div class="tour-backdrop"></div>').css({
                'top': offset.top - rectMargin - scrollTop,
                'left': 0,
                'width': offset.left - rectMargin,
                'height': height + (rectMargin * 2),
            });

            var $rightBackdrop = $('<div class="tour-backdrop"></div>').css({
                'top': offset.top - rectMargin - scrollTop,
                'left': offset.left + width + rectMargin,
                'width': $(window).width() - offset.left - width - rectMargin,
                'height': height + (rectMargin * 2),
            });

            $backdrops.append($topBackdrop);
            $backdrops.append($bottomBackdrop);
            $backdrops.append($leftBackdrop);
            $backdrops.append($rightBackdrop);
            $backdrops.append($('<div class="glass-overlay"></div>').css({
                'top': offset.top - rectMargin,
                'left': offset.left - rectMargin,
                'width': width + (rectMargin * 2),
                'height': height + (rectMargin * 2),
                'position': 'absolute',
            }));

            $backdrops.find('.tour-backdrop').css('z-index', 10000);
            $('body').append($backdrops);

            var popoverOptions = {
                template: '<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>',
                html: true,
                title: options.title || null,
                content: function() {
                    var $content = $('<div />');
                    $content.html(options.content);

                    var $gotItButton = $('<div class="text-right m-t"><div class="btn btn-primary btn-sm">' + (options.confirmText || 'OK, got it!') + '</div></div>');

                    $gotItButton.on('click', function() {
                        $explained.removeClass('explained');

                        markExplained(options.storageKey);

                        $backdrops.remove();
                        $explained.popover('destroy');
                        enableScroll();
                        options.onClose && options.onClose(true);
                    });

                    $content.append($gotItButton);

                    return $content;
                },
                placement: options.placement,
                container: 'body',
            };

            var originalTitle = $explained.attr('title');
            $explained.removeAttr('title'); //remove the title because it is being added to the popover
            $explained.popover(popoverOptions).popover('show');
            $explained.attr('title', originalTitle); //put it back after the popover is shown

            var $popover = $('#' + $explained.attr('aria-describedby')).css('z-index', '10001');
            var $arrow = $popover.find('.arrow');

            if(!$popover.isScrolledIntoView())
            {
                //if the popover is for some reason out of the screen, flip "top" with "bottom" and vice versa
                options.placement = options.placement == 'top' ? 'bottom' : 'top';
                options.wait = 0;

                $explained.explain('remove');
                $explained.explain(options);
            }

            if(options.placement == 'bottom')
            {
                var marginRight = 10;
                $popover.css('margin-top', 10 + rectMargin);
                $popover.css('margin-right', marginRight);

                $arrow.css('left', parseFloat($arrow.css('left')) + marginRight - 4);
            }

            options.popoverCss && $popover.css(options.popoverCss);

            if(options.popoverHorizontalOffset)
            {
                var curLeft = parseInt($popover.css('left'));
                $popover.css('left', (curLeft + options.popoverHorizontalOffset) + 'px');
            }

        }, options.wait || 0));

        disableScroll();

        $explained.addClass('explained');
    };

    $(this).on('click', function(e) {
        var $target = $(e.target);
        var $collapsableParent = $target.parents('.collapsable');

        if($target.hasClass('ignore-collapse'))
        {
            return; //stupid bug, stupid solution, but works
        }

        //another stupid bug:
        //because datepicker doesn't have a "container" options, it is appended to the body,
        //which causes the popover to close on a child datepicker click.
        //what happens is that the "select" event of the datepicker is triggered before this "click" event,
        //which destroys $target's parents, so we have to be more specific and refer to the $target
        //class itself (day), and that is why hasClassOrChildOf('datepicker') is not enough in this case :(
        if($target.hasClassOrChildOf('datepicker')
            || $target.hasClassOrChildOf('day')
                || $target.hasClassOrChildOf('month')
                    || $target.hasClassOrChildOf('year'))
        {
            return;
        }

        if($target.hasClassOrChildOf('collapsable'))
        {
            $('.collapsable').each(function() {
                if($(this).is($target) || $(this).is($collapsableParent))
                {
                    return;
                }

                $(this).trigger('collapse', [$target]);
            });

            return;
        }

        $('.collapsable').trigger('collapse', [$target]);
    });


    $(this).on('collapse', '.popover', function(e, $clickedElement) {
        var popoverId = $(this).attr('id');

        if($clickedElement.attr('aria-describedby') == popoverId
            || $clickedElement.parents('[aria-describedby="' + popoverId + '"]').length)
        {
            return;
        }

        $('[aria-describedby="' + popoverId + '"]').each(function() {
            $(this).addClass('ignore-collapse');
            $(this).trigger('click');
            $(this).removeClass('ignore-collapse');
        });
    });

    $.fn.entitySelector = function(options) {
        var entitySelector = $(this).data('entitySelector');
        if(!entitySelector)
        {
            entitySelector = new EntitySelector(this, options);
            $(this).data('entitySelector', entitySelector);
        }

        return entitySelector;
    };

    $.fn.foodSelector = function(options) {
        var $foodSelector = $(this);
        options = options || {};
        options = $.extend({}, options, {
            sourceUrl: config.api.urls.food.all,
            resultsKey: 'items',
            entityRenderer: function(food) {
                var html = '';

                html += '<div class="chat-user food-selector-item">';
                html += '	    <h3>' + htmlEncode(food.name.capitalizeFirstLetter()) + '</h3>';
                html += '	    <span class="p-l food-brand"><i>' + htmlEncode(food.brand) + '</i></span>';
                html += '       <h5>' + food.measures[0].calories + ' Calories | ' + food.measures[0].fat + 'g Fat | ' + food.measures[0].protein + 'g Protein | ' + food.measures[0].carbs + 'g Carbs (' + htmlEncode(food.measures[0].name.capitalize()) + ')</h5>';
                html += '</div>';

                return $(html);
            },
            addItemRenderer: function(entityName) {
                var html = '';

                html += '<div class="chat-user food-selector-item">';
                html += '   <i class="fa fa-plus"></i>';
                html += '	<h3>Add "' + entityName.capitalize() + '"</h3>';
                html += '</div>';

                return $(html);
            },
            addItemForm: function(entityName) {
                var html = '';

                html += '<div class="wrapper">';
                html += '   <div class="row border-bottom">';
                html += '       <h2 class="text-center">Create new food</h2>';
                html += '   </div>';

                html += '   <div class="form-horizontal">';
                html += '       <form class="add-food-form">';

                html += '           <div class="row m-t">';
                html += '               <div class="form-group">';
                html += '                   <label class="col-lg-4 control-label">Name</label>';
                html += '                   <div class="col-lg-7"><input class="form-control" name="Food[food_name]" /></div>';
                html += '               </div>';
                html += '           </div>';

                html += '           <div class="row">';
                html += '               <div class="form-group">';
                html += '                   <label class="col-lg-4 control-label">Brand</label>';
                html += '                   <div class="col-lg-7"><input class="form-control" name="Food[food_brand]" /></div>';
                html += '               </div>';
                html += '           </div>';

                html += '           <div class="row">';
                html += '               <div class="form-group">';
                html += '                   <label class="col-lg-4 control-label">Measure</label>';
                html += '                   <div class="col-lg-7">';
                html += '                       <input type="text" class="form-control" name="FoodMeasure[food_measure_name]" />';
                html += '                   </div>';
                html += '               </div>';
                html += '           </div>';

                html += '           <div class="row">';
                html += '               <div class="form-group">';
                html += '                   <label class="col-lg-4 control-label">Calories</label>';
                html += '                   <div class="col-lg-7"><input type="number" min="0" class="form-control" name="FoodMeasure[food_measure_calories]" /></div>';
                html += '               </div>';
                html += '           </div>';

                html += '           <div class="row">';
                html += '               <div class="form-group">';
                html += '                   <label class="col-lg-4 control-label">Fat</label>';
                html += '                   <div class="col-lg-7"><input type="number" min="0" class="form-control" name="FoodMeasure[food_measure_fat]" /></div>';
                html += '               </div>';
                html += '           </div>';

                html += '           <div class="row">';
                html += '               <div class="form-group">';
                html += '                   <label class="col-lg-4 control-label">Protein</label>';
                html += '                   <div class="col-lg-7"><input type="number" min="0" class="form-control" name="FoodMeasure[food_measure_protein]" /></div>';
                html += '               </div>';
                html += '           </div>';

                html += '           <div class="row">';
                html += '               <div class="form-group">';
                html += '                   <label class="col-lg-4 control-label">Carbs</label>';
                html += '                   <div class="col-lg-7"><input type="number" min="0" class="form-control" name="FoodMeasure[food_measure_carbs]" /></div>';
                html += '               </div>';
                html += '           </div>';

                html += '           <hr />';

                html += '           <div class="row m-b">';
                html += '               <div class="btn btn-default pull-left m-l cancel-button"><i class="fa fa-remove"></i> Cancel</div>';
                html += '               <div class="btn btn-primary pull-right m-r create-button"><i class="fa fa-save"></i> Create</div>';
                html += '           </div>';

                html += '       </form>';
                html += '   </div>';
                html += '</div>';

                var $addItemForm = $(html);

                $addItemForm.find('input[name="Food[food_name]"]').val(entityName.capitalize());
                $addItemForm.find('.cancel-button').on('click', function() {
                    $foodSelector.removeAttr('disabled');
                    $(this).parents('.add-item-container').hide();
                });

                $addItemForm.find('.create-button').on('click', function() {
                    if($(this).attr('loading') == 'loading')
                    {
                        return;
                    }

                    var $createButton = $(this);
                    var $form = $addItemForm.find('form');

                    $createButton.attr('loading', 'loading');
                    $addItemForm.find(".form-group").removeClass('has-error');

                    apiRequest(config.api.urls.food.create, 'POST', $form.serialize(), function(response) {
                        if(response.status == 'ERROR')
                        {
                            $.each(response.body.errors, function(key, value) {
                                showToast('error', value);
                                $addItemForm.find('[name="' + key + '"]').parents('.form-group').addClass('has-error');
                            });

                            $createButton.removeAttr('loading');
                        }
                        else
                        {
                            $foodSelector.entitySelector().setEntity(response.body.food);
                            $foodSelector.trigger('entitySelected', [response.body.food]);
                            $addItemForm.find('.cancel-button').trigger('click');
                            $foodSelector.removeAttr('disabled');
                        }
                    });
                });

                return $addItemForm;
            },
        });

        return $foodSelector.entitySelector(options);
    };

    $(window).on('resize', onWindowResize);
    onWindowResize();
    function onWindowResize()
    {
        $('.explained').each(function() { $(this).explain('refresh'); });
    }

    $(this).on('click', '.dd-item .dd-handle.collapsable', function(e) {
        if($(e.target).hasClassOrChildOf('cancel-collapse'))
        {
            return;
        }

        var $ddItem = $(this).parent('.dd-item');
        var $collapseButton = $ddItem.children('.collapse-button');
        var collapsed = $collapseButton.attr('data-action') != 'collapse';

        collapsed ? $(this).trigger('dd-expand') : $(this).trigger('dd-collapse');
    });

    $(this).on('click', '.dd-list .collapse-button', function(e) {
        $(this).parent('.dd-item').children('.collapsable').trigger('click');
    });

    $(this).on('dd-collapse', '.dd-item .dd-handle.collapsable', function(e, slideEffect) {
        slideEffect = slideEffect == undefined ? true : slideEffect;
        var $ddItem = $(this).parent('.dd-item');
        var $collapseButton = $ddItem.children('.collapse-button');
        var collapsed = $collapseButton.attr('data-action') != 'collapse';
        var $animatable = $ddItem.children('.dd-list');

        if(!collapsed)
        {
            $collapseButton.removeAttr('data-action');
            slideEffect ? $animatable.slideUp() : $animatable.hide();
        }
    });

    $(this).on('dd-expand', '.dd-item .dd-handle.collapsable', function(e, slideEffect) {
        slideEffect = slideEffect == undefined ? true : slideEffect;
        var $ddItem = $(this).parent('.dd-item');
        var $collapseButton = $ddItem.children('.collapse-button');
        var collapsed = $collapseButton.attr('data-action') != 'collapse';
        var $animatable = $ddItem.children('.dd-list');

        if(collapsed)
        {
            $collapseButton.attr('data-action', 'collapse');
            slideEffect ? $animatable.slideDown() : $animatable.show();
        }
    });
});

function EntitySelector($input, options) {
    options = options || {};
    options.resultsKey = options.resultsKey || 'entities';
    options.multiSelect = options.multiSelect || false;
    options.limit = options.limit || 60;
    options.searchParams = options.searchParams || {};
    options.entityRenderer = options.entityRenderer || function(entity) {
        var html = '';

        html += '<div class="entity chat-user item">';
        html += '   <i class="fa fa-arrow-right"></i>';
        html += '	<div class="chat-user-name">';
        html += '		<a href="#">' + entity.name + '</a>';
        html += '	</div>';
        html += '</div>';

        var $entity = $(html);
        $entity.data('entity', entity);
        return $entity;
    };

    options.addItemRenderer = options.addItemRenderer || null;
    options.addItemForm = options.addItemForm || null;

    var _self = this;
    var _xhr = null;
    var $entities = null;
    var $container = $('<div class="entity-selector-container collapsable"></div>');
    var _excludedEntities = [];
    var $selectedEntities = $('<div class="selected-entities"></div>');
    var _selectedEntities = [];
    var _markedIndex = null;
    var _selectedEntity = null;
    var $addItemContainer = $('<div class="add-item-container"></div>');

    $selectedEntities.on('click', '.entity', function() {
        delete _selectedEntities[$(this).data('entity').id];
        $(this).remove();
        _self.collapse();
    });

    $container.appendTo($input.parent());
    $input.appendTo($container);
    $container.append($selectedEntities);
    $container.append($addItemContainer);

    $input.on('keyup', function(e) {
        if(e.keyCode == 27 || $(this).val() == '') //escape
        {
            _selectedEntity = null;
            $input.trigger('entityDeselected');
            _self.collapse();
        }
        else if(e.keyCode == 40 || e.keyCode == 38) //up or down
        {
            _self.setMarkedIndex((_markedIndex || 0) + (e.keyCode == 40 ? 1 : -1));
        }
        else if(e.keyCode == 13 && $entities && $entities.length && $entities.find('.entity').length) //enter
        {
            selectEntity($entities.find('.entity:eq(' + _markedIndex + ')'));
        }
        else
        {
            _selectedEntity = null;
            _self.search();
            $input.trigger('entityDeselected');
        }

        $input.parents('.form-group').removeClass('has-error');
    });

    $container.on('collapse', function() { _self.collapse(); });

    _self.search = function() {
        var params = options.searchParams;
        params['term'] = $input.val();
        params['limit'] = options.limit;
        params['excludedEntityIds'] = _selectedEntities.map(function(entity) { return entity.id; });

        _xhr && _xhr.abort();
        _xhr = apiRequest(options.sourceUrl, 'GET', params, function(response) {
            _self.renderEntities(response.body[options.resultsKey]);
            _self.setMarkedIndex(0);
        });
    };

    _self.setMarkedIndex = function(markedIndex) {
        if(!$entities || $entities.length == 0)
        {
            return;
        }

        _markedIndex = Math.max(Math.min(markedIndex, $entities.find('.entity').length - 1), 0);
        $entities.find('.entity').removeClass('hovered');
        $entities.find('.entity:eq(' + _markedIndex + ')').addClass('hovered');
    };

    _self.collapse = function() {
        _xhr && _xhr.abort();
        $entities && $entities.remove();
        $addItemContainer.hide();
        $input.removeAttr('disabled');
    };

    _self.renderEntities = function(entities) {
        $entities && $entities.remove();
        $entities = $('<div class="entity-selector-entities"></div>');

        var excludedEntityIds = _excludedEntities.map(function(entity) {
            return entity.id;
        });

        if(options.addItemRenderer && !options.disableAddItem)
        {
            var entityNames = entities.map(function(entity) { return entity.name.toLowerCase().trim(); });
            var term = $input.val().trim();

            if(entityNames.indexOf(term) == -1) //entity not found
            {
                var $addItem = options.addItemRenderer(term);
                $addItem.addClass('entity add-item');
                $entities.append($addItem);
            }
        }

        $.each(entities, function(key, entity) {
            if(excludedEntityIds.indexOf(parseInt(entity.id)) != -1
                || excludedEntityIds.indexOf(entity.id) != -1)
            {
                return;
            }

            var $entity = options.entityRenderer(entity);
            $entity.data('entity', entity);
            $entity.addClass('entity');
            $entities.append($entity);
        });

        $container.append($entities);

        $entities.on('click', '.entity', function() {
            selectEntity($(this));
        }).on('mouseenter', '.entity', function() {
            _self.setMarkedIndex($(this).index());
        });
    };

    function selectEntity($entity)
    {
        if($entity.hasClass('add-item'))
        {
            $entities.remove();

            setTimeout(function() {
                var $addItemForm = options.addItemForm($input.val());
                $addItemContainer.html($addItemForm).show();
                $input.attr('disabled', 'disabled');
            }, 100); //because it will collapse once $entities is removed

            return;
        }

        var entity = $entity.data('entity');
        _self.setEntity(entity);
        $input.focus();
        $input.trigger('entitySelected', [entity]);
    }

    _self.setEntity = function(entity) {
        _self.collapse();

        if(options.multiSelect)
        {
            var $selectedEntity = $('<div class="btn btn-xs btn-danger entity"><i class="fa fa-remove"></i> ' + entity.name + '</div>');
            $selectedEntity.data('entity', entity);
            $selectedEntities.prepend($selectedEntity);
            _selectedEntities[entity.id] = entity;
            $input.val('');
        }
        else
        {
            _selectedEntity = entity;
            $input.val(entity.name);
        }
    };

    _self.destroy = function() {
        _self.collapse();
        $input.off('keyup').removeData('entitySelector').appendTo($container.parent());
        $container.remove();
    };

    _self.setExcludedEntities = function(entities) {
        _excludedEntities = entities;
        return this;
    };

    _self.getEntities = function() {
        return options.multiSelect ? _selectedEntities : [_selectedEntity];
    };

    _self.getEntity = function() {
        return _selectedEntity;
    };

    _self.clear = function() {
        _selectedEntities = [];
        _selectedEntity = null;
        $selectedEntities.empty();
        $input.val('');
        return this;
    };
};

function getSystemColor(name)
{
    switch(name)
    {
        case 'warning':
            return '#f0ad4e';
        case 'danger':
            return '#ed5565';
        case 'primary':
            return '#1ab394';
        case 'info':
            return '#31b0d5';
        default:
            return '#fff';
    }
}

function formatMeasuredFood(measuredFood, noFoodName)
{
    noFoodName = noFoodName || false;

    var addMeasure = measuredFood.measure.name.toLowerCase().trim() != 'each';
    var html = '';

    if(measuredFood.quantity > 1)
    {
        html += measuredFood.quantity + ' x ';

        if(addMeasure)
        {
            html += measuredFood.measure.name.pluralize().capitalize() + ' of ';
        }
    }
    else if(measuredFood.quantity < 1)
    {
        html += measuredFood.quantity + ' x ';

        if(addMeasure)
        {
            html += measuredFood.measure.name.capitalize() + ' of ';
        }
    }
    else //no quantity needed because it's 1
    {
        if(addMeasure)
        {
            html += measuredFood.measure.name.capitalize() + ' of ';
        }
    }

    if(noFoodName)
    {
        return html.substr(0, html.length - ' of '.length);
    }

    if(measuredFood.quantity > 1)
    {
        html += measuredFood.food.name.pluralize().capitalize();
    }
    else
    {
        html += measuredFood.food.name.capitalize();
    }

    return html;
}

function renderFoodValues($targetTable, food, measure, quantity)
{
    var html = '';

    html += '<thead>';
    html += '   <tr>';
    html += '       <th>' + food.name.capitalize() + '</th>';
    html += '       <th> ' + htmlEncode(measure.name.capitalize()) + '</th>';

    if(quantity != 1)
    {
        html += '<th>' + quantity + ' x ' + measure.name.capitalize() + '</th>';
    }

    html += '   </tr>';
    html += '</thead>';

    html += '<tbody>';

    html += formatRow({ name: 'Calories', value: parseFloat(measure.calories) });
    html += formatRow({ name: 'Fat', value: parseFloat(measure.fat) });
    html += formatRow({ name: 'Protein', value: parseFloat(measure.protein) });
    html += formatRow({ name: 'Carbs', value: parseFloat(measure.carbs) });

    html += '</tbody>';

    function formatRow(info)
    {
        var html = '';

        html += '<tr>';
        html += '   <td>' + info.name + '</td>';
        html += '   <td>' + info.value.toFixedDown(2) + '</td>';

        if(quantity != 1)
        {
            html += '<td>' + (info.value * quantity).toFixedDown(2) + '</td>';
        }

        html += '<tr>';

        return html;
    }

    $targetTable.html(html);
}

function loading()
{
    var html = '';

    html += '<div class="sk-spinner sk-spinner-fading-circle loading-icon">';
    html += '   <div class="sk-circle1 sk-circle"></div>';
    html += '   <div class="sk-circle2 sk-circle"></div>';
    html += '   <div class="sk-circle3 sk-circle"></div>';
    html += '   <div class="sk-circle4 sk-circle"></div>';
    html += '   <div class="sk-circle5 sk-circle"></div>';
    html += '   <div class="sk-circle6 sk-circle"></div>';
    html += '   <div class="sk-circle7 sk-circle"></div>';
    html += '   <div class="sk-circle8 sk-circle"></div>';
    html += '   <div class="sk-circle9 sk-circle"></div>';
    html += '   <div class="sk-circle10 sk-circle"></div>';
    html += '   <div class="sk-circle11 sk-circle"></div>';
    html += '   <div class="sk-circle12 sk-circle"></div>';
    html += '</div>';

    return $(html);
}

function formatPopoverOption(option, $triggerButton)
{
    var html = '';

    html += '<div class="row m-b-xs option m-l-xs ' + option.class + '">';
    html += '   <i class="fa ' + option.icon + '"></i> ' + option.text;
    html += '</div>';

    var $option = $(html);
    $option.on('click', function() { $triggerButton.trigger('click'); });
    return $option;
}

function modal(title)
{
    title == title || '';

    var html = '';

    html += '<div class="modal inmodal fade" tabindex="-1" data-backdrop="static">';
    html += '   <div class="modal-dialog modal-md">';
    html += '       <div class="modal-content">';
    html += '           <div class="modal-header">';
    html += '               <button type="button" class="close" data-dismiss="modal">';
    html += '                   <span aria-hidden="true">×</span>';
    html += '                   <span class="sr-only">Close</span>';
    html += '               </button>';
    html += '               <h4 class="modal-title">' + title + '</h4>';
    html += '           </div>';
    html += '           <div class="modal-body"></div>';
    html += '           <div class="modal-footer">';
    html += '               <button type="button" class="btn btn-white close-button" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>';
    html += '           </div>';
    html += '       <div">';
    html += '   </div>';
    html += '</div>';

    var $modal = $(html);
    $modal.on('hidden.bs.modal', function() { $modal.remove(); });

    return $modal;
}

function disableScroll()
{
    $('body').on({
        'mousewheel.disableScroll': function(e) {
            if (e.target.id == 'el') return;
            e.preventDefault();
            e.stopPropagation();
        }
    });

    var scrollTop = $(window).scrollTop();
    $(document).bind("touchmove.disableScroll",function(event){
        event.preventDefault();
        $(window).scrollTop(scrollTop);
    });

    $(window).on('scroll.disableScroll', function(event) {
        event.preventDefault();
        $(window).scrollTop(scrollTop);
    });

    if($('body').hasScrollBar())
    {
        $('html, body').css({ 'overflow': 'hidden', 'height': '100%' });
    }
}

function enableScroll()
{
    $(document).unbind("touchmove.disableScroll");
    $('body').off("mousewheel.disableScroll");
    $(window).off("scroll.disableScroll");

    $('html, body').css({ 'overflow': 'auto', 'height': 'auto' });
}

function getQueryParam(name, url)
{
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}

function setQueryParam(key, value, uri)
{
    uri = uri || window.location.href;
    var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
    var separator = uri.indexOf('?') !== -1 ? "&" : "?";
    var newUrl = null;
    if(uri.match(re))
    {
        newUrl = uri.replace(re, '$1' + key + "=" + value + '$2');
    }
    else
    {
        newUrl = uri + separator + key + "=" + value;
    }

    window.history.pushState('משפט בקליק', 'משפט בקליק', newUrl);
}

function randomInt(min, max)
{
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

Array.prototype.average = function() {
    var sum = 0;
    var j = 0;

    for(var i = 0; i < this.length; i++)
    {
        if(isFinite(this[i]))
        {
            sum = sum + parseFloat(this[i]);
            j++;
        }
    }

    return j === 0 ? 0 : (sum / j);
}

Array.prototype.unique = function() {
    var o = {}, a = [], i, e;
    for (i = 0; e = this[i]; i++) {o[e] = 1};
    for (e in o) {a.push (e)};
    return a;
}

function datesOverlap(startA, endA, startB, endB)
{
    return (startA.getTime() <= endB.getTime()) && (endA.getTime() >= startB.getTime());
}

function explained(storageKey)
{
    return auth.getUser().explainedItems.map(function(explainedItem) { return explainedItem.name; }).contains(storageKey) || explainedItems.contains(storageKey);
}

function markExplained(storageKey)
{
    explainedItems.push(storageKey);
    localStorage.setItem('explainedItems', JSON.stringify(explainedItems));
    apiRequest(config.api.urls.user.addExplained, 'POST', { name: storageKey });
}

String.prototype.formatTime = function() {
    var user = auth ? auth.getUser() : null;
    var timeFormat = user ? auth.getUser().timeFormat : null;

    if(!timeFormat || timeFormat.id == TIME_FORMAT_AM_PM)
    {
        return this.toAmPm();
    }

    return this;
};

Date.prototype.formatTime = function() {
    return this.toHi().formatTime();
};

String.prototype.amPmTo24 = function() {
    var string = this.replace(' ', ''); //remove space
    var hour = parseInt(string.substr(0, 2));
    var minutes = string.substr(3, 2);
    var amOrPm = string.substr(5, 2);

    if(amOrPm == 'AM')
    {
        if(hour == 12)
        {
            return '00:' + minutes;
        }

        return zeroPadTime(hour) + ':' + minutes;
    }
    else if(amOrPm == 'PM')
    {
        if(hour == 12)
        {
            return '12:' + minutes;
        }

        return (hour + 12) + ':' + minutes;
    }

    return this;
};

String.prototype.toAmPm = function() {
    var parts = this.split(':');
    if(!parts[0] || !parts[1] || parts.length != 2)
    {
        return this;
    }

    if(isNaN(parts[0]) || isNaN(parts[1]))
    {
        return this;
    }

    var hours = parseInt(parts[0]);
    var minutes = parseInt(parts[1]);

    var result = "" + zeroPadTime((hours > 12) ? hours - 12 : hours);

    if(hours == 0)
    {
        result = '12';
    }

    result += (minutes < 10) ? ":0" + minutes : ":" + minutes;
    result += (hours >= 12) ? " PM" : " AM";

    return result;
};

String.prototype.cleanTime = function() {
    var time = this;

    if(time.length == 4)
    {
        time = time[0] + time[1] + ':' + time[2] + time[3];
    }

    if(!isNaN(time) && parseInt(time) <= 24)
    {
        time = parseInt(time);

        if(time == 24)
        {
            return '00:00';
        }

        return zeroPadTime(time) + ':00';
    }

    var parts = time.split(':');
    if(!parts[0] || !parts[1] || parts.length != 2)
    {
        return null;
    }

    if(isNaN(parts[0]) || isNaN(parts[1]))
    {
        return null;
    }

    var hour = parseInt(parts[0]);
    var min = parseInt(parts[1]);

    return zeroPadTime(hour) + ':' + zeroPadTime(min);
};

function getCookie(key)
{
    key = key == 'small-chats' ? 'sc' : key;
    key = key == 'guest-small-chats' ? 'gsc' : key;
    key = key == 'affiliate-code' ? 'ac' : key;

    var valueJson = Cookies.get(key);
    return valueJson ? JSON.parse(valueJson) : null;
}

function setCookie(key, value)
{
    key = key == 'small-chats' ? 'sc' : key;
    key = key == 'guest-small-chats' ? 'gsc' : key;
    key = key == 'affiliate-code' ? 'ac' : key;

    Cookies.set(key, JSON.stringify(value), { expires: 60, path: '/' });
}

function refreshUserState(onComplete)
{
    $.ajax({
        url: adminUrls.user.refreshState,
        method: 'POST',
        success: function() {
            onComplete && onComplete();
        }
    });
}

function createClientDialog(contact, onCreate)
{
    var $modal = modal('<i class="fa fa-user-plus"></i> Register Client');
    var $modalBody = $modal.find('.modal-body').empty();
    var $modalFooter = $modal.find('.modal-footer');

    var html = '';

    html += '<div class="form-horizontal">';
    html += '   <div class="form-group" field-name="user_first_name">';
    html += '       <div class="col-xs-3 text-right">';
    html += '           <label class="control-label">First Name</label>';
    html += '       </div>';
    html += '       <div class="col-xs-8">';
    html += '           <input type="text" class="form-control first-name" />';
    html += '       </div>';
    html += '   </div>';
    html += '   <div class="form-group" field-name="user_last_name">';
    html += '       <div class="col-xs-3 text-right">';
    html += '           <label class="control-label">Last Name</label>';
    html += '       </div>';
    html += '       <div class="col-xs-8">';
    html += '           <input type="text" class="form-control last-name" />';
    html += '       </div>';
    html += '   </div>';
    html += '   <div class="form-group" field-name="user_gender">';
    html += '       <div class="col-xs-3 text-right">';
    html += '           <label class="control-label">Gender</label>';
    html += '       </div>';
    html += '       <div class="col-xs-8">';
    html += '           <select class="form-control gender">';
    html += '               <option value=""></option>';
    html += '               <option value="1">Male</option>';
    html += '               <option value="2">Female</option>';
    html += '           </select>';
    html += '       </div>';
    html += '   </div>';
    html += '   <div class="form-group" field-name="user_email">';
    html += '       <div class="col-xs-3 text-right">';
    html += '           <label class="control-label">Email</label>';
    html += '       </div>';
    html += '       <div class="col-xs-8">';
    html += '           <input type="email" class="form-control email" />';
    html += '           <span class="help-block"><i class="fa fa-info-circle"></i> Login instructions will be sent to this email.</span>';
    html += '       </div>';
    html += '   </div>';
    html += '</div>';

    $modalBody.append(html);

    var $firstName = $modalBody.find('.first-name');
    var $lastName = $modalBody.find('.last-name');
    var $email = $modalBody.find('.email');
    var $gender = $modalBody.find('.gender');

    if(contact)
    {
        $firstName.val(contact.firstName.capitalize());
        $lastName.val(contact.lastName.capitalize());
        $email.val(contact.email);
        contact.gender && $gender.val(contact.gender.id);
    }

    var addButtonText = '<i class="fa fa-check"></i> Register';
    var $addButton = $('<button type="button" class="btn btn-primary">' + addButtonText + '</button>').on('click', function() {
        if($addButton.hasClass('disabled'))
        {
            return;
        }

        $addButton.addClass('disabled');
        $addButton.html('<i class="fa fa-spin fa-spinner"></i> Register');
        $modalBody.find('.form-group').removeClass('has-error');

        apiRequest(config.api.urls.client.create, 'POST', {
            first_name: $firstName.val(),
            last_name: $lastName.val(),
            email: $email.val(),
            gender: $gender.val(),
            contact_id: contact ? contact.id : null,
        }, function(response) {
            if(response.status == 'ERROR')
            {
                $.each(response.body.errors, function(key, error) {
                    showToast('error', error);
                    $modalBody.find('.form-group[field-name="' + key + '"]').addClass('has-error');
                });

                $addButton.removeClass('disabled');
                $addButton.html(addButtonText);
            }
            else if(response.status == 'LIMIT_REACHED')
            {
                $modalBody.find('.limit-message').remove();

                var limitMessageHtml = '';
                limitMessageHtml += '<div class="limit-message text-center m-t-lg m-b">';
                limitMessageHtml += '   <h4>You have reached the maximum number of clients in the Free Subcription.</h4>';
                limitMessageHtml += '   <h5>Premium users can have unlimited number of clients.</h5>';
                limitMessageHtml += '   <a href="' + adminUrls.subscription.index + '"><div class="btn btn-primary btn-sm"><i class="fa fa-star"></i> Upgrade Your Account</div></a>';
                limitMessageHtml += '</div>';

                $modalBody.append(limitMessageHtml);
                $addButton.removeClass('disabled');
                $addButton.html(addButtonText);
            }
            else if(response.status == 'OK')
            {
                refreshUserState(function() {
                    var client = response.body.client;
                    $modal.on('hidden.bs.modal', function() {
                        onCreate && onCreate(client);
                    });

                    $modal.modal('hide');
                });
            }
            else
            {
                defaultErrorHandler();
            }
        });
    }).appendTo($modalFooter);

    $email.on('keyup', function(e) {
        if(e.keyCode == 13) //enter
        {
            $addButton.trigger('click');
        }
    });

    $modal.on('shown.bs.modal', function() {
        $firstName.focus();
    });

    $modal.modal();
}

String.prototype.linkify = function() {

    // http://, https://, ftp://
    var urlPattern = /\b(?:https?|ftp):\/\/[a-z0-9-+&@#\/%?=~_|!:,.;]*[a-z0-9-+&@#\/%=~_|]/gim;

    // www. sans http:// or https://
    var pseudoUrlPattern = /(^|[^\/])(www\.[\S]+(\b|$))/gim;

    // Email addresses
    var emailAddressPattern = /[\w.]+@[a-zA-Z_-]+?(?:\.[a-zA-Z]{2,6})+/gim;

    return this
        .replace(urlPattern, '<a target="_blank" href="$&">$&</a>')
        .replace(pseudoUrlPattern, '$1<a target="_blank" href="http://$2">$2</a>')
        .replace(emailAddressPattern, '<a href="mailto:$&">$&</a>');
};

function updateUserSetting(key, value)
{
    apiRequest(config.api.urls.user.updateSetting, 'POST', { key: key, value: value }, function(response) {
        refreshUserState();
    });
}

Array.prototype.joinWithAnd = function() {
    if(this.length == 1)
    {
        return this[0];
    }
    else
    {
        var lastItem = this.pop();
        return this.join(', ') + ' and ' + lastItem;
    }
};