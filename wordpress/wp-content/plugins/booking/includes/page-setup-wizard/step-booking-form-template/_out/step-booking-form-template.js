"use strict";

/**
 * Provide the reusable Booking Form template catalog and interactive preview.
 *
 * Catalog cards remain server-rendered. Direct templates use request-local
 * inline HTML, while Appointment Flow and full-site previews use signed URLs.
 * The browser never receives raw template definitions and never applies a form.
 *
 * @package Booking Calendar
 */
(function (window, document) {
  'use strict';

  var module_config = window.wpbc_setup_wizard_booking_form_template || {
    i18n: {}
  };

  /**
   * Create one isolated Booking Form template selector adapter.
   *
   * @param {Object} config Module endpoint and translation settings.
   * @return {Object} Step adapter accepted by the shared wizard shell.
   */
  function create_booking_form_template_adapter(config) {
    var appointment_flow_dialog_action = 'booking-form-appointment-flow';
    var template_guidance_dialog_action = 'booking-form-template-guidance';
    var shell_api = null;
    var root = null;
    var editor = null;
    var confirmed_booking_form_usage = '';
    var preview_request_sequence = 0;
    var preview_abort_controller = null;
    var open_request_sequence = 0;
    var open_abort_controller = null;
    var open_request_is_loading = false;
    var inline_preview_renderer = null;
    var current_preview = {
      kind: '',
      url: '',
      template_slug: '',
      booking_form_usage: ''
    };

    /**
     * Return one translated string with a safe fallback.
     *
     * @param {string} key      Translation key.
     * @param {string} fallback Fallback copy.
     * @return {string} Translation or fallback.
     */
    function get_message(key, fallback) {
      return config.i18n && config.i18n[key] ? config.i18n[key] : fallback;
    }

    /**
     * Return normalized text for case-insensitive catalog searching.
     *
     * @param {string} text Source text.
     * @return {string} Lowercase normalized text.
     */
    function normalize_search_text(text) {
      return String(text || '').toLocaleLowerCase();
    }

    /**
     * Return one template card's searchable text.
     *
     * @param {HTMLElement} card Template card.
     * @return {string} Normalized searchable text.
     */
    function get_card_search_text(card) {
      var title = card.querySelector('[data-wpbc-template-title]');
      var description = card.querySelector('[data-wpbc-template-description]');
      return normalize_search_text((title ? title.textContent : '') + ' ' + (description ? description.textContent : '') + ' ' + (card.dataset.templateSlug || ''));
    }

    /**
     * Install a graceful fallback for one catalog thumbnail.
     *
     * @param {HTMLImageElement|null} image       Image element.
     * @param {HTMLElement|null}      placeholder Placeholder element.
     * @return {void}
     */
    function initialize_image_fallback(image, placeholder) {
      if (!image) {
        return;
      }
      image.addEventListener('load', function () {
        image.hidden = false;
        if (placeholder) {
          placeholder.hidden = true;
        }
      });
      image.addEventListener('error', function () {
        image.hidden = true;
        if (placeholder) {
          placeholder.hidden = false;
        }
      });
      if (image.complete && image.src && 0 === image.naturalWidth) {
        image.hidden = true;
        if (placeholder) {
          placeholder.hidden = false;
        }
      }
    }

    /**
     * Return the currently selected server-rendered card.
     *
     * @return {HTMLElement|null} Selected card or null.
     */
    function get_selected_card() {
      var selected_radio = editor && editor.node.querySelector('[data-wpbc-template-radio]:checked');
      return selected_radio ? selected_radio.closest('[data-wpbc-template-card]') : null;
    }

    /**
     * Return the selected server-rendered Booking Form entry point.
     *
     * @return {string} Stable usage identifier, or an empty string.
     */
    function get_selected_booking_form_usage() {
      var selected_radio = editor && editor.node.querySelector('[data-wpbc-booking-form-usage-radio]:checked');
      return selected_radio ? String(selected_radio.value || '') : '';
    }

    /**
     * Build the informational shortcode for the current allow-listed choices.
     *
     * The value is inserted with textContent only and is never evaluated by the
     * browser. The preview endpoint independently validates both identifiers.
     *
     * @param {string} booking_form_usage Selected entry-point identifier.
     * @return {string} Informational shortcode.
     */
    function get_preview_shortcode(booking_form_usage) {
      return 'appointment_flow' === booking_form_usage ? '[booking_appointment]' : '[booking]';
    }

    /**
     * Synchronize the selected template metadata below the preview.
     *
     * @param {HTMLElement|null} card Selected template card.
     * @return {void}
     */
    function render_preview_metadata(card) {
      var title_node = editor.node.querySelector('[data-wpbc-template-preview-title]');
      var slug_node = editor.node.querySelector('[data-wpbc-template-preview-slug]');
      var shortcode_node = editor.node.querySelector('[data-wpbc-template-preview-shortcode]');
      var description_node = editor.node.querySelector('[data-wpbc-template-preview-description]');
      var title = card && card.querySelector('[data-wpbc-template-title]');
      var description = card && card.querySelector('[data-wpbc-template-description]');
      var template_slug = card ? String(card.dataset.templateSlug || '') : '';
      if (title_node) {
        title_node.textContent = title ? title.textContent.trim() : '';
      }
      if (slug_node) {
        slug_node.textContent = template_slug;
      }
      if (shortcode_node) {
        shortcode_node.textContent = get_preview_shortcode(get_selected_booking_form_usage());
      }
      if (description_node) {
        description_node.textContent = description ? description.textContent.trim() : '';
      }
    }

    /**
     * Update preview loading, error, and action states.
     *
     * @param {boolean} is_loading Whether a preview request/frame is loading.
     * @param {string}  error_text Plain error text, or an empty string.
     * @return {void}
     */
    function set_preview_state(is_loading, error_text) {
      var has_error = '' !== String(error_text || '');
      editor.preview_stage.classList.toggle('is-loading', is_loading);
      editor.preview_stage.classList.toggle('has-error', has_error);
      editor.preview_stage.setAttribute('aria-busy', is_loading ? 'true' : 'false');
      editor.preview_loader.hidden = !is_loading;
      editor.preview_error.hidden = !has_error;
      if (has_error) {
        editor.preview_error.querySelector('span').textContent = error_text;
      }
      editor.refresh_button.disabled = is_loading || !get_selected_card();
      editor.open_button.disabled = is_loading || open_request_is_loading || !get_selected_card();
    }

    /**
     * Clear the iframe's association with an earlier template preview.
     *
     * The attribute is diagnostic as well as functional: it lets browser tests
     * verify that the visible iframe belongs to the currently selected card.
     *
     * @return {void}
     */
    function clear_preview_frame() {
      editor.preview_frame.removeAttribute('data-preview-template-slug');
      editor.preview_frame.removeAttribute('data-preview-booking-form-usage');
      editor.preview_frame.src = 'about:blank';
      editor.preview_frame.hidden = true;
    }

    /**
     * Record one successfully rendered preview.
     *
     * @param {string} preview_kind      Inline or URL preview kind.
     * @param {string} preview_url       Signed URL when applicable.
     * @param {string} template_slug     Validated template slug.
     * @param {string} booking_form_usage Validated usage identifier.
     * @return {void}
     */
    function set_current_preview(preview_kind, preview_url, template_slug, booking_form_usage) {
      current_preview = {
        kind: preview_kind,
        url: preview_url,
        template_slug: template_slug,
        booking_form_usage: booking_form_usage
      };
    }

    /**
     * Determine whether the current preview matches the selected controls.
     *
     * @param {string} template_slug      Selected template slug.
     * @param {string} booking_form_usage Selected usage identifier.
     * @return {boolean} Whether the active preview belongs to the selection.
     */
    function current_preview_matches(template_slug, booking_form_usage) {
      return current_preview.template_slug === template_slug && current_preview.booking_form_usage === booking_form_usage;
    }

    /**
     * Read a normalized WordPress AJAX error message.
     *
     * @param {Object|null} response Parsed JSON response.
     * @return {string} Plain error message.
     */
    function get_ajax_error_message(response) {
      if (response && response.data && response.data.message) {
        return String(response.data.message);
      }
      return get_message('preview_error', 'The interactive preview could not be loaded. Try refreshing it.');
    }

    /**
     * Return a safe message when an upstream server returns non-JSON content.
     *
     * WordPress JSON errors are parsed normally and retain their server-authored
     * message. This path handles web-server, proxy, and firewall responses that
     * never reached the authenticated WordPress AJAX endpoint.
     *
     * @param {Response|null} response Fetch response.
     * @return {string} Localized plain-text error message.
     */
    function get_preview_transport_error_message(response) {
      if (response && 403 === Number(response.status)) {
        if (true === config.is_live_demo) {
          return get_message('preview_live_demo_blocked_error', 'The interactive Booking Form preview is limited in this Booking Calendar live demo. This limitation does not apply to Booking Calendar installed on your own site.');
        }
        return get_message('preview_blocked_error', 'The web server blocked the preview request before Booking Calendar could process it. Ask your server administrator to allow this WordPress AJAX request, then try again.');
      }
      return get_message('preview_response_error', 'The preview server returned an unexpected response instead of JSON. Try again or contact your server administrator.');
    }

    /**
     * Parse one preview response without exposing browser JSON parser errors.
     *
     * @param {Response} response Fetch response.
     * @return {Promise<Object>} Parsed WordPress AJAX response.
     */
    function parse_preview_response(response) {
      return response.text().then(function (response_text) {
        try {
          return JSON.parse(response_text);
        } catch (parse_error) {
          throw new Error(get_preview_transport_error_message(response));
        }
      });
    }

    /**
     * Build the authenticated request body for one server-owned preview.
     *
     * @param {string} template_slug      Selected template slug.
     * @param {string} booking_form_usage Selected usage identifier.
     * @param {string} preview_target     Embedded or window target.
     * @return {string} URL-encoded request body.
     */
    function build_preview_request_body(template_slug, booking_form_usage, preview_target) {
      var request_fields = {
        action: config.preview_action,
        nonce: config.nonce || '',
        template_slug: template_slug,
        booking_form_usage: booking_form_usage,
        preview_target: preview_target
      };
      return Object.keys(request_fields).map(function (field_name) {
        return window.encodeURIComponent(field_name) + '=' + window.encodeURIComponent(String(request_fields[field_name]));
      }).join('&');
    }

    /**
     * Confirm that a response belongs to the current server-validated selection.
     *
     * @param {Object} response            WordPress AJAX response.
     * @param {string} template_slug       Requested template slug.
     * @param {string} booking_form_usage  Requested usage identifier.
     * @param {string} preview_target      Requested preview target.
     * @return {Object} Validated response data.
     * @throws {Error} When response ownership or shape is invalid.
     */
    function validate_preview_response(response, template_slug, booking_form_usage, preview_target) {
      var response_data;
      var selected_card = get_selected_card();
      if (!response || !response.success || !response.data) {
        throw new Error(get_ajax_error_message(response));
      }
      response_data = response.data;
      if (String(response_data.template_slug || '') !== template_slug || String(response_data.booking_form_usage || '') !== booking_form_usage || String(response_data.preview_target || '') !== preview_target || !selected_card || String(selected_card.dataset.templateSlug || '') !== template_slug || get_selected_booking_form_usage() !== booking_form_usage) {
        throw new Error(get_message('preview_error', 'The interactive preview could not be loaded. Try refreshing it.'));
      }
      return response_data;
    }

    /**
     * Request and render the selected allow-listed template.
     *
     * @param {HTMLElement|null} card Selected template card.
     * @return {void}
     */
    function load_preview(card) {
      var template_slug = card ? String(card.dataset.templateSlug || '') : '';
      var booking_form_usage = get_selected_booking_form_usage();
      var request_sequence;
      var request_body;
      if (!template_slug || !booking_form_usage || !config.ajax_url || !config.preview_action) {
        set_preview_state(false, get_message('preview_error', 'The interactive preview could not be loaded. Try refreshing it.'));
        return;
      }
      if (preview_abort_controller && 'function' === typeof preview_abort_controller.abort) {
        preview_abort_controller.abort();
      }
      preview_abort_controller = 'function' === typeof window.AbortController ? new window.AbortController() : null;
      request_sequence = ++preview_request_sequence;
      request_body = build_preview_request_body(template_slug, booking_form_usage, 'embedded');
      set_preview_state(true, '');
      window.fetch(config.ajax_url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
        },
        body: request_body,
        credentials: 'same-origin',
        signal: preview_abort_controller ? preview_abort_controller.signal : undefined
      }).then(function (response) {
        return parse_preview_response(response);
      }).then(function (response) {
        var response_data;
        var preview_kind;
        var preview_url;
        if (request_sequence !== preview_request_sequence) {
          return;
        }
        response_data = validate_preview_response(response, template_slug, booking_form_usage, 'embedded');
        preview_kind = String(response_data.preview_kind || '');
        if ('inline' === preview_kind && response_data.html && response_data.bootstrap && 'object' === typeof response_data.bootstrap && inline_preview_renderer) {
          return inline_preview_renderer.replace(String(response_data.html), response_data.bootstrap, function () {
            return request_sequence === preview_request_sequence;
          }).then(function () {
            if (request_sequence !== preview_request_sequence) {
              return;
            }
            clear_preview_frame();
            editor.inline_preview.hidden = false;
            set_current_preview('inline', '', template_slug, booking_form_usage);
            set_preview_state(false, '');
          });
        }
        if ('url' !== preview_kind || !response_data.preview_url) {
          throw new Error(get_message('preview_error', 'The interactive preview could not be loaded. Try refreshing it.'));
        }
        preview_url = String(response_data.preview_url);
        editor.preview_frame.onload = function () {
          if (request_sequence !== preview_request_sequence) {
            return;
          }
          editor.inline_preview.hidden = true;
          editor.preview_frame.hidden = false;
          set_current_preview('url', preview_url, template_slug, booking_form_usage);
          set_preview_state(false, '');
        };
        editor.preview_frame.setAttribute('data-preview-template-slug', template_slug);
        editor.preview_frame.setAttribute('data-preview-booking-form-usage', booking_form_usage);
        editor.preview_frame.src = preview_url;
      }).catch(function (error) {
        if (error && 'AbortError' === error.name) {
          return;
        }
        if (request_sequence !== preview_request_sequence) {
          return;
        }
        set_preview_state(false, error && error.message ? error.message : get_message('preview_error', 'The interactive preview could not be loaded. Try refreshing it.'));
      });
    }

    /**
     * Open the full-site preview, lazily creating a signed session when needed.
     *
     * @param {HTMLElement|null} card Selected template card.
     * @return {void}
     */
    function open_preview(card) {
      var template_slug = card ? String(card.dataset.templateSlug || '') : '';
      var booking_form_usage = get_selected_booking_form_usage();
      var preview_window;
      var request_sequence;
      if (!template_slug || !booking_form_usage || editor.open_button.disabled) {
        return;
      }
      if (current_preview_matches(template_slug, booking_form_usage) && current_preview.url) {
        window.open(current_preview.url, '_blank', 'noopener,noreferrer');
        return;
      }
      preview_window = window.open('', '_blank');
      if (!preview_window) {
        set_preview_state(false, get_message('preview_open_error', 'The full-site preview could not be opened. Try again.'));
        return;
      }
      preview_window.opener = null;
      if (open_abort_controller && 'function' === typeof open_abort_controller.abort) {
        open_abort_controller.abort();
      }
      open_abort_controller = 'function' === typeof window.AbortController ? new window.AbortController() : null;
      request_sequence = ++open_request_sequence;
      open_request_is_loading = true;
      editor.open_button.disabled = true;
      window.fetch(config.ajax_url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
        },
        body: build_preview_request_body(template_slug, booking_form_usage, 'window'),
        credentials: 'same-origin',
        signal: open_abort_controller ? open_abort_controller.signal : undefined
      }).then(function (response) {
        return parse_preview_response(response);
      }).then(function (response) {
        var response_data;
        if (request_sequence !== open_request_sequence) {
          return;
        }
        response_data = validate_preview_response(response, template_slug, booking_form_usage, 'window');
        if ('url' !== String(response_data.preview_kind || '') || !response_data.preview_url) {
          throw new Error(get_message('preview_open_error', 'The full-site preview could not be opened. Try again.'));
        }
        preview_window.location.replace(String(response_data.preview_url));
      }).catch(function (error) {
        if (error && 'AbortError' === error.name) {
          preview_window.close();
          return;
        }
        if (request_sequence !== open_request_sequence) {
          return;
        }
        preview_window.close();
        set_preview_state(false, error && error.message ? error.message : get_message('preview_open_error', 'The full-site preview could not be opened. Try again.'));
      }).then(function () {
        if (request_sequence === open_request_sequence) {
          open_request_is_loading = false;
          editor.open_button.disabled = !get_selected_card();
        }
      });
    }

    /**
     * Select one card and load its real preview.
     *
     * @param {HTMLElement} card         Template card.
     * @param {boolean}     load_selected Whether to request a replacement preview.
     * @return {void}
     */
    function select_card(card, load_selected) {
      var radio = card && card.querySelector('[data-wpbc-template-radio]');
      if (!radio) {
        return;
      }
      radio.checked = true;
      editor.cards.forEach(function (template_card) {
        template_card.classList.toggle('is-selected', template_card === card);
      });
      shell_api.clear_field_error('booking_form_template');
      render_preview_metadata(card);
      if (false !== load_selected) {
        load_preview(card);
      }
    }

    /**
     * Return cards matching the current search and category.
     *
     * @return {HTMLElement[]} Matching cards in server order.
     */
    function get_matching_cards() {
      var query = normalize_search_text(editor.search ? editor.search.value.trim() : '');
      return editor.cards.filter(function (card) {
        var matches_filter = 'all' === editor.active_filter || 'recommended' === editor.active_filter && 'true' === card.dataset.templateRecommended || editor.active_filter === card.dataset.templateCategory;
        var matches_search = !query || -1 !== get_card_search_text(card).indexOf(query);
        return matches_filter && matches_search;
      });
    }

    /**
     * Render every card matching the current search and category.
     *
     * @return {void}
     */
    function render_catalog() {
      var matching_cards = get_matching_cards();
      editor.cards.forEach(function (card) {
        card.hidden = true;
      });
      matching_cards.forEach(function (card) {
        card.hidden = false;
      });
      editor.empty.hidden = 0 !== matching_cards.length;
      editor.empty.textContent = get_message('no_matching_templates', 'No templates match this search and filter.');
      editor.grid.scrollLeft = 0;
    }

    /**
     * Activate one category tab and refresh the visible cards.
     *
     * @param {HTMLElement} tab Filter tab button.
     * @return {void}
     */
    function activate_filter(tab) {
      if (!tab) {
        return;
      }
      editor.active_filter = tab.dataset.wpbcTemplateFilter || 'all';
      editor.tabs.forEach(function (filter_tab) {
        var is_active = filter_tab === tab;
        filter_tab.classList.toggle('is-active', is_active);
        filter_tab.setAttribute('aria-selected', is_active ? 'true' : 'false');
        filter_tab.tabIndex = is_active ? 0 : -1;
      });
      editor.grid.setAttribute('aria-labelledby', tab.id);
      render_catalog();
    }

    /**
     * Return the filter tab with the requested stable ID.
     *
     * @param {string} filter_id Stable filter ID.
     * @return {HTMLElement|null} Matching tab, or null.
     */
    function get_filter_tab(filter_id) {
      return editor.tabs.find(function (tab) {
        return filter_id === tab.dataset.wpbcTemplateFilter;
      }) || null;
    }

    /**
     * Apply text-search changes immediately.
     *
     * Text search intentionally switches to All templates. Otherwise a valid
     * match such as a Full Days or contact template can remain hidden merely
     * because the Recommended tab was active before the user started typing.
     *
     * @return {void}
     */
    function handle_search_change() {
      var query = normalize_search_text(editor.search ? editor.search.value.trim() : '');
      var all_templates_tab = get_filter_tab('all');
      if (query && all_templates_tab && 'all' !== editor.active_filter) {
        activate_filter(all_templates_tab);
        return;
      }
      render_catalog();
    }

    /**
     * Handle selector and preview actions.
     *
     * @param {MouseEvent} event Click event.
     * @return {void}
     */
    function handle_click(event) {
      var filter = event.target.closest('[data-wpbc-template-filter]');
      var refresh_button = event.target.closest('[data-wpbc-template-refresh]');
      var open_link = event.target.closest('[data-wpbc-template-open-preview]');
      var template_card = event.target.closest('[data-wpbc-template-card]');
      var template_radio;
      if (filter) {
        activate_filter(filter);
        return;
      }
      if (refresh_button) {
        load_preview(get_selected_card());
        return;
      }
      if (open_link) {
        open_preview(get_selected_card());
        return;
      }
      if (template_card) {
        template_radio = template_card.querySelector('[data-wpbc-template-radio]');
        if (template_radio && !template_radio.checked) {
          event.preventDefault();
          select_card(template_card, true);
          template_radio.focus();
        }
        return;
      }
    }

    /**
     * Synchronize one entry-point choice with its native radio and card.
     *
     * @param {string} booking_form_usage Stable entry-point identifier.
     * @return {HTMLInputElement|null} Matching radio control, or null.
     */
    function select_booking_form_usage(booking_form_usage) {
      var selected_radio = null;
      Array.prototype.slice.call(editor.node.querySelectorAll('[data-wpbc-booking-form-usage-radio]')).forEach(function (usage_radio) {
        var is_selected = booking_form_usage === String(usage_radio.value || '');
        usage_radio.checked = is_selected;
        usage_radio.closest('.wpbc_setup_wizard__booking-form-usage-option').classList.toggle('is-selected', is_selected);
        if (is_selected) {
          selected_radio = usage_radio;
        }
      });
      return selected_radio;
    }

    /**
     * Commit an explained entry-point choice and refresh its preview.
     *
     * @param {string} booking_form_usage Stable entry-point identifier.
     * @return {void}
     */
    function commit_booking_form_usage(booking_form_usage) {
      if (!select_booking_form_usage(booking_form_usage)) {
        return;
      }
      confirmed_booking_form_usage = booking_form_usage;
      shell_api.clear_field_error('booking_form_usage');
      render_preview_metadata(get_selected_card());
      load_preview(get_selected_card());
    }

    /**
     * Commit or roll back the provisional Appointment flow radio selection.
     *
     * @param {CustomEvent} event Shared dialog lifecycle event.
     * @return {void}
     */
    function handle_dialog_closed(event) {
      var dialog_state = event.detail || {};
      if (appointment_flow_dialog_action !== dialog_state.action) {
        return;
      }
      if ('confirm' === dialog_state.outcome) {
        commit_booking_form_usage('appointment_flow');
        return;
      }
      select_booking_form_usage(confirmed_booking_form_usage);
    }

    /**
     * Handle native template radio changes.
     *
     * @param {Event} event Change event.
     * @return {void}
     */
    function handle_change(event) {
      if (event.target.matches('[data-wpbc-template-radio]')) {
        select_card(event.target.closest('[data-wpbc-template-card]'), true);
        return;
      }
      if (event.target.matches('[data-wpbc-booking-form-usage-radio]')) {
        select_booking_form_usage(String(event.target.value || ''));
        shell_api.clear_field_error('booking_form_usage');
        if ('appointment_flow' === event.target.value && 'appointment_flow' !== confirmed_booking_form_usage && 'function' === typeof shell_api.open_dialog) {
          if (shell_api.open_dialog(appointment_flow_dialog_action, event.target)) {
            return;
          }
        }
        commit_booking_form_usage(String(event.target.value || ''));
      }
    }

    /**
     * Provide arrow-key navigation across horizontally scrollable filter tabs.
     *
     * @param {KeyboardEvent} event Keyboard event.
     * @return {void}
     */
    function handle_keydown(event) {
      var tab = event.target.closest('[data-wpbc-template-filter]');
      var current_index;
      var target_index;
      if (!tab || -1 === ['ArrowLeft', 'ArrowRight', 'Home', 'End'].indexOf(event.key)) {
        return;
      }
      event.preventDefault();
      current_index = editor.tabs.indexOf(tab);
      target_index = current_index;
      if ('ArrowLeft' === event.key) {
        target_index = (current_index - 1 + editor.tabs.length) % editor.tabs.length;
      } else if ('ArrowRight' === event.key) {
        target_index = (current_index + 1) % editor.tabs.length;
      } else if ('Home' === event.key) {
        target_index = 0;
      } else if ('End' === event.key) {
        target_index = editor.tabs.length - 1;
      }
      activate_filter(editor.tabs[target_index]);
      editor.tabs[target_index].focus();
      editor.tabs[target_index].scrollIntoView({
        block: 'nearest',
        inline: 'nearest'
      });
    }

    /**
     * Initialize this module against shell-provided APIs.
     *
     * @param {Object} registered_shell_api Shared wizard adapter services.
     * @return {void}
     */
    function initialize(registered_shell_api) {
      var editor_node;
      var selected_card;
      var initial_preview_kind;
      var initial_template_slug;
      var initial_booking_form_usage;
      var initial_preview_bootstrap;
      var initial_preview_html;
      shell_api = registered_shell_api;
      root = shell_api.root;
      editor_node = root.querySelector('[data-wpbc-booking-form-template-editor]');
      if (!editor_node) {
        return;
      }
      editor = {
        node: editor_node,
        cards: Array.prototype.slice.call(editor_node.querySelectorAll('[data-wpbc-template-card]')),
        tabs: Array.prototype.slice.call(editor_node.querySelectorAll('[data-wpbc-template-filter]')),
        search: editor_node.querySelector('[data-wpbc-template-search]'),
        grid: editor_node.querySelector('[data-wpbc-template-grid]'),
        empty: editor_node.querySelector('[data-wpbc-template-empty]'),
        preview_stage: editor_node.querySelector('[data-wpbc-template-preview-stage]'),
        preview_loader: editor_node.querySelector('[data-wpbc-template-preview-loader]'),
        preview_error: editor_node.querySelector('[data-wpbc-template-preview-error]'),
        inline_preview: editor_node.querySelector('[data-wpbc-template-inline-preview]'),
        inline_preview_content: editor_node.querySelector('[data-wpbc-template-inline-preview-content]'),
        initial_preview_bootstrap: editor_node.querySelector('[data-wpbc-template-initial-preview-bootstrap]'),
        preview_frame: editor_node.querySelector('[data-wpbc-template-preview-frame]'),
        refresh_button: editor_node.querySelector('[data-wpbc-template-refresh]'),
        open_button: editor_node.querySelector('[data-wpbc-template-open-preview]'),
        active_filter: editor_node.dataset.initialFilter || 'all'
      };
      if (window.wpbc_bfb_inline_preview && 'function' === typeof window.wpbc_bfb_inline_preview.create) {
        inline_preview_renderer = window.wpbc_bfb_inline_preview.create(editor.inline_preview_content);
      }
      editor.node.addEventListener('click', handle_click);
      editor.node.addEventListener('change', handle_change);
      editor.node.addEventListener('keydown', handle_keydown);
      root.addEventListener('wpbc:setup-wizard-dialog-closed', handle_dialog_closed);
      confirmed_booking_form_usage = get_selected_booking_form_usage();
      if (editor.search) {
        editor.search.addEventListener('input', handle_search_change);
        editor.search.addEventListener('search', handle_search_change);
        editor.search.addEventListener('change', handle_search_change);
      }
      editor.cards.forEach(function (card) {
        initialize_image_fallback(card.querySelector('[data-wpbc-template-card-image]'), card.querySelector('[data-wpbc-template-image-placeholder]'));
      });
      render_catalog();
      selected_card = get_selected_card();
      if (selected_card) {
        initial_preview_kind = String(editor.preview_stage.dataset.initialPreviewKind || '');
        initial_template_slug = String(editor.preview_stage.dataset.initialPreviewTemplateSlug || '');
        initial_booking_form_usage = String(editor.preview_stage.dataset.initialPreviewBookingFormUsage || '');
        select_card(selected_card, false);
        if ('inline' === initial_preview_kind && initial_template_slug === String(selected_card.dataset.templateSlug || '') && initial_booking_form_usage === get_selected_booking_form_usage() && inline_preview_renderer && editor.initial_preview_bootstrap) {
          try {
            initial_preview_bootstrap = JSON.parse(editor.initial_preview_bootstrap.textContent || '{}');
          } catch (error) {
            initial_preview_bootstrap = null;
          }
          initial_preview_html = editor.inline_preview_content.innerHTML;
          if (initial_preview_bootstrap && 'object' === typeof initial_preview_bootstrap) {
            inline_preview_renderer.replace(initial_preview_html, initial_preview_bootstrap).done(function () {
              set_current_preview('inline', '', initial_template_slug, initial_booking_form_usage);
              set_preview_state(false, '');
            }).fail(function () {
              load_preview(selected_card);
            });
          } else {
            load_preview(selected_card);
          }
        } else {
          load_preview(selected_card);
        }
      } else {
        render_preview_metadata(null);
        set_preview_state(false, get_message('preview_error', 'The interactive preview could not be loaded. Try refreshing it.'));
      }
      if ('function' === typeof shell_api.open_dialog) {
        window.setTimeout(function () {
          shell_api.open_dialog(template_guidance_dialog_action, editor.search || editor.node);
        }, 0);
      }
    }

    /**
     * Validate the required selection before forward navigation.
     *
     * @return {HTMLElement|null} First invalid radio, or null.
     */
    function validate() {
      var selected_radio = editor && editor.node.querySelector('[data-wpbc-template-radio]:checked');
      var selected_usage = editor && editor.node.querySelector('[data-wpbc-booking-form-usage-radio]:checked');
      if (!selected_radio) {
        return shell_api.set_field_error('booking_form_template', get_message('template_required', 'Choose a booking form template.'));
      }
      if (!selected_usage) {
        return shell_api.set_field_error('booking_form_usage', get_message('usage_required', 'Choose how customers will open the booking form.'));
      }
      return null;
    }
    return {
      initialize: initialize,
      sync: function () {},
      validate: validate
    };
  }
  window.wpbc_setup_editor_modules = window.wpbc_setup_editor_modules || {};
  window.wpbc_setup_editor_modules.booking_form_template = {
    create: create_booking_form_template_adapter
  };
  if (window.wpbc_setup_wizard_api && 'function' === typeof window.wpbc_setup_wizard_api.register_step_adapter) {
    window.wpbc_setup_wizard_api.register_step_adapter(create_booking_form_template_adapter(module_config));
  }
})(window, document);
//# sourceMappingURL=data:application/json;charset=utf8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiaW5jbHVkZXMvcGFnZS1zZXR1cC13aXphcmQvc3RlcC1ib29raW5nLWZvcm0tdGVtcGxhdGUvX291dC9zdGVwLWJvb2tpbmctZm9ybS10ZW1wbGF0ZS5qcyIsIm5hbWVzIjpbIndpbmRvdyIsImRvY3VtZW50IiwibW9kdWxlX2NvbmZpZyIsIndwYmNfc2V0dXBfd2l6YXJkX2Jvb2tpbmdfZm9ybV90ZW1wbGF0ZSIsImkxOG4iLCJjcmVhdGVfYm9va2luZ19mb3JtX3RlbXBsYXRlX2FkYXB0ZXIiLCJjb25maWciLCJhcHBvaW50bWVudF9mbG93X2RpYWxvZ19hY3Rpb24iLCJ0ZW1wbGF0ZV9ndWlkYW5jZV9kaWFsb2dfYWN0aW9uIiwic2hlbGxfYXBpIiwicm9vdCIsImVkaXRvciIsImNvbmZpcm1lZF9ib29raW5nX2Zvcm1fdXNhZ2UiLCJwcmV2aWV3X3JlcXVlc3Rfc2VxdWVuY2UiLCJwcmV2aWV3X2Fib3J0X2NvbnRyb2xsZXIiLCJvcGVuX3JlcXVlc3Rfc2VxdWVuY2UiLCJvcGVuX2Fib3J0X2NvbnRyb2xsZXIiLCJvcGVuX3JlcXVlc3RfaXNfbG9hZGluZyIsImlubGluZV9wcmV2aWV3X3JlbmRlcmVyIiwiY3VycmVudF9wcmV2aWV3Iiwia2luZCIsInVybCIsInRlbXBsYXRlX3NsdWciLCJib29raW5nX2Zvcm1fdXNhZ2UiLCJnZXRfbWVzc2FnZSIsImtleSIsImZhbGxiYWNrIiwibm9ybWFsaXplX3NlYXJjaF90ZXh0IiwidGV4dCIsIlN0cmluZyIsInRvTG9jYWxlTG93ZXJDYXNlIiwiZ2V0X2NhcmRfc2VhcmNoX3RleHQiLCJjYXJkIiwidGl0bGUiLCJxdWVyeVNlbGVjdG9yIiwiZGVzY3JpcHRpb24iLCJ0ZXh0Q29udGVudCIsImRhdGFzZXQiLCJ0ZW1wbGF0ZVNsdWciLCJpbml0aWFsaXplX2ltYWdlX2ZhbGxiYWNrIiwiaW1hZ2UiLCJwbGFjZWhvbGRlciIsImFkZEV2ZW50TGlzdGVuZXIiLCJoaWRkZW4iLCJjb21wbGV0ZSIsInNyYyIsIm5hdHVyYWxXaWR0aCIsImdldF9zZWxlY3RlZF9jYXJkIiwic2VsZWN0ZWRfcmFkaW8iLCJub2RlIiwiY2xvc2VzdCIsImdldF9zZWxlY3RlZF9ib29raW5nX2Zvcm1fdXNhZ2UiLCJ2YWx1ZSIsImdldF9wcmV2aWV3X3Nob3J0Y29kZSIsInJlbmRlcl9wcmV2aWV3X21ldGFkYXRhIiwidGl0bGVfbm9kZSIsInNsdWdfbm9kZSIsInNob3J0Y29kZV9ub2RlIiwiZGVzY3JpcHRpb25fbm9kZSIsInRyaW0iLCJzZXRfcHJldmlld19zdGF0ZSIsImlzX2xvYWRpbmciLCJlcnJvcl90ZXh0IiwiaGFzX2Vycm9yIiwicHJldmlld19zdGFnZSIsImNsYXNzTGlzdCIsInRvZ2dsZSIsInNldEF0dHJpYnV0ZSIsInByZXZpZXdfbG9hZGVyIiwicHJldmlld19lcnJvciIsInJlZnJlc2hfYnV0dG9uIiwiZGlzYWJsZWQiLCJvcGVuX2J1dHRvbiIsImNsZWFyX3ByZXZpZXdfZnJhbWUiLCJwcmV2aWV3X2ZyYW1lIiwicmVtb3ZlQXR0cmlidXRlIiwic2V0X2N1cnJlbnRfcHJldmlldyIsInByZXZpZXdfa2luZCIsInByZXZpZXdfdXJsIiwiY3VycmVudF9wcmV2aWV3X21hdGNoZXMiLCJnZXRfYWpheF9lcnJvcl9tZXNzYWdlIiwicmVzcG9uc2UiLCJkYXRhIiwibWVzc2FnZSIsImdldF9wcmV2aWV3X3RyYW5zcG9ydF9lcnJvcl9tZXNzYWdlIiwiTnVtYmVyIiwic3RhdHVzIiwiaXNfbGl2ZV9kZW1vIiwicGFyc2VfcHJldmlld19yZXNwb25zZSIsInRoZW4iLCJyZXNwb25zZV90ZXh0IiwiSlNPTiIsInBhcnNlIiwicGFyc2VfZXJyb3IiLCJFcnJvciIsImJ1aWxkX3ByZXZpZXdfcmVxdWVzdF9ib2R5IiwicHJldmlld190YXJnZXQiLCJyZXF1ZXN0X2ZpZWxkcyIsImFjdGlvbiIsInByZXZpZXdfYWN0aW9uIiwibm9uY2UiLCJPYmplY3QiLCJrZXlzIiwibWFwIiwiZmllbGRfbmFtZSIsImVuY29kZVVSSUNvbXBvbmVudCIsImpvaW4iLCJ2YWxpZGF0ZV9wcmV2aWV3X3Jlc3BvbnNlIiwicmVzcG9uc2VfZGF0YSIsInNlbGVjdGVkX2NhcmQiLCJzdWNjZXNzIiwibG9hZF9wcmV2aWV3IiwicmVxdWVzdF9zZXF1ZW5jZSIsInJlcXVlc3RfYm9keSIsImFqYXhfdXJsIiwiYWJvcnQiLCJBYm9ydENvbnRyb2xsZXIiLCJmZXRjaCIsIm1ldGhvZCIsImhlYWRlcnMiLCJib2R5IiwiY3JlZGVudGlhbHMiLCJzaWduYWwiLCJ1bmRlZmluZWQiLCJodG1sIiwiYm9vdHN0cmFwIiwicmVwbGFjZSIsImlubGluZV9wcmV2aWV3Iiwib25sb2FkIiwiY2F0Y2giLCJlcnJvciIsIm5hbWUiLCJvcGVuX3ByZXZpZXciLCJwcmV2aWV3X3dpbmRvdyIsIm9wZW4iLCJvcGVuZXIiLCJsb2NhdGlvbiIsImNsb3NlIiwic2VsZWN0X2NhcmQiLCJsb2FkX3NlbGVjdGVkIiwicmFkaW8iLCJjaGVja2VkIiwiY2FyZHMiLCJmb3JFYWNoIiwidGVtcGxhdGVfY2FyZCIsImNsZWFyX2ZpZWxkX2Vycm9yIiwiZ2V0X21hdGNoaW5nX2NhcmRzIiwicXVlcnkiLCJzZWFyY2giLCJmaWx0ZXIiLCJtYXRjaGVzX2ZpbHRlciIsImFjdGl2ZV9maWx0ZXIiLCJ0ZW1wbGF0ZVJlY29tbWVuZGVkIiwidGVtcGxhdGVDYXRlZ29yeSIsIm1hdGNoZXNfc2VhcmNoIiwiaW5kZXhPZiIsInJlbmRlcl9jYXRhbG9nIiwibWF0Y2hpbmdfY2FyZHMiLCJlbXB0eSIsImxlbmd0aCIsImdyaWQiLCJzY3JvbGxMZWZ0IiwiYWN0aXZhdGVfZmlsdGVyIiwidGFiIiwid3BiY1RlbXBsYXRlRmlsdGVyIiwidGFicyIsImZpbHRlcl90YWIiLCJpc19hY3RpdmUiLCJ0YWJJbmRleCIsImlkIiwiZ2V0X2ZpbHRlcl90YWIiLCJmaWx0ZXJfaWQiLCJmaW5kIiwiaGFuZGxlX3NlYXJjaF9jaGFuZ2UiLCJhbGxfdGVtcGxhdGVzX3RhYiIsImhhbmRsZV9jbGljayIsImV2ZW50IiwidGFyZ2V0Iiwib3Blbl9saW5rIiwidGVtcGxhdGVfcmFkaW8iLCJwcmV2ZW50RGVmYXVsdCIsImZvY3VzIiwic2VsZWN0X2Jvb2tpbmdfZm9ybV91c2FnZSIsIkFycmF5IiwicHJvdG90eXBlIiwic2xpY2UiLCJjYWxsIiwicXVlcnlTZWxlY3RvckFsbCIsInVzYWdlX3JhZGlvIiwiaXNfc2VsZWN0ZWQiLCJjb21taXRfYm9va2luZ19mb3JtX3VzYWdlIiwiaGFuZGxlX2RpYWxvZ19jbG9zZWQiLCJkaWFsb2dfc3RhdGUiLCJkZXRhaWwiLCJvdXRjb21lIiwiaGFuZGxlX2NoYW5nZSIsIm1hdGNoZXMiLCJvcGVuX2RpYWxvZyIsImhhbmRsZV9rZXlkb3duIiwiY3VycmVudF9pbmRleCIsInRhcmdldF9pbmRleCIsInNjcm9sbEludG9WaWV3IiwiYmxvY2siLCJpbmxpbmUiLCJpbml0aWFsaXplIiwicmVnaXN0ZXJlZF9zaGVsbF9hcGkiLCJlZGl0b3Jfbm9kZSIsImluaXRpYWxfcHJldmlld19raW5kIiwiaW5pdGlhbF90ZW1wbGF0ZV9zbHVnIiwiaW5pdGlhbF9ib29raW5nX2Zvcm1fdXNhZ2UiLCJpbml0aWFsX3ByZXZpZXdfYm9vdHN0cmFwIiwiaW5pdGlhbF9wcmV2aWV3X2h0bWwiLCJpbmxpbmVfcHJldmlld19jb250ZW50IiwiaW5pdGlhbEZpbHRlciIsIndwYmNfYmZiX2lubGluZV9wcmV2aWV3IiwiY3JlYXRlIiwiaW5pdGlhbFByZXZpZXdLaW5kIiwiaW5pdGlhbFByZXZpZXdUZW1wbGF0ZVNsdWciLCJpbml0aWFsUHJldmlld0Jvb2tpbmdGb3JtVXNhZ2UiLCJpbm5lckhUTUwiLCJkb25lIiwiZmFpbCIsInNldFRpbWVvdXQiLCJ2YWxpZGF0ZSIsInNlbGVjdGVkX3VzYWdlIiwic2V0X2ZpZWxkX2Vycm9yIiwic3luYyIsIndwYmNfc2V0dXBfZWRpdG9yX21vZHVsZXMiLCJib29raW5nX2Zvcm1fdGVtcGxhdGUiLCJ3cGJjX3NldHVwX3dpemFyZF9hcGkiLCJyZWdpc3Rlcl9zdGVwX2FkYXB0ZXIiXSwic291cmNlcyI6WyJpbmNsdWRlcy9wYWdlLXNldHVwLXdpemFyZC9zdGVwLWJvb2tpbmctZm9ybS10ZW1wbGF0ZS9fc3JjL3N0ZXAtYm9va2luZy1mb3JtLXRlbXBsYXRlLmpzIl0sInNvdXJjZXNDb250ZW50IjpbIi8qKlxuICogUHJvdmlkZSB0aGUgcmV1c2FibGUgQm9va2luZyBGb3JtIHRlbXBsYXRlIGNhdGFsb2cgYW5kIGludGVyYWN0aXZlIHByZXZpZXcuXG4gKlxuICogQ2F0YWxvZyBjYXJkcyByZW1haW4gc2VydmVyLXJlbmRlcmVkLiBEaXJlY3QgdGVtcGxhdGVzIHVzZSByZXF1ZXN0LWxvY2FsXG4gKiBpbmxpbmUgSFRNTCwgd2hpbGUgQXBwb2ludG1lbnQgRmxvdyBhbmQgZnVsbC1zaXRlIHByZXZpZXdzIHVzZSBzaWduZWQgVVJMcy5cbiAqIFRoZSBicm93c2VyIG5ldmVyIHJlY2VpdmVzIHJhdyB0ZW1wbGF0ZSBkZWZpbml0aW9ucyBhbmQgbmV2ZXIgYXBwbGllcyBhIGZvcm0uXG4gKlxuICogQHBhY2thZ2UgQm9va2luZyBDYWxlbmRhclxuICovXG4oIGZ1bmN0aW9uICggd2luZG93LCBkb2N1bWVudCApIHtcblx0J3VzZSBzdHJpY3QnO1xuXG5cdHZhciBtb2R1bGVfY29uZmlnID0gd2luZG93LndwYmNfc2V0dXBfd2l6YXJkX2Jvb2tpbmdfZm9ybV90ZW1wbGF0ZSB8fCB7IGkxOG46IHt9IH07XG5cblx0LyoqXG5cdCAqIENyZWF0ZSBvbmUgaXNvbGF0ZWQgQm9va2luZyBGb3JtIHRlbXBsYXRlIHNlbGVjdG9yIGFkYXB0ZXIuXG5cdCAqXG5cdCAqIEBwYXJhbSB7T2JqZWN0fSBjb25maWcgTW9kdWxlIGVuZHBvaW50IGFuZCB0cmFuc2xhdGlvbiBzZXR0aW5ncy5cblx0ICogQHJldHVybiB7T2JqZWN0fSBTdGVwIGFkYXB0ZXIgYWNjZXB0ZWQgYnkgdGhlIHNoYXJlZCB3aXphcmQgc2hlbGwuXG5cdCAqL1xuXHRmdW5jdGlvbiBjcmVhdGVfYm9va2luZ19mb3JtX3RlbXBsYXRlX2FkYXB0ZXIoIGNvbmZpZyApIHtcblx0XHR2YXIgYXBwb2ludG1lbnRfZmxvd19kaWFsb2dfYWN0aW9uID0gJ2Jvb2tpbmctZm9ybS1hcHBvaW50bWVudC1mbG93Jztcblx0XHR2YXIgdGVtcGxhdGVfZ3VpZGFuY2VfZGlhbG9nX2FjdGlvbiA9ICdib29raW5nLWZvcm0tdGVtcGxhdGUtZ3VpZGFuY2UnO1xuXHRcdHZhciBzaGVsbF9hcGkgPSBudWxsO1xuXHRcdHZhciByb290ID0gbnVsbDtcblx0XHR2YXIgZWRpdG9yID0gbnVsbDtcblx0XHR2YXIgY29uZmlybWVkX2Jvb2tpbmdfZm9ybV91c2FnZSA9ICcnO1xuXHRcdHZhciBwcmV2aWV3X3JlcXVlc3Rfc2VxdWVuY2UgPSAwO1xuXHRcdHZhciBwcmV2aWV3X2Fib3J0X2NvbnRyb2xsZXIgPSBudWxsO1xuXHRcdHZhciBvcGVuX3JlcXVlc3Rfc2VxdWVuY2UgPSAwO1xuXHRcdHZhciBvcGVuX2Fib3J0X2NvbnRyb2xsZXIgPSBudWxsO1xuXHRcdHZhciBvcGVuX3JlcXVlc3RfaXNfbG9hZGluZyA9IGZhbHNlO1xuXHRcdHZhciBpbmxpbmVfcHJldmlld19yZW5kZXJlciA9IG51bGw7XG5cdFx0dmFyIGN1cnJlbnRfcHJldmlldyA9IHtcblx0XHRcdGtpbmQ6ICcnLFxuXHRcdFx0dXJsOiAnJyxcblx0XHRcdHRlbXBsYXRlX3NsdWc6ICcnLFxuXHRcdFx0Ym9va2luZ19mb3JtX3VzYWdlOiAnJ1xuXHRcdH07XG5cblx0XHQvKipcblx0XHQgKiBSZXR1cm4gb25lIHRyYW5zbGF0ZWQgc3RyaW5nIHdpdGggYSBzYWZlIGZhbGxiYWNrLlxuXHRcdCAqXG5cdFx0ICogQHBhcmFtIHtzdHJpbmd9IGtleSAgICAgIFRyYW5zbGF0aW9uIGtleS5cblx0XHQgKiBAcGFyYW0ge3N0cmluZ30gZmFsbGJhY2sgRmFsbGJhY2sgY29weS5cblx0XHQgKiBAcmV0dXJuIHtzdHJpbmd9IFRyYW5zbGF0aW9uIG9yIGZhbGxiYWNrLlxuXHRcdCAqL1xuXHRcdGZ1bmN0aW9uIGdldF9tZXNzYWdlKCBrZXksIGZhbGxiYWNrICkge1xuXHRcdFx0cmV0dXJuIGNvbmZpZy5pMThuICYmIGNvbmZpZy5pMThuWyBrZXkgXSA/IGNvbmZpZy5pMThuWyBrZXkgXSA6IGZhbGxiYWNrO1xuXHRcdH1cblxuXHRcdC8qKlxuXHRcdCAqIFJldHVybiBub3JtYWxpemVkIHRleHQgZm9yIGNhc2UtaW5zZW5zaXRpdmUgY2F0YWxvZyBzZWFyY2hpbmcuXG5cdFx0ICpcblx0XHQgKiBAcGFyYW0ge3N0cmluZ30gdGV4dCBTb3VyY2UgdGV4dC5cblx0XHQgKiBAcmV0dXJuIHtzdHJpbmd9IExvd2VyY2FzZSBub3JtYWxpemVkIHRleHQuXG5cdFx0ICovXG5cdFx0ZnVuY3Rpb24gbm9ybWFsaXplX3NlYXJjaF90ZXh0KCB0ZXh0ICkge1xuXHRcdFx0cmV0dXJuIFN0cmluZyggdGV4dCB8fCAnJyApLnRvTG9jYWxlTG93ZXJDYXNlKCk7XG5cdFx0fVxuXG5cdFx0LyoqXG5cdFx0ICogUmV0dXJuIG9uZSB0ZW1wbGF0ZSBjYXJkJ3Mgc2VhcmNoYWJsZSB0ZXh0LlxuXHRcdCAqXG5cdFx0ICogQHBhcmFtIHtIVE1MRWxlbWVudH0gY2FyZCBUZW1wbGF0ZSBjYXJkLlxuXHRcdCAqIEByZXR1cm4ge3N0cmluZ30gTm9ybWFsaXplZCBzZWFyY2hhYmxlIHRleHQuXG5cdFx0ICovXG5cdFx0ZnVuY3Rpb24gZ2V0X2NhcmRfc2VhcmNoX3RleHQoIGNhcmQgKSB7XG5cdFx0XHR2YXIgdGl0bGUgPSBjYXJkLnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLXRlbXBsYXRlLXRpdGxlXScgKTtcblx0XHRcdHZhciBkZXNjcmlwdGlvbiA9IGNhcmQucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtdGVtcGxhdGUtZGVzY3JpcHRpb25dJyApO1xuXG5cdFx0XHRyZXR1cm4gbm9ybWFsaXplX3NlYXJjaF90ZXh0KFxuXHRcdFx0XHQoIHRpdGxlID8gdGl0bGUudGV4dENvbnRlbnQgOiAnJyApICsgJyAnICtcblx0XHRcdFx0KCBkZXNjcmlwdGlvbiA/IGRlc2NyaXB0aW9uLnRleHRDb250ZW50IDogJycgKSArICcgJyArXG5cdFx0XHRcdCggY2FyZC5kYXRhc2V0LnRlbXBsYXRlU2x1ZyB8fCAnJyApXG5cdFx0XHQpO1xuXHRcdH1cblxuXHRcdC8qKlxuXHRcdCAqIEluc3RhbGwgYSBncmFjZWZ1bCBmYWxsYmFjayBmb3Igb25lIGNhdGFsb2cgdGh1bWJuYWlsLlxuXHRcdCAqXG5cdFx0ICogQHBhcmFtIHtIVE1MSW1hZ2VFbGVtZW50fG51bGx9IGltYWdlICAgICAgIEltYWdlIGVsZW1lbnQuXG5cdFx0ICogQHBhcmFtIHtIVE1MRWxlbWVudHxudWxsfSAgICAgIHBsYWNlaG9sZGVyIFBsYWNlaG9sZGVyIGVsZW1lbnQuXG5cdFx0ICogQHJldHVybiB7dm9pZH1cblx0XHQgKi9cblx0XHRmdW5jdGlvbiBpbml0aWFsaXplX2ltYWdlX2ZhbGxiYWNrKCBpbWFnZSwgcGxhY2Vob2xkZXIgKSB7XG5cdFx0XHRpZiAoICEgaW1hZ2UgKSB7XG5cdFx0XHRcdHJldHVybjtcblx0XHRcdH1cblxuXHRcdFx0aW1hZ2UuYWRkRXZlbnRMaXN0ZW5lciggJ2xvYWQnLCBmdW5jdGlvbiAoKSB7XG5cdFx0XHRcdGltYWdlLmhpZGRlbiA9IGZhbHNlO1xuXHRcdFx0XHRpZiAoIHBsYWNlaG9sZGVyICkge1xuXHRcdFx0XHRcdHBsYWNlaG9sZGVyLmhpZGRlbiA9IHRydWU7XG5cdFx0XHRcdH1cblx0XHRcdH0gKTtcblx0XHRcdGltYWdlLmFkZEV2ZW50TGlzdGVuZXIoICdlcnJvcicsIGZ1bmN0aW9uICgpIHtcblx0XHRcdFx0aW1hZ2UuaGlkZGVuID0gdHJ1ZTtcblx0XHRcdFx0aWYgKCBwbGFjZWhvbGRlciApIHtcblx0XHRcdFx0XHRwbGFjZWhvbGRlci5oaWRkZW4gPSBmYWxzZTtcblx0XHRcdFx0fVxuXHRcdFx0fSApO1xuXG5cdFx0XHRpZiAoIGltYWdlLmNvbXBsZXRlICYmIGltYWdlLnNyYyAmJiAwID09PSBpbWFnZS5uYXR1cmFsV2lkdGggKSB7XG5cdFx0XHRcdGltYWdlLmhpZGRlbiA9IHRydWU7XG5cdFx0XHRcdGlmICggcGxhY2Vob2xkZXIgKSB7XG5cdFx0XHRcdFx0cGxhY2Vob2xkZXIuaGlkZGVuID0gZmFsc2U7XG5cdFx0XHRcdH1cblx0XHRcdH1cblx0XHR9XG5cblx0XHQvKipcblx0XHQgKiBSZXR1cm4gdGhlIGN1cnJlbnRseSBzZWxlY3RlZCBzZXJ2ZXItcmVuZGVyZWQgY2FyZC5cblx0XHQgKlxuXHRcdCAqIEByZXR1cm4ge0hUTUxFbGVtZW50fG51bGx9IFNlbGVjdGVkIGNhcmQgb3IgbnVsbC5cblx0XHQgKi9cblx0XHRmdW5jdGlvbiBnZXRfc2VsZWN0ZWRfY2FyZCgpIHtcblx0XHRcdHZhciBzZWxlY3RlZF9yYWRpbyA9IGVkaXRvciAmJiBlZGl0b3Iubm9kZS5xdWVyeVNlbGVjdG9yKCAnW2RhdGEtd3BiYy10ZW1wbGF0ZS1yYWRpb106Y2hlY2tlZCcgKTtcblxuXHRcdFx0cmV0dXJuIHNlbGVjdGVkX3JhZGlvID8gc2VsZWN0ZWRfcmFkaW8uY2xvc2VzdCggJ1tkYXRhLXdwYmMtdGVtcGxhdGUtY2FyZF0nICkgOiBudWxsO1xuXHRcdH1cblxuXHRcdC8qKlxuXHRcdCAqIFJldHVybiB0aGUgc2VsZWN0ZWQgc2VydmVyLXJlbmRlcmVkIEJvb2tpbmcgRm9ybSBlbnRyeSBwb2ludC5cblx0XHQgKlxuXHRcdCAqIEByZXR1cm4ge3N0cmluZ30gU3RhYmxlIHVzYWdlIGlkZW50aWZpZXIsIG9yIGFuIGVtcHR5IHN0cmluZy5cblx0XHQgKi9cblx0XHRmdW5jdGlvbiBnZXRfc2VsZWN0ZWRfYm9va2luZ19mb3JtX3VzYWdlKCkge1xuXHRcdFx0dmFyIHNlbGVjdGVkX3JhZGlvID0gZWRpdG9yICYmIGVkaXRvci5ub2RlLnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLWJvb2tpbmctZm9ybS11c2FnZS1yYWRpb106Y2hlY2tlZCcgKTtcblxuXHRcdFx0cmV0dXJuIHNlbGVjdGVkX3JhZGlvID8gU3RyaW5nKCBzZWxlY3RlZF9yYWRpby52YWx1ZSB8fCAnJyApIDogJyc7XG5cdFx0fVxuXG5cdFx0LyoqXG5cdFx0ICogQnVpbGQgdGhlIGluZm9ybWF0aW9uYWwgc2hvcnRjb2RlIGZvciB0aGUgY3VycmVudCBhbGxvdy1saXN0ZWQgY2hvaWNlcy5cblx0XHQgKlxuXHRcdCAqIFRoZSB2YWx1ZSBpcyBpbnNlcnRlZCB3aXRoIHRleHRDb250ZW50IG9ubHkgYW5kIGlzIG5ldmVyIGV2YWx1YXRlZCBieSB0aGVcblx0XHQgKiBicm93c2VyLiBUaGUgcHJldmlldyBlbmRwb2ludCBpbmRlcGVuZGVudGx5IHZhbGlkYXRlcyBib3RoIGlkZW50aWZpZXJzLlxuXHRcdCAqXG5cdFx0ICogQHBhcmFtIHtzdHJpbmd9IGJvb2tpbmdfZm9ybV91c2FnZSBTZWxlY3RlZCBlbnRyeS1wb2ludCBpZGVudGlmaWVyLlxuXHRcdCAqIEByZXR1cm4ge3N0cmluZ30gSW5mb3JtYXRpb25hbCBzaG9ydGNvZGUuXG5cdFx0ICovXG5cdFx0ZnVuY3Rpb24gZ2V0X3ByZXZpZXdfc2hvcnRjb2RlKCBib29raW5nX2Zvcm1fdXNhZ2UgKSB7XG5cdFx0XHRyZXR1cm4gJ2FwcG9pbnRtZW50X2Zsb3cnID09PSBib29raW5nX2Zvcm1fdXNhZ2Vcblx0XHRcdFx0PyAnW2Jvb2tpbmdfYXBwb2ludG1lbnRdJ1xuXHRcdFx0XHQ6ICdbYm9va2luZ10nO1xuXHRcdH1cblxuXHRcdC8qKlxuXHRcdCAqIFN5bmNocm9uaXplIHRoZSBzZWxlY3RlZCB0ZW1wbGF0ZSBtZXRhZGF0YSBiZWxvdyB0aGUgcHJldmlldy5cblx0XHQgKlxuXHRcdCAqIEBwYXJhbSB7SFRNTEVsZW1lbnR8bnVsbH0gY2FyZCBTZWxlY3RlZCB0ZW1wbGF0ZSBjYXJkLlxuXHRcdCAqIEByZXR1cm4ge3ZvaWR9XG5cdFx0ICovXG5cdFx0ZnVuY3Rpb24gcmVuZGVyX3ByZXZpZXdfbWV0YWRhdGEoIGNhcmQgKSB7XG5cdFx0XHR2YXIgdGl0bGVfbm9kZSA9IGVkaXRvci5ub2RlLnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLXRlbXBsYXRlLXByZXZpZXctdGl0bGVdJyApO1xuXHRcdFx0dmFyIHNsdWdfbm9kZSA9IGVkaXRvci5ub2RlLnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLXRlbXBsYXRlLXByZXZpZXctc2x1Z10nICk7XG5cdFx0XHR2YXIgc2hvcnRjb2RlX25vZGUgPSBlZGl0b3Iubm9kZS5xdWVyeVNlbGVjdG9yKCAnW2RhdGEtd3BiYy10ZW1wbGF0ZS1wcmV2aWV3LXNob3J0Y29kZV0nICk7XG5cdFx0XHR2YXIgZGVzY3JpcHRpb25fbm9kZSA9IGVkaXRvci5ub2RlLnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLXRlbXBsYXRlLXByZXZpZXctZGVzY3JpcHRpb25dJyApO1xuXHRcdFx0dmFyIHRpdGxlID0gY2FyZCAmJiBjYXJkLnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLXRlbXBsYXRlLXRpdGxlXScgKTtcblx0XHRcdHZhciBkZXNjcmlwdGlvbiA9IGNhcmQgJiYgY2FyZC5xdWVyeVNlbGVjdG9yKCAnW2RhdGEtd3BiYy10ZW1wbGF0ZS1kZXNjcmlwdGlvbl0nICk7XG5cdFx0XHR2YXIgdGVtcGxhdGVfc2x1ZyA9IGNhcmQgPyBTdHJpbmcoIGNhcmQuZGF0YXNldC50ZW1wbGF0ZVNsdWcgfHwgJycgKSA6ICcnO1xuXG5cdFx0XHRpZiAoIHRpdGxlX25vZGUgKSB7XG5cdFx0XHRcdHRpdGxlX25vZGUudGV4dENvbnRlbnQgPSB0aXRsZSA/IHRpdGxlLnRleHRDb250ZW50LnRyaW0oKSA6ICcnO1xuXHRcdFx0fVxuXHRcdFx0aWYgKCBzbHVnX25vZGUgKSB7XG5cdFx0XHRcdHNsdWdfbm9kZS50ZXh0Q29udGVudCA9IHRlbXBsYXRlX3NsdWc7XG5cdFx0XHR9XG5cdFx0XHRpZiAoIHNob3J0Y29kZV9ub2RlICkge1xuXHRcdFx0XHRzaG9ydGNvZGVfbm9kZS50ZXh0Q29udGVudCA9IGdldF9wcmV2aWV3X3Nob3J0Y29kZSggZ2V0X3NlbGVjdGVkX2Jvb2tpbmdfZm9ybV91c2FnZSgpICk7XG5cdFx0XHR9XG5cdFx0XHRpZiAoIGRlc2NyaXB0aW9uX25vZGUgKSB7XG5cdFx0XHRcdGRlc2NyaXB0aW9uX25vZGUudGV4dENvbnRlbnQgPSBkZXNjcmlwdGlvbiA/IGRlc2NyaXB0aW9uLnRleHRDb250ZW50LnRyaW0oKSA6ICcnO1xuXHRcdFx0fVxuXHRcdH1cblxuXHRcdC8qKlxuXHRcdCAqIFVwZGF0ZSBwcmV2aWV3IGxvYWRpbmcsIGVycm9yLCBhbmQgYWN0aW9uIHN0YXRlcy5cblx0XHQgKlxuXHRcdCAqIEBwYXJhbSB7Ym9vbGVhbn0gaXNfbG9hZGluZyBXaGV0aGVyIGEgcHJldmlldyByZXF1ZXN0L2ZyYW1lIGlzIGxvYWRpbmcuXG5cdFx0ICogQHBhcmFtIHtzdHJpbmd9ICBlcnJvcl90ZXh0IFBsYWluIGVycm9yIHRleHQsIG9yIGFuIGVtcHR5IHN0cmluZy5cblx0XHQgKiBAcmV0dXJuIHt2b2lkfVxuXHRcdCAqL1xuXHRcdGZ1bmN0aW9uIHNldF9wcmV2aWV3X3N0YXRlKCBpc19sb2FkaW5nLCBlcnJvcl90ZXh0ICkge1xuXHRcdFx0dmFyIGhhc19lcnJvciA9ICcnICE9PSBTdHJpbmcoIGVycm9yX3RleHQgfHwgJycgKTtcblxuXHRcdFx0ZWRpdG9yLnByZXZpZXdfc3RhZ2UuY2xhc3NMaXN0LnRvZ2dsZSggJ2lzLWxvYWRpbmcnLCBpc19sb2FkaW5nICk7XG5cdFx0XHRlZGl0b3IucHJldmlld19zdGFnZS5jbGFzc0xpc3QudG9nZ2xlKCAnaGFzLWVycm9yJywgaGFzX2Vycm9yICk7XG5cdFx0XHRlZGl0b3IucHJldmlld19zdGFnZS5zZXRBdHRyaWJ1dGUoICdhcmlhLWJ1c3knLCBpc19sb2FkaW5nID8gJ3RydWUnIDogJ2ZhbHNlJyApO1xuXHRcdFx0ZWRpdG9yLnByZXZpZXdfbG9hZGVyLmhpZGRlbiA9ICEgaXNfbG9hZGluZztcblx0XHRcdGVkaXRvci5wcmV2aWV3X2Vycm9yLmhpZGRlbiA9ICEgaGFzX2Vycm9yO1xuXHRcdFx0aWYgKCBoYXNfZXJyb3IgKSB7XG5cdFx0XHRcdGVkaXRvci5wcmV2aWV3X2Vycm9yLnF1ZXJ5U2VsZWN0b3IoICdzcGFuJyApLnRleHRDb250ZW50ID0gZXJyb3JfdGV4dDtcblx0XHRcdH1cblx0XHRcdGVkaXRvci5yZWZyZXNoX2J1dHRvbi5kaXNhYmxlZCA9IGlzX2xvYWRpbmcgfHwgISBnZXRfc2VsZWN0ZWRfY2FyZCgpO1xuXHRcdFx0ZWRpdG9yLm9wZW5fYnV0dG9uLmRpc2FibGVkID0gaXNfbG9hZGluZyB8fCBvcGVuX3JlcXVlc3RfaXNfbG9hZGluZyB8fCAhIGdldF9zZWxlY3RlZF9jYXJkKCk7XG5cdFx0fVxuXG5cdFx0LyoqXG5cdFx0ICogQ2xlYXIgdGhlIGlmcmFtZSdzIGFzc29jaWF0aW9uIHdpdGggYW4gZWFybGllciB0ZW1wbGF0ZSBwcmV2aWV3LlxuXHRcdCAqXG5cdFx0ICogVGhlIGF0dHJpYnV0ZSBpcyBkaWFnbm9zdGljIGFzIHdlbGwgYXMgZnVuY3Rpb25hbDogaXQgbGV0cyBicm93c2VyIHRlc3RzXG5cdFx0ICogdmVyaWZ5IHRoYXQgdGhlIHZpc2libGUgaWZyYW1lIGJlbG9uZ3MgdG8gdGhlIGN1cnJlbnRseSBzZWxlY3RlZCBjYXJkLlxuXHRcdCAqXG5cdFx0ICogQHJldHVybiB7dm9pZH1cblx0XHQgKi9cblx0XHRmdW5jdGlvbiBjbGVhcl9wcmV2aWV3X2ZyYW1lKCkge1xuXHRcdFx0ZWRpdG9yLnByZXZpZXdfZnJhbWUucmVtb3ZlQXR0cmlidXRlKCAnZGF0YS1wcmV2aWV3LXRlbXBsYXRlLXNsdWcnICk7XG5cdFx0XHRlZGl0b3IucHJldmlld19mcmFtZS5yZW1vdmVBdHRyaWJ1dGUoICdkYXRhLXByZXZpZXctYm9va2luZy1mb3JtLXVzYWdlJyApO1xuXHRcdFx0ZWRpdG9yLnByZXZpZXdfZnJhbWUuc3JjID0gJ2Fib3V0OmJsYW5rJztcblx0XHRcdGVkaXRvci5wcmV2aWV3X2ZyYW1lLmhpZGRlbiA9IHRydWU7XG5cdFx0fVxuXG5cdFx0LyoqXG5cdFx0ICogUmVjb3JkIG9uZSBzdWNjZXNzZnVsbHkgcmVuZGVyZWQgcHJldmlldy5cblx0XHQgKlxuXHRcdCAqIEBwYXJhbSB7c3RyaW5nfSBwcmV2aWV3X2tpbmQgICAgICBJbmxpbmUgb3IgVVJMIHByZXZpZXcga2luZC5cblx0XHQgKiBAcGFyYW0ge3N0cmluZ30gcHJldmlld191cmwgICAgICAgU2lnbmVkIFVSTCB3aGVuIGFwcGxpY2FibGUuXG5cdFx0ICogQHBhcmFtIHtzdHJpbmd9IHRlbXBsYXRlX3NsdWcgICAgIFZhbGlkYXRlZCB0ZW1wbGF0ZSBzbHVnLlxuXHRcdCAqIEBwYXJhbSB7c3RyaW5nfSBib29raW5nX2Zvcm1fdXNhZ2UgVmFsaWRhdGVkIHVzYWdlIGlkZW50aWZpZXIuXG5cdFx0ICogQHJldHVybiB7dm9pZH1cblx0XHQgKi9cblx0XHRmdW5jdGlvbiBzZXRfY3VycmVudF9wcmV2aWV3KCBwcmV2aWV3X2tpbmQsIHByZXZpZXdfdXJsLCB0ZW1wbGF0ZV9zbHVnLCBib29raW5nX2Zvcm1fdXNhZ2UgKSB7XG5cdFx0XHRjdXJyZW50X3ByZXZpZXcgPSB7XG5cdFx0XHRcdGtpbmQ6IHByZXZpZXdfa2luZCxcblx0XHRcdFx0dXJsOiBwcmV2aWV3X3VybCxcblx0XHRcdFx0dGVtcGxhdGVfc2x1ZzogdGVtcGxhdGVfc2x1Zyxcblx0XHRcdFx0Ym9va2luZ19mb3JtX3VzYWdlOiBib29raW5nX2Zvcm1fdXNhZ2Vcblx0XHRcdH07XG5cdFx0fVxuXG5cdFx0LyoqXG5cdFx0ICogRGV0ZXJtaW5lIHdoZXRoZXIgdGhlIGN1cnJlbnQgcHJldmlldyBtYXRjaGVzIHRoZSBzZWxlY3RlZCBjb250cm9scy5cblx0XHQgKlxuXHRcdCAqIEBwYXJhbSB7c3RyaW5nfSB0ZW1wbGF0ZV9zbHVnICAgICAgU2VsZWN0ZWQgdGVtcGxhdGUgc2x1Zy5cblx0XHQgKiBAcGFyYW0ge3N0cmluZ30gYm9va2luZ19mb3JtX3VzYWdlIFNlbGVjdGVkIHVzYWdlIGlkZW50aWZpZXIuXG5cdFx0ICogQHJldHVybiB7Ym9vbGVhbn0gV2hldGhlciB0aGUgYWN0aXZlIHByZXZpZXcgYmVsb25ncyB0byB0aGUgc2VsZWN0aW9uLlxuXHRcdCAqL1xuXHRcdGZ1bmN0aW9uIGN1cnJlbnRfcHJldmlld19tYXRjaGVzKCB0ZW1wbGF0ZV9zbHVnLCBib29raW5nX2Zvcm1fdXNhZ2UgKSB7XG5cdFx0XHRyZXR1cm4gY3VycmVudF9wcmV2aWV3LnRlbXBsYXRlX3NsdWcgPT09IHRlbXBsYXRlX3NsdWcgJiYgY3VycmVudF9wcmV2aWV3LmJvb2tpbmdfZm9ybV91c2FnZSA9PT0gYm9va2luZ19mb3JtX3VzYWdlO1xuXHRcdH1cblxuXHRcdC8qKlxuXHRcdCAqIFJlYWQgYSBub3JtYWxpemVkIFdvcmRQcmVzcyBBSkFYIGVycm9yIG1lc3NhZ2UuXG5cdFx0ICpcblx0XHQgKiBAcGFyYW0ge09iamVjdHxudWxsfSByZXNwb25zZSBQYXJzZWQgSlNPTiByZXNwb25zZS5cblx0XHQgKiBAcmV0dXJuIHtzdHJpbmd9IFBsYWluIGVycm9yIG1lc3NhZ2UuXG5cdFx0ICovXG5cdFx0ZnVuY3Rpb24gZ2V0X2FqYXhfZXJyb3JfbWVzc2FnZSggcmVzcG9uc2UgKSB7XG5cdFx0XHRpZiAoIHJlc3BvbnNlICYmIHJlc3BvbnNlLmRhdGEgJiYgcmVzcG9uc2UuZGF0YS5tZXNzYWdlICkge1xuXHRcdFx0XHRyZXR1cm4gU3RyaW5nKCByZXNwb25zZS5kYXRhLm1lc3NhZ2UgKTtcblx0XHRcdH1cblxuXHRcdFx0cmV0dXJuIGdldF9tZXNzYWdlKCAncHJldmlld19lcnJvcicsICdUaGUgaW50ZXJhY3RpdmUgcHJldmlldyBjb3VsZCBub3QgYmUgbG9hZGVkLiBUcnkgcmVmcmVzaGluZyBpdC4nICk7XG5cdFx0fVxuXG5cdFx0LyoqXG5cdFx0ICogUmV0dXJuIGEgc2FmZSBtZXNzYWdlIHdoZW4gYW4gdXBzdHJlYW0gc2VydmVyIHJldHVybnMgbm9uLUpTT04gY29udGVudC5cblx0XHQgKlxuXHRcdCAqIFdvcmRQcmVzcyBKU09OIGVycm9ycyBhcmUgcGFyc2VkIG5vcm1hbGx5IGFuZCByZXRhaW4gdGhlaXIgc2VydmVyLWF1dGhvcmVkXG5cdFx0ICogbWVzc2FnZS4gVGhpcyBwYXRoIGhhbmRsZXMgd2ViLXNlcnZlciwgcHJveHksIGFuZCBmaXJld2FsbCByZXNwb25zZXMgdGhhdFxuXHRcdCAqIG5ldmVyIHJlYWNoZWQgdGhlIGF1dGhlbnRpY2F0ZWQgV29yZFByZXNzIEFKQVggZW5kcG9pbnQuXG5cdFx0ICpcblx0XHQgKiBAcGFyYW0ge1Jlc3BvbnNlfG51bGx9IHJlc3BvbnNlIEZldGNoIHJlc3BvbnNlLlxuXHRcdCAqIEByZXR1cm4ge3N0cmluZ30gTG9jYWxpemVkIHBsYWluLXRleHQgZXJyb3IgbWVzc2FnZS5cblx0XHQgKi9cblx0XHRmdW5jdGlvbiBnZXRfcHJldmlld190cmFuc3BvcnRfZXJyb3JfbWVzc2FnZSggcmVzcG9uc2UgKSB7XG5cdFx0XHRpZiAoIHJlc3BvbnNlICYmIDQwMyA9PT0gTnVtYmVyKCByZXNwb25zZS5zdGF0dXMgKSApIHtcblx0XHRcdFx0aWYgKCB0cnVlID09PSBjb25maWcuaXNfbGl2ZV9kZW1vICkge1xuXHRcdFx0XHRcdHJldHVybiBnZXRfbWVzc2FnZSggJ3ByZXZpZXdfbGl2ZV9kZW1vX2Jsb2NrZWRfZXJyb3InLCAnVGhlIGludGVyYWN0aXZlIEJvb2tpbmcgRm9ybSBwcmV2aWV3IGlzIGxpbWl0ZWQgaW4gdGhpcyBCb29raW5nIENhbGVuZGFyIGxpdmUgZGVtby4gVGhpcyBsaW1pdGF0aW9uIGRvZXMgbm90IGFwcGx5IHRvIEJvb2tpbmcgQ2FsZW5kYXIgaW5zdGFsbGVkIG9uIHlvdXIgb3duIHNpdGUuJyApO1xuXHRcdFx0XHR9XG5cblx0XHRcdFx0cmV0dXJuIGdldF9tZXNzYWdlKCAncHJldmlld19ibG9ja2VkX2Vycm9yJywgJ1RoZSB3ZWIgc2VydmVyIGJsb2NrZWQgdGhlIHByZXZpZXcgcmVxdWVzdCBiZWZvcmUgQm9va2luZyBDYWxlbmRhciBjb3VsZCBwcm9jZXNzIGl0LiBBc2sgeW91ciBzZXJ2ZXIgYWRtaW5pc3RyYXRvciB0byBhbGxvdyB0aGlzIFdvcmRQcmVzcyBBSkFYIHJlcXVlc3QsIHRoZW4gdHJ5IGFnYWluLicgKTtcblx0XHRcdH1cblxuXHRcdFx0cmV0dXJuIGdldF9tZXNzYWdlKCAncHJldmlld19yZXNwb25zZV9lcnJvcicsICdUaGUgcHJldmlldyBzZXJ2ZXIgcmV0dXJuZWQgYW4gdW5leHBlY3RlZCByZXNwb25zZSBpbnN0ZWFkIG9mIEpTT04uIFRyeSBhZ2FpbiBvciBjb250YWN0IHlvdXIgc2VydmVyIGFkbWluaXN0cmF0b3IuJyApO1xuXHRcdH1cblxuXHRcdC8qKlxuXHRcdCAqIFBhcnNlIG9uZSBwcmV2aWV3IHJlc3BvbnNlIHdpdGhvdXQgZXhwb3NpbmcgYnJvd3NlciBKU09OIHBhcnNlciBlcnJvcnMuXG5cdFx0ICpcblx0XHQgKiBAcGFyYW0ge1Jlc3BvbnNlfSByZXNwb25zZSBGZXRjaCByZXNwb25zZS5cblx0XHQgKiBAcmV0dXJuIHtQcm9taXNlPE9iamVjdD59IFBhcnNlZCBXb3JkUHJlc3MgQUpBWCByZXNwb25zZS5cblx0XHQgKi9cblx0XHRmdW5jdGlvbiBwYXJzZV9wcmV2aWV3X3Jlc3BvbnNlKCByZXNwb25zZSApIHtcblx0XHRcdHJldHVybiByZXNwb25zZS50ZXh0KCkudGhlbiggZnVuY3Rpb24gKCByZXNwb25zZV90ZXh0ICkge1xuXHRcdFx0XHR0cnkge1xuXHRcdFx0XHRcdHJldHVybiBKU09OLnBhcnNlKCByZXNwb25zZV90ZXh0ICk7XG5cdFx0XHRcdH0gY2F0Y2ggKCBwYXJzZV9lcnJvciApIHtcblx0XHRcdFx0XHR0aHJvdyBuZXcgRXJyb3IoIGdldF9wcmV2aWV3X3RyYW5zcG9ydF9lcnJvcl9tZXNzYWdlKCByZXNwb25zZSApICk7XG5cdFx0XHRcdH1cblx0XHRcdH0gKTtcblx0XHR9XG5cblx0XHQvKipcblx0XHQgKiBCdWlsZCB0aGUgYXV0aGVudGljYXRlZCByZXF1ZXN0IGJvZHkgZm9yIG9uZSBzZXJ2ZXItb3duZWQgcHJldmlldy5cblx0XHQgKlxuXHRcdCAqIEBwYXJhbSB7c3RyaW5nfSB0ZW1wbGF0ZV9zbHVnICAgICAgU2VsZWN0ZWQgdGVtcGxhdGUgc2x1Zy5cblx0XHQgKiBAcGFyYW0ge3N0cmluZ30gYm9va2luZ19mb3JtX3VzYWdlIFNlbGVjdGVkIHVzYWdlIGlkZW50aWZpZXIuXG5cdFx0ICogQHBhcmFtIHtzdHJpbmd9IHByZXZpZXdfdGFyZ2V0ICAgICBFbWJlZGRlZCBvciB3aW5kb3cgdGFyZ2V0LlxuXHRcdCAqIEByZXR1cm4ge3N0cmluZ30gVVJMLWVuY29kZWQgcmVxdWVzdCBib2R5LlxuXHRcdCAqL1xuXHRcdGZ1bmN0aW9uIGJ1aWxkX3ByZXZpZXdfcmVxdWVzdF9ib2R5KCB0ZW1wbGF0ZV9zbHVnLCBib29raW5nX2Zvcm1fdXNhZ2UsIHByZXZpZXdfdGFyZ2V0ICkge1xuXHRcdFx0dmFyIHJlcXVlc3RfZmllbGRzID0ge1xuXHRcdFx0XHRhY3Rpb246IGNvbmZpZy5wcmV2aWV3X2FjdGlvbixcblx0XHRcdFx0bm9uY2U6IGNvbmZpZy5ub25jZSB8fCAnJyxcblx0XHRcdFx0dGVtcGxhdGVfc2x1ZzogdGVtcGxhdGVfc2x1Zyxcblx0XHRcdFx0Ym9va2luZ19mb3JtX3VzYWdlOiBib29raW5nX2Zvcm1fdXNhZ2UsXG5cdFx0XHRcdHByZXZpZXdfdGFyZ2V0OiBwcmV2aWV3X3RhcmdldFxuXHRcdFx0fTtcblxuXHRcdFx0cmV0dXJuIE9iamVjdC5rZXlzKCByZXF1ZXN0X2ZpZWxkcyApLm1hcCggZnVuY3Rpb24gKCBmaWVsZF9uYW1lICkge1xuXHRcdFx0XHRyZXR1cm4gd2luZG93LmVuY29kZVVSSUNvbXBvbmVudCggZmllbGRfbmFtZSApICsgJz0nICsgd2luZG93LmVuY29kZVVSSUNvbXBvbmVudCggU3RyaW5nKCByZXF1ZXN0X2ZpZWxkc1sgZmllbGRfbmFtZSBdICkgKTtcblx0XHRcdH0gKS5qb2luKCAnJicgKTtcblx0XHR9XG5cblx0XHQvKipcblx0XHQgKiBDb25maXJtIHRoYXQgYSByZXNwb25zZSBiZWxvbmdzIHRvIHRoZSBjdXJyZW50IHNlcnZlci12YWxpZGF0ZWQgc2VsZWN0aW9uLlxuXHRcdCAqXG5cdFx0ICogQHBhcmFtIHtPYmplY3R9IHJlc3BvbnNlICAgICAgICAgICAgV29yZFByZXNzIEFKQVggcmVzcG9uc2UuXG5cdFx0ICogQHBhcmFtIHtzdHJpbmd9IHRlbXBsYXRlX3NsdWcgICAgICAgUmVxdWVzdGVkIHRlbXBsYXRlIHNsdWcuXG5cdFx0ICogQHBhcmFtIHtzdHJpbmd9IGJvb2tpbmdfZm9ybV91c2FnZSAgUmVxdWVzdGVkIHVzYWdlIGlkZW50aWZpZXIuXG5cdFx0ICogQHBhcmFtIHtzdHJpbmd9IHByZXZpZXdfdGFyZ2V0ICAgICAgUmVxdWVzdGVkIHByZXZpZXcgdGFyZ2V0LlxuXHRcdCAqIEByZXR1cm4ge09iamVjdH0gVmFsaWRhdGVkIHJlc3BvbnNlIGRhdGEuXG5cdFx0ICogQHRocm93cyB7RXJyb3J9IFdoZW4gcmVzcG9uc2Ugb3duZXJzaGlwIG9yIHNoYXBlIGlzIGludmFsaWQuXG5cdFx0ICovXG5cdFx0ZnVuY3Rpb24gdmFsaWRhdGVfcHJldmlld19yZXNwb25zZSggcmVzcG9uc2UsIHRlbXBsYXRlX3NsdWcsIGJvb2tpbmdfZm9ybV91c2FnZSwgcHJldmlld190YXJnZXQgKSB7XG5cdFx0XHR2YXIgcmVzcG9uc2VfZGF0YTtcblx0XHRcdHZhciBzZWxlY3RlZF9jYXJkID0gZ2V0X3NlbGVjdGVkX2NhcmQoKTtcblxuXHRcdFx0aWYgKCAhIHJlc3BvbnNlIHx8ICEgcmVzcG9uc2Uuc3VjY2VzcyB8fCAhIHJlc3BvbnNlLmRhdGEgKSB7XG5cdFx0XHRcdHRocm93IG5ldyBFcnJvciggZ2V0X2FqYXhfZXJyb3JfbWVzc2FnZSggcmVzcG9uc2UgKSApO1xuXHRcdFx0fVxuXG5cdFx0XHRyZXNwb25zZV9kYXRhID0gcmVzcG9uc2UuZGF0YTtcblx0XHRcdGlmIChcblx0XHRcdFx0U3RyaW5nKCByZXNwb25zZV9kYXRhLnRlbXBsYXRlX3NsdWcgfHwgJycgKSAhPT0gdGVtcGxhdGVfc2x1ZyB8fFxuXHRcdFx0XHRTdHJpbmcoIHJlc3BvbnNlX2RhdGEuYm9va2luZ19mb3JtX3VzYWdlIHx8ICcnICkgIT09IGJvb2tpbmdfZm9ybV91c2FnZSB8fFxuXHRcdFx0XHRTdHJpbmcoIHJlc3BvbnNlX2RhdGEucHJldmlld190YXJnZXQgfHwgJycgKSAhPT0gcHJldmlld190YXJnZXQgfHxcblx0XHRcdFx0ISBzZWxlY3RlZF9jYXJkIHx8XG5cdFx0XHRcdFN0cmluZyggc2VsZWN0ZWRfY2FyZC5kYXRhc2V0LnRlbXBsYXRlU2x1ZyB8fCAnJyApICE9PSB0ZW1wbGF0ZV9zbHVnIHx8XG5cdFx0XHRcdGdldF9zZWxlY3RlZF9ib29raW5nX2Zvcm1fdXNhZ2UoKSAhPT0gYm9va2luZ19mb3JtX3VzYWdlXG5cdFx0XHQpIHtcblx0XHRcdFx0dGhyb3cgbmV3IEVycm9yKCBnZXRfbWVzc2FnZSggJ3ByZXZpZXdfZXJyb3InLCAnVGhlIGludGVyYWN0aXZlIHByZXZpZXcgY291bGQgbm90IGJlIGxvYWRlZC4gVHJ5IHJlZnJlc2hpbmcgaXQuJyApICk7XG5cdFx0XHR9XG5cblx0XHRcdHJldHVybiByZXNwb25zZV9kYXRhO1xuXHRcdH1cblxuXHRcdC8qKlxuXHRcdCAqIFJlcXVlc3QgYW5kIHJlbmRlciB0aGUgc2VsZWN0ZWQgYWxsb3ctbGlzdGVkIHRlbXBsYXRlLlxuXHRcdCAqXG5cdFx0ICogQHBhcmFtIHtIVE1MRWxlbWVudHxudWxsfSBjYXJkIFNlbGVjdGVkIHRlbXBsYXRlIGNhcmQuXG5cdFx0ICogQHJldHVybiB7dm9pZH1cblx0XHQgKi9cblx0XHRmdW5jdGlvbiBsb2FkX3ByZXZpZXcoIGNhcmQgKSB7XG5cdFx0XHR2YXIgdGVtcGxhdGVfc2x1ZyA9IGNhcmQgPyBTdHJpbmcoIGNhcmQuZGF0YXNldC50ZW1wbGF0ZVNsdWcgfHwgJycgKSA6ICcnO1xuXHRcdFx0dmFyIGJvb2tpbmdfZm9ybV91c2FnZSA9IGdldF9zZWxlY3RlZF9ib29raW5nX2Zvcm1fdXNhZ2UoKTtcblx0XHRcdHZhciByZXF1ZXN0X3NlcXVlbmNlO1xuXHRcdFx0dmFyIHJlcXVlc3RfYm9keTtcblxuXHRcdFx0aWYgKCAhIHRlbXBsYXRlX3NsdWcgfHwgISBib29raW5nX2Zvcm1fdXNhZ2UgfHwgISBjb25maWcuYWpheF91cmwgfHwgISBjb25maWcucHJldmlld19hY3Rpb24gKSB7XG5cdFx0XHRcdHNldF9wcmV2aWV3X3N0YXRlKCBmYWxzZSwgZ2V0X21lc3NhZ2UoICdwcmV2aWV3X2Vycm9yJywgJ1RoZSBpbnRlcmFjdGl2ZSBwcmV2aWV3IGNvdWxkIG5vdCBiZSBsb2FkZWQuIFRyeSByZWZyZXNoaW5nIGl0LicgKSApO1xuXHRcdFx0XHRyZXR1cm47XG5cdFx0XHR9XG5cblx0XHRcdGlmICggcHJldmlld19hYm9ydF9jb250cm9sbGVyICYmICdmdW5jdGlvbicgPT09IHR5cGVvZiBwcmV2aWV3X2Fib3J0X2NvbnRyb2xsZXIuYWJvcnQgKSB7XG5cdFx0XHRcdHByZXZpZXdfYWJvcnRfY29udHJvbGxlci5hYm9ydCgpO1xuXHRcdFx0fVxuXHRcdFx0cHJldmlld19hYm9ydF9jb250cm9sbGVyID0gJ2Z1bmN0aW9uJyA9PT0gdHlwZW9mIHdpbmRvdy5BYm9ydENvbnRyb2xsZXIgPyBuZXcgd2luZG93LkFib3J0Q29udHJvbGxlcigpIDogbnVsbDtcblx0XHRcdHJlcXVlc3Rfc2VxdWVuY2UgPSArK3ByZXZpZXdfcmVxdWVzdF9zZXF1ZW5jZTtcblx0XHRcdHJlcXVlc3RfYm9keSA9IGJ1aWxkX3ByZXZpZXdfcmVxdWVzdF9ib2R5KCB0ZW1wbGF0ZV9zbHVnLCBib29raW5nX2Zvcm1fdXNhZ2UsICdlbWJlZGRlZCcgKTtcblxuXHRcdFx0c2V0X3ByZXZpZXdfc3RhdGUoIHRydWUsICcnICk7XG5cblx0XHRcdHdpbmRvdy5mZXRjaCggY29uZmlnLmFqYXhfdXJsLCB7XG5cdFx0XHRcdG1ldGhvZDogJ1BPU1QnLFxuXHRcdFx0XHRoZWFkZXJzOiB7XG5cdFx0XHRcdFx0J0NvbnRlbnQtVHlwZSc6ICdhcHBsaWNhdGlvbi94LXd3dy1mb3JtLXVybGVuY29kZWQ7IGNoYXJzZXQ9VVRGLTgnXG5cdFx0XHRcdH0sXG5cdFx0XHRcdGJvZHk6IHJlcXVlc3RfYm9keSxcblx0XHRcdFx0Y3JlZGVudGlhbHM6ICdzYW1lLW9yaWdpbicsXG5cdFx0XHRcdHNpZ25hbDogcHJldmlld19hYm9ydF9jb250cm9sbGVyID8gcHJldmlld19hYm9ydF9jb250cm9sbGVyLnNpZ25hbCA6IHVuZGVmaW5lZFxuXHRcdFx0fSApLnRoZW4oIGZ1bmN0aW9uICggcmVzcG9uc2UgKSB7XG5cdFx0XHRcdHJldHVybiBwYXJzZV9wcmV2aWV3X3Jlc3BvbnNlKCByZXNwb25zZSApO1xuXHRcdFx0fSApLnRoZW4oIGZ1bmN0aW9uICggcmVzcG9uc2UgKSB7XG5cdFx0XHRcdHZhciByZXNwb25zZV9kYXRhO1xuXHRcdFx0XHR2YXIgcHJldmlld19raW5kO1xuXHRcdFx0XHR2YXIgcHJldmlld191cmw7XG5cblx0XHRcdFx0aWYgKCByZXF1ZXN0X3NlcXVlbmNlICE9PSBwcmV2aWV3X3JlcXVlc3Rfc2VxdWVuY2UgKSB7XG5cdFx0XHRcdFx0cmV0dXJuO1xuXHRcdFx0XHR9XG5cblx0XHRcdFx0cmVzcG9uc2VfZGF0YSA9IHZhbGlkYXRlX3ByZXZpZXdfcmVzcG9uc2UoIHJlc3BvbnNlLCB0ZW1wbGF0ZV9zbHVnLCBib29raW5nX2Zvcm1fdXNhZ2UsICdlbWJlZGRlZCcgKTtcblx0XHRcdFx0cHJldmlld19raW5kID0gU3RyaW5nKCByZXNwb25zZV9kYXRhLnByZXZpZXdfa2luZCB8fCAnJyApO1xuXG5cdFx0XHRcdGlmIChcblx0XHRcdFx0XHQnaW5saW5lJyA9PT0gcHJldmlld19raW5kICYmXG5cdFx0XHRcdFx0cmVzcG9uc2VfZGF0YS5odG1sICYmXG5cdFx0XHRcdFx0cmVzcG9uc2VfZGF0YS5ib290c3RyYXAgJiZcblx0XHRcdFx0XHQnb2JqZWN0JyA9PT0gdHlwZW9mIHJlc3BvbnNlX2RhdGEuYm9vdHN0cmFwICYmXG5cdFx0XHRcdFx0aW5saW5lX3ByZXZpZXdfcmVuZGVyZXJcblx0XHRcdFx0KSB7XG5cdFx0XHRcdFx0cmV0dXJuIGlubGluZV9wcmV2aWV3X3JlbmRlcmVyLnJlcGxhY2UoIFN0cmluZyggcmVzcG9uc2VfZGF0YS5odG1sICksIHJlc3BvbnNlX2RhdGEuYm9vdHN0cmFwLCBmdW5jdGlvbiAoKSB7XG5cdFx0XHRcdFx0XHRyZXR1cm4gcmVxdWVzdF9zZXF1ZW5jZSA9PT0gcHJldmlld19yZXF1ZXN0X3NlcXVlbmNlO1xuXHRcdFx0XHRcdH0gKS50aGVuKCBmdW5jdGlvbiAoKSB7XG5cdFx0XHRcdFx0XHRpZiAoIHJlcXVlc3Rfc2VxdWVuY2UgIT09IHByZXZpZXdfcmVxdWVzdF9zZXF1ZW5jZSApIHtcblx0XHRcdFx0XHRcdFx0cmV0dXJuO1xuXHRcdFx0XHRcdFx0fVxuXHRcdFx0XHRcdFx0Y2xlYXJfcHJldmlld19mcmFtZSgpO1xuXHRcdFx0XHRcdFx0ZWRpdG9yLmlubGluZV9wcmV2aWV3LmhpZGRlbiA9IGZhbHNlO1xuXHRcdFx0XHRcdFx0c2V0X2N1cnJlbnRfcHJldmlldyggJ2lubGluZScsICcnLCB0ZW1wbGF0ZV9zbHVnLCBib29raW5nX2Zvcm1fdXNhZ2UgKTtcblx0XHRcdFx0XHRcdHNldF9wcmV2aWV3X3N0YXRlKCBmYWxzZSwgJycgKTtcblx0XHRcdFx0XHR9ICk7XG5cdFx0XHRcdH1cblxuXHRcdFx0XHRpZiAoICd1cmwnICE9PSBwcmV2aWV3X2tpbmQgfHwgISByZXNwb25zZV9kYXRhLnByZXZpZXdfdXJsICkge1xuXHRcdFx0XHRcdHRocm93IG5ldyBFcnJvciggZ2V0X21lc3NhZ2UoICdwcmV2aWV3X2Vycm9yJywgJ1RoZSBpbnRlcmFjdGl2ZSBwcmV2aWV3IGNvdWxkIG5vdCBiZSBsb2FkZWQuIFRyeSByZWZyZXNoaW5nIGl0LicgKSApO1xuXHRcdFx0XHR9XG5cblx0XHRcdFx0cHJldmlld191cmwgPSBTdHJpbmcoIHJlc3BvbnNlX2RhdGEucHJldmlld191cmwgKTtcblx0XHRcdFx0ZWRpdG9yLnByZXZpZXdfZnJhbWUub25sb2FkID0gZnVuY3Rpb24gKCkge1xuXHRcdFx0XHRcdGlmICggcmVxdWVzdF9zZXF1ZW5jZSAhPT0gcHJldmlld19yZXF1ZXN0X3NlcXVlbmNlICkge1xuXHRcdFx0XHRcdFx0cmV0dXJuO1xuXHRcdFx0XHRcdH1cblx0XHRcdFx0XHRlZGl0b3IuaW5saW5lX3ByZXZpZXcuaGlkZGVuID0gdHJ1ZTtcblx0XHRcdFx0XHRlZGl0b3IucHJldmlld19mcmFtZS5oaWRkZW4gPSBmYWxzZTtcblx0XHRcdFx0XHRzZXRfY3VycmVudF9wcmV2aWV3KCAndXJsJywgcHJldmlld191cmwsIHRlbXBsYXRlX3NsdWcsIGJvb2tpbmdfZm9ybV91c2FnZSApO1xuXHRcdFx0XHRcdHNldF9wcmV2aWV3X3N0YXRlKCBmYWxzZSwgJycgKTtcblx0XHRcdFx0fTtcblx0XHRcdFx0ZWRpdG9yLnByZXZpZXdfZnJhbWUuc2V0QXR0cmlidXRlKCAnZGF0YS1wcmV2aWV3LXRlbXBsYXRlLXNsdWcnLCB0ZW1wbGF0ZV9zbHVnICk7XG5cdFx0XHRcdGVkaXRvci5wcmV2aWV3X2ZyYW1lLnNldEF0dHJpYnV0ZSggJ2RhdGEtcHJldmlldy1ib29raW5nLWZvcm0tdXNhZ2UnLCBib29raW5nX2Zvcm1fdXNhZ2UgKTtcblx0XHRcdFx0ZWRpdG9yLnByZXZpZXdfZnJhbWUuc3JjID0gcHJldmlld191cmw7XG5cdFx0XHR9ICkuY2F0Y2goIGZ1bmN0aW9uICggZXJyb3IgKSB7XG5cdFx0XHRcdGlmICggZXJyb3IgJiYgJ0Fib3J0RXJyb3InID09PSBlcnJvci5uYW1lICkge1xuXHRcdFx0XHRcdHJldHVybjtcblx0XHRcdFx0fVxuXHRcdFx0XHRpZiAoIHJlcXVlc3Rfc2VxdWVuY2UgIT09IHByZXZpZXdfcmVxdWVzdF9zZXF1ZW5jZSApIHtcblx0XHRcdFx0XHRyZXR1cm47XG5cdFx0XHRcdH1cblx0XHRcdFx0c2V0X3ByZXZpZXdfc3RhdGUoIGZhbHNlLCBlcnJvciAmJiBlcnJvci5tZXNzYWdlID8gZXJyb3IubWVzc2FnZSA6IGdldF9tZXNzYWdlKCAncHJldmlld19lcnJvcicsICdUaGUgaW50ZXJhY3RpdmUgcHJldmlldyBjb3VsZCBub3QgYmUgbG9hZGVkLiBUcnkgcmVmcmVzaGluZyBpdC4nICkgKTtcblx0XHRcdH0gKTtcblx0XHR9XG5cblx0XHQvKipcblx0XHQgKiBPcGVuIHRoZSBmdWxsLXNpdGUgcHJldmlldywgbGF6aWx5IGNyZWF0aW5nIGEgc2lnbmVkIHNlc3Npb24gd2hlbiBuZWVkZWQuXG5cdFx0ICpcblx0XHQgKiBAcGFyYW0ge0hUTUxFbGVtZW50fG51bGx9IGNhcmQgU2VsZWN0ZWQgdGVtcGxhdGUgY2FyZC5cblx0XHQgKiBAcmV0dXJuIHt2b2lkfVxuXHRcdCAqL1xuXHRcdGZ1bmN0aW9uIG9wZW5fcHJldmlldyggY2FyZCApIHtcblx0XHRcdHZhciB0ZW1wbGF0ZV9zbHVnID0gY2FyZCA/IFN0cmluZyggY2FyZC5kYXRhc2V0LnRlbXBsYXRlU2x1ZyB8fCAnJyApIDogJyc7XG5cdFx0XHR2YXIgYm9va2luZ19mb3JtX3VzYWdlID0gZ2V0X3NlbGVjdGVkX2Jvb2tpbmdfZm9ybV91c2FnZSgpO1xuXHRcdFx0dmFyIHByZXZpZXdfd2luZG93O1xuXHRcdFx0dmFyIHJlcXVlc3Rfc2VxdWVuY2U7XG5cblx0XHRcdGlmICggISB0ZW1wbGF0ZV9zbHVnIHx8ICEgYm9va2luZ19mb3JtX3VzYWdlIHx8IGVkaXRvci5vcGVuX2J1dHRvbi5kaXNhYmxlZCApIHtcblx0XHRcdFx0cmV0dXJuO1xuXHRcdFx0fVxuXG5cdFx0XHRpZiAoIGN1cnJlbnRfcHJldmlld19tYXRjaGVzKCB0ZW1wbGF0ZV9zbHVnLCBib29raW5nX2Zvcm1fdXNhZ2UgKSAmJiBjdXJyZW50X3ByZXZpZXcudXJsICkge1xuXHRcdFx0XHR3aW5kb3cub3BlbiggY3VycmVudF9wcmV2aWV3LnVybCwgJ19ibGFuaycsICdub29wZW5lcixub3JlZmVycmVyJyApO1xuXHRcdFx0XHRyZXR1cm47XG5cdFx0XHR9XG5cblx0XHRcdHByZXZpZXdfd2luZG93ID0gd2luZG93Lm9wZW4oICcnLCAnX2JsYW5rJyApO1xuXHRcdFx0aWYgKCAhIHByZXZpZXdfd2luZG93ICkge1xuXHRcdFx0XHRzZXRfcHJldmlld19zdGF0ZSggZmFsc2UsIGdldF9tZXNzYWdlKCAncHJldmlld19vcGVuX2Vycm9yJywgJ1RoZSBmdWxsLXNpdGUgcHJldmlldyBjb3VsZCBub3QgYmUgb3BlbmVkLiBUcnkgYWdhaW4uJyApICk7XG5cdFx0XHRcdHJldHVybjtcblx0XHRcdH1cblx0XHRcdHByZXZpZXdfd2luZG93Lm9wZW5lciA9IG51bGw7XG5cblx0XHRcdGlmICggb3Blbl9hYm9ydF9jb250cm9sbGVyICYmICdmdW5jdGlvbicgPT09IHR5cGVvZiBvcGVuX2Fib3J0X2NvbnRyb2xsZXIuYWJvcnQgKSB7XG5cdFx0XHRcdG9wZW5fYWJvcnRfY29udHJvbGxlci5hYm9ydCgpO1xuXHRcdFx0fVxuXHRcdFx0b3Blbl9hYm9ydF9jb250cm9sbGVyID0gJ2Z1bmN0aW9uJyA9PT0gdHlwZW9mIHdpbmRvdy5BYm9ydENvbnRyb2xsZXIgPyBuZXcgd2luZG93LkFib3J0Q29udHJvbGxlcigpIDogbnVsbDtcblx0XHRcdHJlcXVlc3Rfc2VxdWVuY2UgPSArK29wZW5fcmVxdWVzdF9zZXF1ZW5jZTtcblx0XHRcdG9wZW5fcmVxdWVzdF9pc19sb2FkaW5nID0gdHJ1ZTtcblx0XHRcdGVkaXRvci5vcGVuX2J1dHRvbi5kaXNhYmxlZCA9IHRydWU7XG5cblx0XHRcdHdpbmRvdy5mZXRjaCggY29uZmlnLmFqYXhfdXJsLCB7XG5cdFx0XHRcdG1ldGhvZDogJ1BPU1QnLFxuXHRcdFx0XHRoZWFkZXJzOiB7XG5cdFx0XHRcdFx0J0NvbnRlbnQtVHlwZSc6ICdhcHBsaWNhdGlvbi94LXd3dy1mb3JtLXVybGVuY29kZWQ7IGNoYXJzZXQ9VVRGLTgnXG5cdFx0XHRcdH0sXG5cdFx0XHRcdGJvZHk6IGJ1aWxkX3ByZXZpZXdfcmVxdWVzdF9ib2R5KCB0ZW1wbGF0ZV9zbHVnLCBib29raW5nX2Zvcm1fdXNhZ2UsICd3aW5kb3cnICksXG5cdFx0XHRcdGNyZWRlbnRpYWxzOiAnc2FtZS1vcmlnaW4nLFxuXHRcdFx0XHRzaWduYWw6IG9wZW5fYWJvcnRfY29udHJvbGxlciA/IG9wZW5fYWJvcnRfY29udHJvbGxlci5zaWduYWwgOiB1bmRlZmluZWRcblx0XHRcdH0gKS50aGVuKCBmdW5jdGlvbiAoIHJlc3BvbnNlICkge1xuXHRcdFx0XHRyZXR1cm4gcGFyc2VfcHJldmlld19yZXNwb25zZSggcmVzcG9uc2UgKTtcblx0XHRcdH0gKS50aGVuKCBmdW5jdGlvbiAoIHJlc3BvbnNlICkge1xuXHRcdFx0XHR2YXIgcmVzcG9uc2VfZGF0YTtcblxuXHRcdFx0XHRpZiAoIHJlcXVlc3Rfc2VxdWVuY2UgIT09IG9wZW5fcmVxdWVzdF9zZXF1ZW5jZSApIHtcblx0XHRcdFx0XHRyZXR1cm47XG5cdFx0XHRcdH1cblx0XHRcdFx0cmVzcG9uc2VfZGF0YSA9IHZhbGlkYXRlX3ByZXZpZXdfcmVzcG9uc2UoIHJlc3BvbnNlLCB0ZW1wbGF0ZV9zbHVnLCBib29raW5nX2Zvcm1fdXNhZ2UsICd3aW5kb3cnICk7XG5cdFx0XHRcdGlmICggJ3VybCcgIT09IFN0cmluZyggcmVzcG9uc2VfZGF0YS5wcmV2aWV3X2tpbmQgfHwgJycgKSB8fCAhIHJlc3BvbnNlX2RhdGEucHJldmlld191cmwgKSB7XG5cdFx0XHRcdFx0dGhyb3cgbmV3IEVycm9yKCBnZXRfbWVzc2FnZSggJ3ByZXZpZXdfb3Blbl9lcnJvcicsICdUaGUgZnVsbC1zaXRlIHByZXZpZXcgY291bGQgbm90IGJlIG9wZW5lZC4gVHJ5IGFnYWluLicgKSApO1xuXHRcdFx0XHR9XG5cblx0XHRcdFx0cHJldmlld193aW5kb3cubG9jYXRpb24ucmVwbGFjZSggU3RyaW5nKCByZXNwb25zZV9kYXRhLnByZXZpZXdfdXJsICkgKTtcblx0XHRcdH0gKS5jYXRjaCggZnVuY3Rpb24gKCBlcnJvciApIHtcblx0XHRcdFx0aWYgKCBlcnJvciAmJiAnQWJvcnRFcnJvcicgPT09IGVycm9yLm5hbWUgKSB7XG5cdFx0XHRcdFx0cHJldmlld193aW5kb3cuY2xvc2UoKTtcblx0XHRcdFx0XHRyZXR1cm47XG5cdFx0XHRcdH1cblx0XHRcdFx0aWYgKCByZXF1ZXN0X3NlcXVlbmNlICE9PSBvcGVuX3JlcXVlc3Rfc2VxdWVuY2UgKSB7XG5cdFx0XHRcdFx0cmV0dXJuO1xuXHRcdFx0XHR9XG5cdFx0XHRcdHByZXZpZXdfd2luZG93LmNsb3NlKCk7XG5cdFx0XHRcdHNldF9wcmV2aWV3X3N0YXRlKCBmYWxzZSwgZXJyb3IgJiYgZXJyb3IubWVzc2FnZSA/IGVycm9yLm1lc3NhZ2UgOiBnZXRfbWVzc2FnZSggJ3ByZXZpZXdfb3Blbl9lcnJvcicsICdUaGUgZnVsbC1zaXRlIHByZXZpZXcgY291bGQgbm90IGJlIG9wZW5lZC4gVHJ5IGFnYWluLicgKSApO1xuXHRcdFx0fSApLnRoZW4oIGZ1bmN0aW9uICgpIHtcblx0XHRcdFx0aWYgKCByZXF1ZXN0X3NlcXVlbmNlID09PSBvcGVuX3JlcXVlc3Rfc2VxdWVuY2UgKSB7XG5cdFx0XHRcdFx0b3Blbl9yZXF1ZXN0X2lzX2xvYWRpbmcgPSBmYWxzZTtcblx0XHRcdFx0XHRlZGl0b3Iub3Blbl9idXR0b24uZGlzYWJsZWQgPSAhIGdldF9zZWxlY3RlZF9jYXJkKCk7XG5cdFx0XHRcdH1cblx0XHRcdH0gKTtcblx0XHR9XG5cblx0XHQvKipcblx0XHQgKiBTZWxlY3Qgb25lIGNhcmQgYW5kIGxvYWQgaXRzIHJlYWwgcHJldmlldy5cblx0XHQgKlxuXHRcdCAqIEBwYXJhbSB7SFRNTEVsZW1lbnR9IGNhcmQgICAgICAgICBUZW1wbGF0ZSBjYXJkLlxuXHRcdCAqIEBwYXJhbSB7Ym9vbGVhbn0gICAgIGxvYWRfc2VsZWN0ZWQgV2hldGhlciB0byByZXF1ZXN0IGEgcmVwbGFjZW1lbnQgcHJldmlldy5cblx0XHQgKiBAcmV0dXJuIHt2b2lkfVxuXHRcdCAqL1xuXHRcdGZ1bmN0aW9uIHNlbGVjdF9jYXJkKCBjYXJkLCBsb2FkX3NlbGVjdGVkICkge1xuXHRcdFx0dmFyIHJhZGlvID0gY2FyZCAmJiBjYXJkLnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLXRlbXBsYXRlLXJhZGlvXScgKTtcblxuXHRcdFx0aWYgKCAhIHJhZGlvICkge1xuXHRcdFx0XHRyZXR1cm47XG5cdFx0XHR9XG5cblx0XHRcdHJhZGlvLmNoZWNrZWQgPSB0cnVlO1xuXHRcdFx0ZWRpdG9yLmNhcmRzLmZvckVhY2goIGZ1bmN0aW9uICggdGVtcGxhdGVfY2FyZCApIHtcblx0XHRcdFx0dGVtcGxhdGVfY2FyZC5jbGFzc0xpc3QudG9nZ2xlKCAnaXMtc2VsZWN0ZWQnLCB0ZW1wbGF0ZV9jYXJkID09PSBjYXJkICk7XG5cdFx0XHR9ICk7XG5cdFx0XHRzaGVsbF9hcGkuY2xlYXJfZmllbGRfZXJyb3IoICdib29raW5nX2Zvcm1fdGVtcGxhdGUnICk7XG5cdFx0XHRyZW5kZXJfcHJldmlld19tZXRhZGF0YSggY2FyZCApO1xuXHRcdFx0aWYgKCBmYWxzZSAhPT0gbG9hZF9zZWxlY3RlZCApIHtcblx0XHRcdFx0bG9hZF9wcmV2aWV3KCBjYXJkICk7XG5cdFx0XHR9XG5cdFx0fVxuXG5cdFx0LyoqXG5cdFx0ICogUmV0dXJuIGNhcmRzIG1hdGNoaW5nIHRoZSBjdXJyZW50IHNlYXJjaCBhbmQgY2F0ZWdvcnkuXG5cdFx0ICpcblx0XHQgKiBAcmV0dXJuIHtIVE1MRWxlbWVudFtdfSBNYXRjaGluZyBjYXJkcyBpbiBzZXJ2ZXIgb3JkZXIuXG5cdFx0ICovXG5cdFx0ZnVuY3Rpb24gZ2V0X21hdGNoaW5nX2NhcmRzKCkge1xuXHRcdFx0dmFyIHF1ZXJ5ID0gbm9ybWFsaXplX3NlYXJjaF90ZXh0KCBlZGl0b3Iuc2VhcmNoID8gZWRpdG9yLnNlYXJjaC52YWx1ZS50cmltKCkgOiAnJyApO1xuXG5cdFx0XHRyZXR1cm4gZWRpdG9yLmNhcmRzLmZpbHRlciggZnVuY3Rpb24gKCBjYXJkICkge1xuXHRcdFx0XHR2YXIgbWF0Y2hlc19maWx0ZXIgPSAnYWxsJyA9PT0gZWRpdG9yLmFjdGl2ZV9maWx0ZXIgfHxcblx0XHRcdFx0XHQoICdyZWNvbW1lbmRlZCcgPT09IGVkaXRvci5hY3RpdmVfZmlsdGVyICYmICd0cnVlJyA9PT0gY2FyZC5kYXRhc2V0LnRlbXBsYXRlUmVjb21tZW5kZWQgKSB8fFxuXHRcdFx0XHRcdGVkaXRvci5hY3RpdmVfZmlsdGVyID09PSBjYXJkLmRhdGFzZXQudGVtcGxhdGVDYXRlZ29yeTtcblx0XHRcdFx0dmFyIG1hdGNoZXNfc2VhcmNoID0gISBxdWVyeSB8fCAtMSAhPT0gZ2V0X2NhcmRfc2VhcmNoX3RleHQoIGNhcmQgKS5pbmRleE9mKCBxdWVyeSApO1xuXG5cdFx0XHRcdHJldHVybiBtYXRjaGVzX2ZpbHRlciAmJiBtYXRjaGVzX3NlYXJjaDtcblx0XHRcdH0gKTtcblx0XHR9XG5cblx0XHQvKipcblx0XHQgKiBSZW5kZXIgZXZlcnkgY2FyZCBtYXRjaGluZyB0aGUgY3VycmVudCBzZWFyY2ggYW5kIGNhdGVnb3J5LlxuXHRcdCAqXG5cdFx0ICogQHJldHVybiB7dm9pZH1cblx0XHQgKi9cblx0XHRmdW5jdGlvbiByZW5kZXJfY2F0YWxvZygpIHtcblx0XHRcdHZhciBtYXRjaGluZ19jYXJkcyA9IGdldF9tYXRjaGluZ19jYXJkcygpO1xuXG5cdFx0XHRlZGl0b3IuY2FyZHMuZm9yRWFjaCggZnVuY3Rpb24gKCBjYXJkICkge1xuXHRcdFx0XHRjYXJkLmhpZGRlbiA9IHRydWU7XG5cdFx0XHR9ICk7XG5cdFx0XHRtYXRjaGluZ19jYXJkcy5mb3JFYWNoKCBmdW5jdGlvbiAoIGNhcmQgKSB7XG5cdFx0XHRcdGNhcmQuaGlkZGVuID0gZmFsc2U7XG5cdFx0XHR9ICk7XG5cblx0XHRcdGVkaXRvci5lbXB0eS5oaWRkZW4gPSAwICE9PSBtYXRjaGluZ19jYXJkcy5sZW5ndGg7XG5cdFx0XHRlZGl0b3IuZW1wdHkudGV4dENvbnRlbnQgPSBnZXRfbWVzc2FnZSggJ25vX21hdGNoaW5nX3RlbXBsYXRlcycsICdObyB0ZW1wbGF0ZXMgbWF0Y2ggdGhpcyBzZWFyY2ggYW5kIGZpbHRlci4nICk7XG5cdFx0XHRlZGl0b3IuZ3JpZC5zY3JvbGxMZWZ0ID0gMDtcblx0XHR9XG5cblx0XHQvKipcblx0XHQgKiBBY3RpdmF0ZSBvbmUgY2F0ZWdvcnkgdGFiIGFuZCByZWZyZXNoIHRoZSB2aXNpYmxlIGNhcmRzLlxuXHRcdCAqXG5cdFx0ICogQHBhcmFtIHtIVE1MRWxlbWVudH0gdGFiIEZpbHRlciB0YWIgYnV0dG9uLlxuXHRcdCAqIEByZXR1cm4ge3ZvaWR9XG5cdFx0ICovXG5cdFx0ZnVuY3Rpb24gYWN0aXZhdGVfZmlsdGVyKCB0YWIgKSB7XG5cdFx0XHRpZiAoICEgdGFiICkge1xuXHRcdFx0XHRyZXR1cm47XG5cdFx0XHR9XG5cblx0XHRcdGVkaXRvci5hY3RpdmVfZmlsdGVyID0gdGFiLmRhdGFzZXQud3BiY1RlbXBsYXRlRmlsdGVyIHx8ICdhbGwnO1xuXHRcdFx0ZWRpdG9yLnRhYnMuZm9yRWFjaCggZnVuY3Rpb24gKCBmaWx0ZXJfdGFiICkge1xuXHRcdFx0XHR2YXIgaXNfYWN0aXZlID0gZmlsdGVyX3RhYiA9PT0gdGFiO1xuXHRcdFx0XHRmaWx0ZXJfdGFiLmNsYXNzTGlzdC50b2dnbGUoICdpcy1hY3RpdmUnLCBpc19hY3RpdmUgKTtcblx0XHRcdFx0ZmlsdGVyX3RhYi5zZXRBdHRyaWJ1dGUoICdhcmlhLXNlbGVjdGVkJywgaXNfYWN0aXZlID8gJ3RydWUnIDogJ2ZhbHNlJyApO1xuXHRcdFx0XHRmaWx0ZXJfdGFiLnRhYkluZGV4ID0gaXNfYWN0aXZlID8gMCA6IC0xO1xuXHRcdFx0fSApO1xuXHRcdFx0ZWRpdG9yLmdyaWQuc2V0QXR0cmlidXRlKCAnYXJpYS1sYWJlbGxlZGJ5JywgdGFiLmlkICk7XG5cdFx0XHRyZW5kZXJfY2F0YWxvZygpO1xuXHRcdH1cblxuXHRcdC8qKlxuXHRcdCAqIFJldHVybiB0aGUgZmlsdGVyIHRhYiB3aXRoIHRoZSByZXF1ZXN0ZWQgc3RhYmxlIElELlxuXHRcdCAqXG5cdFx0ICogQHBhcmFtIHtzdHJpbmd9IGZpbHRlcl9pZCBTdGFibGUgZmlsdGVyIElELlxuXHRcdCAqIEByZXR1cm4ge0hUTUxFbGVtZW50fG51bGx9IE1hdGNoaW5nIHRhYiwgb3IgbnVsbC5cblx0XHQgKi9cblx0XHRmdW5jdGlvbiBnZXRfZmlsdGVyX3RhYiggZmlsdGVyX2lkICkge1xuXHRcdFx0cmV0dXJuIGVkaXRvci50YWJzLmZpbmQoIGZ1bmN0aW9uICggdGFiICkge1xuXHRcdFx0XHRyZXR1cm4gZmlsdGVyX2lkID09PSB0YWIuZGF0YXNldC53cGJjVGVtcGxhdGVGaWx0ZXI7XG5cdFx0XHR9ICkgfHwgbnVsbDtcblx0XHR9XG5cblx0XHQvKipcblx0XHQgKiBBcHBseSB0ZXh0LXNlYXJjaCBjaGFuZ2VzIGltbWVkaWF0ZWx5LlxuXHRcdCAqXG5cdFx0ICogVGV4dCBzZWFyY2ggaW50ZW50aW9uYWxseSBzd2l0Y2hlcyB0byBBbGwgdGVtcGxhdGVzLiBPdGhlcndpc2UgYSB2YWxpZFxuXHRcdCAqIG1hdGNoIHN1Y2ggYXMgYSBGdWxsIERheXMgb3IgY29udGFjdCB0ZW1wbGF0ZSBjYW4gcmVtYWluIGhpZGRlbiBtZXJlbHlcblx0XHQgKiBiZWNhdXNlIHRoZSBSZWNvbW1lbmRlZCB0YWIgd2FzIGFjdGl2ZSBiZWZvcmUgdGhlIHVzZXIgc3RhcnRlZCB0eXBpbmcuXG5cdFx0ICpcblx0XHQgKiBAcmV0dXJuIHt2b2lkfVxuXHRcdCAqL1xuXHRcdGZ1bmN0aW9uIGhhbmRsZV9zZWFyY2hfY2hhbmdlKCkge1xuXHRcdFx0dmFyIHF1ZXJ5ID0gbm9ybWFsaXplX3NlYXJjaF90ZXh0KCBlZGl0b3Iuc2VhcmNoID8gZWRpdG9yLnNlYXJjaC52YWx1ZS50cmltKCkgOiAnJyApO1xuXHRcdFx0dmFyIGFsbF90ZW1wbGF0ZXNfdGFiID0gZ2V0X2ZpbHRlcl90YWIoICdhbGwnICk7XG5cblx0XHRcdGlmICggcXVlcnkgJiYgYWxsX3RlbXBsYXRlc190YWIgJiYgJ2FsbCcgIT09IGVkaXRvci5hY3RpdmVfZmlsdGVyICkge1xuXHRcdFx0XHRhY3RpdmF0ZV9maWx0ZXIoIGFsbF90ZW1wbGF0ZXNfdGFiICk7XG5cdFx0XHRcdHJldHVybjtcblx0XHRcdH1cblxuXHRcdFx0cmVuZGVyX2NhdGFsb2coKTtcblx0XHR9XG5cblx0XHQvKipcblx0XHQgKiBIYW5kbGUgc2VsZWN0b3IgYW5kIHByZXZpZXcgYWN0aW9ucy5cblx0XHQgKlxuXHRcdCAqIEBwYXJhbSB7TW91c2VFdmVudH0gZXZlbnQgQ2xpY2sgZXZlbnQuXG5cdFx0ICogQHJldHVybiB7dm9pZH1cblx0XHQgKi9cblx0XHRmdW5jdGlvbiBoYW5kbGVfY2xpY2soIGV2ZW50ICkge1xuXHRcdFx0dmFyIGZpbHRlciA9IGV2ZW50LnRhcmdldC5jbG9zZXN0KCAnW2RhdGEtd3BiYy10ZW1wbGF0ZS1maWx0ZXJdJyApO1xuXHRcdFx0dmFyIHJlZnJlc2hfYnV0dG9uID0gZXZlbnQudGFyZ2V0LmNsb3Nlc3QoICdbZGF0YS13cGJjLXRlbXBsYXRlLXJlZnJlc2hdJyApO1xuXHRcdFx0dmFyIG9wZW5fbGluayA9IGV2ZW50LnRhcmdldC5jbG9zZXN0KCAnW2RhdGEtd3BiYy10ZW1wbGF0ZS1vcGVuLXByZXZpZXddJyApO1xuXHRcdFx0dmFyIHRlbXBsYXRlX2NhcmQgPSBldmVudC50YXJnZXQuY2xvc2VzdCggJ1tkYXRhLXdwYmMtdGVtcGxhdGUtY2FyZF0nICk7XG5cdFx0XHR2YXIgdGVtcGxhdGVfcmFkaW87XG5cblx0XHRcdGlmICggZmlsdGVyICkge1xuXHRcdFx0XHRhY3RpdmF0ZV9maWx0ZXIoIGZpbHRlciApO1xuXHRcdFx0XHRyZXR1cm47XG5cdFx0XHR9XG5cdFx0XHRpZiAoIHJlZnJlc2hfYnV0dG9uICkge1xuXHRcdFx0XHRsb2FkX3ByZXZpZXcoIGdldF9zZWxlY3RlZF9jYXJkKCkgKTtcblx0XHRcdFx0cmV0dXJuO1xuXHRcdFx0fVxuXHRcdFx0aWYgKCBvcGVuX2xpbmsgKSB7XG5cdFx0XHRcdG9wZW5fcHJldmlldyggZ2V0X3NlbGVjdGVkX2NhcmQoKSApO1xuXHRcdFx0XHRyZXR1cm47XG5cdFx0XHR9XG5cdFx0XHRpZiAoIHRlbXBsYXRlX2NhcmQgKSB7XG5cdFx0XHRcdHRlbXBsYXRlX3JhZGlvID0gdGVtcGxhdGVfY2FyZC5xdWVyeVNlbGVjdG9yKCAnW2RhdGEtd3BiYy10ZW1wbGF0ZS1yYWRpb10nICk7XG5cdFx0XHRcdGlmICggdGVtcGxhdGVfcmFkaW8gJiYgISB0ZW1wbGF0ZV9yYWRpby5jaGVja2VkICkge1xuXHRcdFx0XHRcdGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XG5cdFx0XHRcdFx0c2VsZWN0X2NhcmQoIHRlbXBsYXRlX2NhcmQsIHRydWUgKTtcblx0XHRcdFx0XHR0ZW1wbGF0ZV9yYWRpby5mb2N1cygpO1xuXHRcdFx0XHR9XG5cdFx0XHRcdHJldHVybjtcblx0XHRcdH1cblx0XHR9XG5cblx0XHQvKipcblx0XHQgKiBTeW5jaHJvbml6ZSBvbmUgZW50cnktcG9pbnQgY2hvaWNlIHdpdGggaXRzIG5hdGl2ZSByYWRpbyBhbmQgY2FyZC5cblx0XHQgKlxuXHRcdCAqIEBwYXJhbSB7c3RyaW5nfSBib29raW5nX2Zvcm1fdXNhZ2UgU3RhYmxlIGVudHJ5LXBvaW50IGlkZW50aWZpZXIuXG5cdFx0ICogQHJldHVybiB7SFRNTElucHV0RWxlbWVudHxudWxsfSBNYXRjaGluZyByYWRpbyBjb250cm9sLCBvciBudWxsLlxuXHRcdCAqL1xuXHRcdGZ1bmN0aW9uIHNlbGVjdF9ib29raW5nX2Zvcm1fdXNhZ2UoIGJvb2tpbmdfZm9ybV91c2FnZSApIHtcblx0XHRcdHZhciBzZWxlY3RlZF9yYWRpbyA9IG51bGw7XG5cblx0XHRcdEFycmF5LnByb3RvdHlwZS5zbGljZS5jYWxsKCBlZGl0b3Iubm9kZS5xdWVyeVNlbGVjdG9yQWxsKCAnW2RhdGEtd3BiYy1ib29raW5nLWZvcm0tdXNhZ2UtcmFkaW9dJyApICkuZm9yRWFjaCggZnVuY3Rpb24gKCB1c2FnZV9yYWRpbyApIHtcblx0XHRcdFx0dmFyIGlzX3NlbGVjdGVkID0gYm9va2luZ19mb3JtX3VzYWdlID09PSBTdHJpbmcoIHVzYWdlX3JhZGlvLnZhbHVlIHx8ICcnICk7XG5cblx0XHRcdFx0dXNhZ2VfcmFkaW8uY2hlY2tlZCA9IGlzX3NlbGVjdGVkO1xuXHRcdFx0XHR1c2FnZV9yYWRpby5jbG9zZXN0KCAnLndwYmNfc2V0dXBfd2l6YXJkX19ib29raW5nLWZvcm0tdXNhZ2Utb3B0aW9uJyApLmNsYXNzTGlzdC50b2dnbGUoICdpcy1zZWxlY3RlZCcsIGlzX3NlbGVjdGVkICk7XG5cdFx0XHRcdGlmICggaXNfc2VsZWN0ZWQgKSB7XG5cdFx0XHRcdFx0c2VsZWN0ZWRfcmFkaW8gPSB1c2FnZV9yYWRpbztcblx0XHRcdFx0fVxuXHRcdFx0fSApO1xuXG5cdFx0XHRyZXR1cm4gc2VsZWN0ZWRfcmFkaW87XG5cdFx0fVxuXG5cdFx0LyoqXG5cdFx0ICogQ29tbWl0IGFuIGV4cGxhaW5lZCBlbnRyeS1wb2ludCBjaG9pY2UgYW5kIHJlZnJlc2ggaXRzIHByZXZpZXcuXG5cdFx0ICpcblx0XHQgKiBAcGFyYW0ge3N0cmluZ30gYm9va2luZ19mb3JtX3VzYWdlIFN0YWJsZSBlbnRyeS1wb2ludCBpZGVudGlmaWVyLlxuXHRcdCAqIEByZXR1cm4ge3ZvaWR9XG5cdFx0ICovXG5cdFx0ZnVuY3Rpb24gY29tbWl0X2Jvb2tpbmdfZm9ybV91c2FnZSggYm9va2luZ19mb3JtX3VzYWdlICkge1xuXHRcdFx0aWYgKCAhIHNlbGVjdF9ib29raW5nX2Zvcm1fdXNhZ2UoIGJvb2tpbmdfZm9ybV91c2FnZSApICkge1xuXHRcdFx0XHRyZXR1cm47XG5cdFx0XHR9XG5cblx0XHRcdGNvbmZpcm1lZF9ib29raW5nX2Zvcm1fdXNhZ2UgPSBib29raW5nX2Zvcm1fdXNhZ2U7XG5cdFx0XHRzaGVsbF9hcGkuY2xlYXJfZmllbGRfZXJyb3IoICdib29raW5nX2Zvcm1fdXNhZ2UnICk7XG5cdFx0XHRyZW5kZXJfcHJldmlld19tZXRhZGF0YSggZ2V0X3NlbGVjdGVkX2NhcmQoKSApO1xuXHRcdFx0bG9hZF9wcmV2aWV3KCBnZXRfc2VsZWN0ZWRfY2FyZCgpICk7XG5cdFx0fVxuXG5cdFx0LyoqXG5cdFx0ICogQ29tbWl0IG9yIHJvbGwgYmFjayB0aGUgcHJvdmlzaW9uYWwgQXBwb2ludG1lbnQgZmxvdyByYWRpbyBzZWxlY3Rpb24uXG5cdFx0ICpcblx0XHQgKiBAcGFyYW0ge0N1c3RvbUV2ZW50fSBldmVudCBTaGFyZWQgZGlhbG9nIGxpZmVjeWNsZSBldmVudC5cblx0XHQgKiBAcmV0dXJuIHt2b2lkfVxuXHRcdCAqL1xuXHRcdGZ1bmN0aW9uIGhhbmRsZV9kaWFsb2dfY2xvc2VkKCBldmVudCApIHtcblx0XHRcdHZhciBkaWFsb2dfc3RhdGUgPSBldmVudC5kZXRhaWwgfHwge307XG5cblx0XHRcdGlmICggYXBwb2ludG1lbnRfZmxvd19kaWFsb2dfYWN0aW9uICE9PSBkaWFsb2dfc3RhdGUuYWN0aW9uICkge1xuXHRcdFx0XHRyZXR1cm47XG5cdFx0XHR9XG5cblx0XHRcdGlmICggJ2NvbmZpcm0nID09PSBkaWFsb2dfc3RhdGUub3V0Y29tZSApIHtcblx0XHRcdFx0Y29tbWl0X2Jvb2tpbmdfZm9ybV91c2FnZSggJ2FwcG9pbnRtZW50X2Zsb3cnICk7XG5cdFx0XHRcdHJldHVybjtcblx0XHRcdH1cblxuXHRcdFx0c2VsZWN0X2Jvb2tpbmdfZm9ybV91c2FnZSggY29uZmlybWVkX2Jvb2tpbmdfZm9ybV91c2FnZSApO1xuXHRcdH1cblxuXHRcdC8qKlxuXHRcdCAqIEhhbmRsZSBuYXRpdmUgdGVtcGxhdGUgcmFkaW8gY2hhbmdlcy5cblx0XHQgKlxuXHRcdCAqIEBwYXJhbSB7RXZlbnR9IGV2ZW50IENoYW5nZSBldmVudC5cblx0XHQgKiBAcmV0dXJuIHt2b2lkfVxuXHRcdCAqL1xuXHRcdGZ1bmN0aW9uIGhhbmRsZV9jaGFuZ2UoIGV2ZW50ICkge1xuXHRcdFx0aWYgKCBldmVudC50YXJnZXQubWF0Y2hlcyggJ1tkYXRhLXdwYmMtdGVtcGxhdGUtcmFkaW9dJyApICkge1xuXHRcdFx0XHRzZWxlY3RfY2FyZCggZXZlbnQudGFyZ2V0LmNsb3Nlc3QoICdbZGF0YS13cGJjLXRlbXBsYXRlLWNhcmRdJyApLCB0cnVlICk7XG5cdFx0XHRcdHJldHVybjtcblx0XHRcdH1cblxuXHRcdFx0aWYgKCBldmVudC50YXJnZXQubWF0Y2hlcyggJ1tkYXRhLXdwYmMtYm9va2luZy1mb3JtLXVzYWdlLXJhZGlvXScgKSApIHtcblx0XHRcdFx0c2VsZWN0X2Jvb2tpbmdfZm9ybV91c2FnZSggU3RyaW5nKCBldmVudC50YXJnZXQudmFsdWUgfHwgJycgKSApO1xuXHRcdFx0XHRzaGVsbF9hcGkuY2xlYXJfZmllbGRfZXJyb3IoICdib29raW5nX2Zvcm1fdXNhZ2UnICk7XG5cblx0XHRcdFx0aWYgKFxuXHRcdFx0XHRcdCdhcHBvaW50bWVudF9mbG93JyA9PT0gZXZlbnQudGFyZ2V0LnZhbHVlICYmXG5cdFx0XHRcdFx0J2FwcG9pbnRtZW50X2Zsb3cnICE9PSBjb25maXJtZWRfYm9va2luZ19mb3JtX3VzYWdlICYmXG5cdFx0XHRcdFx0J2Z1bmN0aW9uJyA9PT0gdHlwZW9mIHNoZWxsX2FwaS5vcGVuX2RpYWxvZ1xuXHRcdFx0XHQpIHtcblx0XHRcdFx0XHRpZiAoIHNoZWxsX2FwaS5vcGVuX2RpYWxvZyggYXBwb2ludG1lbnRfZmxvd19kaWFsb2dfYWN0aW9uLCBldmVudC50YXJnZXQgKSApIHtcblx0XHRcdFx0XHRcdHJldHVybjtcblx0XHRcdFx0XHR9XG5cdFx0XHRcdH1cblxuXHRcdFx0XHRjb21taXRfYm9va2luZ19mb3JtX3VzYWdlKCBTdHJpbmcoIGV2ZW50LnRhcmdldC52YWx1ZSB8fCAnJyApICk7XG5cdFx0XHR9XG5cdFx0fVxuXG5cdFx0LyoqXG5cdFx0ICogUHJvdmlkZSBhcnJvdy1rZXkgbmF2aWdhdGlvbiBhY3Jvc3MgaG9yaXpvbnRhbGx5IHNjcm9sbGFibGUgZmlsdGVyIHRhYnMuXG5cdFx0ICpcblx0XHQgKiBAcGFyYW0ge0tleWJvYXJkRXZlbnR9IGV2ZW50IEtleWJvYXJkIGV2ZW50LlxuXHRcdCAqIEByZXR1cm4ge3ZvaWR9XG5cdFx0ICovXG5cdFx0ZnVuY3Rpb24gaGFuZGxlX2tleWRvd24oIGV2ZW50ICkge1xuXHRcdFx0dmFyIHRhYiA9IGV2ZW50LnRhcmdldC5jbG9zZXN0KCAnW2RhdGEtd3BiYy10ZW1wbGF0ZS1maWx0ZXJdJyApO1xuXHRcdFx0dmFyIGN1cnJlbnRfaW5kZXg7XG5cdFx0XHR2YXIgdGFyZ2V0X2luZGV4O1xuXG5cdFx0XHRpZiAoICEgdGFiIHx8IC0xID09PSBbICdBcnJvd0xlZnQnLCAnQXJyb3dSaWdodCcsICdIb21lJywgJ0VuZCcgXS5pbmRleE9mKCBldmVudC5rZXkgKSApIHtcblx0XHRcdFx0cmV0dXJuO1xuXHRcdFx0fVxuXG5cdFx0XHRldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xuXHRcdFx0Y3VycmVudF9pbmRleCA9IGVkaXRvci50YWJzLmluZGV4T2YoIHRhYiApO1xuXHRcdFx0dGFyZ2V0X2luZGV4ID0gY3VycmVudF9pbmRleDtcblx0XHRcdGlmICggJ0Fycm93TGVmdCcgPT09IGV2ZW50LmtleSApIHtcblx0XHRcdFx0dGFyZ2V0X2luZGV4ID0gKCBjdXJyZW50X2luZGV4IC0gMSArIGVkaXRvci50YWJzLmxlbmd0aCApICUgZWRpdG9yLnRhYnMubGVuZ3RoO1xuXHRcdFx0fSBlbHNlIGlmICggJ0Fycm93UmlnaHQnID09PSBldmVudC5rZXkgKSB7XG5cdFx0XHRcdHRhcmdldF9pbmRleCA9ICggY3VycmVudF9pbmRleCArIDEgKSAlIGVkaXRvci50YWJzLmxlbmd0aDtcblx0XHRcdH0gZWxzZSBpZiAoICdIb21lJyA9PT0gZXZlbnQua2V5ICkge1xuXHRcdFx0XHR0YXJnZXRfaW5kZXggPSAwO1xuXHRcdFx0fSBlbHNlIGlmICggJ0VuZCcgPT09IGV2ZW50LmtleSApIHtcblx0XHRcdFx0dGFyZ2V0X2luZGV4ID0gZWRpdG9yLnRhYnMubGVuZ3RoIC0gMTtcblx0XHRcdH1cblxuXHRcdFx0YWN0aXZhdGVfZmlsdGVyKCBlZGl0b3IudGFic1sgdGFyZ2V0X2luZGV4IF0gKTtcblx0XHRcdGVkaXRvci50YWJzWyB0YXJnZXRfaW5kZXggXS5mb2N1cygpO1xuXHRcdFx0ZWRpdG9yLnRhYnNbIHRhcmdldF9pbmRleCBdLnNjcm9sbEludG9WaWV3KCB7IGJsb2NrOiAnbmVhcmVzdCcsIGlubGluZTogJ25lYXJlc3QnIH0gKTtcblx0XHR9XG5cblx0XHQvKipcblx0XHQgKiBJbml0aWFsaXplIHRoaXMgbW9kdWxlIGFnYWluc3Qgc2hlbGwtcHJvdmlkZWQgQVBJcy5cblx0XHQgKlxuXHRcdCAqIEBwYXJhbSB7T2JqZWN0fSByZWdpc3RlcmVkX3NoZWxsX2FwaSBTaGFyZWQgd2l6YXJkIGFkYXB0ZXIgc2VydmljZXMuXG5cdFx0ICogQHJldHVybiB7dm9pZH1cblx0XHQgKi9cblx0XHRmdW5jdGlvbiBpbml0aWFsaXplKCByZWdpc3RlcmVkX3NoZWxsX2FwaSApIHtcblx0XHRcdHZhciBlZGl0b3Jfbm9kZTtcblx0XHRcdHZhciBzZWxlY3RlZF9jYXJkO1xuXHRcdFx0dmFyIGluaXRpYWxfcHJldmlld19raW5kO1xuXHRcdFx0dmFyIGluaXRpYWxfdGVtcGxhdGVfc2x1Zztcblx0XHRcdHZhciBpbml0aWFsX2Jvb2tpbmdfZm9ybV91c2FnZTtcblx0XHRcdHZhciBpbml0aWFsX3ByZXZpZXdfYm9vdHN0cmFwO1xuXHRcdFx0dmFyIGluaXRpYWxfcHJldmlld19odG1sO1xuXG5cdFx0XHRzaGVsbF9hcGkgPSByZWdpc3RlcmVkX3NoZWxsX2FwaTtcblx0XHRcdHJvb3QgPSBzaGVsbF9hcGkucm9vdDtcblx0XHRcdGVkaXRvcl9ub2RlID0gcm9vdC5xdWVyeVNlbGVjdG9yKCAnW2RhdGEtd3BiYy1ib29raW5nLWZvcm0tdGVtcGxhdGUtZWRpdG9yXScgKTtcblx0XHRcdGlmICggISBlZGl0b3Jfbm9kZSApIHtcblx0XHRcdFx0cmV0dXJuO1xuXHRcdFx0fVxuXG5cdFx0XHRlZGl0b3IgPSB7XG5cdFx0XHRcdG5vZGU6IGVkaXRvcl9ub2RlLFxuXHRcdFx0XHRjYXJkczogQXJyYXkucHJvdG90eXBlLnNsaWNlLmNhbGwoIGVkaXRvcl9ub2RlLnF1ZXJ5U2VsZWN0b3JBbGwoICdbZGF0YS13cGJjLXRlbXBsYXRlLWNhcmRdJyApICksXG5cdFx0XHRcdHRhYnM6IEFycmF5LnByb3RvdHlwZS5zbGljZS5jYWxsKCBlZGl0b3Jfbm9kZS5xdWVyeVNlbGVjdG9yQWxsKCAnW2RhdGEtd3BiYy10ZW1wbGF0ZS1maWx0ZXJdJyApICksXG5cdFx0XHRcdHNlYXJjaDogZWRpdG9yX25vZGUucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtdGVtcGxhdGUtc2VhcmNoXScgKSxcblx0XHRcdFx0Z3JpZDogZWRpdG9yX25vZGUucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtdGVtcGxhdGUtZ3JpZF0nICksXG5cdFx0XHRcdGVtcHR5OiBlZGl0b3Jfbm9kZS5xdWVyeVNlbGVjdG9yKCAnW2RhdGEtd3BiYy10ZW1wbGF0ZS1lbXB0eV0nICksXG5cdFx0XHRcdHByZXZpZXdfc3RhZ2U6IGVkaXRvcl9ub2RlLnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLXRlbXBsYXRlLXByZXZpZXctc3RhZ2VdJyApLFxuXHRcdFx0XHRwcmV2aWV3X2xvYWRlcjogZWRpdG9yX25vZGUucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtdGVtcGxhdGUtcHJldmlldy1sb2FkZXJdJyApLFxuXHRcdFx0XHRwcmV2aWV3X2Vycm9yOiBlZGl0b3Jfbm9kZS5xdWVyeVNlbGVjdG9yKCAnW2RhdGEtd3BiYy10ZW1wbGF0ZS1wcmV2aWV3LWVycm9yXScgKSxcblx0XHRcdFx0aW5saW5lX3ByZXZpZXc6IGVkaXRvcl9ub2RlLnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLXRlbXBsYXRlLWlubGluZS1wcmV2aWV3XScgKSxcblx0XHRcdFx0aW5saW5lX3ByZXZpZXdfY29udGVudDogZWRpdG9yX25vZGUucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtdGVtcGxhdGUtaW5saW5lLXByZXZpZXctY29udGVudF0nICksXG5cdFx0XHRcdGluaXRpYWxfcHJldmlld19ib290c3RyYXA6IGVkaXRvcl9ub2RlLnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLXRlbXBsYXRlLWluaXRpYWwtcHJldmlldy1ib290c3RyYXBdJyApLFxuXHRcdFx0XHRwcmV2aWV3X2ZyYW1lOiBlZGl0b3Jfbm9kZS5xdWVyeVNlbGVjdG9yKCAnW2RhdGEtd3BiYy10ZW1wbGF0ZS1wcmV2aWV3LWZyYW1lXScgKSxcblx0XHRcdFx0cmVmcmVzaF9idXR0b246IGVkaXRvcl9ub2RlLnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLXRlbXBsYXRlLXJlZnJlc2hdJyApLFxuXHRcdFx0XHRvcGVuX2J1dHRvbjogZWRpdG9yX25vZGUucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtdGVtcGxhdGUtb3Blbi1wcmV2aWV3XScgKSxcblx0XHRcdFx0YWN0aXZlX2ZpbHRlcjogZWRpdG9yX25vZGUuZGF0YXNldC5pbml0aWFsRmlsdGVyIHx8ICdhbGwnXG5cdFx0XHR9O1xuXG5cdFx0XHRpZiAoXG5cdFx0XHRcdHdpbmRvdy53cGJjX2JmYl9pbmxpbmVfcHJldmlldyAmJlxuXHRcdFx0XHQnZnVuY3Rpb24nID09PSB0eXBlb2Ygd2luZG93LndwYmNfYmZiX2lubGluZV9wcmV2aWV3LmNyZWF0ZVxuXHRcdFx0KSB7XG5cdFx0XHRcdGlubGluZV9wcmV2aWV3X3JlbmRlcmVyID0gd2luZG93LndwYmNfYmZiX2lubGluZV9wcmV2aWV3LmNyZWF0ZSggZWRpdG9yLmlubGluZV9wcmV2aWV3X2NvbnRlbnQgKTtcblx0XHRcdH1cblxuXHRcdFx0ZWRpdG9yLm5vZGUuYWRkRXZlbnRMaXN0ZW5lciggJ2NsaWNrJywgaGFuZGxlX2NsaWNrICk7XG5cdFx0XHRlZGl0b3Iubm9kZS5hZGRFdmVudExpc3RlbmVyKCAnY2hhbmdlJywgaGFuZGxlX2NoYW5nZSApO1xuXHRcdFx0ZWRpdG9yLm5vZGUuYWRkRXZlbnRMaXN0ZW5lciggJ2tleWRvd24nLCBoYW5kbGVfa2V5ZG93biApO1xuXHRcdFx0cm9vdC5hZGRFdmVudExpc3RlbmVyKCAnd3BiYzpzZXR1cC13aXphcmQtZGlhbG9nLWNsb3NlZCcsIGhhbmRsZV9kaWFsb2dfY2xvc2VkICk7XG5cdFx0XHRjb25maXJtZWRfYm9va2luZ19mb3JtX3VzYWdlID0gZ2V0X3NlbGVjdGVkX2Jvb2tpbmdfZm9ybV91c2FnZSgpO1xuXHRcdFx0aWYgKCBlZGl0b3Iuc2VhcmNoICkge1xuXHRcdFx0XHRlZGl0b3Iuc2VhcmNoLmFkZEV2ZW50TGlzdGVuZXIoICdpbnB1dCcsIGhhbmRsZV9zZWFyY2hfY2hhbmdlICk7XG5cdFx0XHRcdGVkaXRvci5zZWFyY2guYWRkRXZlbnRMaXN0ZW5lciggJ3NlYXJjaCcsIGhhbmRsZV9zZWFyY2hfY2hhbmdlICk7XG5cdFx0XHRcdGVkaXRvci5zZWFyY2guYWRkRXZlbnRMaXN0ZW5lciggJ2NoYW5nZScsIGhhbmRsZV9zZWFyY2hfY2hhbmdlICk7XG5cdFx0XHR9XG5cblx0XHRcdGVkaXRvci5jYXJkcy5mb3JFYWNoKCBmdW5jdGlvbiAoIGNhcmQgKSB7XG5cdFx0XHRcdGluaXRpYWxpemVfaW1hZ2VfZmFsbGJhY2soXG5cdFx0XHRcdFx0Y2FyZC5xdWVyeVNlbGVjdG9yKCAnW2RhdGEtd3BiYy10ZW1wbGF0ZS1jYXJkLWltYWdlXScgKSxcblx0XHRcdFx0XHRjYXJkLnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLXRlbXBsYXRlLWltYWdlLXBsYWNlaG9sZGVyXScgKVxuXHRcdFx0XHQpO1xuXHRcdFx0fSApO1xuXG5cdFx0XHRyZW5kZXJfY2F0YWxvZygpO1xuXHRcdFx0c2VsZWN0ZWRfY2FyZCA9IGdldF9zZWxlY3RlZF9jYXJkKCk7XG5cdFx0XHRpZiAoIHNlbGVjdGVkX2NhcmQgKSB7XG5cdFx0XHRcdGluaXRpYWxfcHJldmlld19raW5kID0gU3RyaW5nKCBlZGl0b3IucHJldmlld19zdGFnZS5kYXRhc2V0LmluaXRpYWxQcmV2aWV3S2luZCB8fCAnJyApO1xuXHRcdFx0XHRpbml0aWFsX3RlbXBsYXRlX3NsdWcgPSBTdHJpbmcoIGVkaXRvci5wcmV2aWV3X3N0YWdlLmRhdGFzZXQuaW5pdGlhbFByZXZpZXdUZW1wbGF0ZVNsdWcgfHwgJycgKTtcblx0XHRcdFx0aW5pdGlhbF9ib29raW5nX2Zvcm1fdXNhZ2UgPSBTdHJpbmcoIGVkaXRvci5wcmV2aWV3X3N0YWdlLmRhdGFzZXQuaW5pdGlhbFByZXZpZXdCb29raW5nRm9ybVVzYWdlIHx8ICcnICk7XG5cdFx0XHRcdHNlbGVjdF9jYXJkKCBzZWxlY3RlZF9jYXJkLCBmYWxzZSApO1xuXG5cdFx0XHRcdGlmIChcblx0XHRcdFx0XHQnaW5saW5lJyA9PT0gaW5pdGlhbF9wcmV2aWV3X2tpbmQgJiZcblx0XHRcdFx0XHRpbml0aWFsX3RlbXBsYXRlX3NsdWcgPT09IFN0cmluZyggc2VsZWN0ZWRfY2FyZC5kYXRhc2V0LnRlbXBsYXRlU2x1ZyB8fCAnJyApICYmXG5cdFx0XHRcdFx0aW5pdGlhbF9ib29raW5nX2Zvcm1fdXNhZ2UgPT09IGdldF9zZWxlY3RlZF9ib29raW5nX2Zvcm1fdXNhZ2UoKSAmJlxuXHRcdFx0XHRcdGlubGluZV9wcmV2aWV3X3JlbmRlcmVyICYmXG5cdFx0XHRcdFx0ZWRpdG9yLmluaXRpYWxfcHJldmlld19ib290c3RyYXBcblx0XHRcdFx0KSB7XG5cdFx0XHRcdFx0dHJ5IHtcblx0XHRcdFx0XHRcdGluaXRpYWxfcHJldmlld19ib290c3RyYXAgPSBKU09OLnBhcnNlKCBlZGl0b3IuaW5pdGlhbF9wcmV2aWV3X2Jvb3RzdHJhcC50ZXh0Q29udGVudCB8fCAne30nICk7XG5cdFx0XHRcdFx0fSBjYXRjaCAoIGVycm9yICkge1xuXHRcdFx0XHRcdFx0aW5pdGlhbF9wcmV2aWV3X2Jvb3RzdHJhcCA9IG51bGw7XG5cdFx0XHRcdFx0fVxuXHRcdFx0XHRcdGluaXRpYWxfcHJldmlld19odG1sID0gZWRpdG9yLmlubGluZV9wcmV2aWV3X2NvbnRlbnQuaW5uZXJIVE1MO1xuXHRcdFx0XHRcdGlmICggaW5pdGlhbF9wcmV2aWV3X2Jvb3RzdHJhcCAmJiAnb2JqZWN0JyA9PT0gdHlwZW9mIGluaXRpYWxfcHJldmlld19ib290c3RyYXAgKSB7XG5cdFx0XHRcdFx0XHRpbmxpbmVfcHJldmlld19yZW5kZXJlci5yZXBsYWNlKCBpbml0aWFsX3ByZXZpZXdfaHRtbCwgaW5pdGlhbF9wcmV2aWV3X2Jvb3RzdHJhcCApXG5cdFx0XHRcdFx0XHRcdC5kb25lKCBmdW5jdGlvbiAoKSB7XG5cdFx0XHRcdFx0XHRcdFx0c2V0X2N1cnJlbnRfcHJldmlldyggJ2lubGluZScsICcnLCBpbml0aWFsX3RlbXBsYXRlX3NsdWcsIGluaXRpYWxfYm9va2luZ19mb3JtX3VzYWdlICk7XG5cdFx0XHRcdFx0XHRcdFx0c2V0X3ByZXZpZXdfc3RhdGUoIGZhbHNlLCAnJyApO1xuXHRcdFx0XHRcdFx0XHR9IClcblx0XHRcdFx0XHRcdFx0LmZhaWwoIGZ1bmN0aW9uICgpIHtcblx0XHRcdFx0XHRcdFx0XHRsb2FkX3ByZXZpZXcoIHNlbGVjdGVkX2NhcmQgKTtcblx0XHRcdFx0XHRcdFx0fSApO1xuXHRcdFx0XHRcdH0gZWxzZSB7XG5cdFx0XHRcdFx0XHRsb2FkX3ByZXZpZXcoIHNlbGVjdGVkX2NhcmQgKTtcblx0XHRcdFx0XHR9XG5cdFx0XHRcdH0gZWxzZSB7XG5cdFx0XHRcdFx0bG9hZF9wcmV2aWV3KCBzZWxlY3RlZF9jYXJkICk7XG5cdFx0XHRcdH1cblx0XHRcdH0gZWxzZSB7XG5cdFx0XHRcdHJlbmRlcl9wcmV2aWV3X21ldGFkYXRhKCBudWxsICk7XG5cdFx0XHRcdHNldF9wcmV2aWV3X3N0YXRlKCBmYWxzZSwgZ2V0X21lc3NhZ2UoICdwcmV2aWV3X2Vycm9yJywgJ1RoZSBpbnRlcmFjdGl2ZSBwcmV2aWV3IGNvdWxkIG5vdCBiZSBsb2FkZWQuIFRyeSByZWZyZXNoaW5nIGl0LicgKSApO1xuXHRcdFx0fVxuXG5cdFx0XHRpZiAoICdmdW5jdGlvbicgPT09IHR5cGVvZiBzaGVsbF9hcGkub3Blbl9kaWFsb2cgKSB7XG5cdFx0XHRcdHdpbmRvdy5zZXRUaW1lb3V0KCBmdW5jdGlvbiAoKSB7XG5cdFx0XHRcdFx0c2hlbGxfYXBpLm9wZW5fZGlhbG9nKCB0ZW1wbGF0ZV9ndWlkYW5jZV9kaWFsb2dfYWN0aW9uLCBlZGl0b3Iuc2VhcmNoIHx8IGVkaXRvci5ub2RlICk7XG5cdFx0XHRcdH0sIDAgKTtcblx0XHRcdH1cblx0XHR9XG5cblx0XHQvKipcblx0XHQgKiBWYWxpZGF0ZSB0aGUgcmVxdWlyZWQgc2VsZWN0aW9uIGJlZm9yZSBmb3J3YXJkIG5hdmlnYXRpb24uXG5cdFx0ICpcblx0XHQgKiBAcmV0dXJuIHtIVE1MRWxlbWVudHxudWxsfSBGaXJzdCBpbnZhbGlkIHJhZGlvLCBvciBudWxsLlxuXHRcdCAqL1xuXHRcdGZ1bmN0aW9uIHZhbGlkYXRlKCkge1xuXHRcdFx0dmFyIHNlbGVjdGVkX3JhZGlvID0gZWRpdG9yICYmIGVkaXRvci5ub2RlLnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLXRlbXBsYXRlLXJhZGlvXTpjaGVja2VkJyApO1xuXHRcdFx0dmFyIHNlbGVjdGVkX3VzYWdlID0gZWRpdG9yICYmIGVkaXRvci5ub2RlLnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLWJvb2tpbmctZm9ybS11c2FnZS1yYWRpb106Y2hlY2tlZCcgKTtcblxuXHRcdFx0aWYgKCAhIHNlbGVjdGVkX3JhZGlvICkge1xuXHRcdFx0XHRyZXR1cm4gc2hlbGxfYXBpLnNldF9maWVsZF9lcnJvciggJ2Jvb2tpbmdfZm9ybV90ZW1wbGF0ZScsIGdldF9tZXNzYWdlKCAndGVtcGxhdGVfcmVxdWlyZWQnLCAnQ2hvb3NlIGEgYm9va2luZyBmb3JtIHRlbXBsYXRlLicgKSApO1xuXHRcdFx0fVxuXG5cdFx0XHRpZiAoICEgc2VsZWN0ZWRfdXNhZ2UgKSB7XG5cdFx0XHRcdHJldHVybiBzaGVsbF9hcGkuc2V0X2ZpZWxkX2Vycm9yKCAnYm9va2luZ19mb3JtX3VzYWdlJywgZ2V0X21lc3NhZ2UoICd1c2FnZV9yZXF1aXJlZCcsICdDaG9vc2UgaG93IGN1c3RvbWVycyB3aWxsIG9wZW4gdGhlIGJvb2tpbmcgZm9ybS4nICkgKTtcblx0XHRcdH1cblxuXHRcdFx0cmV0dXJuIG51bGw7XG5cdFx0fVxuXG5cdFx0cmV0dXJuIHtcblx0XHRcdGluaXRpYWxpemU6IGluaXRpYWxpemUsXG5cdFx0XHRzeW5jOiBmdW5jdGlvbiAoKSB7fSxcblx0XHRcdHZhbGlkYXRlOiB2YWxpZGF0ZVxuXHRcdH07XG5cdH1cblxuXHR3aW5kb3cud3BiY19zZXR1cF9lZGl0b3JfbW9kdWxlcyA9IHdpbmRvdy53cGJjX3NldHVwX2VkaXRvcl9tb2R1bGVzIHx8IHt9O1xuXHR3aW5kb3cud3BiY19zZXR1cF9lZGl0b3JfbW9kdWxlcy5ib29raW5nX2Zvcm1fdGVtcGxhdGUgPSB7XG5cdFx0Y3JlYXRlOiBjcmVhdGVfYm9va2luZ19mb3JtX3RlbXBsYXRlX2FkYXB0ZXJcblx0fTtcblxuXHRpZiAoIHdpbmRvdy53cGJjX3NldHVwX3dpemFyZF9hcGkgJiYgJ2Z1bmN0aW9uJyA9PT0gdHlwZW9mIHdpbmRvdy53cGJjX3NldHVwX3dpemFyZF9hcGkucmVnaXN0ZXJfc3RlcF9hZGFwdGVyICkge1xuXHRcdHdpbmRvdy53cGJjX3NldHVwX3dpemFyZF9hcGkucmVnaXN0ZXJfc3RlcF9hZGFwdGVyKCBjcmVhdGVfYm9va2luZ19mb3JtX3RlbXBsYXRlX2FkYXB0ZXIoIG1vZHVsZV9jb25maWcgKSApO1xuXHR9XG59KCB3aW5kb3csIGRvY3VtZW50ICkgKTtcbiJdLCJtYXBwaW5ncyI6Ijs7QUFBQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDRSxXQUFXQSxNQUFNLEVBQUVDLFFBQVEsRUFBRztFQUMvQixZQUFZOztFQUVaLElBQUlDLGFBQWEsR0FBR0YsTUFBTSxDQUFDRyx1Q0FBdUMsSUFBSTtJQUFFQyxJQUFJLEVBQUUsQ0FBQztFQUFFLENBQUM7O0VBRWxGO0FBQ0Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtFQUNDLFNBQVNDLG9DQUFvQ0EsQ0FBRUMsTUFBTSxFQUFHO0lBQ3ZELElBQUlDLDhCQUE4QixHQUFHLCtCQUErQjtJQUNwRSxJQUFJQywrQkFBK0IsR0FBRyxnQ0FBZ0M7SUFDdEUsSUFBSUMsU0FBUyxHQUFHLElBQUk7SUFDcEIsSUFBSUMsSUFBSSxHQUFHLElBQUk7SUFDZixJQUFJQyxNQUFNLEdBQUcsSUFBSTtJQUNqQixJQUFJQyw0QkFBNEIsR0FBRyxFQUFFO0lBQ3JDLElBQUlDLHdCQUF3QixHQUFHLENBQUM7SUFDaEMsSUFBSUMsd0JBQXdCLEdBQUcsSUFBSTtJQUNuQyxJQUFJQyxxQkFBcUIsR0FBRyxDQUFDO0lBQzdCLElBQUlDLHFCQUFxQixHQUFHLElBQUk7SUFDaEMsSUFBSUMsdUJBQXVCLEdBQUcsS0FBSztJQUNuQyxJQUFJQyx1QkFBdUIsR0FBRyxJQUFJO0lBQ2xDLElBQUlDLGVBQWUsR0FBRztNQUNyQkMsSUFBSSxFQUFFLEVBQUU7TUFDUkMsR0FBRyxFQUFFLEVBQUU7TUFDUEMsYUFBYSxFQUFFLEVBQUU7TUFDakJDLGtCQUFrQixFQUFFO0lBQ3JCLENBQUM7O0lBRUQ7QUFDRjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7SUFDRSxTQUFTQyxXQUFXQSxDQUFFQyxHQUFHLEVBQUVDLFFBQVEsRUFBRztNQUNyQyxPQUFPcEIsTUFBTSxDQUFDRixJQUFJLElBQUlFLE1BQU0sQ0FBQ0YsSUFBSSxDQUFFcUIsR0FBRyxDQUFFLEdBQUduQixNQUFNLENBQUNGLElBQUksQ0FBRXFCLEdBQUcsQ0FBRSxHQUFHQyxRQUFRO0lBQ3pFOztJQUVBO0FBQ0Y7QUFDQTtBQUNBO0FBQ0E7QUFDQTtJQUNFLFNBQVNDLHFCQUFxQkEsQ0FBRUMsSUFBSSxFQUFHO01BQ3RDLE9BQU9DLE1BQU0sQ0FBRUQsSUFBSSxJQUFJLEVBQUcsQ0FBQyxDQUFDRSxpQkFBaUIsQ0FBQyxDQUFDO0lBQ2hEOztJQUVBO0FBQ0Y7QUFDQTtBQUNBO0FBQ0E7QUFDQTtJQUNFLFNBQVNDLG9CQUFvQkEsQ0FBRUMsSUFBSSxFQUFHO01BQ3JDLElBQUlDLEtBQUssR0FBR0QsSUFBSSxDQUFDRSxhQUFhLENBQUUsNEJBQTZCLENBQUM7TUFDOUQsSUFBSUMsV0FBVyxHQUFHSCxJQUFJLENBQUNFLGFBQWEsQ0FBRSxrQ0FBbUMsQ0FBQztNQUUxRSxPQUFPUCxxQkFBcUIsQ0FDM0IsQ0FBRU0sS0FBSyxHQUFHQSxLQUFLLENBQUNHLFdBQVcsR0FBRyxFQUFFLElBQUssR0FBRyxJQUN0Q0QsV0FBVyxHQUFHQSxXQUFXLENBQUNDLFdBQVcsR0FBRyxFQUFFLENBQUUsR0FBRyxHQUFHLElBQ2xESixJQUFJLENBQUNLLE9BQU8sQ0FBQ0MsWUFBWSxJQUFJLEVBQUUsQ0FDbEMsQ0FBQztJQUNGOztJQUVBO0FBQ0Y7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0lBQ0UsU0FBU0MseUJBQXlCQSxDQUFFQyxLQUFLLEVBQUVDLFdBQVcsRUFBRztNQUN4RCxJQUFLLENBQUVELEtBQUssRUFBRztRQUNkO01BQ0Q7TUFFQUEsS0FBSyxDQUFDRSxnQkFBZ0IsQ0FBRSxNQUFNLEVBQUUsWUFBWTtRQUMzQ0YsS0FBSyxDQUFDRyxNQUFNLEdBQUcsS0FBSztRQUNwQixJQUFLRixXQUFXLEVBQUc7VUFDbEJBLFdBQVcsQ0FBQ0UsTUFBTSxHQUFHLElBQUk7UUFDMUI7TUFDRCxDQUFFLENBQUM7TUFDSEgsS0FBSyxDQUFDRSxnQkFBZ0IsQ0FBRSxPQUFPLEVBQUUsWUFBWTtRQUM1Q0YsS0FBSyxDQUFDRyxNQUFNLEdBQUcsSUFBSTtRQUNuQixJQUFLRixXQUFXLEVBQUc7VUFDbEJBLFdBQVcsQ0FBQ0UsTUFBTSxHQUFHLEtBQUs7UUFDM0I7TUFDRCxDQUFFLENBQUM7TUFFSCxJQUFLSCxLQUFLLENBQUNJLFFBQVEsSUFBSUosS0FBSyxDQUFDSyxHQUFHLElBQUksQ0FBQyxLQUFLTCxLQUFLLENBQUNNLFlBQVksRUFBRztRQUM5RE4sS0FBSyxDQUFDRyxNQUFNLEdBQUcsSUFBSTtRQUNuQixJQUFLRixXQUFXLEVBQUc7VUFDbEJBLFdBQVcsQ0FBQ0UsTUFBTSxHQUFHLEtBQUs7UUFDM0I7TUFDRDtJQUNEOztJQUVBO0FBQ0Y7QUFDQTtBQUNBO0FBQ0E7SUFDRSxTQUFTSSxpQkFBaUJBLENBQUEsRUFBRztNQUM1QixJQUFJQyxjQUFjLEdBQUdyQyxNQUFNLElBQUlBLE1BQU0sQ0FBQ3NDLElBQUksQ0FBQ2YsYUFBYSxDQUFFLG9DQUFxQyxDQUFDO01BRWhHLE9BQU9jLGNBQWMsR0FBR0EsY0FBYyxDQUFDRSxPQUFPLENBQUUsMkJBQTRCLENBQUMsR0FBRyxJQUFJO0lBQ3JGOztJQUVBO0FBQ0Y7QUFDQTtBQUNBO0FBQ0E7SUFDRSxTQUFTQywrQkFBK0JBLENBQUEsRUFBRztNQUMxQyxJQUFJSCxjQUFjLEdBQUdyQyxNQUFNLElBQUlBLE1BQU0sQ0FBQ3NDLElBQUksQ0FBQ2YsYUFBYSxDQUFFLDhDQUErQyxDQUFDO01BRTFHLE9BQU9jLGNBQWMsR0FBR25CLE1BQU0sQ0FBRW1CLGNBQWMsQ0FBQ0ksS0FBSyxJQUFJLEVBQUcsQ0FBQyxHQUFHLEVBQUU7SUFDbEU7O0lBRUE7QUFDRjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0lBQ0UsU0FBU0MscUJBQXFCQSxDQUFFOUIsa0JBQWtCLEVBQUc7TUFDcEQsT0FBTyxrQkFBa0IsS0FBS0Esa0JBQWtCLEdBQzdDLHVCQUF1QixHQUN2QixXQUFXO0lBQ2Y7O0lBRUE7QUFDRjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0lBQ0UsU0FBUytCLHVCQUF1QkEsQ0FBRXRCLElBQUksRUFBRztNQUN4QyxJQUFJdUIsVUFBVSxHQUFHNUMsTUFBTSxDQUFDc0MsSUFBSSxDQUFDZixhQUFhLENBQUUsb0NBQXFDLENBQUM7TUFDbEYsSUFBSXNCLFNBQVMsR0FBRzdDLE1BQU0sQ0FBQ3NDLElBQUksQ0FBQ2YsYUFBYSxDQUFFLG1DQUFvQyxDQUFDO01BQ2hGLElBQUl1QixjQUFjLEdBQUc5QyxNQUFNLENBQUNzQyxJQUFJLENBQUNmLGFBQWEsQ0FBRSx3Q0FBeUMsQ0FBQztNQUMxRixJQUFJd0IsZ0JBQWdCLEdBQUcvQyxNQUFNLENBQUNzQyxJQUFJLENBQUNmLGFBQWEsQ0FBRSwwQ0FBMkMsQ0FBQztNQUM5RixJQUFJRCxLQUFLLEdBQUdELElBQUksSUFBSUEsSUFBSSxDQUFDRSxhQUFhLENBQUUsNEJBQTZCLENBQUM7TUFDdEUsSUFBSUMsV0FBVyxHQUFHSCxJQUFJLElBQUlBLElBQUksQ0FBQ0UsYUFBYSxDQUFFLGtDQUFtQyxDQUFDO01BQ2xGLElBQUlaLGFBQWEsR0FBR1UsSUFBSSxHQUFHSCxNQUFNLENBQUVHLElBQUksQ0FBQ0ssT0FBTyxDQUFDQyxZQUFZLElBQUksRUFBRyxDQUFDLEdBQUcsRUFBRTtNQUV6RSxJQUFLaUIsVUFBVSxFQUFHO1FBQ2pCQSxVQUFVLENBQUNuQixXQUFXLEdBQUdILEtBQUssR0FBR0EsS0FBSyxDQUFDRyxXQUFXLENBQUN1QixJQUFJLENBQUMsQ0FBQyxHQUFHLEVBQUU7TUFDL0Q7TUFDQSxJQUFLSCxTQUFTLEVBQUc7UUFDaEJBLFNBQVMsQ0FBQ3BCLFdBQVcsR0FBR2QsYUFBYTtNQUN0QztNQUNBLElBQUttQyxjQUFjLEVBQUc7UUFDckJBLGNBQWMsQ0FBQ3JCLFdBQVcsR0FBR2lCLHFCQUFxQixDQUFFRiwrQkFBK0IsQ0FBQyxDQUFFLENBQUM7TUFDeEY7TUFDQSxJQUFLTyxnQkFBZ0IsRUFBRztRQUN2QkEsZ0JBQWdCLENBQUN0QixXQUFXLEdBQUdELFdBQVcsR0FBR0EsV0FBVyxDQUFDQyxXQUFXLENBQUN1QixJQUFJLENBQUMsQ0FBQyxHQUFHLEVBQUU7TUFDakY7SUFDRDs7SUFFQTtBQUNGO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtJQUNFLFNBQVNDLGlCQUFpQkEsQ0FBRUMsVUFBVSxFQUFFQyxVQUFVLEVBQUc7TUFDcEQsSUFBSUMsU0FBUyxHQUFHLEVBQUUsS0FBS2xDLE1BQU0sQ0FBRWlDLFVBQVUsSUFBSSxFQUFHLENBQUM7TUFFakRuRCxNQUFNLENBQUNxRCxhQUFhLENBQUNDLFNBQVMsQ0FBQ0MsTUFBTSxDQUFFLFlBQVksRUFBRUwsVUFBVyxDQUFDO01BQ2pFbEQsTUFBTSxDQUFDcUQsYUFBYSxDQUFDQyxTQUFTLENBQUNDLE1BQU0sQ0FBRSxXQUFXLEVBQUVILFNBQVUsQ0FBQztNQUMvRHBELE1BQU0sQ0FBQ3FELGFBQWEsQ0FBQ0csWUFBWSxDQUFFLFdBQVcsRUFBRU4sVUFBVSxHQUFHLE1BQU0sR0FBRyxPQUFRLENBQUM7TUFDL0VsRCxNQUFNLENBQUN5RCxjQUFjLENBQUN6QixNQUFNLEdBQUcsQ0FBRWtCLFVBQVU7TUFDM0NsRCxNQUFNLENBQUMwRCxhQUFhLENBQUMxQixNQUFNLEdBQUcsQ0FBRW9CLFNBQVM7TUFDekMsSUFBS0EsU0FBUyxFQUFHO1FBQ2hCcEQsTUFBTSxDQUFDMEQsYUFBYSxDQUFDbkMsYUFBYSxDQUFFLE1BQU8sQ0FBQyxDQUFDRSxXQUFXLEdBQUcwQixVQUFVO01BQ3RFO01BQ0FuRCxNQUFNLENBQUMyRCxjQUFjLENBQUNDLFFBQVEsR0FBR1YsVUFBVSxJQUFJLENBQUVkLGlCQUFpQixDQUFDLENBQUM7TUFDcEVwQyxNQUFNLENBQUM2RCxXQUFXLENBQUNELFFBQVEsR0FBR1YsVUFBVSxJQUFJNUMsdUJBQXVCLElBQUksQ0FBRThCLGlCQUFpQixDQUFDLENBQUM7SUFDN0Y7O0lBRUE7QUFDRjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtJQUNFLFNBQVMwQixtQkFBbUJBLENBQUEsRUFBRztNQUM5QjlELE1BQU0sQ0FBQytELGFBQWEsQ0FBQ0MsZUFBZSxDQUFFLDRCQUE2QixDQUFDO01BQ3BFaEUsTUFBTSxDQUFDK0QsYUFBYSxDQUFDQyxlQUFlLENBQUUsaUNBQWtDLENBQUM7TUFDekVoRSxNQUFNLENBQUMrRCxhQUFhLENBQUM3QixHQUFHLEdBQUcsYUFBYTtNQUN4Q2xDLE1BQU0sQ0FBQytELGFBQWEsQ0FBQy9CLE1BQU0sR0FBRyxJQUFJO0lBQ25DOztJQUVBO0FBQ0Y7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtJQUNFLFNBQVNpQyxtQkFBbUJBLENBQUVDLFlBQVksRUFBRUMsV0FBVyxFQUFFeEQsYUFBYSxFQUFFQyxrQkFBa0IsRUFBRztNQUM1RkosZUFBZSxHQUFHO1FBQ2pCQyxJQUFJLEVBQUV5RCxZQUFZO1FBQ2xCeEQsR0FBRyxFQUFFeUQsV0FBVztRQUNoQnhELGFBQWEsRUFBRUEsYUFBYTtRQUM1QkMsa0JBQWtCLEVBQUVBO01BQ3JCLENBQUM7SUFDRjs7SUFFQTtBQUNGO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtJQUNFLFNBQVN3RCx1QkFBdUJBLENBQUV6RCxhQUFhLEVBQUVDLGtCQUFrQixFQUFHO01BQ3JFLE9BQU9KLGVBQWUsQ0FBQ0csYUFBYSxLQUFLQSxhQUFhLElBQUlILGVBQWUsQ0FBQ0ksa0JBQWtCLEtBQUtBLGtCQUFrQjtJQUNwSDs7SUFFQTtBQUNGO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7SUFDRSxTQUFTeUQsc0JBQXNCQSxDQUFFQyxRQUFRLEVBQUc7TUFDM0MsSUFBS0EsUUFBUSxJQUFJQSxRQUFRLENBQUNDLElBQUksSUFBSUQsUUFBUSxDQUFDQyxJQUFJLENBQUNDLE9BQU8sRUFBRztRQUN6RCxPQUFPdEQsTUFBTSxDQUFFb0QsUUFBUSxDQUFDQyxJQUFJLENBQUNDLE9BQVEsQ0FBQztNQUN2QztNQUVBLE9BQU8zRCxXQUFXLENBQUUsZUFBZSxFQUFFLGlFQUFrRSxDQUFDO0lBQ3pHOztJQUVBO0FBQ0Y7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0lBQ0UsU0FBUzRELG1DQUFtQ0EsQ0FBRUgsUUFBUSxFQUFHO01BQ3hELElBQUtBLFFBQVEsSUFBSSxHQUFHLEtBQUtJLE1BQU0sQ0FBRUosUUFBUSxDQUFDSyxNQUFPLENBQUMsRUFBRztRQUNwRCxJQUFLLElBQUksS0FBS2hGLE1BQU0sQ0FBQ2lGLFlBQVksRUFBRztVQUNuQyxPQUFPL0QsV0FBVyxDQUFFLGlDQUFpQyxFQUFFLG9LQUFxSyxDQUFDO1FBQzlOO1FBRUEsT0FBT0EsV0FBVyxDQUFFLHVCQUF1QixFQUFFLDBLQUEySyxDQUFDO01BQzFOO01BRUEsT0FBT0EsV0FBVyxDQUFFLHdCQUF3QixFQUFFLHFIQUFzSCxDQUFDO0lBQ3RLOztJQUVBO0FBQ0Y7QUFDQTtBQUNBO0FBQ0E7QUFDQTtJQUNFLFNBQVNnRSxzQkFBc0JBLENBQUVQLFFBQVEsRUFBRztNQUMzQyxPQUFPQSxRQUFRLENBQUNyRCxJQUFJLENBQUMsQ0FBQyxDQUFDNkQsSUFBSSxDQUFFLFVBQVdDLGFBQWEsRUFBRztRQUN2RCxJQUFJO1VBQ0gsT0FBT0MsSUFBSSxDQUFDQyxLQUFLLENBQUVGLGFBQWMsQ0FBQztRQUNuQyxDQUFDLENBQUMsT0FBUUcsV0FBVyxFQUFHO1VBQ3ZCLE1BQU0sSUFBSUMsS0FBSyxDQUFFVixtQ0FBbUMsQ0FBRUgsUUFBUyxDQUFFLENBQUM7UUFDbkU7TUFDRCxDQUFFLENBQUM7SUFDSjs7SUFFQTtBQUNGO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0lBQ0UsU0FBU2MsMEJBQTBCQSxDQUFFekUsYUFBYSxFQUFFQyxrQkFBa0IsRUFBRXlFLGNBQWMsRUFBRztNQUN4RixJQUFJQyxjQUFjLEdBQUc7UUFDcEJDLE1BQU0sRUFBRTVGLE1BQU0sQ0FBQzZGLGNBQWM7UUFDN0JDLEtBQUssRUFBRTlGLE1BQU0sQ0FBQzhGLEtBQUssSUFBSSxFQUFFO1FBQ3pCOUUsYUFBYSxFQUFFQSxhQUFhO1FBQzVCQyxrQkFBa0IsRUFBRUEsa0JBQWtCO1FBQ3RDeUUsY0FBYyxFQUFFQTtNQUNqQixDQUFDO01BRUQsT0FBT0ssTUFBTSxDQUFDQyxJQUFJLENBQUVMLGNBQWUsQ0FBQyxDQUFDTSxHQUFHLENBQUUsVUFBV0MsVUFBVSxFQUFHO1FBQ2pFLE9BQU94RyxNQUFNLENBQUN5RyxrQkFBa0IsQ0FBRUQsVUFBVyxDQUFDLEdBQUcsR0FBRyxHQUFHeEcsTUFBTSxDQUFDeUcsa0JBQWtCLENBQUU1RSxNQUFNLENBQUVvRSxjQUFjLENBQUVPLFVBQVUsQ0FBRyxDQUFFLENBQUM7TUFDM0gsQ0FBRSxDQUFDLENBQUNFLElBQUksQ0FBRSxHQUFJLENBQUM7SUFDaEI7O0lBRUE7QUFDRjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7SUFDRSxTQUFTQyx5QkFBeUJBLENBQUUxQixRQUFRLEVBQUUzRCxhQUFhLEVBQUVDLGtCQUFrQixFQUFFeUUsY0FBYyxFQUFHO01BQ2pHLElBQUlZLGFBQWE7TUFDakIsSUFBSUMsYUFBYSxHQUFHOUQsaUJBQWlCLENBQUMsQ0FBQztNQUV2QyxJQUFLLENBQUVrQyxRQUFRLElBQUksQ0FBRUEsUUFBUSxDQUFDNkIsT0FBTyxJQUFJLENBQUU3QixRQUFRLENBQUNDLElBQUksRUFBRztRQUMxRCxNQUFNLElBQUlZLEtBQUssQ0FBRWQsc0JBQXNCLENBQUVDLFFBQVMsQ0FBRSxDQUFDO01BQ3REO01BRUEyQixhQUFhLEdBQUczQixRQUFRLENBQUNDLElBQUk7TUFDN0IsSUFDQ3JELE1BQU0sQ0FBRStFLGFBQWEsQ0FBQ3RGLGFBQWEsSUFBSSxFQUFHLENBQUMsS0FBS0EsYUFBYSxJQUM3RE8sTUFBTSxDQUFFK0UsYUFBYSxDQUFDckYsa0JBQWtCLElBQUksRUFBRyxDQUFDLEtBQUtBLGtCQUFrQixJQUN2RU0sTUFBTSxDQUFFK0UsYUFBYSxDQUFDWixjQUFjLElBQUksRUFBRyxDQUFDLEtBQUtBLGNBQWMsSUFDL0QsQ0FBRWEsYUFBYSxJQUNmaEYsTUFBTSxDQUFFZ0YsYUFBYSxDQUFDeEUsT0FBTyxDQUFDQyxZQUFZLElBQUksRUFBRyxDQUFDLEtBQUtoQixhQUFhLElBQ3BFNkIsK0JBQStCLENBQUMsQ0FBQyxLQUFLNUIsa0JBQWtCLEVBQ3ZEO1FBQ0QsTUFBTSxJQUFJdUUsS0FBSyxDQUFFdEUsV0FBVyxDQUFFLGVBQWUsRUFBRSxpRUFBa0UsQ0FBRSxDQUFDO01BQ3JIO01BRUEsT0FBT29GLGFBQWE7SUFDckI7O0lBRUE7QUFDRjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0lBQ0UsU0FBU0csWUFBWUEsQ0FBRS9FLElBQUksRUFBRztNQUM3QixJQUFJVixhQUFhLEdBQUdVLElBQUksR0FBR0gsTUFBTSxDQUFFRyxJQUFJLENBQUNLLE9BQU8sQ0FBQ0MsWUFBWSxJQUFJLEVBQUcsQ0FBQyxHQUFHLEVBQUU7TUFDekUsSUFBSWYsa0JBQWtCLEdBQUc0QiwrQkFBK0IsQ0FBQyxDQUFDO01BQzFELElBQUk2RCxnQkFBZ0I7TUFDcEIsSUFBSUMsWUFBWTtNQUVoQixJQUFLLENBQUUzRixhQUFhLElBQUksQ0FBRUMsa0JBQWtCLElBQUksQ0FBRWpCLE1BQU0sQ0FBQzRHLFFBQVEsSUFBSSxDQUFFNUcsTUFBTSxDQUFDNkYsY0FBYyxFQUFHO1FBQzlGdkMsaUJBQWlCLENBQUUsS0FBSyxFQUFFcEMsV0FBVyxDQUFFLGVBQWUsRUFBRSxpRUFBa0UsQ0FBRSxDQUFDO1FBQzdIO01BQ0Q7TUFFQSxJQUFLVix3QkFBd0IsSUFBSSxVQUFVLEtBQUssT0FBT0Esd0JBQXdCLENBQUNxRyxLQUFLLEVBQUc7UUFDdkZyRyx3QkFBd0IsQ0FBQ3FHLEtBQUssQ0FBQyxDQUFDO01BQ2pDO01BQ0FyRyx3QkFBd0IsR0FBRyxVQUFVLEtBQUssT0FBT2QsTUFBTSxDQUFDb0gsZUFBZSxHQUFHLElBQUlwSCxNQUFNLENBQUNvSCxlQUFlLENBQUMsQ0FBQyxHQUFHLElBQUk7TUFDN0dKLGdCQUFnQixHQUFHLEVBQUVuRyx3QkFBd0I7TUFDN0NvRyxZQUFZLEdBQUdsQiwwQkFBMEIsQ0FBRXpFLGFBQWEsRUFBRUMsa0JBQWtCLEVBQUUsVUFBVyxDQUFDO01BRTFGcUMsaUJBQWlCLENBQUUsSUFBSSxFQUFFLEVBQUcsQ0FBQztNQUU3QjVELE1BQU0sQ0FBQ3FILEtBQUssQ0FBRS9HLE1BQU0sQ0FBQzRHLFFBQVEsRUFBRTtRQUM5QkksTUFBTSxFQUFFLE1BQU07UUFDZEMsT0FBTyxFQUFFO1VBQ1IsY0FBYyxFQUFFO1FBQ2pCLENBQUM7UUFDREMsSUFBSSxFQUFFUCxZQUFZO1FBQ2xCUSxXQUFXLEVBQUUsYUFBYTtRQUMxQkMsTUFBTSxFQUFFNUcsd0JBQXdCLEdBQUdBLHdCQUF3QixDQUFDNEcsTUFBTSxHQUFHQztNQUN0RSxDQUFFLENBQUMsQ0FBQ2xDLElBQUksQ0FBRSxVQUFXUixRQUFRLEVBQUc7UUFDL0IsT0FBT08sc0JBQXNCLENBQUVQLFFBQVMsQ0FBQztNQUMxQyxDQUFFLENBQUMsQ0FBQ1EsSUFBSSxDQUFFLFVBQVdSLFFBQVEsRUFBRztRQUMvQixJQUFJMkIsYUFBYTtRQUNqQixJQUFJL0IsWUFBWTtRQUNoQixJQUFJQyxXQUFXO1FBRWYsSUFBS2tDLGdCQUFnQixLQUFLbkcsd0JBQXdCLEVBQUc7VUFDcEQ7UUFDRDtRQUVBK0YsYUFBYSxHQUFHRCx5QkFBeUIsQ0FBRTFCLFFBQVEsRUFBRTNELGFBQWEsRUFBRUMsa0JBQWtCLEVBQUUsVUFBVyxDQUFDO1FBQ3BHc0QsWUFBWSxHQUFHaEQsTUFBTSxDQUFFK0UsYUFBYSxDQUFDL0IsWUFBWSxJQUFJLEVBQUcsQ0FBQztRQUV6RCxJQUNDLFFBQVEsS0FBS0EsWUFBWSxJQUN6QitCLGFBQWEsQ0FBQ2dCLElBQUksSUFDbEJoQixhQUFhLENBQUNpQixTQUFTLElBQ3ZCLFFBQVEsS0FBSyxPQUFPakIsYUFBYSxDQUFDaUIsU0FBUyxJQUMzQzNHLHVCQUF1QixFQUN0QjtVQUNELE9BQU9BLHVCQUF1QixDQUFDNEcsT0FBTyxDQUFFakcsTUFBTSxDQUFFK0UsYUFBYSxDQUFDZ0IsSUFBSyxDQUFDLEVBQUVoQixhQUFhLENBQUNpQixTQUFTLEVBQUUsWUFBWTtZQUMxRyxPQUFPYixnQkFBZ0IsS0FBS25HLHdCQUF3QjtVQUNyRCxDQUFFLENBQUMsQ0FBQzRFLElBQUksQ0FBRSxZQUFZO1lBQ3JCLElBQUt1QixnQkFBZ0IsS0FBS25HLHdCQUF3QixFQUFHO2NBQ3BEO1lBQ0Q7WUFDQTRELG1CQUFtQixDQUFDLENBQUM7WUFDckI5RCxNQUFNLENBQUNvSCxjQUFjLENBQUNwRixNQUFNLEdBQUcsS0FBSztZQUNwQ2lDLG1CQUFtQixDQUFFLFFBQVEsRUFBRSxFQUFFLEVBQUV0RCxhQUFhLEVBQUVDLGtCQUFtQixDQUFDO1lBQ3RFcUMsaUJBQWlCLENBQUUsS0FBSyxFQUFFLEVBQUcsQ0FBQztVQUMvQixDQUFFLENBQUM7UUFDSjtRQUVBLElBQUssS0FBSyxLQUFLaUIsWUFBWSxJQUFJLENBQUUrQixhQUFhLENBQUM5QixXQUFXLEVBQUc7VUFDNUQsTUFBTSxJQUFJZ0IsS0FBSyxDQUFFdEUsV0FBVyxDQUFFLGVBQWUsRUFBRSxpRUFBa0UsQ0FBRSxDQUFDO1FBQ3JIO1FBRUFzRCxXQUFXLEdBQUdqRCxNQUFNLENBQUUrRSxhQUFhLENBQUM5QixXQUFZLENBQUM7UUFDakRuRSxNQUFNLENBQUMrRCxhQUFhLENBQUNzRCxNQUFNLEdBQUcsWUFBWTtVQUN6QyxJQUFLaEIsZ0JBQWdCLEtBQUtuRyx3QkFBd0IsRUFBRztZQUNwRDtVQUNEO1VBQ0FGLE1BQU0sQ0FBQ29ILGNBQWMsQ0FBQ3BGLE1BQU0sR0FBRyxJQUFJO1VBQ25DaEMsTUFBTSxDQUFDK0QsYUFBYSxDQUFDL0IsTUFBTSxHQUFHLEtBQUs7VUFDbkNpQyxtQkFBbUIsQ0FBRSxLQUFLLEVBQUVFLFdBQVcsRUFBRXhELGFBQWEsRUFBRUMsa0JBQW1CLENBQUM7VUFDNUVxQyxpQkFBaUIsQ0FBRSxLQUFLLEVBQUUsRUFBRyxDQUFDO1FBQy9CLENBQUM7UUFDRGpELE1BQU0sQ0FBQytELGFBQWEsQ0FBQ1AsWUFBWSxDQUFFLDRCQUE0QixFQUFFN0MsYUFBYyxDQUFDO1FBQ2hGWCxNQUFNLENBQUMrRCxhQUFhLENBQUNQLFlBQVksQ0FBRSxpQ0FBaUMsRUFBRTVDLGtCQUFtQixDQUFDO1FBQzFGWixNQUFNLENBQUMrRCxhQUFhLENBQUM3QixHQUFHLEdBQUdpQyxXQUFXO01BQ3ZDLENBQUUsQ0FBQyxDQUFDbUQsS0FBSyxDQUFFLFVBQVdDLEtBQUssRUFBRztRQUM3QixJQUFLQSxLQUFLLElBQUksWUFBWSxLQUFLQSxLQUFLLENBQUNDLElBQUksRUFBRztVQUMzQztRQUNEO1FBQ0EsSUFBS25CLGdCQUFnQixLQUFLbkcsd0JBQXdCLEVBQUc7VUFDcEQ7UUFDRDtRQUNBK0MsaUJBQWlCLENBQUUsS0FBSyxFQUFFc0UsS0FBSyxJQUFJQSxLQUFLLENBQUMvQyxPQUFPLEdBQUcrQyxLQUFLLENBQUMvQyxPQUFPLEdBQUczRCxXQUFXLENBQUUsZUFBZSxFQUFFLGlFQUFrRSxDQUFFLENBQUM7TUFDdkssQ0FBRSxDQUFDO0lBQ0o7O0lBRUE7QUFDRjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0lBQ0UsU0FBUzRHLFlBQVlBLENBQUVwRyxJQUFJLEVBQUc7TUFDN0IsSUFBSVYsYUFBYSxHQUFHVSxJQUFJLEdBQUdILE1BQU0sQ0FBRUcsSUFBSSxDQUFDSyxPQUFPLENBQUNDLFlBQVksSUFBSSxFQUFHLENBQUMsR0FBRyxFQUFFO01BQ3pFLElBQUlmLGtCQUFrQixHQUFHNEIsK0JBQStCLENBQUMsQ0FBQztNQUMxRCxJQUFJa0YsY0FBYztNQUNsQixJQUFJckIsZ0JBQWdCO01BRXBCLElBQUssQ0FBRTFGLGFBQWEsSUFBSSxDQUFFQyxrQkFBa0IsSUFBSVosTUFBTSxDQUFDNkQsV0FBVyxDQUFDRCxRQUFRLEVBQUc7UUFDN0U7TUFDRDtNQUVBLElBQUtRLHVCQUF1QixDQUFFekQsYUFBYSxFQUFFQyxrQkFBbUIsQ0FBQyxJQUFJSixlQUFlLENBQUNFLEdBQUcsRUFBRztRQUMxRnJCLE1BQU0sQ0FBQ3NJLElBQUksQ0FBRW5ILGVBQWUsQ0FBQ0UsR0FBRyxFQUFFLFFBQVEsRUFBRSxxQkFBc0IsQ0FBQztRQUNuRTtNQUNEO01BRUFnSCxjQUFjLEdBQUdySSxNQUFNLENBQUNzSSxJQUFJLENBQUUsRUFBRSxFQUFFLFFBQVMsQ0FBQztNQUM1QyxJQUFLLENBQUVELGNBQWMsRUFBRztRQUN2QnpFLGlCQUFpQixDQUFFLEtBQUssRUFBRXBDLFdBQVcsQ0FBRSxvQkFBb0IsRUFBRSx1REFBd0QsQ0FBRSxDQUFDO1FBQ3hIO01BQ0Q7TUFDQTZHLGNBQWMsQ0FBQ0UsTUFBTSxHQUFHLElBQUk7TUFFNUIsSUFBS3ZILHFCQUFxQixJQUFJLFVBQVUsS0FBSyxPQUFPQSxxQkFBcUIsQ0FBQ21HLEtBQUssRUFBRztRQUNqRm5HLHFCQUFxQixDQUFDbUcsS0FBSyxDQUFDLENBQUM7TUFDOUI7TUFDQW5HLHFCQUFxQixHQUFHLFVBQVUsS0FBSyxPQUFPaEIsTUFBTSxDQUFDb0gsZUFBZSxHQUFHLElBQUlwSCxNQUFNLENBQUNvSCxlQUFlLENBQUMsQ0FBQyxHQUFHLElBQUk7TUFDMUdKLGdCQUFnQixHQUFHLEVBQUVqRyxxQkFBcUI7TUFDMUNFLHVCQUF1QixHQUFHLElBQUk7TUFDOUJOLE1BQU0sQ0FBQzZELFdBQVcsQ0FBQ0QsUUFBUSxHQUFHLElBQUk7TUFFbEN2RSxNQUFNLENBQUNxSCxLQUFLLENBQUUvRyxNQUFNLENBQUM0RyxRQUFRLEVBQUU7UUFDOUJJLE1BQU0sRUFBRSxNQUFNO1FBQ2RDLE9BQU8sRUFBRTtVQUNSLGNBQWMsRUFBRTtRQUNqQixDQUFDO1FBQ0RDLElBQUksRUFBRXpCLDBCQUEwQixDQUFFekUsYUFBYSxFQUFFQyxrQkFBa0IsRUFBRSxRQUFTLENBQUM7UUFDL0VrRyxXQUFXLEVBQUUsYUFBYTtRQUMxQkMsTUFBTSxFQUFFMUcscUJBQXFCLEdBQUdBLHFCQUFxQixDQUFDMEcsTUFBTSxHQUFHQztNQUNoRSxDQUFFLENBQUMsQ0FBQ2xDLElBQUksQ0FBRSxVQUFXUixRQUFRLEVBQUc7UUFDL0IsT0FBT08sc0JBQXNCLENBQUVQLFFBQVMsQ0FBQztNQUMxQyxDQUFFLENBQUMsQ0FBQ1EsSUFBSSxDQUFFLFVBQVdSLFFBQVEsRUFBRztRQUMvQixJQUFJMkIsYUFBYTtRQUVqQixJQUFLSSxnQkFBZ0IsS0FBS2pHLHFCQUFxQixFQUFHO1VBQ2pEO1FBQ0Q7UUFDQTZGLGFBQWEsR0FBR0QseUJBQXlCLENBQUUxQixRQUFRLEVBQUUzRCxhQUFhLEVBQUVDLGtCQUFrQixFQUFFLFFBQVMsQ0FBQztRQUNsRyxJQUFLLEtBQUssS0FBS00sTUFBTSxDQUFFK0UsYUFBYSxDQUFDL0IsWUFBWSxJQUFJLEVBQUcsQ0FBQyxJQUFJLENBQUUrQixhQUFhLENBQUM5QixXQUFXLEVBQUc7VUFDMUYsTUFBTSxJQUFJZ0IsS0FBSyxDQUFFdEUsV0FBVyxDQUFFLG9CQUFvQixFQUFFLHVEQUF3RCxDQUFFLENBQUM7UUFDaEg7UUFFQTZHLGNBQWMsQ0FBQ0csUUFBUSxDQUFDVixPQUFPLENBQUVqRyxNQUFNLENBQUUrRSxhQUFhLENBQUM5QixXQUFZLENBQUUsQ0FBQztNQUN2RSxDQUFFLENBQUMsQ0FBQ21ELEtBQUssQ0FBRSxVQUFXQyxLQUFLLEVBQUc7UUFDN0IsSUFBS0EsS0FBSyxJQUFJLFlBQVksS0FBS0EsS0FBSyxDQUFDQyxJQUFJLEVBQUc7VUFDM0NFLGNBQWMsQ0FBQ0ksS0FBSyxDQUFDLENBQUM7VUFDdEI7UUFDRDtRQUNBLElBQUt6QixnQkFBZ0IsS0FBS2pHLHFCQUFxQixFQUFHO1VBQ2pEO1FBQ0Q7UUFDQXNILGNBQWMsQ0FBQ0ksS0FBSyxDQUFDLENBQUM7UUFDdEI3RSxpQkFBaUIsQ0FBRSxLQUFLLEVBQUVzRSxLQUFLLElBQUlBLEtBQUssQ0FBQy9DLE9BQU8sR0FBRytDLEtBQUssQ0FBQy9DLE9BQU8sR0FBRzNELFdBQVcsQ0FBRSxvQkFBb0IsRUFBRSx1REFBd0QsQ0FBRSxDQUFDO01BQ2xLLENBQUUsQ0FBQyxDQUFDaUUsSUFBSSxDQUFFLFlBQVk7UUFDckIsSUFBS3VCLGdCQUFnQixLQUFLakcscUJBQXFCLEVBQUc7VUFDakRFLHVCQUF1QixHQUFHLEtBQUs7VUFDL0JOLE1BQU0sQ0FBQzZELFdBQVcsQ0FBQ0QsUUFBUSxHQUFHLENBQUV4QixpQkFBaUIsQ0FBQyxDQUFDO1FBQ3BEO01BQ0QsQ0FBRSxDQUFDO0lBQ0o7O0lBRUE7QUFDRjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7SUFDRSxTQUFTMkYsV0FBV0EsQ0FBRTFHLElBQUksRUFBRTJHLGFBQWEsRUFBRztNQUMzQyxJQUFJQyxLQUFLLEdBQUc1RyxJQUFJLElBQUlBLElBQUksQ0FBQ0UsYUFBYSxDQUFFLDRCQUE2QixDQUFDO01BRXRFLElBQUssQ0FBRTBHLEtBQUssRUFBRztRQUNkO01BQ0Q7TUFFQUEsS0FBSyxDQUFDQyxPQUFPLEdBQUcsSUFBSTtNQUNwQmxJLE1BQU0sQ0FBQ21JLEtBQUssQ0FBQ0MsT0FBTyxDQUFFLFVBQVdDLGFBQWEsRUFBRztRQUNoREEsYUFBYSxDQUFDL0UsU0FBUyxDQUFDQyxNQUFNLENBQUUsYUFBYSxFQUFFOEUsYUFBYSxLQUFLaEgsSUFBSyxDQUFDO01BQ3hFLENBQUUsQ0FBQztNQUNIdkIsU0FBUyxDQUFDd0ksaUJBQWlCLENBQUUsdUJBQXdCLENBQUM7TUFDdEQzRix1QkFBdUIsQ0FBRXRCLElBQUssQ0FBQztNQUMvQixJQUFLLEtBQUssS0FBSzJHLGFBQWEsRUFBRztRQUM5QjVCLFlBQVksQ0FBRS9FLElBQUssQ0FBQztNQUNyQjtJQUNEOztJQUVBO0FBQ0Y7QUFDQTtBQUNBO0FBQ0E7SUFDRSxTQUFTa0gsa0JBQWtCQSxDQUFBLEVBQUc7TUFDN0IsSUFBSUMsS0FBSyxHQUFHeEgscUJBQXFCLENBQUVoQixNQUFNLENBQUN5SSxNQUFNLEdBQUd6SSxNQUFNLENBQUN5SSxNQUFNLENBQUNoRyxLQUFLLENBQUNPLElBQUksQ0FBQyxDQUFDLEdBQUcsRUFBRyxDQUFDO01BRXBGLE9BQU9oRCxNQUFNLENBQUNtSSxLQUFLLENBQUNPLE1BQU0sQ0FBRSxVQUFXckgsSUFBSSxFQUFHO1FBQzdDLElBQUlzSCxjQUFjLEdBQUcsS0FBSyxLQUFLM0ksTUFBTSxDQUFDNEksYUFBYSxJQUNoRCxhQUFhLEtBQUs1SSxNQUFNLENBQUM0SSxhQUFhLElBQUksTUFBTSxLQUFLdkgsSUFBSSxDQUFDSyxPQUFPLENBQUNtSCxtQkFBcUIsSUFDekY3SSxNQUFNLENBQUM0SSxhQUFhLEtBQUt2SCxJQUFJLENBQUNLLE9BQU8sQ0FBQ29ILGdCQUFnQjtRQUN2RCxJQUFJQyxjQUFjLEdBQUcsQ0FBRVAsS0FBSyxJQUFJLENBQUMsQ0FBQyxLQUFLcEgsb0JBQW9CLENBQUVDLElBQUssQ0FBQyxDQUFDMkgsT0FBTyxDQUFFUixLQUFNLENBQUM7UUFFcEYsT0FBT0csY0FBYyxJQUFJSSxjQUFjO01BQ3hDLENBQUUsQ0FBQztJQUNKOztJQUVBO0FBQ0Y7QUFDQTtBQUNBO0FBQ0E7SUFDRSxTQUFTRSxjQUFjQSxDQUFBLEVBQUc7TUFDekIsSUFBSUMsY0FBYyxHQUFHWCxrQkFBa0IsQ0FBQyxDQUFDO01BRXpDdkksTUFBTSxDQUFDbUksS0FBSyxDQUFDQyxPQUFPLENBQUUsVUFBVy9HLElBQUksRUFBRztRQUN2Q0EsSUFBSSxDQUFDVyxNQUFNLEdBQUcsSUFBSTtNQUNuQixDQUFFLENBQUM7TUFDSGtILGNBQWMsQ0FBQ2QsT0FBTyxDQUFFLFVBQVcvRyxJQUFJLEVBQUc7UUFDekNBLElBQUksQ0FBQ1csTUFBTSxHQUFHLEtBQUs7TUFDcEIsQ0FBRSxDQUFDO01BRUhoQyxNQUFNLENBQUNtSixLQUFLLENBQUNuSCxNQUFNLEdBQUcsQ0FBQyxLQUFLa0gsY0FBYyxDQUFDRSxNQUFNO01BQ2pEcEosTUFBTSxDQUFDbUosS0FBSyxDQUFDMUgsV0FBVyxHQUFHWixXQUFXLENBQUUsdUJBQXVCLEVBQUUsNENBQTZDLENBQUM7TUFDL0diLE1BQU0sQ0FBQ3FKLElBQUksQ0FBQ0MsVUFBVSxHQUFHLENBQUM7SUFDM0I7O0lBRUE7QUFDRjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0lBQ0UsU0FBU0MsZUFBZUEsQ0FBRUMsR0FBRyxFQUFHO01BQy9CLElBQUssQ0FBRUEsR0FBRyxFQUFHO1FBQ1o7TUFDRDtNQUVBeEosTUFBTSxDQUFDNEksYUFBYSxHQUFHWSxHQUFHLENBQUM5SCxPQUFPLENBQUMrSCxrQkFBa0IsSUFBSSxLQUFLO01BQzlEekosTUFBTSxDQUFDMEosSUFBSSxDQUFDdEIsT0FBTyxDQUFFLFVBQVd1QixVQUFVLEVBQUc7UUFDNUMsSUFBSUMsU0FBUyxHQUFHRCxVQUFVLEtBQUtILEdBQUc7UUFDbENHLFVBQVUsQ0FBQ3JHLFNBQVMsQ0FBQ0MsTUFBTSxDQUFFLFdBQVcsRUFBRXFHLFNBQVUsQ0FBQztRQUNyREQsVUFBVSxDQUFDbkcsWUFBWSxDQUFFLGVBQWUsRUFBRW9HLFNBQVMsR0FBRyxNQUFNLEdBQUcsT0FBUSxDQUFDO1FBQ3hFRCxVQUFVLENBQUNFLFFBQVEsR0FBR0QsU0FBUyxHQUFHLENBQUMsR0FBRyxDQUFDLENBQUM7TUFDekMsQ0FBRSxDQUFDO01BQ0g1SixNQUFNLENBQUNxSixJQUFJLENBQUM3RixZQUFZLENBQUUsaUJBQWlCLEVBQUVnRyxHQUFHLENBQUNNLEVBQUcsQ0FBQztNQUNyRGIsY0FBYyxDQUFDLENBQUM7SUFDakI7O0lBRUE7QUFDRjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0lBQ0UsU0FBU2MsY0FBY0EsQ0FBRUMsU0FBUyxFQUFHO01BQ3BDLE9BQU9oSyxNQUFNLENBQUMwSixJQUFJLENBQUNPLElBQUksQ0FBRSxVQUFXVCxHQUFHLEVBQUc7UUFDekMsT0FBT1EsU0FBUyxLQUFLUixHQUFHLENBQUM5SCxPQUFPLENBQUMrSCxrQkFBa0I7TUFDcEQsQ0FBRSxDQUFDLElBQUksSUFBSTtJQUNaOztJQUVBO0FBQ0Y7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtJQUNFLFNBQVNTLG9CQUFvQkEsQ0FBQSxFQUFHO01BQy9CLElBQUkxQixLQUFLLEdBQUd4SCxxQkFBcUIsQ0FBRWhCLE1BQU0sQ0FBQ3lJLE1BQU0sR0FBR3pJLE1BQU0sQ0FBQ3lJLE1BQU0sQ0FBQ2hHLEtBQUssQ0FBQ08sSUFBSSxDQUFDLENBQUMsR0FBRyxFQUFHLENBQUM7TUFDcEYsSUFBSW1ILGlCQUFpQixHQUFHSixjQUFjLENBQUUsS0FBTSxDQUFDO01BRS9DLElBQUt2QixLQUFLLElBQUkyQixpQkFBaUIsSUFBSSxLQUFLLEtBQUtuSyxNQUFNLENBQUM0SSxhQUFhLEVBQUc7UUFDbkVXLGVBQWUsQ0FBRVksaUJBQWtCLENBQUM7UUFDcEM7TUFDRDtNQUVBbEIsY0FBYyxDQUFDLENBQUM7SUFDakI7O0lBRUE7QUFDRjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0lBQ0UsU0FBU21CLFlBQVlBLENBQUVDLEtBQUssRUFBRztNQUM5QixJQUFJM0IsTUFBTSxHQUFHMkIsS0FBSyxDQUFDQyxNQUFNLENBQUMvSCxPQUFPLENBQUUsNkJBQThCLENBQUM7TUFDbEUsSUFBSW9CLGNBQWMsR0FBRzBHLEtBQUssQ0FBQ0MsTUFBTSxDQUFDL0gsT0FBTyxDQUFFLDhCQUErQixDQUFDO01BQzNFLElBQUlnSSxTQUFTLEdBQUdGLEtBQUssQ0FBQ0MsTUFBTSxDQUFDL0gsT0FBTyxDQUFFLG1DQUFvQyxDQUFDO01BQzNFLElBQUk4RixhQUFhLEdBQUdnQyxLQUFLLENBQUNDLE1BQU0sQ0FBQy9ILE9BQU8sQ0FBRSwyQkFBNEIsQ0FBQztNQUN2RSxJQUFJaUksY0FBYztNQUVsQixJQUFLOUIsTUFBTSxFQUFHO1FBQ2JhLGVBQWUsQ0FBRWIsTUFBTyxDQUFDO1FBQ3pCO01BQ0Q7TUFDQSxJQUFLL0UsY0FBYyxFQUFHO1FBQ3JCeUMsWUFBWSxDQUFFaEUsaUJBQWlCLENBQUMsQ0FBRSxDQUFDO1FBQ25DO01BQ0Q7TUFDQSxJQUFLbUksU0FBUyxFQUFHO1FBQ2hCOUMsWUFBWSxDQUFFckYsaUJBQWlCLENBQUMsQ0FBRSxDQUFDO1FBQ25DO01BQ0Q7TUFDQSxJQUFLaUcsYUFBYSxFQUFHO1FBQ3BCbUMsY0FBYyxHQUFHbkMsYUFBYSxDQUFDOUcsYUFBYSxDQUFFLDRCQUE2QixDQUFDO1FBQzVFLElBQUtpSixjQUFjLElBQUksQ0FBRUEsY0FBYyxDQUFDdEMsT0FBTyxFQUFHO1VBQ2pEbUMsS0FBSyxDQUFDSSxjQUFjLENBQUMsQ0FBQztVQUN0QjFDLFdBQVcsQ0FBRU0sYUFBYSxFQUFFLElBQUssQ0FBQztVQUNsQ21DLGNBQWMsQ0FBQ0UsS0FBSyxDQUFDLENBQUM7UUFDdkI7UUFDQTtNQUNEO0lBQ0Q7O0lBRUE7QUFDRjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0lBQ0UsU0FBU0MseUJBQXlCQSxDQUFFL0osa0JBQWtCLEVBQUc7TUFDeEQsSUFBSXlCLGNBQWMsR0FBRyxJQUFJO01BRXpCdUksS0FBSyxDQUFDQyxTQUFTLENBQUNDLEtBQUssQ0FBQ0MsSUFBSSxDQUFFL0ssTUFBTSxDQUFDc0MsSUFBSSxDQUFDMEksZ0JBQWdCLENBQUUsc0NBQXVDLENBQUUsQ0FBQyxDQUFDNUMsT0FBTyxDQUFFLFVBQVc2QyxXQUFXLEVBQUc7UUFDdEksSUFBSUMsV0FBVyxHQUFHdEssa0JBQWtCLEtBQUtNLE1BQU0sQ0FBRStKLFdBQVcsQ0FBQ3hJLEtBQUssSUFBSSxFQUFHLENBQUM7UUFFMUV3SSxXQUFXLENBQUMvQyxPQUFPLEdBQUdnRCxXQUFXO1FBQ2pDRCxXQUFXLENBQUMxSSxPQUFPLENBQUUsK0NBQWdELENBQUMsQ0FBQ2UsU0FBUyxDQUFDQyxNQUFNLENBQUUsYUFBYSxFQUFFMkgsV0FBWSxDQUFDO1FBQ3JILElBQUtBLFdBQVcsRUFBRztVQUNsQjdJLGNBQWMsR0FBRzRJLFdBQVc7UUFDN0I7TUFDRCxDQUFFLENBQUM7TUFFSCxPQUFPNUksY0FBYztJQUN0Qjs7SUFFQTtBQUNGO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7SUFDRSxTQUFTOEkseUJBQXlCQSxDQUFFdkssa0JBQWtCLEVBQUc7TUFDeEQsSUFBSyxDQUFFK0oseUJBQXlCLENBQUUvSixrQkFBbUIsQ0FBQyxFQUFHO1FBQ3hEO01BQ0Q7TUFFQVgsNEJBQTRCLEdBQUdXLGtCQUFrQjtNQUNqRGQsU0FBUyxDQUFDd0ksaUJBQWlCLENBQUUsb0JBQXFCLENBQUM7TUFDbkQzRix1QkFBdUIsQ0FBRVAsaUJBQWlCLENBQUMsQ0FBRSxDQUFDO01BQzlDZ0UsWUFBWSxDQUFFaEUsaUJBQWlCLENBQUMsQ0FBRSxDQUFDO0lBQ3BDOztJQUVBO0FBQ0Y7QUFDQTtBQUNBO0FBQ0E7QUFDQTtJQUNFLFNBQVNnSixvQkFBb0JBLENBQUVmLEtBQUssRUFBRztNQUN0QyxJQUFJZ0IsWUFBWSxHQUFHaEIsS0FBSyxDQUFDaUIsTUFBTSxJQUFJLENBQUMsQ0FBQztNQUVyQyxJQUFLMUwsOEJBQThCLEtBQUt5TCxZQUFZLENBQUM5RixNQUFNLEVBQUc7UUFDN0Q7TUFDRDtNQUVBLElBQUssU0FBUyxLQUFLOEYsWUFBWSxDQUFDRSxPQUFPLEVBQUc7UUFDekNKLHlCQUF5QixDQUFFLGtCQUFtQixDQUFDO1FBQy9DO01BQ0Q7TUFFQVIseUJBQXlCLENBQUUxSyw0QkFBNkIsQ0FBQztJQUMxRDs7SUFFQTtBQUNGO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7SUFDRSxTQUFTdUwsYUFBYUEsQ0FBRW5CLEtBQUssRUFBRztNQUMvQixJQUFLQSxLQUFLLENBQUNDLE1BQU0sQ0FBQ21CLE9BQU8sQ0FBRSw0QkFBNkIsQ0FBQyxFQUFHO1FBQzNEMUQsV0FBVyxDQUFFc0MsS0FBSyxDQUFDQyxNQUFNLENBQUMvSCxPQUFPLENBQUUsMkJBQTRCLENBQUMsRUFBRSxJQUFLLENBQUM7UUFDeEU7TUFDRDtNQUVBLElBQUs4SCxLQUFLLENBQUNDLE1BQU0sQ0FBQ21CLE9BQU8sQ0FBRSxzQ0FBdUMsQ0FBQyxFQUFHO1FBQ3JFZCx5QkFBeUIsQ0FBRXpKLE1BQU0sQ0FBRW1KLEtBQUssQ0FBQ0MsTUFBTSxDQUFDN0gsS0FBSyxJQUFJLEVBQUcsQ0FBRSxDQUFDO1FBQy9EM0MsU0FBUyxDQUFDd0ksaUJBQWlCLENBQUUsb0JBQXFCLENBQUM7UUFFbkQsSUFDQyxrQkFBa0IsS0FBSytCLEtBQUssQ0FBQ0MsTUFBTSxDQUFDN0gsS0FBSyxJQUN6QyxrQkFBa0IsS0FBS3hDLDRCQUE0QixJQUNuRCxVQUFVLEtBQUssT0FBT0gsU0FBUyxDQUFDNEwsV0FBVyxFQUMxQztVQUNELElBQUs1TCxTQUFTLENBQUM0TCxXQUFXLENBQUU5TCw4QkFBOEIsRUFBRXlLLEtBQUssQ0FBQ0MsTUFBTyxDQUFDLEVBQUc7WUFDNUU7VUFDRDtRQUNEO1FBRUFhLHlCQUF5QixDQUFFakssTUFBTSxDQUFFbUosS0FBSyxDQUFDQyxNQUFNLENBQUM3SCxLQUFLLElBQUksRUFBRyxDQUFFLENBQUM7TUFDaEU7SUFDRDs7SUFFQTtBQUNGO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7SUFDRSxTQUFTa0osY0FBY0EsQ0FBRXRCLEtBQUssRUFBRztNQUNoQyxJQUFJYixHQUFHLEdBQUdhLEtBQUssQ0FBQ0MsTUFBTSxDQUFDL0gsT0FBTyxDQUFFLDZCQUE4QixDQUFDO01BQy9ELElBQUlxSixhQUFhO01BQ2pCLElBQUlDLFlBQVk7TUFFaEIsSUFBSyxDQUFFckMsR0FBRyxJQUFJLENBQUMsQ0FBQyxLQUFLLENBQUUsV0FBVyxFQUFFLFlBQVksRUFBRSxNQUFNLEVBQUUsS0FBSyxDQUFFLENBQUNSLE9BQU8sQ0FBRXFCLEtBQUssQ0FBQ3ZKLEdBQUksQ0FBQyxFQUFHO1FBQ3hGO01BQ0Q7TUFFQXVKLEtBQUssQ0FBQ0ksY0FBYyxDQUFDLENBQUM7TUFDdEJtQixhQUFhLEdBQUc1TCxNQUFNLENBQUMwSixJQUFJLENBQUNWLE9BQU8sQ0FBRVEsR0FBSSxDQUFDO01BQzFDcUMsWUFBWSxHQUFHRCxhQUFhO01BQzVCLElBQUssV0FBVyxLQUFLdkIsS0FBSyxDQUFDdkosR0FBRyxFQUFHO1FBQ2hDK0ssWUFBWSxHQUFHLENBQUVELGFBQWEsR0FBRyxDQUFDLEdBQUc1TCxNQUFNLENBQUMwSixJQUFJLENBQUNOLE1BQU0sSUFBS3BKLE1BQU0sQ0FBQzBKLElBQUksQ0FBQ04sTUFBTTtNQUMvRSxDQUFDLE1BQU0sSUFBSyxZQUFZLEtBQUtpQixLQUFLLENBQUN2SixHQUFHLEVBQUc7UUFDeEMrSyxZQUFZLEdBQUcsQ0FBRUQsYUFBYSxHQUFHLENBQUMsSUFBSzVMLE1BQU0sQ0FBQzBKLElBQUksQ0FBQ04sTUFBTTtNQUMxRCxDQUFDLE1BQU0sSUFBSyxNQUFNLEtBQUtpQixLQUFLLENBQUN2SixHQUFHLEVBQUc7UUFDbEMrSyxZQUFZLEdBQUcsQ0FBQztNQUNqQixDQUFDLE1BQU0sSUFBSyxLQUFLLEtBQUt4QixLQUFLLENBQUN2SixHQUFHLEVBQUc7UUFDakMrSyxZQUFZLEdBQUc3TCxNQUFNLENBQUMwSixJQUFJLENBQUNOLE1BQU0sR0FBRyxDQUFDO01BQ3RDO01BRUFHLGVBQWUsQ0FBRXZKLE1BQU0sQ0FBQzBKLElBQUksQ0FBRW1DLFlBQVksQ0FBRyxDQUFDO01BQzlDN0wsTUFBTSxDQUFDMEosSUFBSSxDQUFFbUMsWUFBWSxDQUFFLENBQUNuQixLQUFLLENBQUMsQ0FBQztNQUNuQzFLLE1BQU0sQ0FBQzBKLElBQUksQ0FBRW1DLFlBQVksQ0FBRSxDQUFDQyxjQUFjLENBQUU7UUFBRUMsS0FBSyxFQUFFLFNBQVM7UUFBRUMsTUFBTSxFQUFFO01BQVUsQ0FBRSxDQUFDO0lBQ3RGOztJQUVBO0FBQ0Y7QUFDQTtBQUNBO0FBQ0E7QUFDQTtJQUNFLFNBQVNDLFVBQVVBLENBQUVDLG9CQUFvQixFQUFHO01BQzNDLElBQUlDLFdBQVc7TUFDZixJQUFJakcsYUFBYTtNQUNqQixJQUFJa0csb0JBQW9CO01BQ3hCLElBQUlDLHFCQUFxQjtNQUN6QixJQUFJQywwQkFBMEI7TUFDOUIsSUFBSUMseUJBQXlCO01BQzdCLElBQUlDLG9CQUFvQjtNQUV4QjFNLFNBQVMsR0FBR29NLG9CQUFvQjtNQUNoQ25NLElBQUksR0FBR0QsU0FBUyxDQUFDQyxJQUFJO01BQ3JCb00sV0FBVyxHQUFHcE0sSUFBSSxDQUFDd0IsYUFBYSxDQUFFLDBDQUEyQyxDQUFDO01BQzlFLElBQUssQ0FBRTRLLFdBQVcsRUFBRztRQUNwQjtNQUNEO01BRUFuTSxNQUFNLEdBQUc7UUFDUnNDLElBQUksRUFBRTZKLFdBQVc7UUFDakJoRSxLQUFLLEVBQUV5QyxLQUFLLENBQUNDLFNBQVMsQ0FBQ0MsS0FBSyxDQUFDQyxJQUFJLENBQUVvQixXQUFXLENBQUNuQixnQkFBZ0IsQ0FBRSwyQkFBNEIsQ0FBRSxDQUFDO1FBQ2hHdEIsSUFBSSxFQUFFa0IsS0FBSyxDQUFDQyxTQUFTLENBQUNDLEtBQUssQ0FBQ0MsSUFBSSxDQUFFb0IsV0FBVyxDQUFDbkIsZ0JBQWdCLENBQUUsNkJBQThCLENBQUUsQ0FBQztRQUNqR3ZDLE1BQU0sRUFBRTBELFdBQVcsQ0FBQzVLLGFBQWEsQ0FBRSw2QkFBOEIsQ0FBQztRQUNsRThILElBQUksRUFBRThDLFdBQVcsQ0FBQzVLLGFBQWEsQ0FBRSwyQkFBNEIsQ0FBQztRQUM5RDRILEtBQUssRUFBRWdELFdBQVcsQ0FBQzVLLGFBQWEsQ0FBRSw0QkFBNkIsQ0FBQztRQUNoRThCLGFBQWEsRUFBRThJLFdBQVcsQ0FBQzVLLGFBQWEsQ0FBRSxvQ0FBcUMsQ0FBQztRQUNoRmtDLGNBQWMsRUFBRTBJLFdBQVcsQ0FBQzVLLGFBQWEsQ0FBRSxxQ0FBc0MsQ0FBQztRQUNsRm1DLGFBQWEsRUFBRXlJLFdBQVcsQ0FBQzVLLGFBQWEsQ0FBRSxvQ0FBcUMsQ0FBQztRQUNoRjZGLGNBQWMsRUFBRStFLFdBQVcsQ0FBQzVLLGFBQWEsQ0FBRSxxQ0FBc0MsQ0FBQztRQUNsRmtMLHNCQUFzQixFQUFFTixXQUFXLENBQUM1SyxhQUFhLENBQUUsNkNBQThDLENBQUM7UUFDbEdnTCx5QkFBeUIsRUFBRUosV0FBVyxDQUFDNUssYUFBYSxDQUFFLGdEQUFpRCxDQUFDO1FBQ3hHd0MsYUFBYSxFQUFFb0ksV0FBVyxDQUFDNUssYUFBYSxDQUFFLG9DQUFxQyxDQUFDO1FBQ2hGb0MsY0FBYyxFQUFFd0ksV0FBVyxDQUFDNUssYUFBYSxDQUFFLDhCQUErQixDQUFDO1FBQzNFc0MsV0FBVyxFQUFFc0ksV0FBVyxDQUFDNUssYUFBYSxDQUFFLG1DQUFvQyxDQUFDO1FBQzdFcUgsYUFBYSxFQUFFdUQsV0FBVyxDQUFDekssT0FBTyxDQUFDZ0wsYUFBYSxJQUFJO01BQ3JELENBQUM7TUFFRCxJQUNDck4sTUFBTSxDQUFDc04sdUJBQXVCLElBQzlCLFVBQVUsS0FBSyxPQUFPdE4sTUFBTSxDQUFDc04sdUJBQXVCLENBQUNDLE1BQU0sRUFDMUQ7UUFDRHJNLHVCQUF1QixHQUFHbEIsTUFBTSxDQUFDc04sdUJBQXVCLENBQUNDLE1BQU0sQ0FBRTVNLE1BQU0sQ0FBQ3lNLHNCQUF1QixDQUFDO01BQ2pHO01BRUF6TSxNQUFNLENBQUNzQyxJQUFJLENBQUNQLGdCQUFnQixDQUFFLE9BQU8sRUFBRXFJLFlBQWEsQ0FBQztNQUNyRHBLLE1BQU0sQ0FBQ3NDLElBQUksQ0FBQ1AsZ0JBQWdCLENBQUUsUUFBUSxFQUFFeUosYUFBYyxDQUFDO01BQ3ZEeEwsTUFBTSxDQUFDc0MsSUFBSSxDQUFDUCxnQkFBZ0IsQ0FBRSxTQUFTLEVBQUU0SixjQUFlLENBQUM7TUFDekQ1TCxJQUFJLENBQUNnQyxnQkFBZ0IsQ0FBRSxpQ0FBaUMsRUFBRXFKLG9CQUFxQixDQUFDO01BQ2hGbkwsNEJBQTRCLEdBQUd1QywrQkFBK0IsQ0FBQyxDQUFDO01BQ2hFLElBQUt4QyxNQUFNLENBQUN5SSxNQUFNLEVBQUc7UUFDcEJ6SSxNQUFNLENBQUN5SSxNQUFNLENBQUMxRyxnQkFBZ0IsQ0FBRSxPQUFPLEVBQUVtSSxvQkFBcUIsQ0FBQztRQUMvRGxLLE1BQU0sQ0FBQ3lJLE1BQU0sQ0FBQzFHLGdCQUFnQixDQUFFLFFBQVEsRUFBRW1JLG9CQUFxQixDQUFDO1FBQ2hFbEssTUFBTSxDQUFDeUksTUFBTSxDQUFDMUcsZ0JBQWdCLENBQUUsUUFBUSxFQUFFbUksb0JBQXFCLENBQUM7TUFDakU7TUFFQWxLLE1BQU0sQ0FBQ21JLEtBQUssQ0FBQ0MsT0FBTyxDQUFFLFVBQVcvRyxJQUFJLEVBQUc7UUFDdkNPLHlCQUF5QixDQUN4QlAsSUFBSSxDQUFDRSxhQUFhLENBQUUsaUNBQWtDLENBQUMsRUFDdkRGLElBQUksQ0FBQ0UsYUFBYSxDQUFFLHdDQUF5QyxDQUM5RCxDQUFDO01BQ0YsQ0FBRSxDQUFDO01BRUgwSCxjQUFjLENBQUMsQ0FBQztNQUNoQi9DLGFBQWEsR0FBRzlELGlCQUFpQixDQUFDLENBQUM7TUFDbkMsSUFBSzhELGFBQWEsRUFBRztRQUNwQmtHLG9CQUFvQixHQUFHbEwsTUFBTSxDQUFFbEIsTUFBTSxDQUFDcUQsYUFBYSxDQUFDM0IsT0FBTyxDQUFDbUwsa0JBQWtCLElBQUksRUFBRyxDQUFDO1FBQ3RGUixxQkFBcUIsR0FBR25MLE1BQU0sQ0FBRWxCLE1BQU0sQ0FBQ3FELGFBQWEsQ0FBQzNCLE9BQU8sQ0FBQ29MLDBCQUEwQixJQUFJLEVBQUcsQ0FBQztRQUMvRlIsMEJBQTBCLEdBQUdwTCxNQUFNLENBQUVsQixNQUFNLENBQUNxRCxhQUFhLENBQUMzQixPQUFPLENBQUNxTCw4QkFBOEIsSUFBSSxFQUFHLENBQUM7UUFDeEdoRixXQUFXLENBQUU3QixhQUFhLEVBQUUsS0FBTSxDQUFDO1FBRW5DLElBQ0MsUUFBUSxLQUFLa0csb0JBQW9CLElBQ2pDQyxxQkFBcUIsS0FBS25MLE1BQU0sQ0FBRWdGLGFBQWEsQ0FBQ3hFLE9BQU8sQ0FBQ0MsWUFBWSxJQUFJLEVBQUcsQ0FBQyxJQUM1RTJLLDBCQUEwQixLQUFLOUosK0JBQStCLENBQUMsQ0FBQyxJQUNoRWpDLHVCQUF1QixJQUN2QlAsTUFBTSxDQUFDdU0seUJBQXlCLEVBQy9CO1VBQ0QsSUFBSTtZQUNIQSx5QkFBeUIsR0FBR3ZILElBQUksQ0FBQ0MsS0FBSyxDQUFFakYsTUFBTSxDQUFDdU0seUJBQXlCLENBQUM5SyxXQUFXLElBQUksSUFBSyxDQUFDO1VBQy9GLENBQUMsQ0FBQyxPQUFROEYsS0FBSyxFQUFHO1lBQ2pCZ0YseUJBQXlCLEdBQUcsSUFBSTtVQUNqQztVQUNBQyxvQkFBb0IsR0FBR3hNLE1BQU0sQ0FBQ3lNLHNCQUFzQixDQUFDTyxTQUFTO1VBQzlELElBQUtULHlCQUF5QixJQUFJLFFBQVEsS0FBSyxPQUFPQSx5QkFBeUIsRUFBRztZQUNqRmhNLHVCQUF1QixDQUFDNEcsT0FBTyxDQUFFcUYsb0JBQW9CLEVBQUVELHlCQUEwQixDQUFDLENBQ2hGVSxJQUFJLENBQUUsWUFBWTtjQUNsQmhKLG1CQUFtQixDQUFFLFFBQVEsRUFBRSxFQUFFLEVBQUVvSSxxQkFBcUIsRUFBRUMsMEJBQTJCLENBQUM7Y0FDdEZySixpQkFBaUIsQ0FBRSxLQUFLLEVBQUUsRUFBRyxDQUFDO1lBQy9CLENBQUUsQ0FBQyxDQUNGaUssSUFBSSxDQUFFLFlBQVk7Y0FDbEI5RyxZQUFZLENBQUVGLGFBQWMsQ0FBQztZQUM5QixDQUFFLENBQUM7VUFDTCxDQUFDLE1BQU07WUFDTkUsWUFBWSxDQUFFRixhQUFjLENBQUM7VUFDOUI7UUFDRCxDQUFDLE1BQU07VUFDTkUsWUFBWSxDQUFFRixhQUFjLENBQUM7UUFDOUI7TUFDRCxDQUFDLE1BQU07UUFDTnZELHVCQUF1QixDQUFFLElBQUssQ0FBQztRQUMvQk0saUJBQWlCLENBQUUsS0FBSyxFQUFFcEMsV0FBVyxDQUFFLGVBQWUsRUFBRSxpRUFBa0UsQ0FBRSxDQUFDO01BQzlIO01BRUEsSUFBSyxVQUFVLEtBQUssT0FBT2YsU0FBUyxDQUFDNEwsV0FBVyxFQUFHO1FBQ2xEck0sTUFBTSxDQUFDOE4sVUFBVSxDQUFFLFlBQVk7VUFDOUJyTixTQUFTLENBQUM0TCxXQUFXLENBQUU3TCwrQkFBK0IsRUFBRUcsTUFBTSxDQUFDeUksTUFBTSxJQUFJekksTUFBTSxDQUFDc0MsSUFBSyxDQUFDO1FBQ3ZGLENBQUMsRUFBRSxDQUFFLENBQUM7TUFDUDtJQUNEOztJQUVBO0FBQ0Y7QUFDQTtBQUNBO0FBQ0E7SUFDRSxTQUFTOEssUUFBUUEsQ0FBQSxFQUFHO01BQ25CLElBQUkvSyxjQUFjLEdBQUdyQyxNQUFNLElBQUlBLE1BQU0sQ0FBQ3NDLElBQUksQ0FBQ2YsYUFBYSxDQUFFLG9DQUFxQyxDQUFDO01BQ2hHLElBQUk4TCxjQUFjLEdBQUdyTixNQUFNLElBQUlBLE1BQU0sQ0FBQ3NDLElBQUksQ0FBQ2YsYUFBYSxDQUFFLDhDQUErQyxDQUFDO01BRTFHLElBQUssQ0FBRWMsY0FBYyxFQUFHO1FBQ3ZCLE9BQU92QyxTQUFTLENBQUN3TixlQUFlLENBQUUsdUJBQXVCLEVBQUV6TSxXQUFXLENBQUUsbUJBQW1CLEVBQUUsaUNBQWtDLENBQUUsQ0FBQztNQUNuSTtNQUVBLElBQUssQ0FBRXdNLGNBQWMsRUFBRztRQUN2QixPQUFPdk4sU0FBUyxDQUFDd04sZUFBZSxDQUFFLG9CQUFvQixFQUFFek0sV0FBVyxDQUFFLGdCQUFnQixFQUFFLGtEQUFtRCxDQUFFLENBQUM7TUFDOUk7TUFFQSxPQUFPLElBQUk7SUFDWjtJQUVBLE9BQU87TUFDTm9MLFVBQVUsRUFBRUEsVUFBVTtNQUN0QnNCLElBQUksRUFBRSxTQUFBQSxDQUFBLEVBQVksQ0FBQyxDQUFDO01BQ3BCSCxRQUFRLEVBQUVBO0lBQ1gsQ0FBQztFQUNGO0VBRUEvTixNQUFNLENBQUNtTyx5QkFBeUIsR0FBR25PLE1BQU0sQ0FBQ21PLHlCQUF5QixJQUFJLENBQUMsQ0FBQztFQUN6RW5PLE1BQU0sQ0FBQ21PLHlCQUF5QixDQUFDQyxxQkFBcUIsR0FBRztJQUN4RGIsTUFBTSxFQUFFbE47RUFDVCxDQUFDO0VBRUQsSUFBS0wsTUFBTSxDQUFDcU8scUJBQXFCLElBQUksVUFBVSxLQUFLLE9BQU9yTyxNQUFNLENBQUNxTyxxQkFBcUIsQ0FBQ0MscUJBQXFCLEVBQUc7SUFDL0d0TyxNQUFNLENBQUNxTyxxQkFBcUIsQ0FBQ0MscUJBQXFCLENBQUVqTyxvQ0FBb0MsQ0FBRUgsYUFBYyxDQUFFLENBQUM7RUFDNUc7QUFDRCxDQUFDLEVBQUVGLE1BQU0sRUFBRUMsUUFBUyxDQUFDIiwiaWdub3JlTGlzdCI6W119
