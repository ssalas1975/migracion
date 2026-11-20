<?php

namespace ElementsKit_Lite;

defined('ABSPATH') || exit;

class ElementsKit_Json_Handler {

	const MIME_TYPE = 'application/json';
	const EXT = 'json';

	/**
	 * Widget type the save-time checks apply to.
	 */
	const WIDGET_TYPE = 'elementskit-lottie';

	/**
	 * Lottie JSON can carry expressions that lottie-web runs with eval(), so the
	 * animation source is trusted content. Like Elementor Pro, only users who can
	 * publish pages may upload JSON files or point the widget at a JSON URL.
	 */
	const CAPABILITY = 'publish_pages';


	/**
	 * The plugin instance.
	 */
	public static $instance = null;


	/**
	 * constructor function.
	 */
	public function __construct() {
		add_filter('upload_mimes', array($this, 'upload_mimes'));
		add_filter('wp_handle_upload_prefilter', array($this, 'wp_handle_upload_prefilter'));
		add_filter('wp_check_filetype_and_ext', array($this, 'wp_check_filetype_and_ext'), 10, 4);
		add_filter('elementor/document/save/data', array($this, 'restrict_lottie_sources'), 10, 2);
	}


	/**
	 * Whether the current user may choose the animation source: upload JSON
	 * files or set a JSON URL.
	 */
	public static function current_user_can_manage_json() {
		return current_user_can(self::CAPABILITY);
	}


	/**
	 * Adds json file format
	 */
	public function upload_mimes($allowed_types) {
		if(self::current_user_can_manage_json()) {
			$allowed_types[self::EXT] = self::MIME_TYPE;
		}

		return $allowed_types;
	}


	public function wp_handle_upload_prefilter($file) {
		if(self::MIME_TYPE !== $file['type']) {
			return $file;
		}

		$ext = pathinfo($file['name'], PATHINFO_EXTENSION);

		if(self::EXT !== $ext) {
			$file['error'] = sprintf('%1$s %2$s %3$s',
				esc_html__('The uploaded', 'elementskit-lite'),
				$file['name'],
				esc_html__('file is not supported. Please upload a valid JSON file', 'elementskit-lite'),
			);

			return $file;
		}

		return $file;
	}


	public function wp_check_filetype_and_ext($data, $file, $filename, $mimes) {
		if(!empty($data['ext']) && !empty($data['type'])) {
			return $data;
		}

		if(!self::current_user_can_manage_json()) {
			return $data;
		}

		$filetype = wp_check_filetype($filename, $mimes);

		if(self::EXT === $filetype['ext']) {
			$data['ext']  = self::EXT;
			$data['type'] = self::MIME_TYPE;
		}

		return $data;
	}


	/**
	 * Server-side enforcement of the source restriction. Hiding the controls in
	 * the editor is not enough: the save request can be crafted by hand.
	 *
	 * For users without the capability, every Lottie widget keeps the JSON URL
	 * and media value it already had; new values are dropped. A media value is
	 * only accepted when it points to an existing JSON attachment, and its URL is
	 * then rebuilt from the attachment ID.
	 *
	 * @param array                         $data     Document data being saved.
	 * @param \Elementor\Core\Base\Document $document Document being saved.
	 */
	public function restrict_lottie_sources($data, $document) {
		if(self::current_user_can_manage_json() || empty($data['elements']) || !is_array($data['elements'])) {
			return $data;
		}

		$saved = [];

		// The document itself, then its main post (autosaves start out empty).
		$documents = [$document];

		if(method_exists($document, 'get_main_id') && $document->get_main_id() !== $document->get_id()) {
			$documents[] = \Elementor\Plugin::$instance->documents->get($document->get_main_id());
		}

		foreach($documents as $doc) {
			if($doc) {
				$saved += $this->collect_lottie_settings((array) $doc->get_elements_data());
			}
		}

		$data['elements'] = $this->sanitize_elements($data['elements'], $saved);

		return $data;
	}


	/**
	 * Map of element ID => settings for every Lottie widget in the tree.
	 */
	private function collect_lottie_settings(array $elements) {
		$found = [];

		foreach($elements as $element) {
			if(!is_array($element)) {
				continue;
			}

			if(self::WIDGET_TYPE === ($element['widgetType'] ?? '') && !empty($element['id'])) {
				$found[$element['id']] = isset($element['settings']) && is_array($element['settings']) ? $element['settings'] : [];
			}

			if(!empty($element['elements']) && is_array($element['elements'])) {
				$found += $this->collect_lottie_settings($element['elements']);
			}
		}

		return $found;
	}


	private function sanitize_elements(array $elements, array $saved) {
		foreach($elements as $index => $element) {
			if(!is_array($element)) {
				continue;
			}

			if(self::WIDGET_TYPE === ($element['widgetType'] ?? '')) {
				$old_settings = isset($element['id'], $saved[$element['id']]) ? $saved[$element['id']] : [];
				$settings     = isset($element['settings']) && is_array($element['settings']) ? $element['settings'] : [];

				$elements[$index]['settings'] = $this->sanitize_settings($settings, $old_settings);
			}

			if(!empty($element['elements']) && is_array($element['elements'])) {
				$elements[$index]['elements'] = $this->sanitize_elements($element['elements'], $saved);
			}
		}

		return $elements;
	}


	private function sanitize_settings(array $settings, array $old_settings) {
		// JSON URL, static or dynamic: keep only what was already saved.
		$settings = $this->keep_saved_value($settings, $old_settings, 'ekit_lottie_url');

		$dynamic     = isset($settings['__dynamic__']) && is_array($settings['__dynamic__']) ? $settings['__dynamic__'] : [];
		$old_dynamic = isset($old_settings['__dynamic__']) && is_array($old_settings['__dynamic__']) ? $old_settings['__dynamic__'] : [];

		foreach(['ekit_lottie_url', 'ekit_lottie_json'] as $key) {
			$dynamic = $this->keep_saved_value($dynamic, $old_dynamic, $key);
		}

		if(empty($dynamic)) {
			unset($settings['__dynamic__']);
		} else {
			$settings['__dynamic__'] = $dynamic;
		}

		// Media file: an existing JSON attachment, with its real URL.
		$media = isset($settings['ekit_lottie_json']) && is_array($settings['ekit_lottie_json']) ? $settings['ekit_lottie_json'] : null;

		if(null !== $media) {
			$attachment_url = self::get_json_attachment_url($media['id'] ?? 0);

			if($attachment_url) {
				$settings['ekit_lottie_json'] = [
					'id'  => absint($media['id']),
					'url' => $attachment_url,
				];
			} else {
				$settings = $this->keep_saved_value($settings, $old_settings, 'ekit_lottie_json');
			}
		}

		return $settings;
	}


	/**
	 * Keep $key only when it equals the saved value; otherwise restore the saved
	 * value, or drop the key when nothing was saved.
	 */
	private function keep_saved_value(array $settings, array $old_settings, $key) {
		if(!array_key_exists($key, $settings)) {
			return $settings;
		}

		if(array_key_exists($key, $old_settings)) {
			$settings[$key] = $old_settings[$key];
		} else {
			unset($settings[$key]);
		}

		return $settings;
	}


	/**
	 * URL of an existing JSON attachment, or '' when the ID is not one.
	 */
	public static function get_json_attachment_url($attachment_id) {
		$attachment_id = absint($attachment_id);

		if(!$attachment_id || 'attachment' !== get_post_type($attachment_id)) {
			return '';
		}

		$file_url  = wp_get_attachment_url($attachment_id);
		$file_type = wp_check_filetype((string) wp_parse_url((string) $file_url, PHP_URL_PATH), [self::EXT => self::MIME_TYPE]);

		return ($file_url && self::EXT === $file_type['ext']) ? $file_url : '';
	}


	/**
	 * Instance.
	 */
	public static function instance() {
		if(is_null(self::$instance)) {
			// Fire when ElementsKit_Lite instance.
			self::$instance = new self();
		}

		return self::$instance;
	}
}

// Run the instance.
ElementsKit_Json_Handler::instance();
