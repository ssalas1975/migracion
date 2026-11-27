(function () {
    const consentType = {
        gdpr: 'optin',
        ccpa: 'optout',
    };
    const categoryMap = {
        functional: 'preferences',
        analytics: ['statistics','statistics-anonymous'],
        performance: 'functional',
        advertisement: 'marketing',
    };
    const gskEnabled = typeof _ckyGsk !== 'undefined' && _ckyGsk ? _ckyGsk : false;
    // Last type announced with wp_consent_type_defined, so it is not repeated for the same value.
    let announcedType = null;
    // Whether the type was announced once the page was ready, for plugins whose scripts load after this one.
    let announcedWhenReady = false;
    // Whether the stored choice (or, for GDPR, the lack of one) has been passed to the WP Consent API on this page load.
    let storedChoiceApplied = false;

    function getConsentData() {
        if (typeof window.getCkyConsent !== 'function') {
            return null;
        }
        try {
            return window.getCkyConsent();
        } catch (e) {
            return null;
        }
    }

    function setConsentStatus(key, status) {
        if (Array.isArray(categoryMap[key])) {
            categoryMap[key].forEach(el => {
                wp_set_consent(el, status);
            });
        } else {
            wp_set_consent(categoryMap[key], status);
        }
    }

    /**
     * Set window.wp_consent_type, without announcing it yet.
     *
     * @param {object} consentData getCkyConsent() result.
     * @returns {string} The type set.
     */
    function setConsentType(consentData) {
        const type = consentType[consentData.activeLaw] || 'optin';
        window.wp_consent_type = type;
        return type;
    }

    /**
     * Announce the type with wp_consent_type_defined. Called only after the visitor's consent has
     * been passed to wp_set_consent(), so plugins that call wp_has_consent() on this event read the
     * current choice, not consent left over from an earlier one.
     *
     * @param {string} type Type set with setConsentType().
     * @param {boolean} force Announce even when this type was announced already.
     */
    function announceConsentType(type, force) {
        if (force || type !== announcedType) {
            announcedType = type;
            document.dispatchEvent(new CustomEvent('wp_consent_type_defined'));
        }
    }

    /**
     * The value wp_has_consent() reads for a WP Consent API category: its cookie, or '' if unset.
     *
     * @param {string} category WP Consent API category.
     * @returns {string}
     */
    function getStoredStatus(category) {
        const prefix = (typeof consent_api !== 'undefined' && consent_api && consent_api.cookie_prefix) || 'wp_consent';
        const name = prefix + '_' + category + '=';
        const cookies = (document.cookie || '').split(';');
        for (let i = 0; i < cookies.length; i++) {
            const cookie = cookies[i].trim();
            if (cookie.indexOf(name) === 0) {
                return cookie.substring(name.length);
            }
        }
        return '';
    }

    /**
     * Pass each category to the WP Consent API.
     *
     * @returns {boolean} Whether the categories were applied.
     */
    function applyCategories(consentData) {
        // Normally loaded before this file (a script dependency); missing only if another plugin removed it.
        if (typeof wp_set_consent !== 'function') {
            return false;
        }
        const categories = Object.entries(consentData.categories || {});
        categories.forEach(([key, value]) => {
            if (!(key in categoryMap))
                return;
            setConsentStatus(key, value ? 'allow' : 'deny');
        });
        return categories.length > 0;
    }

    // Site Kit: no explicit deny signals before the visitor has made a choice.
    function skipsDefaults(consentData) {
        return consentData.isUserActionCompleted === false && gskEnabled && !Object.values(consentData.categories || {}).slice(1).includes(true);
    }

    /**
     * GDPR visitor with no valid CookieYes choice (first visit, or the choice is missing or expired).
     * The WP Consent API may still hold "allow" from an earlier choice, which wp_has_consent() would
     * keep reporting; every category gets the banner's default instead. Site Kit sites still get no
     * deny signal before a choice, except to replace such a leftover "allow".
     *
     * @returns {boolean} Whether the categories were applied.
     */
    function applyWithoutChoice(consentData) {
        if (!skipsDefaults(consentData)) {
            return applyCategories(consentData);
        }
        if (typeof wp_set_consent !== 'function') {
            return false;
        }
        Object.keys(consentData.categories || {}).forEach((key) => {
            if (!(key in categoryMap) || consentData.categories[key]) {
                return;
            }
            [].concat(categoryMap[key]).forEach((category) => {
                if (getStoredStatus(category) === 'allow') {
                    wp_set_consent(category, 'deny');
                }
            });
        });
        return true;
    }

    /**
     * Pass the visitor's consent to the WP Consent API.
     *
     * @param {object} consentData getCkyConsent() result.
     * @returns {boolean} Whether the categories were applied.
     */
    function applyConsent(consentData) {
        if (consentData.isUserActionCompleted !== true && consentData.activeLaw === 'gdpr') {
            return applyWithoutChoice(consentData);
        }
        return skipsDefaults(consentData) ? false : applyCategories(consentData);
    }

    // On every consent update: the banner's defaults on a first visit, and each choice the visitor makes.
    function onConsentUpdate() {
        const consentData = getConsentData();
        if (!consentData) {
            return;
        }
        // Always set the type, also when Site Kit is active: an unset type makes wp_has_consent() allow everything.
        const type = setConsentType(consentData);
        applyConsent(consentData);
        announceConsentType(type, true);
    }

    /**
     * Apply the stored consent without waiting for cookieyes_consent_update. That event does not fire
     * for a returning visitor, and when JavaScript is delayed it fires before this file has loaded.
     *
     * @param {boolean} lawResolved The banner has resolved the visitor's law, even if no law applies.
     * @param {boolean} pageReady   The page has loaded, so later plugins are listening.
     */
    function applyStoredConsent(lawResolved, pageReady) {
        const consentData = getConsentData();
        // Until the web app banner has resolved the visitor's region, the law is unknown. Announcing a
        // guess would make plugins act on the wrong type; the server's type covers the meantime.
        if (!consentData || (!consentData.activeLaw && !lawResolved)) {
            return;
        }
        const announceAgain = pageReady && !announcedWhenReady;
        if (pageReady) {
            announcedWhenReady = true;
        }
        const type = setConsentType(consentData);
        // A stored choice, or a GDPR visitor without one. Not CCPA defaults: before the banner has
        // stored them every category reads as not granted, which would opt CCPA visitors out.
        // Defaults arrive with cookieyes_consent_update.
        if (!storedChoiceApplied && (consentData.isUserActionCompleted === true || consentData.activeLaw === 'gdpr')) {
            storedChoiceApplied = applyConsent(consentData);
        }
        announceConsentType(type, announceAgain);
    }

    /**
     * The banner script never loaded (blocked or failed). Announce optin anyway, so plugins told to
     * wait for wp_consent_type_defined do not wait forever; the visitor's wp_consent_* cookies from
     * earlier visits still apply.
     */
    function announceWithoutBanner() {
        if (announcedType === null && typeof window.getCkyConsent !== 'function') {
            window.wp_consent_type = 'optin';
            announcedType = 'optin';
            document.dispatchEvent(new CustomEvent('wp_consent_type_defined'));
        }
    }

    document.addEventListener("cookieyes_consent_update", onConsentUpdate);
    // The web app banner fires this once it has resolved the visitor's region and law.
    document.addEventListener("cookieyes_banner_load", function () {
        applyStoredConsent(true, false);
    });
    // Once more when the page is ready, so plugins whose scripts load after this one (WooCommerce,
    // Site Kit) receive wp_consent_type_defined. When an optimiser delays JavaScript the page is
    // already ready and LiteSpeed (7.9.3) does not fire this event again; those plugins then rely on
    // the server-side type, which wp_has_consent() falls back to.
    document.addEventListener("DOMContentLoaded", function () {
        applyStoredConsent(false, true);
    });
    window.addEventListener("load", announceWithoutBanner);
    // Loaded after the page (an optimiser delayed or async-loaded the scripts): the banner may already
    // have fired cookieyes_banner_load, so take the law as resolved, even when no law applies.
    const loadedLate = document.readyState === 'complete';
    applyStoredConsent(loadedLate, false);
    if (loadedLate) {
        announceWithoutBanner();
    }
})();
