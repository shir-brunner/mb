function defaultErrorHandler(error)
{
    alert(error);
}

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

    return $.ajax({
        url: url,
        method: method,
        cache: false,
        data: data,
        success: function(data) {
            var response = typeof(data) == 'object' ? data : $.parseJSON(data);

            onComplete && onComplete(response);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            //defaultErrorHandler(jqXHR, textStatus, errorThrown);
            //onError && typeof onError == 'function' && onError(jqXHR, textStatus, errorThrown);
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
});

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

function validateId(str)
{
    var IDnum = String(str);

    if ((IDnum.length > 9) || (IDnum.length < 5))
        return false;
    if (isNaN(IDnum))
        return false;

    if (IDnum.length < 9)
    {
        while(IDnum.length < 9)
        {
            IDnum = '0' + IDnum;
        }
    }

    var mone = 0, incNum;
    for (var i=0; i < 9; i++)
    {
        incNum = Number(IDnum.charAt(i));
        incNum *= (i%2)+1;
        if (incNum > 9)
            incNum -= 9;
        mone += incNum;
    }

    return mone % 10 == 0;
}

function validateEmail(email)
{
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

function numberFormat(number, decimals, dec_point, thousands_sep) {
    // Strip all characters but numerical ones.
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}

function guid() {
    function s4() {
        return Math.floor((1 + Math.random()) * 0x10000)
            .toString(16)
            .substring(1);
    }
    return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
        s4() + '-' + s4() + s4() + s4();
}