<?php

class CacheHeaderSkeleton
{
    private $cache_header_skeleton_options;
    private $messages=array();

    public function __construct()
    {
        $this->loadSettings();

        if (isset($_POST["submit-cache-header-settings"])) {
            $this->save();
        }

        // add_action('network_admin_menu', array( $this, 'cache_header_skeleton_add_plugin_page' ));
        add_action('admin_menu', array( $this, 'cache_header_skeleton_add_plugin_page' ));
        add_action('admin_init', array( $this, 'cache_header_skeleton_page_init' ));
    }

    public function loadSettings()
    {
        //Set options
        // if (is_network_admin()) {
        //     $this->cache_header_skeleton_options = json_decode(get_site_option('submit_cache_header_settings'), true);
        // }

        if (is_admin()) {
            $this->cache_header_skeleton_options = json_decode(get_option('submit_cache_header_settings'), true);
        }


        if (!is_array($this->cache_header_skeleton_options)) {
            $this->cache_header_skeleton_options=array();
        }
    }

    public function cache_header_skeleton_add_plugin_page()
    {
        add_menu_page(
            'Cache Header Skeleton', // page_title
            'Cache Header Skeleton', // menu_title
            'manage_options', // capability
            'cache-header-skeleton', // menu_slug
            array( $this, 'cache_header_skeleton_create_admin_page' ), // function
            'dashicons-admin-generic', // icon_url
            3 // position
        );
    }

    public function cache_header_skeleton_create_admin_page()
    {
        ?>

		<div class="wrap">
			<h2>Cache Header Skeleton</h2>
			<?php settings_errors(); ?>

			<form method="post">
				<?php
                    settings_fields('cache_header_skeleton_option_group');
        do_settings_sections('cache-header-skeleton-admin');
        submit_button('Guardar', 'button button-primary', 'submit-cache-header-settings'); ?>
			</form>
		</div>
	<?php
    }

    public function cache_header_skeleton_page_init()
    {
        register_setting(
            'cache_header_skeleton_option_group', // option_group
            'cache_header_skeleton_option_name', // option_name
            array( $this, 'cache_header_skeleton_sanitize' ) // sanitize_callback
        );

        add_settings_section(
            'cache_header_skeleton_setting_section', // id
            'Settings', // title
            array( $this, 'cache_header_skeleton_section_info' ), // callback
            'cache-header-skeleton-admin' // page
        );

        add_settings_field(
            'url_skeleton', // id
            'url skeleton', // title
            array( $this, 'url_skeleton_callback' ), // callback
            'cache-header-skeleton-admin', // page
            'cache_header_skeleton_setting_section' // section
        );

        add_settings_field(
            'url_skeleton_amp', // id
            'url skeleton amp', // title
            array( $this, 'url_skeleton_callback_amp' ), // callback
            'cache-header-skeleton-admin', // page
            'cache_header_skeleton_setting_section' // section
        );

        // add_settings_field(
        //     'host', // id
        //     'host <br/><span style="font-size:10px">(dejar vacion si no se consulta al HttpFrontalClient)</span>', // title
        //     array( $this, 'host_callback' ), // callback
        //     'cache-header-skeleton-admin', // page
        //     'cache_header_skeleton_setting_section' // section
        // );

        add_settings_field(
            'duration', // id
            'Duración <span style="font-size:10px">(en segundos)</span>', // title
            array( $this, 'duration_callback' ), // callback
            'cache-header-skeleton-admin', // page
            'cache_header_skeleton_setting_section' // section
        );
    }

    public function cache_header_skeleton_sanitize($input)
    {
        $sanitary_values = array();
        if (isset($input['url_skeleton'])) {
            $sanitary_values['url_skeleton'] = sanitize_text_field($input['url_skeleton']);
        }

        if (isset($input['host'])) {
            $sanitary_values['host'] = sanitize_text_field($input['host']);
        }

        if (isset($input['duration'])) {
            $sanitary_values['duration'] = sanitize_text_field($input['duration']);
        }

        return $sanitary_values;
    }

    public function cache_header_skeleton_section_info()
    {
    }

    public function url_skeleton_callback()
    {
        printf(
            '<input class="regular-text" type="text" name="cache_header_skeleton_option_name[url_skeleton]" id="url_skeleton" value="%s">',
            isset($this->cache_header_skeleton_options['url_skeleton']) ? esc_attr($this->cache_header_skeleton_options['url_skeleton']) : ''
        );
    }

    public function url_skeleton_callback_amp()
    {
        printf(
            '<input class="regular-text" type="text" name="cache_header_skeleton_option_name[url_skeleton_amp]" id="url_skeleton_amp" value="%s">',
            isset($this->cache_header_skeleton_options['url_skeleton_amp']) ? esc_attr($this->cache_header_skeleton_options['url_skeleton_amp']) : ''
        );
    }

    
    public function host_callback()
    {
        printf(
            '<input class="regular-text" type="text" name="cache_header_skeleton_option_name[host]" id="host" value="%s">',
            isset($this->cache_header_skeleton_options['host']) ? esc_attr($this->cache_header_skeleton_options['host']) : ''
        );
    }

    public function duration_callback()
    {
        printf(
            '<input class="regular-text" type="text" name="cache_header_skeleton_option_name[duration]" id="section" value="%s">',
            isset($this->cache_header_skeleton_options['duration']) ? esc_attr($this->cache_header_skeleton_options['duration']) : ''
        );
    }

    public function addMessage($mesage)
    {
        array_push($this->messages, array('class'=>'notice-success', 'msg'=>$mesage));
    }

    public function displayMessages()
    {
        foreach ($this->messages as $msg) {
            echo "<div class='notice  ".$msg['class']."'>".$msg['msg']."</div>";
        }
    }

    public function addErrorMessage($mesage)
    {
        array_push($this->messages, array('class'=>'notice-success', 'msg'=>$mesage));
    }

    public function save()
    {
        $this->cache_header_skeleton_options['url_skeleton']=$_POST["cache_header_skeleton_option_name"]["url_skeleton"];
        $this->cache_header_skeleton_options['url_skeleton_amp']=$_POST["cache_header_skeleton_option_name"]["url_skeleton_amp"];
        $this->cache_header_skeleton_options['host']=$_POST["cache_header_skeleton_option_name"]["host"];
        $this->cache_header_skeleton_options['duration']=$_POST["cache_header_skeleton_option_name"]["duration"];

        $this->cache_header_skeleton_options =  json_encode($this->cache_header_skeleton_options);
       
        if (is_network_admin()) {
            // update_site_option('submit_cache_header_settings', $this->cache_header_skeleton_options, true);
            // $this->addMessage("Ajustes guardados correctamente");
        } elseif (is_admin()) {
            update_option('submit_cache_header_settings', $this->cache_header_skeleton_options, true);
            $this->addMessage("Ajustes guardados correctamente");
        } else {
            $this->addErrorMessage("Ajustes no guardados. Solo se puede editar a nivel de red");
        }

        $this->loadSettings();
    }
}
if (is_admin()) {
    $cache_header_skeleton = new CacheHeaderSkeleton();
}
