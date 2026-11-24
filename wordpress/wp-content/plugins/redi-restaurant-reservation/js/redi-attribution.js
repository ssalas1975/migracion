(function () {
    if (document.cookie.indexOf('redi_attribution=') !== -1) {
        return;
    }

    var params = new URLSearchParams(window.location.search);

    var data = {};

    var referrer = document.referrer;
    if (referrer && referrer.indexOf(window.location.hostname) === -1) {
        data.referrer = referrer;
    }

    var utmKeys = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term'];
    var clickIds = ['gclid', 'gbraid', 'wbraid', 'fbclid'];
    var allKeys = utmKeys.concat(clickIds);

    for (var i = 0; i < allKeys.length; i++) {
        var val = params.get(allKeys[i]);
        if (val) {
            data[allKeys[i]] = val;
        }
    }

    data.landing_page = window.location.pathname;

    var hasValue = !!data.referrer;
    if (!hasValue) {
        for (var j = 0; j < allKeys.length; j++) {
            if (data[allKeys[j]]) {
                hasValue = true;
                break;
            }
        }
    }

    if (!hasValue) {
        return;
    }

    var expires = new Date();
    expires.setTime(expires.getTime() + 30 * 24 * 60 * 60 * 1000);

    document.cookie = 'redi_attribution=' + encodeURIComponent(JSON.stringify(data)) +
        ';expires=' + expires.toUTCString() +
        ';path=/;SameSite=Lax';
})();
