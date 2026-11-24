<?php
if (!defined('ABSPATH')) {
    exit;
}

$redi_support_place_name = '';
$redi_support_place_email = '';
if (isset($place)) {
    if (is_object($place)) {
        $redi_support_place_name = isset($place->Name) ? $place->Name : '';
        $redi_support_place_email = isset($place->Email) ? $place->Email : '';
    } elseif (is_array($place)) {
        $redi_support_place_name = isset($place['Name']) ? $place['Name'] : '';
        $redi_support_place_email = isset($place['Email']) ? $place['Email'] : '';
    }
}
if ($redi_support_place_email === '') {
    $redi_support_place_email = get_option('admin_email');
}
$redi_support_api_key = isset($this->ApiKey) ? $this->ApiKey : '';
?>
<div id="redi-support-issue-modal" class="radi-modal redi-support-issue-modal" style="display:none" role="dialog" aria-modal="true" aria-labelledby="redi-support-issue-title">
    <div class="radi-modal-dialog">
        <form id="redi-support-issue-form" method="post" enctype="multipart/form-data">
            <div class="radi-modal-header">
                <h4 id="redi-support-issue-title"><?php _e('Report a problem', 'redi-restaurant-reservation'); ?></h4>
            </div>
            <div class="radi-modal-body">
                <p class="description"><?php _e('Describe the problem. We will email the details to support and include your restaurant information so we can help faster.', 'redi-restaurant-reservation'); ?></p>

                <input type="hidden" name="action" value="redi_support_issue_submit" />
                <input type="hidden" name="redi_plugin_nonce" value="<?php echo esc_attr(wp_create_nonce('redi_restaurant_ajax')); ?>" />
                <input type="hidden" name="place_name" value="<?php echo esc_attr($redi_support_place_name); ?>" />
                <input type="hidden" name="api_key" value="<?php echo esc_attr($redi_support_api_key); ?>" />

                <p>
                    <label for="redi-support-contact-email"><?php _e('Contact email', 'redi-restaurant-reservation'); ?><span class="redi_required"> *</span></label><br />
                    <input type="email" id="redi-support-contact-email" name="contact_email" required value="<?php echo esc_attr($redi_support_place_email); ?>" />
                </p>
                <p>
                    <label for="redi-support-description"><?php _e('Describe the problem', 'redi-restaurant-reservation'); ?><span class="redi_required"> *</span></label><br />
                    <textarea id="redi-support-description" name="description" required rows="8"></textarea>
                </p>
                <p>
                    <label for="redi-support-attachments"><?php _e('Attachments (optional)', 'redi-restaurant-reservation'); ?></label><br />
                    <input type="file" id="redi-support-attachments" name="attachments[]" multiple accept=".jpg,.jpeg,.png,.gif,.webp,.pdf,.txt,.log,.csv,.doc,.docx,.zip" />
                    <span class="description"><?php _e('You can attach screenshots or files (max 5 files, 5 MB each).', 'redi-restaurant-reservation'); ?></span>
                </p>
                <div id="redi-support-issue-message" class="notice" style="display:none;"></div>
            </div>
            <div class="radi-modal-footer">
                <button type="button" class="button-secondary button-close redi-support-issue-close"><?php _e('Cancel', 'redi-restaurant-reservation'); ?></button>
                <button type="submit" class="button-secondary" id="redi-support-issue-submit"><?php _e('Send', 'redi-restaurant-reservation'); ?></button>
            </div>
        </form>
    </div>
</div>
