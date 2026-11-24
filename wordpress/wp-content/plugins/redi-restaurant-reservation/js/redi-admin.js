jQuery( document ).ready(function() {
    jQuery( ".redi_video_btn" ).on( "click", function() {
         var datid = jQuery(this).attr("data-id");
        jQuery(".redi_video_modal iframe").attr("src","https://www.youtube.com/embed/"+datid);
        jQuery('.redi_video_modal').show();
    } );

    jQuery( ".redi_video_modal span.close" ).on( "click", function() {
        jQuery(".redi_video_modal iframe").attr("src","");
        jQuery('.redi_video_modal').hide();
    } );

    // Subscription Checkbox
    jQuery(document).on('change', '#IsSubscription', function(){

        var isSub = jQuery(this).prop('checked');
        if(isSub){
            jQuery('#SubscriptionName').prop('required',true);
            jQuery('#SubscriptionEmail').prop('required',true);
        }else{
            jQuery('#SubscriptionName').prop('required', false);
            jQuery('#SubscriptionEmail').prop('required', false);
        }
    });

    // Scroll To input
    jQuery('.error a[href*="#"], .notice-error a[href*="#"]').click(function (e) {
        
        e.preventDefault();
        var hash = jQuery(this).attr('href');
        hash = hash.slice(hash.indexOf('#') + 1);
        if (hash) {
            jQuery('html, body').animate({
                scrollTop: jQuery('#' + hash).offset().top - 140
            }, 1000);
            jQuery('#' + hash).addClass('section_active');
        }
    });

    function toggleRediCaptchaSettings() {
        var enabled = jQuery('#Captcha').is(':checked');
        var isV3 = jQuery('#CaptchaVersion').val() === 'v3';
        jQuery('.redi-captcha-dependent').toggle(enabled);
        jQuery('.redi-captcha-v3-only').toggle(enabled && isV3);
    }

    jQuery(document).on('change', '#Captcha, #CaptchaVersion', toggleRediCaptchaSettings);
    toggleRediCaptchaSettings();

    // Children settings: Max children is required when children selection is enabled.
    // Without it the whole settings save is rejected server-side and looks like
    // "customizations disappear after refresh".
    function toggleChildrenDependentSettings() {
        var enabled = jQuery('#ChildrenSelection').is(':checked');
        var $maxChild = jQuery('#MaxChild');
        jQuery('#TotalSelection').closest('tr').toggle(enabled);
        $maxChild.closest('tr').toggle(enabled);
        jQuery('#ChildrenDescription').closest('tr').toggle(enabled);
        $maxChild.prop('required', enabled);
        if (!enabled) {
            $maxChild.removeClass('section_active');
        }
    }

    jQuery(document).on('change', '#ChildrenSelection', toggleChildrenDependentSettings);
    toggleChildrenDependentSettings();

    // If save failed, jump to the first error so the reason is visible.
    var $firstErrorLink = jQuery('.notice-error a[href*="#"]').first();
    if ($firstErrorLink.length) {
        $firstErrorLink.trigger('click');
    }

    // WordPress-native saving indicator (core .spinner + notice overlay).
    var rediSavingMessage = (typeof rediAdmin !== 'undefined' && rediAdmin.saving)
        ? rediAdmin.saving
        : 'Saving changes…';

    function rediEnsureSpinners($form) {
        $form.find('input[type="submit"]').each(function () {
            var $btn = jQuery(this);
            if (!$btn.next('.spinner').length) {
                $btn.after('<span class="spinner" aria-hidden="true"></span>');
            }
        });
    }

    function rediShowSavingState($form) {
        $form.attr('aria-busy', 'true');
        rediEnsureSpinners($form);
        $form.find('.spinner').addClass('is-active');

        if (!jQuery('#redi-settings-saving').length) {
            var $overlay = jQuery(
                '<div id="redi-settings-saving" class="redi-settings-saving" role="alert" aria-live="assertive" aria-busy="true">' +
                    '<div class="redi-settings-saving__notice notice notice-info">' +
                        '<p>' +
                            '<span class="spinner is-active" aria-hidden="true"></span>' +
                            '<span class="redi-settings-saving__text"></span>' +
                        '</p>' +
                    '</div>' +
                '</div>'
            );
            $overlay.find('.redi-settings-saving__text').text(rediSavingMessage);
            jQuery('body').append($overlay);
        }

        jQuery('body').addClass('redi-is-saving');

        if (window.wp && wp.a11y && typeof wp.a11y.speak === 'function') {
            wp.a11y.speak(rediSavingMessage);
        }
    }

    var $rediAdminForms = jQuery('#redi-setting-form, #redi-reservation-cancel');
    $rediAdminForms.each(function () {
        rediEnsureSpinners(jQuery(this));
    });
    $rediAdminForms.on('submit', function (e) {
        var form = this;
        if (typeof form.checkValidity === 'function' && !form.checkValidity()) {
            e.preventDefault();
            if (typeof form.reportValidity === 'function') {
                form.reportValidity();
            }
            return;
        }
        rediShowSavingState(jQuery(this));
    });
    jQuery(document).ajaxComplete(function () {
        jQuery('#redi-setting-form').each(function () {
            rediEnsureSpinners(jQuery(this));
        });
    });

    function rediSupportIssueMessage(text, isError) {
        var $msg = jQuery('#redi-support-issue-message');
        $msg.removeClass('notice-success notice-error')
            .addClass(isError ? 'notice-error' : 'notice-success')
            .html('<p></p>')
            .show();
        $msg.find('p').text(text);
    }

    jQuery(document).on('click', '.redi-open-support-issue', function (e) {
        e.preventDefault();
        jQuery('#redi-support-issue-modal').show().addClass('active');
        jQuery('#redi-support-issue-message').hide();
    });

    jQuery(document).on('click', '.redi-support-issue-close', function (e) {
        e.preventDefault();
        jQuery('#redi-support-issue-modal').hide().removeClass('active');
    });

    jQuery(document).on('click', '#redi-support-issue-modal', function (e) {
        if (e.target === this) {
            jQuery('#redi-support-issue-modal').hide().removeClass('active');
        }
    });

    jQuery(document).on('submit', '#redi-support-issue-form', function (e) {
        e.preventDefault();
        var $form = jQuery(this);
        var $submit = jQuery('#redi-support-issue-submit');
        var sending = (typeof rediAdmin !== 'undefined' && rediAdmin.supportSending) ? rediAdmin.supportSending : 'Sending…';
        var sent = (typeof rediAdmin !== 'undefined' && rediAdmin.supportSent) ? rediAdmin.supportSent : 'Thank you. Your message was sent to support.';
        var failed = (typeof rediAdmin !== 'undefined' && rediAdmin.supportError) ? rediAdmin.supportError : 'Could not send the message. Please email info@reservationdiary.eu.';

        $submit.prop('disabled', true).text(sending);
        jQuery('#redi-support-issue-message').hide();

        var formData = new FormData(this);
        jQuery.ajax({
            url: ajaxurl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false
        }).done(function (response) {
            if (response && response.success) {
                var message = (response.data && response.data.message) ? response.data.message : sent;
                rediSupportIssueMessage(message, false);
                $form.find('textarea[name="description"]').val('');
                $form.find('input[type="file"]').val('');
            } else {
                var errorMessage = (response && response.data && response.data.message) ? response.data.message : failed;
                rediSupportIssueMessage(errorMessage, true);
            }
        }).fail(function (xhr) {
            var errorMessage = failed;
            if (xhr.responseJSON && xhr.responseJSON.data && xhr.responseJSON.data.message) {
                errorMessage = xhr.responseJSON.data.message;
            }
            rediSupportIssueMessage(errorMessage, true);
        }).always(function () {
            var sendLabel = (typeof rediAdmin !== 'undefined' && rediAdmin.supportSend) ? rediAdmin.supportSend : 'Send';
            $submit.prop('disabled', false).text(sendLabel);
        });
    });

    jQuery(document).on('click', '#redi-free-plan-banner .notice-dismiss', function () {
        if (typeof rediAdmin === 'undefined' || !rediAdmin.dismissBannerNonce) {
            return;
        }
        jQuery.post(rediAdmin.ajaxUrl || ajaxurl, {
            action: 'redi_dismiss_free_plan_banner',
            nonce: rediAdmin.dismissBannerNonce
        });
    });

});
