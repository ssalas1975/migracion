<?php

/**
 * VocentoAnalyticsMB short summary.
 *
 * VocentoAnalyticsMB description.
 *
 * @version 1.0
 * @author j.godoy
 */
class VocentoAnalyticsMB
{

    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action('save_post', array($this, 'vcanly_save_analytics_meta_box_data'), 1);

        add_action('add_meta_boxes', function ($hook) {

            $screens = array('post', 'page');
            if ($screens && is_array($screens)) {
                foreach ($screens as $screen) {

                    add_meta_box(
                        'vcanly_mb',
                        __('Vocento Analytics', 'vcanly'),
                        array($this, 'vcanly_meta_box_callback'),
                        $screen,
                        'normal'
                    );
                }
            }
        });

    }


    public function vcanly_meta_box_callback($post)
    {

        wp_nonce_field('vcanly_save_analytics_meta_box_data', 'vcanly_meta_box_nonce');
        $value = get_post_meta($post->ID, 'vcanly_page_config', true);

        if (!is_array($value)) {
            $value = array();
        }

        //Recuperamos datos actuales y pintamos
        echo ' <div class="vcanly_metabox">';
        echo ' <div class="vcanly_info">Analytics personalizado: Puede sobreescribir los siguientes valores para esta entrada.</div>';
        echo ' <label for="voc_tc">voc_tc</label><input type="text" placeholder="voc_tc" value="' . (($value['voc_tc']) ? $value['voc_tc'] : '') . '" id="voc_tc" name="voc_tc"/><br />';
        echo ' <label for="voc_tn">voc_tn</label><input type="text" placeholder="voc_tn" value="' . (($value['voc_tn']) ? $value['voc_tn'] : '') . '" id="voc_tn" name="voc_tn"/><br />';
        echo ' <label for="voc_se">voc_se</label><input type="text" placeholder="voc_se" value="' . (($value['voc_se']) ? $value['voc_se'] : '') . '" id="voc_se" name="voc_se"/><br /><br />';
        echo ' <label for="voc_s1">voc_s1</label><input type="text" placeholder="voc_s1" value="' . (($value['voc_s1']) ? $value['voc_s1'] : '') . '" id="voc_s1" name="voc_s1"/><br /><br />';
        echo ' <label for="voc_s2">voc_s2</label><input type="text" placeholder="voc_s2" value="' . (($value['voc_s2']) ? $value['voc_s2'] : '') . '" id="voc_s2" name="voc_s2"/><br /><br />';
        echo ' <label for="voc_s3">voc_s3</label><input type="text" placeholder="voc_s3" value="' . (($value['voc_s3']) ? $value['voc_s3'] : '') . '" id="voc_s3" name="voc_s3"/><br /><br />';
        echo ' <label for="voc_s4">voc_s4</label><input type="text" placeholder="voc_s4" value="' . (($value['voc_s4']) ? $value['voc_s4'] : '') . '" id="voc_s4" name="voc_s4"/><br /><br />';
        echo ' <label for="name">voc_name/name</label><input type="text" placeholder="name" value="' . (($value['name']) ? $value['name'] : '') . '" id="name" name="name"/><br /><br />';
        echo '<input style="width:initial;" type="checkbox"' . (($value['voc_hide_dates']) ? "checked" : '') . ' value="true" id="voc_hide_dates" name="voc_hide_dates"/> <b>Ocultar fechas (voc_fep/voc_fem)</b><br /><br />';
        echo '<input style="width:initial;" type="checkbox"' . (($value['amp']) ? "checked" : '') . ' value="true" id="amp" name="amp"/> <b>Incluir AMP</b><br />';
        echo ' </div>';
    }


    public function vcanly_save_analytics_meta_box_data($post_id)
    {


        if (!isset($_POST['vcanly_meta_box_nonce'])) {
            return;
        }

        if (!wp_verify_nonce($_POST['vcanly_meta_box_nonce'], 'vcanly_save_analytics_meta_box_data')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }


        if (!current_user_can('edit_post', $post_id)) {
            return;
        }


        $metavalues = array();

        if (isset($_POST['voc_tc'])) {
            $metavalues['voc_tc'] = $_POST['voc_tc'];
        }

        if (isset($_POST['voc_tn'])) {
            $metavalues['voc_tn'] = $_POST['voc_tn'];
        }

        if (isset($_POST['voc_se'])) {
            $metavalues['voc_se'] = $_POST['voc_se'];
        }

        if (isset($_POST['voc_s1'])) {
            $metavalues['voc_s1'] = $_POST['voc_s1'];
        }

        if (isset($_POST['voc_s2'])) {
            $metavalues['voc_s2'] = $_POST['voc_s2'];
        }

        if (isset($_POST['voc_s3'])) {
            $metavalues['voc_s3'] = $_POST['voc_s3'];
        }
        if (isset($_POST['voc_s4'])) {
            $metavalues['voc_s4'] = $_POST['voc_s4'];
        }

        if (isset($_POST['name'])) {
            $metavalues['name'] = $_POST['name'];
        }

        if (isset($_POST['voc_hide_dates'])) {
            $metavalues['voc_hide_dates'] = $_POST['voc_hide_dates'];
        }

        if (isset($_POST['amp'])) {
            $metavalues['amp'] = $_POST['amp'];
        }

        update_post_meta($post_id, 'vcanly_page_config', $metavalues);
    }


}