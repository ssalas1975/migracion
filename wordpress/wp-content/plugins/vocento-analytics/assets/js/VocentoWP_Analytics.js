VocentoWP_Analytics.utils = {
    gigya: {
        init: function () {
            VocentoWP_Analytics.comScore.data.voc_usrid = VocentoWP_Analytics.utils.gigya.loadCookieValue('uid');
            VocentoWP_Analytics.comScore.data.voc_tusr = ((typeof getUserType == 'function') ? getUserType() : "anonimo");
            VocentoWP_Analytics.comScore.data.voc_usrag = VocentoWP_Analytics.utils.gigya.loadCookieValue('age');
            VocentoWP_Analytics.comScore.data.voc_usrcp = VocentoWP_Analytics.utils.gigya.loadCookieValue('zip');
            VocentoWP_Analytics.comScore.data.voc_usrgen = VocentoWP_Analytics.utils.gigya.loadCookieValue('gender');
            VocentoWP_Analytics.comScore.data.voc_usrser = VocentoWP_Analytics.utils.gigya.loadCookieValue('dataRegistrationService');
            VocentoWP_Analytics.comScore.data.voc_usrsvs = VocentoWP_Analytics.utils.gigya.loadCookieValue('');
            VocentoWP_Analytics.comScore.data.voc_krux = (typeof window.Krux == 'undefined') ? 0 : Krux.vocento.user;

        },
        loadCookieValue: function (value) {
            $cookie = VocentoWP_Analytics.utils.common.getCookie('voc_uid');
            if ($cookie) {
                $cookie = JSON.parse($cookie);
                if ($cookie) {
                    return ($cookie[value] || '');
                }

            }
            return '';
        }
    },
    paywall: {},
    common: {
        getCookie: function (cname) {
            var name = cname + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        },
        getDomain: function () {
            var url = location.host;
            var TLDs = ["com", "es", "net"];
            var parts = url.split('.');
            if (parts[0] === 'www' && parts[1] !== 'com') {
                parts.shift()
            }
            var ln = parts.length
                , i = ln
                , minLength = parts[parts.length - 1].length
                , part;

            // iterate backwards
            while (part = parts[--i]) {
                // stop when we find a non-TLD part
                if (i === 0                    // 'asia.com' (last remaining must be the SLD)
                    || i < ln - 2                // TLDs only span 2 levels
                    || part.length < minLength // 'www.cn.com' (valid TLD as second-level domain)
                    || TLDs.indexOf(part) < 0  // officialy not a TLD
                ) {
                    return part
                }
            }
        }
    },
    dynamic: {
        refreshTracking: function (voc_se, voc_tn, voc_name, name, voc_ho, voc_tc, voc_au, voc_fep, ns_http_status, document_location) {
            //comscore
            if (VocentoWP_Analytics.comScore.enable)
                VocentoWP_Analytics.utils.dynamic.comscore.refresh(voc_se, voc_tn, voc_name, name, voc_ho, voc_tc, voc_au, voc_fep, ns_http_status, voc_s1);
            //Chartbeat
            if (VocentoWP_Analytics.chartbeat.enable)
                VocentoWP_Analytics.utils.dynamic.chartbeat.refresh(voc_se, voc_au, voc_tn, document_location);
            //gtm
            if (VocentoWP_Analytics.gtm.enable)
                VocentoWP_Analytics.utils.dynamic.gtm.refresh(voc_se, voc_tc, voc_ho);
            //ga
            if (VocentoWP_Analytics.ga.enable)
                VocentoWP_Analytics.utils.dynamic.ga.refresh(document_location);
            //krux
            if (VocentoWP_Analytics.krux.enable)
                VocentoWP_Analytics.utils.dynamic.krux.refresh(voc_se, voc_s1, voc_tc);
        },
        comscore: {
            refresh: function (voc_se, voc_tn, voc_name, name, voc_ho, voc_tc, voc_au, voc_fep, ns_http_status) {
                jQuery.get("/page_view_candidate.xml?rand=" + Math.round((Math.random() * 10000) + 1), function () {
                }).always(function () {
                    VocentoWP_Analytics.utils.dynamic.comscore.push(voc_se, voc_tn, voc_name, name, voc_ho, voc_tc, voc_au, voc_fep, ns_http_status);
                });
            },
            push: function () {
                var _comscore = _comscore || [];
                comscoreConfig = {
                    c1: "2",
                    c2: "7715761",
                    ns_site: VocentoWP_Analytics.comScore.data.voc_site,
                    voc_site: VocentoWP_Analytics.comScore.data.voc_site,
                    voc_se: voc_se,
                    voc_s1: "",
                    voc_s2: "",
                    voc_s3: "",
                    voc_s4: "",
                    voc_tn: voc_tn,
                    voc_name: voc_name,
                    name: name,
                    voc_ho: voc_ho,
                    voc_tc: voc_tc,
                    voc_ed: "",
                    voc_au: normat_analytics(voc_au),
                    voc_fep: voc_fep,
                    voc_fem: voc_fem,
                    voc_or: VocentoWP_Analytics.comScore.data.voc_or,
                    voc_pr: VocentoWP_Analytics.comScore.data.voc_pr,
                    voc_pl: VocentoWP_Analytics.comScore.data.voc_pl,
                    voc_pu: VocentoWP_Analytics.comScore.data.voc_pu,
                    voc_ate: VocentoWP_Analytics.comScore.data.voc_ate,
                    voc_adp: VocentoWP_Analytics.comScore.data.voc_adp,
                    voc_adb: VocentoWP_Analytics.comScore.data.voc_adb,
                    voc_idi: VocentoWP_Analytics.comScore.data.voc_idi,
                    voc_usrid: VocentoWP_Analytics.comScore.data.voc_usrid,
                    voc_tusr: VocentoWP_Analytics.comScore.data.voc_tusr,
                    voc_usrag: VocentoWP_Analytics.comScore.data.voc_usrag,
                    voc_usrcp: VocentoWP_Analytics.comScore.data.voc_usrcp,
                    voc_usrgen: VocentoWP_Analytics.comScore.data.voc_usrgen,
                    voc_usrser: VocentoWP_Analytics.comScore.data.voc_usrser,
                    voc_usrsvs: VocentoWP_Analytics.comScore.data.voc_usrsvs,
                    voc_pw_pid: VocentoWP_Analytics.comScore.data.voc_pw_pid,
                    voc_pw_meter: VocentoWP_Analytics.comScore.data.voc_pw_meter,
                    voc_pw_count: VocentoWP_Analytics.comScore.data.voc_pw_count,
                    voc_pw_max: VocentoWP_Analytics.comScore.data.voc_pw_max,
                    voc_pw_result: VocentoWP_Analytics.comScore.data.voc_pw_result,
                    voc_pw_cookie: VocentoWP_Analytics.comScore.data.voc_pw_cookie,
                    ns_http_status: ns_http_status
                };
                _comscore.push(comscoreConfig);
                var include = (document.location.protocol == "https:" ? "https://sb" : "http://b") + ".scorecardresearch.com/beacon.js";
                jQuery.getScript(include, function () {
                    try {
                        _loadJSComscore = false;
                        COMSCORE.beacon(comscoreConfig);
                    } catch (err) {
                    }
                });
            }
        },
        chartbeat: {
            refresh: function (voc_se, voc_au, voc_tn, document_location) {
                var _sf_async_config = window._sf_async_config || {};
                _sf_async_config.sections = voc_se;
                _sf_async_config.authors = voc_au;
                pSUPERFLY.virtualPage(((window.location.pathname) ? window.location.pathname : window.location.pathname), voc_tn);
            },
            getChartbeatSections: function () {
                return (VocentoWP_Analytics.comScore.data.voc_se != '' ? VocentoWP_Analytics.comScore.data.voc_se + ',' : '') + (VocentoWP_Analytics.comScore.data.voc_s1 != '' ? VocentoWP_Analytics.comScore.data.voc_s1 + ',' : '') + (VocentoWP_Analytics.comScore.data.voc_s2 != '' ? VocentoWP_Analytics.comScore.data.voc_s2 + ',' : '') + (VocentoWP_Analytics.comScore.data.voc_s3 != '' ? VocentoWP_Analytics.comScore.data.voc_s3 + ',' : '') + (VocentoWP_Analytics.comScore.data.voc_s4 != '' ? VocentoWP_Analytics.comScore.data.voc_s4 + ',' : '');
            }
        },
        gtm: {
            refresh: function (voc_se, voc_tc, tags) {
                var dataLayer = [{
                    'Seccion': voc_se,
                    'tipo': voc_tc,
                    'tag': ((tags && Array.isArray(tags)) ? tags.join() : '')

                }];
                //si son topics o tags mandamos tambien el campo
                if (VocentoWP_Analytics.comScore.ho_type == '1' || VocentoWP_Analytics.comScore.ho_type == '2') {
                    dataLayer[0].tag = voc_ho.split(',')
                }
                (function (w, d, s, l, i) {
                    w[l] = w[l] || [];
                    w[l].push({'gtm.start': new Date().getTime(), event: 'gtm.js'});
                    var f = d.getElementsByTagName(s)[0], j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
                    j.async = true;
                    j.src = '//www.googletagmanager.com/gtm.js?id=' + i + dl;
                    f.parentNode.insertBefore(j, f);
                })(window, document, 'script', 'dataLayer', VocentoWP_Analytics.gtm.data.ID);
            },
            customRefresh: function (dataLayer) {
                (function (w, d, s, l, i) {
                    w[l] = w[l] || [];
                    w[l].push({'gtm.start': new Date().getTime(), event: 'gtm.js'});
                    var f = d.getElementsByTagName(s)[0], j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
                    j.async = true;
                    j.src = '//www.googletagmanager.com/gtm.js?id=' + i + dl;
                    f.parentNode.insertBefore(j, f);
                })(window, document, 'script', 'dataLayer', VocentoWP_Analytics.gtm.data.ID);
            }
        },
        ga: {
            refresh: function (document_location) {
                ga('send', 'pageview', ((document_location) ? document_location : window.location.pathname));
            }
        },
        krux: {
            refresh: function (voc_se, voc_s1, voc_tc) {
                if (typeof DigitalContext != "undefined") {
                    Kruxinit = {'site': voc_site, 'section': voc_se, 'subsection': voc_s1, 'type': voc_tc};
                    DigitalContext.init(Kruxinit);
                    context = DigitalContext.getContext();
                }
            }
        }
    },
    init: function () {
        // //Solo marcamos si que viene de la home si no estamos en la home y solo con voc_ho=0
        // if (VocentoWP_Analytics.comScore.ho_type == 0 && document.location != ('http://' + document.domain + '/'))
        //     VocentoWP_Analytics.comScore.data.voc_ho = (document.referrer == ('http://' + document.domain + '/') || document.referrer == ('http://' + document.domain + '/index.html')) ? 1 : 0;
        // VocentoWP_Analytics.comScore.data.voc_adp = ((VocentoWP_Analytics.utils.common.getCookie('voc_mobile')) ? 2 : 0);
        // //si el dominio es auto en comscore
        // if (VocentoWP_Analytics.comScore.data.voc_site_auto == true)
        //     VocentoWP_Analytics.comScore.data.voc_site = VocentoWP_Analytics.utils.common.getDomain();
        // //si el dominio es auto en chartbeat
        // if (VocentoWP_Analytics.chartbeat.data.domain_auto == true)
        //     VocentoWP_Analytics.chartbeat.data.domain = window.location.host;
        // VocentoWP_Analytics.utils.gigya.init();
        let result = document.location.pathname.includes("eslaliga");

        if(result !== true){
            console.log(document.location.pathname)
            //Solo marcamos si que viene de la home si no estamos en la home y solo con voc_ho=0
            if (VocentoWP_Analytics.comScore.ho_type == 0 && document.location != ('http://' + document.domain + '/'))
                VocentoWP_Analytics.comScore.data.voc_ho = (document.referrer == ('http://' + document.domain + '/') || document.referrer == ('http://' + document.domain + '/index.html')) ? 1 : 0;
            VocentoWP_Analytics.comScore.data.voc_adp = ((VocentoWP_Analytics.utils.common.getCookie('voc_mobile')) ? 2 : 0);
            //si el dominio es auto en comscore
            if (VocentoWP_Analytics.comScore.data.voc_site_auto == true)
                VocentoWP_Analytics.comScore.data.voc_site = VocentoWP_Analytics.utils.common.getDomain();
            //si el dominio es auto en chartbeat
            if (VocentoWP_Analytics.chartbeat.data.domain_auto == true)
                VocentoWP_Analytics.chartbeat.data.domain = window.location.host;
        }
        VocentoWP_Analytics.utils.gigya.init();
    }
};
VocentoWP_Analytics.utils.init();