<?php
if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('ReDiDeactivationFeedback')) {
    return;
}
$redi_reasons     = ReDiDeactivationFeedback::reasons();
$redi_contact_for = ReDiDeactivationFeedback::contact_reasons();
$redi_user_email  = wp_get_current_user()->user_email;
$redi_td          = 'redi-restaurant-reservation';
?>
<div class="radi-modal" id="redi-deactivate-modal" style="display:none">
    <div class="radi-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="redi-deactivate-title">
        <form method="post" id="redi-deactivate-form">
            <div class="radi-modal-header">
                <h4 id="redi-deactivate-title"><?php esc_html_e('Quick feedback', $redi_td); ?></h4>
            </div>
            <div class="radi-modal-body">
                <h3><?php esc_html_e('If you have a moment, please tell us why you are deactivating ReDi:', $redi_td); ?></h3>
                <ul id="radi-list">
                    <?php foreach ($redi_reasons as $redi_code => $redi_reason) : ?>
                    <li class="reason" data-reason="<?php echo esc_attr($redi_code); ?>">
                        <label>
                            <input type="radio" name="redi_reason" value="<?php echo esc_attr($redi_code); ?>">
                            <span><?php echo esc_html($redi_reason); ?></span>
                        </label>
                        <div class="redi-reason-extra">
                        <?php switch ($redi_code) :
                            case 'not_working': ?>
                            <textarea name="redi_comment" maxlength="2000" placeholder="<?php esc_attr_e('What went wrong? An error message, which page, what you expected to happen.', $redi_td); ?>"></textarea>
                            <?php break;
                            case 'cant_configure': ?>
                            <select name="redi_setup_step">
                                <option value=""><?php esc_html_e('Where did you get stuck?', $redi_td); ?></option>
                                <?php foreach (ReDiDeactivationFeedback::setup_steps() as $redi_k => $redi_v) : ?>
                                <option value="<?php echo esc_attr($redi_k); ?>"><?php echo esc_html($redi_v); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <textarea name="redi_comment" maxlength="2000" placeholder="<?php esc_attr_e('What were you trying to set up?', $redi_td); ?>"></textarea>
                            <?php break;
                            case 'missing_feature': ?>
                            <select name="redi_feature">
                                <option value=""><?php esc_html_e('Which feature?', $redi_td); ?></option>
                                <?php foreach (ReDiDeactivationFeedback::features() as $redi_k => $redi_v) : ?>
                                <option value="<?php echo esc_attr($redi_k); ?>"><?php echo esc_html($redi_v); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <textarea name="redi_comment" maxlength="2000" placeholder="<?php esc_attr_e('Tell us a bit more about how you would use it.', $redi_td); ?>"></textarea>
                            <?php break;
                            case 'too_expensive': ?>
                            <input type="text" name="redi_price" maxlength="100" placeholder="<?php esc_attr_e('What monthly price would work for you?', $redi_td); ?>">
                            <?php break;
                            case 'found_better': ?>
                            <select name="redi_competitor">
                                <option value=""><?php esc_html_e('Which one?', $redi_td); ?></option>
                                <?php foreach (ReDiDeactivationFeedback::competitors() as $redi_v) : ?>
                                <option value="<?php echo esc_attr($redi_v); ?>"><?php echo esc_html($redi_v); ?></option>
                                <?php endforeach; ?>
                                <option value="other"><?php esc_html_e('Another one', $redi_td); ?></option>
                            </select>
                            <input type="text" name="redi_competitor_other" class="redi-competitor-other" maxlength="200" placeholder="<?php esc_attr_e('Name of the plugin or service', $redi_td); ?>">
                            <textarea name="redi_comment" maxlength="2000" placeholder="<?php esc_attr_e('What does it do better?', $redi_td); ?>"></textarea>
                            <?php break;
                            case 'design_outdated': ?>
                            <textarea name="redi_comment" maxlength="2000" placeholder="<?php esc_attr_e('What should look better: the booking form, the admin, the emails?', $redi_td); ?>"></textarea>
                            <?php break;
                            case 'other': ?>
                            <textarea name="redi_comment" maxlength="2000" class="redi-required-comment" placeholder="<?php esc_attr_e('Please tell us the reason', $redi_td); ?>"></textarea>
                            <?php break;
                        endswitch; ?>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>

                <div class="redi-contact" hidden>
                    <label>
                        <input type="checkbox" name="redi_contact" value="1">
                        <?php esc_html_e('Contact me, I would like help with this', $redi_td); ?>
                    </label>
                    <input type="email" name="redi_contact_email" value="<?php echo esc_attr($redi_user_email); ?>" placeholder="<?php esc_attr_e('Email address', $redi_td); ?>">
                </div>

                <div class="redi-tech">
                    <label>
                        <input type="checkbox" name="redi_include_tech" value="1" checked>
                        <?php esc_html_e('Include technical details', $redi_td); ?>
                    </label>
                    <a href="#" class="redi-what-sent-toggle"><?php esc_html_e('What is sent?', $redi_td); ?></a>
                    <p class="redi-what-sent" hidden>
                        <?php esc_html_e('Your answer is sent to ReDi together with your ReDi plugin key and an anonymous install ID. Technical details add: site address, ReDi, WordPress and PHP versions, site language, active theme and the list of active plugins. Your email is sent only if you ask us to contact you.', $redi_td); ?>
                    </p>
                </div>
            </div>
            <div class="radi-modal-footer">
                <a href="#" class="redi-skip"><?php esc_html_e('Skip & deactivate', $redi_td); ?></a>
                <a href="#" class="button button-secondary button-close"><?php esc_html_e('Cancel', $redi_td); ?></a>
                <input type="submit" class="button button-secondary redi-submit" value="<?php esc_attr_e('Submit & deactivate', $redi_td); ?>" disabled>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
(function ($) {
    var $modal = $('#redi-deactivate-modal');
    var $form = $('#redi-deactivate-form');
    var contactReasons = <?php echo wp_json_encode(array_values($redi_contact_for)); ?>;
    var linkSelector = 'tr[data-slug="redi-restaurant-reservation"] .deactivate a';

    function hide() { $modal.hide().removeClass('active'); }

    function setReason(code) {
        $form.find('li.reason').each(function () {
            var on = $(this).data('reason') === code;
            $(this).toggleClass('is-selected', on);
            $(this).find('.redi-reason-extra').toggle(on).find(':input').prop('disabled', !on);
        });
        $form.find('.redi-required-comment').prop('required', code === 'other');
        $form.find('.redi-competitor-other').hide();
        $form.find('select[name="redi_competitor"]').val('');

        var showContact = contactReasons.indexOf(code) > -1;
        var $contact = $form.find('.redi-contact');
        $contact.prop('hidden', !showContact).find(':input').prop('disabled', !showContact);
        updateContact();
        $form.find('.redi-submit').prop('disabled', false);
    }

    function updateContact() {
        var $contact = $form.find('.redi-contact');
        var on = !$contact.prop('hidden') && $contact.find('input[name="redi_contact"]').is(':checked');
        $contact.find('input[name="redi_contact_email"]').toggle(on).prop('required', on);
    }

    $(document).on('click', linkSelector, function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $form.attr('action', url);
        $form.find('.redi-skip').attr('href', url);
        $modal.show().addClass('active');
    });

    $form.on('change', 'input[name="redi_reason"]', function () { setReason(this.value); });
    $form.on('change', 'input[name="redi_contact"]', updateContact);
    $form.on('change', 'select[name="redi_competitor"]', function () {
        $form.find('.redi-competitor-other').toggle(this.value === 'other');
    });
    $form.on('click', '.redi-what-sent-toggle', function (e) {
        e.preventDefault();
        var $p = $form.find('.redi-what-sent');
        $p.prop('hidden', !$p.prop('hidden'));
    });
    $form.on('click', '.button-close', function (e) { e.preventDefault(); hide(); });
    $form.on('submit', function () {
        $form.find('.redi-submit').prop('disabled', true).val('<?php echo esc_js(__('Deactivating…', $redi_td)); ?>');
    });
    $modal.on('click', function (e) { if (e.target === this) { hide(); } });
    $(document).on('keydown', function (e) { if (e.key === 'Escape' && $modal.is(':visible')) { hide(); } });

    $form.find('.redi-reason-extra').hide().find(':input').prop('disabled', true);
    $form.find('.redi-contact :input').prop('disabled', true);
})(jQuery);
</script>
