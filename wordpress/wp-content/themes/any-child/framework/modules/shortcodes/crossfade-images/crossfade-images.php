<?php
namespace AnyEdge\Modules\Shortcodes\CrossfadeImages;

use AnyEdge\Modules\Shortcodes\Lib\ShortcodeInterface;

/**
 * Class CrossfadeImages
 */
class CrossfadeImages implements ShortcodeInterface {
    /**
     * @var string
     */
	private $base; 
	
    /**
     * CrossfadeImages constructor.
     */
	function __construct() {
		$this->base = 'edgtf_crossfade_images';

		add_action('vc_before_init', array($this, 'vcMap'));
	}
	
	/**
		* Returns base for shortcode
		* @return string
	 */
	public function getBase() {
		return $this->base;
	}	
    
	public function vcMap() {
						
		vc_map( array(
			'name' => 'Crossfade Images',
			'base' => $this->getBase(),
			'category' => 'by EDGE',
			'icon' => 'icon-wpb-crossfade-images extended-custom-icon',
			'params' =>	array(
                array(
                    'type' => 'attach_image',
                    'heading' => 'Initial Image',
                    'param_name' => 'initial_image'
                ),
                array(
                    'type' => 'attach_image',
                    'heading' => 'Hover Image',
                    'param_name' => 'hover_image'
                ),
                array(
                    'type' => 'textfield',
                    'heading' => 'Title',
                    'param_name' => 'title',
                    'admin_label' => true,
                ),
                array(
                    'type'        => 'textfield',
                    'heading'     => 'Link',
                    'param_name'  => 'link',
                    'admin_label' => true
                ),
                array(
                    'type'        => 'dropdown',
                    'heading'     => 'Link target',
                    'param_name'  => 'link_target',
                    'description' => '',
                    'value'       => array(
                        'New Window' => '_blank',
                        'Same Window'  => '_self'
                    ),
                    'save_always' => true,
                    'dependency' => array( 'element' => 'link', 'not_empty' => true )
                ),
                array(
                    'type'        => 'colorpicker',
                    'heading'     => 'Background Color',
                    'param_name'  => 'background_color',
                    'group'       => 'Design Options'
                ),
            )
		) );

	}

	public function render($atts, $content = null) {
		
		$args = array(
            'initial_image' => '',
            'hover_image'   => '',
            'title'         => '',
            'link'          => '',
            'link_target'   => '',
            'background_color'   => '',
        );

        $params = shortcode_atts($args, $atts);

        extract($params);

        return any_edge_get_shortcode_module_template_part('templates/crossfade-images-template', 'crossfade-images', '', $params);
    }
}