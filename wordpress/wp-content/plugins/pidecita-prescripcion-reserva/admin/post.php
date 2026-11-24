<?php
add_action( 'load-post.php', 'pidecita_prescripcion_addedit' ) ;
add_action( 'load-post-new.php', 'pidecita_prescripcion_addnew'  );
add_action('save_post', 'pidecita_prescripcion_save_meta');

function pidecita_prescripcion_addedit() {
    add_action( 'add_meta_boxes', 'pidecita_prescripcion_admin' );
}

function pidecita_prescripcion_addnew() {
    add_action( 'add_meta_boxes', 'pidecita_prescripcion_admin_new');
}

function pidecita_prescripcion_admin_new($type) {
    pidecita_prescripcion_admin($type, true);
}

function pidecita_prescripcion_admin($type, $isNew = false)
{
    if ($type === 'post') {
        add_action( 'admin_enqueue_scripts', 'pidecita_prescripcion_enqueue' );
        add_meta_box('pidecita_prescripcion', 'Pidecita Prescripcion', 'pidecita_prescripcion_render', $type, 'advanced', 'default', ['isNew' => $isNew]);
    }
}

function pidecita_prescripcion_save_meta( $post_id ) {
    $prefix = 'pidecita-prescripcion-reserva';
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    if ( isset($_POST[$prefix]) ) {
        foreach ($_POST[$prefix] as $key => $value) {
            update_post_meta($post_id, $prefix. '_' . $key, sanitize_text_field( $value ));
        }
        if (!array_key_exists('enabled', $_POST[$prefix])) {
            update_post_meta($post_id, $prefix. '_enabled', '');
        }
    }
}

function pidecita_prescripcion_render( $post_object, $param = []) { ?>
    <?php
    $prefix = 'pidecita-prescripcion-reserva';
    $isNew = $param['args']['isNew'];
    ?>
        <div class="td-meta-box-row">
            <span class="td-page-o-custom-label">Incluir</span>
            <label for="<?php echo $prefix; ?>_enabled">
                <input type="checkbox" name="<?php echo $prefix; ?>[enabled]"  id="<?php echo $prefix; ?>_enabled" value="1" <?php if (esc_attr( get_post_meta( $post_object->ID, $prefix. '_enabled', true )) == 1 || $isNew) echo "checked=checked"; ?>>
                Incluir módulo de prescripción de pidecita
            </label>
            <span class="td-page-o-info">Seleccione la Categoria, subcategoría y/o ciudad</span>
        </div>
        <div class="td-meta-box-row">
            <span class="td-page-o-custom-label"></span>
            <a href="" id="<?php echo $prefix; ?>_reload" class="page-title-action">Reiniciar</a>
        </div>
        <div class="td-meta-box-row" style="display: none">
            <span class="td-page-o-custom-label">Categoría</span>
            <input type="hidden" name="<?php echo $prefix; ?>[category_name]" class="<?php echo $prefix; ?>_name" id="<?php echo $prefix; ?>_category_name" value="<?php echo esc_attr( get_post_meta( $post_object->ID, $prefix. '_category_name', true ) ); ?>">
            <select name="<?php echo $prefix; ?>[category]" class="<?php echo $prefix; ?> td-selected-sidebar" data-value="<?php echo esc_attr( get_post_meta( $post_object->ID, $prefix. '_category', true ) ); ?>" id="<?php echo $prefix; ?>_category_value">
                <option selected="selected" value="0">Categoría</option>
            </select>
        </div>
        <div class="td-meta-box-row">
            <span class="td-page-o-custom-label">Categoría, Subcategoría</span>
            <input type="hidden" name="<?php echo $prefix; ?>[subcategory_name]" class="<?php echo $prefix; ?>_name" id="<?php echo $prefix; ?>_subcategory_name" value="<?php echo esc_attr( get_post_meta( $post_object->ID, $prefix. '_subcategory_name', true ) ); ?>">
            <select name="<?php echo $prefix; ?>[subcategory]" class="<?php echo $prefix; ?> td-selected-sidebar" data-value="<?php echo esc_attr( get_post_meta( $post_object->ID, $prefix. '_subcategory', true ) ); ?>" id="<?php echo $prefix; ?>_subcategory_value">
                <option selected="selected" value="0">Categoría, Subcategoría</option>
            </select>
        </div>
        <div class="td-meta-box-row">
            <span class="td-page-o-custom-label">Ciudad</span>
            <input type="hidden" name="<?php echo $prefix; ?>[city_name]" class="<?php echo $prefix; ?>_name" id="<?php echo $prefix; ?>_city_name" value="<?php echo esc_attr( get_post_meta( $post_object->ID, $prefix. '_city_name', true ) ); ?>">
            <select name="<?php echo $prefix; ?>[city]" class="<?php echo $prefix; ?> td-selected-sidebar" data-value="<?php echo esc_attr( get_post_meta( $post_object->ID, $prefix. '_city', true ) ); ?>" id="<?php echo $prefix; ?>_city_value">
                <option selected="selected" value="0">Ciudad</option>
            </select>
        </div>
        <div class="td-meta-box-row">
            <span class="td-page-o-custom-label">Título</span>
            <input type="text" class="widefat <?php echo $prefix; ?>_name" name="<?php echo $prefix; ?>[title]" id="<?php echo $prefix; ?>_title" value="<?php echo esc_attr( get_post_meta( $post_object->ID, $prefix. '_title', true ) ); ?>">
        </div>
        <div class="td-meta-box-row">
            <span class="td-page-o-custom-label"></span>
            <span class=""></span>
        </div>
    <?php
}

function pidecita_prescripcion_enqueue(){
    wp_enqueue_style('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css' );
    wp_enqueue_script('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js', array('jquery') );
    wp_enqueue_script('pidecita-prescripcion-metabox_script', plugins_url('js/pidecita-prescripcion-metabox-js.js', __FILE__), array('jquery'));
}
?>