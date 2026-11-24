<!-- Revision: 20261018 -->
<div style="clear:both"></div>
<div class="wrap">
    <div class="redi_box">
        <h2><?php _e('Api Information', 'redi-restaurant-reservation'); ?></h2>
        <h3><?php _e('ApiKey', 'redi-restaurant-reservation'); ?>:</h3>
        <b><i><?php echo($this->ApiKey); ?></i></b></br>
        <br/>
        <input value="<?php _e('Change API key', 'redi-restaurant-reservation'); ?>" class="button-primary1" id="key_edit" type="submit">
        <br/>
        <form method="post" id="form_key" style="display: none"><input type="hidden" name="action">
            <input id="new_key" type="text" name="new_key">
            <input type="hidden" name="redi_plugin_nonce" value="<?php echo wp_create_nonce('redi_restaurant_ajax') ?>" />
            <input value="<?php _e('Change', 'redi-restaurant-reservation'); ?>" class="button-primary1" id="key_edit" type="submit">
        </form>
        <br/>
        <?php _e('This is your registration key. Please use it when you send request for support.', 'redi-restaurant-reservation'); ?>
        <br/>
    </div>
    <div class="redi_box">
        <h2><?php _e('Contact Us', 'redi-restaurant-reservation'); ?></h2>
        <p><?php _e('Are you facing trouble configuring the plugin? Don\'t worry! We offer free personal assistance to help you out. You can schedule a session with us using Calendly or reach out to us via Skype, WhatsApp, or Facebook. We\'re here to provide the support you need.', 'redi-restaurant-reservation') ?></p>
        <p class="calendly_image"><a href="https://calendly.com/reservationdiary/demo?month=2023-04" target="_blank"><img style="width: 100px;" src="<?php echo esc_url(plugin_dir_url(__DIR__)."img/Consultation.png"); ?>" alt="<?php _e('Calendly', 'redi-restaurant-reservation'); ?>"></a>    
        </p>
        <div class="social_my_list">
                <a href="https://www.facebook.com/ReDiReservation" target="_blank"><img src="<?php echo esc_url(plugin_dir_url(__DIR__)."img/facebook.svg"); ?>" alt="<?php _e('facebook', 'redi-restaurant-reservation'); ?>"></a>
                <a href="https://wa.me/+3725165285" target="_blank"><img src="<?php echo esc_url(plugin_dir_url(__DIR__)."img/whatsapp.svg"); ?>" alt="<?php _e('whatsapp', 'redi-restaurant-reservation'); ?>"></a>
        </div>
        <p>
    		<a href="#redi-support-issue-modal" class="button-primary1 redi-open-support-issue"><?php _e('Report a problem', 'redi-restaurant-reservation') ?></a>
    	</p>
    </div>
    <?php
    $redi_trial_bottom_url = $this->get_trial_start_url('bottom');
    $redi_pricing_url = add_query_arg(
        array(
            'ref' => $this->ApiKey,
            'utm_source' => 'wp_settings',
        ),
        'https://reservationdiary.eu/pricing/'
    );
    ?>
    <div class="redi_box">
        <h2><?php esc_html_e('Try every paid feature free for 30 days', 'redi-restaurant-reservation'); ?></h2>
        <p><?php esc_html_e('No card needed. After the trial you stay on the Free plan unless you subscribe.', 'redi-restaurant-reservation'); ?></p>
        <p>
            <a class="button-primary1" href="<?php echo esc_url($redi_trial_bottom_url); ?>"><?php esc_html_e('Try all paid features free for 30 days →', 'redi-restaurant-reservation'); ?></a>
        </p>
    </div>
    <div class="redi_box">
        <h2><?php esc_html_e('Basic', 'redi-restaurant-reservation'); ?></h2>
        <p class="description">
            ◾ <?php _e('Open Host Dashboard from your mobile or tablet and never miss a guest. Keep this page open on a tablet so the host can see all reservations for today. The page refreshes every 15 min and shows reservations from the past 3 hours as well as the next 24 hours. Click a reservation to see its details. A demo can be accessed for 30 days using this link: ', 'redi-restaurant-reservation'); ?>
            <a href="<?php echo esc_url($this->redi->getWaiterDashboardUrl(self::lang())); ?>"
               target="_blank"><?php _e('Open Host Dashboard →', 'redi-restaurant-reservation'); ?></a><br/>
            ◾ <?php _e('Open times. This option will enable you to choose between various working hours whichever is most convenient to you.', 'redi-restaurant-reservation'); ?>
            <br/>
            ◾ <?php _e('Blocked Time. Define time range when online reservation should not be accepted. Specify a reason why reservations are not accepted at this time to keep your clients happy.', 'redi-restaurant-reservation'); ?>
            <br/>
            ◾ <?php _e('Email template customization for all supported languages.', 'redi-restaurant-reservation'); ?><br/>
        </p>
        <h2><?php esc_html_e('Full', 'redi-restaurant-reservation'); ?></h2>
        <p class="description">
            ◾ <?php _e('Support for multiple places. Number of places depends on number of subscriptions.', 'redi-restaurant-reservation'); ?>
            <br/>
            ◾ <?php _e('Table management', 'redi-restaurant-reservation'); ?>
            <br/>
            ◾ <?php _e('Use table capacity instead of total seats', 'redi-restaurant-reservation'); ?>
            <br/>
            ◾ <?php _e('Note: SMS and WhatsApp notifications are included in Full.', 'redi-restaurant-reservation'); ?><br/>
        </p>
        <p class="redi-subscribe-option">
            <a href="<?php echo esc_url($redi_pricing_url); ?>"
               class="button-primary2"
               target="_blank" rel="noopener noreferrer"><?php esc_html_e('See prices & subscribe →', 'redi-restaurant-reservation'); ?></a>
        </p>
        <p><?php esc_html_e('Your account is upgraded automatically after payment.', 'redi-restaurant-reservation'); ?></p>
        <p>
            <a href="mailto:info@reservationdiary.eu"><?php esc_html_e('Need something custom? Contact us', 'redi-restaurant-reservation'); ?></a>
        </p>
    </div>
</div>