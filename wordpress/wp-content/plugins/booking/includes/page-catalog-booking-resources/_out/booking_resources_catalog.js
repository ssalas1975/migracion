"use strict";

/**
 * Render normalized Booking Resource DTOs through identifiable WP templates.
 *
 * @since 11.6.0
 */
(function (window, document) {
  'use strict';

  var catalog_controller = null;
  var inline_workflow_controller = null;
  var inline_review_workflow_controller = null;
  var delete_review_workflow_controller = null;
  var inspector_workflow_controller = null;
  var catalog_response = null;
  var details_abort_controller = null;
  var details_request_sequence = 0;
  var details_resource_id = 0;
  var details_toggle_button = null;
  var pending_focus_direction = '';
  var inspector_dirty = false;
  var inspector_focus_target = null;
  var inspector_mode = '';
  var inspector_mutation_in_progress = false;
  var inspector_mutation_request_sequence = 0;
  var inspector_original_fields = '';
  var inspector_request_sequence = 0;
  var inspector_resource_id = 0;
  var inspector_resource_ids = [];
  var inspector_bulk_operations = {};
  var inspector_review_token = '';
  var inspector_selection_stale = false;
  var inspector_tracks_selection = false;
  var inspector_capacity_context = null;
  var inspector_capacity_detach_ids = [];
  var inspector_capacity_decrease_action = 'detach';
  var inspector_capacity_target = 0;
  var pending_highlight_ids = [];
  var launch_intent_handled = false;
  var inline_state = {
    active: false,
    changed_rows: [],
    loading: false,
    request_sequence: 0,
    review_token: ''
  };

  /**
   * Normalize a localized WordPress flag to a strict boolean.
   *
   * @param {*} flag_value Localized flag value.
   * @return {boolean} True only for an explicitly enabled flag.
   */
  function is_true_flag(flag_value) {
    return true === flag_value || 1 === flag_value || '1' === flag_value || 'true' === String(flag_value).toLowerCase();
  }

  /**
   * Format a localized positional-placeholder string.
   *
   * @param {string} template Localized string containing `%1$s` placeholders.
   * @param {Array<*>} values Scalar replacement values.
   * @return {string} Formatted plain text.
   */
  function format_message(template, values) {
    var message = String(template || '');
    values.forEach(function (replacement, replacement_index) {
      var placeholder = new RegExp('%' + (replacement_index + 1) + '\\$s', 'g');
      message = message.replace(placeholder, String(replacement));
    });
    return message;
  }

  /**
   * Return the shared signed-review presentation controller.
   *
   * @return {Object|false} Shared review controller or false when unavailable.
   */
  function get_inline_review_workflow() {
    if (inline_review_workflow_controller) {
      return inline_review_workflow_controller;
    }
    if (!window.wpbc_ui_catalog || 'function' !== typeof window.wpbc_ui_catalog.create_inline_review_workflow) {
      return false;
    }
    inline_review_workflow_controller = window.wpbc_ui_catalog.create_inline_review_workflow({
      apply_selector: '[data-wpbc-ui-catalog-inspector-save]',
      cancel_selector: '[data-wpbc-ui-catalog-inspector-cancel]',
      root: document
    });
    return inline_review_workflow_controller;
  }

  /**
   * Return the shared permanent-deletion presentation controller.
   *
   * @return {Object|false} Shared deletion controller or false when unavailable.
   */
  function get_delete_review_workflow() {
    if (delete_review_workflow_controller) {
      return delete_review_workflow_controller;
    }
    if (!window.wpbc_ui_catalog || 'function' !== typeof window.wpbc_ui_catalog.create_delete_review_workflow) {
      return false;
    }
    delete_review_workflow_controller = window.wpbc_ui_catalog.create_delete_review_workflow({
      acknowledgement_selector: '[data-wpbc-catalog-resource-delete-acknowledgement]',
      apply_selector: '[data-wpbc-ui-catalog-inspector-save]',
      cancel_selector: '[data-wpbc-ui-catalog-inspector-cancel]',
      root: document
    });
    return delete_review_workflow_controller;
  }

  /**
   * Return the localized count shown in a collapsed child-group summary.
   *
   * The DTO label remains authoritative because PHP applies WordPress locale
   * plural rules. The numeric fallback consumes the shared direct-child count
   * when an older or custom domain DTO does not include the prepared label.
   *
   * @param {Object} parent_resource Parent Booking Resource DTO.
   * @param {Object} i18n            Localized catalog strings.
   * @return {string} Localized child-count label.
   */
  function get_children_summary_label(parent_resource, i18n) {
    var hierarchy = parent_resource && parent_resource.hierarchy ? parent_resource.hierarchy : {};
    var server_label = String(hierarchy.children_label || '').trim();
    var child_count = Math.max(0, Number(hierarchy.rendered_children_count) || 0);
    var label_template;
    if (server_label) {
      return server_label;
    }
    label_template = 1 === child_count ? i18n.child_count_singular || '%1$s child resource' : i18n.child_count_plural || '%1$s child resources';
    return format_message(label_template, [child_count]);
  }

  /**
   * Render one allow-listed Resource presentation template.
   *
   * @param {Object} config        Registered catalog configuration.
   * @param {string} template_role Registered template role.
   * @param {Object} template_data Normalized DTO or presentation data.
   * @return {string} Escaped template HTML or an empty string.
   */
  function render_component(config, template_role, template_data) {
    var component_template = window.wpbc_ui_catalog.load_template(config, template_role);
    if (!component_template) {
      return '';
    }
    try {
      return component_template(template_data || {});
    } catch (error) {
      return '';
    }
  }

  /**
   * Return complete column presentation records in the active order.
   *
   * @param {Object} config         Registered catalog configuration.
   * @param {Object} display_state  Normalized display request or response.
   * @param {boolean} visible_only  Whether hidden columns must be omitted.
   * @param {Object} sorting_state  Normalized sorting response.
   * @return {Array<Object>} Ordered presentation-only column records.
   */
  function get_columns(config, display_state, visible_only, sorting_state) {
    var column_config = config.columns || {};
    var definitions = column_config.definitions || {};
    var default_order = Array.isArray(column_config.default_order) ? column_config.default_order : [];
    var order = display_state && Array.isArray(display_state.column_order) ? display_state.column_order.slice() : default_order.slice();
    var visible_columns = display_state && Array.isArray(display_state.visible_columns) ? display_state.visible_columns : column_config.default_visible || [];
    default_order.forEach(function (column_id) {
      if (-1 === order.indexOf(column_id)) {
        order.push(column_id);
      }
    });
    return order.filter(function (column_id) {
      return definitions[column_id] && (!visible_only || -1 !== visible_columns.indexOf(column_id));
    }).map(function (column_id, column_index) {
      var definition = definitions[column_id];
      var is_sorted = !!definition.sort_key && sorting_state && definition.sort_key === sorting_state.sort_by;
      return {
        aria_sort: is_sorted ? 'desc' === sorting_state.sort_order ? 'descending' : 'ascending' : 'none',
        class_name: definition.class || 'column-' + column_id,
        default_index: default_order.indexOf(column_id),
        id: column_id,
        is_sorted: is_sorted,
        label: definition.label || column_id,
        move_label: format_message(config.i18n.move_column || '', [definition.label || column_id]),
        reorderable: false !== definition.reorderable,
        required: !!definition.required,
        sort_icon: is_sorted ? 'desc' === sorting_state.sort_order ? 'wpbc-bi-arrow-down' : 'wpbc-bi-arrow-up' : 'wpbc_icn_import_export',
        sort_key: definition.sort_key || '',
        visible: -1 !== visible_columns.indexOf(column_id)
      };
    });
  }

  /**
   * Determine whether display values match the Overview defaults.
   *
   * @param {Object} config        Registered catalog configuration.
   * @param {Object} display_state Current normalized display state.
   * @return {string} overview or custom.
   */
  function get_active_view(config, display_state) {
    var view_definitions = config.views && config.views.definitions ? config.views.definitions : {};
    var current_visible = display_state && Array.isArray(display_state.visible_columns) ? display_state.visible_columns : [];
    var matching_view = '';
    Object.keys(view_definitions).some(function (view_id) {
      var view_fields = Array.isArray(view_definitions[view_id].fields) ? view_definitions[view_id].fields : [];
      if (JSON.stringify(current_visible) === JSON.stringify(view_fields)) {
        matching_view = view_id;
        return true;
      }
      return false;
    });
    return matching_view || 'custom';
  }

  /**
   * Return ordered view presets declared by the independent PHP configuration.
   *
   * @param {Object} config Registered catalog configuration.
   * @return {Array<Object>} Browser-safe view definitions.
   */
  function get_view_definitions(config) {
    var definitions = config.views && config.views.definitions ? config.views.definitions : {};
    return Object.keys(definitions).map(function (view_id) {
      return definitions[view_id];
    });
  }

  /**
   * Return the allow-listed presentation packs declared by PHP.
   *
   * Labels remain domain-owned while the shared controller validates and
   * persists only pack identifiers registered in the catalog configuration.
   *
   * @param {Object} config Registered catalog configuration.
   * @return {Array<Object>} Ordered browser-safe pack options.
   */
  function get_template_pack_definitions(config) {
    var labels = {
      cards: config.i18n.layout_cards || '',
      compact: config.i18n.layout_compact || '',
      table: config.i18n.layout_table || ''
    };
    var template_packs = config.template_packs || {};
    return Object.keys(template_packs).map(function (template_pack_id) {
      return {
        id: template_pack_id,
        label: labels[template_pack_id] || template_pack_id
      };
    });
  }

  /**
   * Render the established Resource filters above the bordered listing.
   *
   * Free editions intentionally render no filter form, matching the existing
   * page where a single default Resource makes these controls unnecessary.
   *
   * @param {Object} config Registered catalog configuration.
   * @return {boolean} True when the filters target was found.
   */
  function render_booking_resources_filters(config) {
    var initial_request = config.initial_request || {};
    var mount_element = document.getElementById(config.mount_id);
    var filters_target = mount_element ? mount_element.querySelector('[data-wpbc-booking-resources-filters]') : null;
    if (!filters_target) {
      return false;
    }
    filters_target.innerHTML = render_component(config, 'filters', {
      i18n: config.i18n || {},
      resource_type: initial_request.resource_type || 'all',
      search: initial_request.search || '',
      show_filters: !!(config.features && config.features.resource_filters),
      show_resource_type_filter: !!(config.features && config.features.resource_type_filter)
    });
    return true;
  }

  /**
   * Render persistent filters and display controls outside response content.
   *
   * @param {Object} config Registered catalog configuration.
   * @return {boolean} True when the toolbar target was populated.
   */
  function render_booking_resources_toolbar(config) {
    var initial_request = config.initial_request || {};
    var mount_element = document.getElementById(config.mount_id);
    var toolbar_target = mount_element ? mount_element.querySelector('[data-wpbc-booking-resources-toolbar]') : null;
    if (!toolbar_target) {
      return false;
    }
    toolbar_target.innerHTML = render_component(config, 'toolbar', {
      active_template_pack: initial_request.template_pack || config.default_template_pack || 'table',
      active_view: get_active_view(config, initial_request),
      columns: get_columns(config, initial_request, false, initial_request),
      i18n: config.i18n || {},
      template_packs: get_template_pack_definitions(config),
      views: get_view_definitions(config)
    });
    if (catalog_controller && 'function' === typeof catalog_controller.refresh_controls) {
      catalog_controller.refresh_controls();
    }
    return !!toolbar_target.firstElementChild;
  }

  /**
   * Add full-text tooltips only to elements whose rendered text is clipped.
   *
   * Native title text remains available without a JavaScript tooltip library;
   * the established tooltip attributes are also supplied for Booking Calendar
   * admin themes that initialize them globally.
   *
   * @param {HTMLElement} catalog_mount Catalog mount element.
   * @return {void}
   */
  function synchronize_overflow_tooltips(catalog_mount) {
    if (!window.wpbc_ui_catalog || 'function' !== typeof window.wpbc_ui_catalog.synchronize_overflow_tooltips) {
      return;
    }
    window.wpbc_ui_catalog.synchronize_overflow_tooltips(catalog_mount);
  }

  /**
   * Initialize tooltips for compact controls inserted by a lazy details render.
   *
   * The native title remains as a fallback when the established Booking
   * Calendar tooltip helper is unavailable.
   *
   * @param {HTMLElement} catalog_mount Catalog mount element.
   * @return {void}
   */
  function initialize_details_tooltips(catalog_mount) {
    var tooltip_selector;
    if (!catalog_mount || !catalog_mount.id || 'function' !== typeof window.wpbc_define_tippy_tooltips) {
      return;
    }
    tooltip_selector = '#' + catalog_mount.id + ' [data-wpbc-ui-catalog-details-tooltip]';
    window.wpbc_define_tippy_tooltips(tooltip_selector);
  }

  /**
   * Synchronize persistent controls with server-authoritative response state.
   *
   * @param {Object} config   Registered catalog configuration.
   * @param {Object} response Normalized catalog response.
   * @return {void}
   */
  function synchronize_booking_resources_toolbar(config, response) {
    var columns = get_columns(config, response.display || {}, false, response.sorting || {});
    var mount_element = document.getElementById(config.mount_id);
    var column_list = mount_element ? mount_element.querySelector('[data-wpbc-ui-catalog-column-list]') : null;
    var search_control = mount_element ? mount_element.querySelector('[data-wpbc-ui-catalog-search]') : null;
    var template_pack_control = mount_element ? mount_element.querySelector('[data-wpbc-ui-catalog-template-pack]') : null;
    var type_control = mount_element ? mount_element.querySelector('[data-wpbc-ui-catalog-filter="resource_type"]') : null;
    var view_control = mount_element ? mount_element.querySelector('[data-wpbc-ui-catalog-view]') : null;
    if (search_control && document.activeElement !== search_control) {
      search_control.value = response.filters.search || '';
    }
    if (type_control) {
      type_control.value = response.filters.resource_type || 'all';
    }
    if (template_pack_control && response.display && response.display.template_pack) {
      template_pack_control.value = response.display.template_pack;
    }
    columns.forEach(function (column) {
      var column_control = mount_element.querySelector('[data-wpbc-ui-catalog-column-visible][value="' + column.id + '"]');
      var column_item = mount_element.querySelector('[data-wpbc-ui-catalog-column-item="' + column.id + '"]');
      if (column_control) {
        column_control.checked = column.visible;
      }
      if (column_list && column_item) {
        column_list.appendChild(column_item);
      }
    });
    if (view_control) {
      view_control.value = get_active_view(config, response.display || {});
    }
  }

  /**
   * Render the persistent inline-edit status bar from its registered template.
   *
   * @param {Object} config Catalog configuration.
   * @return {void}
   */
  function render_inline_bar(config) {
    var mount_element = document.getElementById(config.mount_id);
    var inline_host = mount_element ? mount_element.querySelector('[data-wpbc-catalog-inline-bar-host]') : null;
    if (inline_host && !inline_host.firstElementChild) {
      inline_host.innerHTML = render_component(config, 'inline_bar', {
        i18n: config.i18n || {}
      });
    }
    if (inline_workflow_controller) {
      inline_workflow_controller.register_sticky_bar();
    }
  }

  /**
   * Synchronize inline activation, changed count, and disabled navigation.
   *
   * @param {Object} config Catalog configuration.
   * @return {void}
   */
  function synchronize_inline_controls(config) {
    var mount_element = document.getElementById(config.mount_id);
    var changed_count = inline_state.changed_rows.length;
    var count_label = 1 === changed_count ? config.i18n.inline_changed_row : config.i18n.inline_changed_rows;
    if (!mount_element || !inline_workflow_controller) {
      return;
    }
    inline_workflow_controller.synchronize({
      active: inline_state.active,
      active_toggle_text: config.i18n.inline_editing_rows || '',
      busy: inline_state.loading,
      changed_count: changed_count,
      count_text: inline_state.loading ? config.i18n.inline_loading || '' : format_message(count_label || '%1$s changed rows', [changed_count]),
      has_items: !!mount_element.querySelector('[data-wpbc-booking-resource-id]'),
      inactive_toggle_text: config.i18n.edit_rows || ''
    });
  }

  /**
   * Block page-changing catalog controls before shared handlers can discard drafts.
   *
   * Native summary elements do not honor a disabled property, so this capture
   * guard complements the visual disabled state while inline editing is active.
   *
   * @param {Event} event Captured catalog event.
   * @return {void}
   */
  function protect_inline_drafts_from_catalog_controls(event) {
    if (inline_workflow_controller) {
      inline_workflow_controller.protect_event(event, inline_state.active);
    }
  }

  /**
   * Show or clear an inline workflow error.
   *
   * @param {Object} config Catalog configuration.
   * @param {string} message Safe message or empty string.
   * @return {void}
   */
  function show_inline_message(config, message) {
    var mount_element = document.getElementById(config.mount_id);
    var notice = mount_element ? mount_element.querySelector('[data-wpbc-catalog-inline-message]') : null;
    if (notice) {
      notice.hidden = !message;
      var text = notice.querySelector('p');
      if (text) {
        text.textContent = message || '';
      }
    }
  }

  /**
   * Render one server-declared field through the inline WP template.
   *
   * @param {Object} config Catalog configuration.
   * @param {Object} row_schema Row schema.
   * @return {void}
   */
  function render_inline_row(config, row_schema) {
    var mount_element = document.getElementById(config.mount_id);
    var resource_id = Number(row_schema.resource_id) || 0;
    var row = mount_element ? mount_element.querySelector('[data-wpbc-booking-resource-id="' + resource_id + '"]') : null;
    var resource_fields = [];
    if (!row) {
      return;
    }
    (row_schema.fields || []).forEach(function (field) {
      var cell = row.querySelector('[data-wpbc-ui-catalog-field="' + String(field.column || '') + '"]');
      if (!cell || cell.hidden) {
        return;
      }
      if ('resource' === field.column) {
        resource_fields.push(field);
        return;
      }
      cell.innerHTML = render_component(config, 'inline_field', {
        field: field,
        resource_id: resource_id
      });
    });
    if (resource_fields.length) {
      var copy = row.querySelector('[data-wpbc-ui-catalog-field="resource"] .wpbc_ui_listing__item_copy');
      if (copy) {
        var wrapper = document.createElement('span');
        wrapper.className = 'wpbc_booking_resources__inline_identity_fields';
        resource_fields.forEach(function (field) {
          wrapper.insertAdjacentHTML('beforeend', render_component(config, 'inline_field', {
            field: field,
            resource_id: resource_id
          }));
        });
        copy.replaceWith(wrapper);
      }
    }
  }

  /**
   * Collect only changed row fields while preserving visible catalog order.
   *
   * @param {Object} config Catalog configuration.
   * @return {Array<Object>} Changed row envelopes.
   */
  function collect_inline_drafts(config) {
    var mount_element = document.getElementById(config.mount_id);
    var changed_rows = [];
    if (!mount_element) {
      return changed_rows;
    }
    mount_element.querySelectorAll('.wpbc_booking_resources__item[data-wpbc-booking-resource-id]').forEach(function (row) {
      var fields = {};
      var has_changes;
      var indicator_host;
      row.querySelectorAll('[data-wpbc-catalog-inline-field]').forEach(function (control) {
        var field_key = control.getAttribute('data-wpbc-catalog-inline-field') || '';
        if (field_key && String(control.value || '') !== String(control.getAttribute('data-wpbc-catalog-inline-original') || '')) {
          fields[field_key] = control.value;
        }
      });
      has_changes = 0 < Object.keys(fields).length;
      indicator_host = row.querySelector('[data-wpbc-ui-catalog-field="resource"]');
      row.classList.toggle('is-inline-dirty', has_changes);
      if (inline_workflow_controller) {
        inline_workflow_controller.set_row_changed(row, has_changes, indicator_host, config.i18n.inline_changed || '');
      }
      if (has_changes) {
        changed_rows.push({
          resource_id: Number(row.getAttribute('data-wpbc-booking-resource-id')),
          fields: fields
        });
      }
    });
    return changed_rows;
  }

  /**
   * Invalidate a prior review and synchronize draft state.
   *
   * @param {Object} config Catalog configuration.
   * @return {void}
   */
  function synchronize_inline_drafts(config) {
    inline_state.changed_rows = collect_inline_drafts(config);
    inline_state.review_token = '';
    show_inline_message(config, '');
    synchronize_inline_controls(config);
  }

  /**
   * Exit inline mode and optionally reload canonical rows.
   *
   * @param {Object} config Catalog configuration.
   * @param {boolean} reload Whether to reload the catalog.
   * @param {string} message Optional success message.
   * @return {void}
   */
  function leave_inline_mode(config, reload, message) {
    inline_state.request_sequence += 1;
    inline_state.active = false;
    inline_state.loading = false;
    inline_state.changed_rows = [];
    inline_state.review_token = '';
    if ('inline_review' === inspector_mode) {
      close_inspector(config, false);
    }
    synchronize_inline_controls(config);
    if (message) {
      show_admin_message(message, 'success', 4000);
    }
    if (reload && catalog_controller) {
      catalog_controller.load();
    }
  }

  /**
   * Start row editing for the current visible Resource page.
   *
   * @param {Object} config Catalog configuration.
   * @return {void}
   */
  function start_inline_mode(config) {
    var mount_element = document.getElementById(config.mount_id);
    var resource_ids = [];
    var request_sequence;
    if (inline_state.active) {
      synchronize_inline_drafts(config);
      if (!inline_state.changed_rows.length || window.confirm(config.i18n.inline_discard || '')) {
        leave_inline_mode(config, true, '');
      }
      return;
    }
    mount_element.querySelectorAll('.wpbc_booking_resources__item[data-wpbc-booking-resource-id]').forEach(function (row) {
      resource_ids.push(Number(row.getAttribute('data-wpbc-booking-resource-id')));
    });
    if (!resource_ids.length) {
      return;
    }
    if (!can_discard_inspector(config)) {
      return;
    }
    close_inspector(config, false);
    mount_element.querySelectorAll('[data-wpbc-ui-catalog-display-customizer][open]').forEach(function (customizer) {
      customizer.removeAttribute('open');
    });
    inline_state.active = true;
    inline_state.loading = true;
    inline_state.changed_rows = [];
    request_sequence = ++inline_state.request_sequence;
    close_details_row(false);
    synchronize_inline_controls(config);
    request_inspector(config, config.inline_schema_action, {
      resource_ids: JSON.stringify(resource_ids)
    }).then(function (response) {
      if (request_sequence !== inline_state.request_sequence || !inline_state.active || !response || !response.success || !response.data || !response.data.schema) {
        throw new Error(get_inspector_response_message(response, config.i18n.inline_load_failed));
      }
      (response.data.schema.rows || []).forEach(function (row_schema) {
        render_inline_row(config, row_schema);
      });
      var first_field = mount_element.querySelector('[data-wpbc-catalog-inline-field]');
      if (first_field) {
        first_field.focus();
      }
    }).catch(function (error) {
      if (request_sequence === inline_state.request_sequence) {
        show_admin_message(error.message || config.i18n.inline_load_failed || '', 'error', 5000);
        inline_state.active = false;
        if (catalog_controller) {
          catalog_controller.load();
        }
      }
    }).then(function () {
      if (request_sequence === inline_state.request_sequence) {
        inline_state.loading = false;
        synchronize_inline_controls(config);
      }
    });
  }

  /**
   * Preview current inline drafts and open their signed review inspector.
   *
   * @param {Object} config Catalog configuration.
   * @param {HTMLElement} focus_target Review trigger for focus restoration.
   * @return {void}
   */
  function preview_inline_changes(config, focus_target) {
    var inspector_workflow;
    var request_sequence;
    synchronize_inline_drafts(config);
    inspector_workflow = get_inspector_workflow(config);
    if (!inline_state.changed_rows.length || inline_state.loading || !inspector_workflow || !inspector_workflow.mount()) {
      return;
    }
    inline_state.loading = true;
    request_sequence = ++inline_state.request_sequence;
    inspector_focus_target = focus_target;
    inspector_mode = 'inline_review';
    inspector_dirty = true;
    if (!inspector_workflow.open_loading()) {
      inline_state.loading = false;
      return;
    }
    synchronize_inline_controls(config);
    request_inspector(config, config.inline_preview_action, {
      rows: JSON.stringify(inline_state.changed_rows)
    }).then(function (response) {
      var review_workflow;
      var review_model;
      var target;
      if (request_sequence !== inline_state.request_sequence) {
        return;
      }
      if (!response || !response.success || !response.data || !response.data.preview) {
        throw new Error(get_inspector_response_message(response, config.i18n.inline_review_failed));
      }
      inline_state.review_token = String(response.data.preview.review_token || '');
      target = get_inspector_host().querySelector('[data-wpbc-ui-catalog-inspector-form]');
      review_workflow = get_inline_review_workflow();
      review_model = review_workflow ? review_workflow.prepare(response.data.preview.review || {}, {
        changed_label: format_message(1 === inline_state.changed_rows.length ? config.i18n.inline_changed_row : config.i18n.inline_changed_rows, [inline_state.changed_rows.length]),
        description: config.i18n.inline_review_description || '',
        form_id: 'wpbc_catalog_booking_resources_inline_review_form',
        mode: 'inline_review',
        pending_message: config.i18n.review_changes_help || '',
        title: config.i18n.inline_review_title || ''
      }) : {};
      target.innerHTML = render_component(config, 'inspector_inline_review', review_model);
      set_inspector_state('form', '');
      configure_inspector_footer('wpbc_catalog_booking_resources_inline_review_form', config.i18n.apply_changes || '', false, !inline_state.review_token);
      if (review_workflow) {
        review_workflow.synchronize({
          busy: false,
          can_apply: !!inline_state.review_token
        });
      }
      focus_inspector_heading(target.querySelector('[data-wpbc-catalog-inline-review-form]'));
    }).catch(function (error) {
      if (request_sequence !== inline_state.request_sequence) {
        return;
      }
      inline_state.review_token = '';
      inspector_dirty = false;
      show_admin_message(error.message || config.i18n.inline_review_failed || '', 'error', 5000);
      close_inspector(config, false);
    }).then(function () {
      if (request_sequence === inline_state.request_sequence) {
        inline_state.loading = false;
        synchronize_inline_controls(config);
      }
    });
  }

  /**
   * Apply the reviewed inline plan and retain the catalog selection.
   *
   * @param {SubmitEvent} event Review form submit event.
   * @param {Object} config Catalog configuration.
   * @return {void}
   */
  function apply_inline_changes(event, config) {
    var form = event.target;
    var save_button = document.querySelector('[data-wpbc-ui-catalog-inspector-save]');
    var request_sequence;
    event.preventDefault();
    if (inline_state.loading || !inline_state.review_token || save_button && save_button.disabled) {
      return;
    }
    inline_state.loading = true;
    inspector_mutation_in_progress = true;
    if (get_inline_review_workflow()) {
      get_inline_review_workflow().synchronize({
        busy: true,
        can_apply: true
      });
    }
    request_sequence = ++inspector_mutation_request_sequence;
    if (save_button) {
      save_button.disabled = true;
      save_button.classList.add('is-busy');
    }
    request_inspector(config, config.inline_apply_action, {
      rows: JSON.stringify(inline_state.changed_rows),
      review_token: inline_state.review_token
    }).then(function (response) {
      if (request_sequence !== inspector_mutation_request_sequence || !response || !response.success || !response.data) {
        throw new Error(get_inspector_response_message(response, config.i18n.inline_apply_failed));
      }
      pending_highlight_ids = (response.data.updated_ids || []).map(String);
      inspector_mutation_in_progress = false;
      leave_inline_mode(config, true, get_inspector_response_message(response, ''));
    }).catch(function (error) {
      inspector_mutation_in_progress = false;
      inline_state.review_token = '';
      if (get_inline_review_workflow()) {
        get_inline_review_workflow().synchronize({
          busy: false,
          can_apply: false
        });
      }
      if (document.documentElement.contains(form)) {
        show_inspector_message(form, error.message || config.i18n.inline_apply_failed || '', true);
      } else {
        show_admin_message(error.message || config.i18n.inline_apply_failed || '', 'error', 5000);
      }
      if (save_button) {
        save_button.disabled = true;
        save_button.classList.remove('is-busy');
      }
    }).then(function () {
      inline_state.loading = false;
      synchronize_inline_controls(config);
    });
  }

  /**
   * Render the header, complete Resource rows, partials, and pagination.
   *
   * @param {Object} config   Registered catalog configuration.
   * @param {Object} response Normalized catalog response.
   * @return {boolean} True when every required presentation target exists.
   */
  function render_booking_resources_response(config, response) {
    var catalog_heading;
    var catalog_mount = document.getElementById(config.mount_id);
    var card_groups = {};
    var children_by_parent = {};
    var columns;
    var header_element;
    var hierarchy_enabled;
    var hierarchy_is_expanded;
    var is_cards_pack;
    var parent_resources = {};
    var pagination;
    var pagination_element;
    var rows_element;
    if (!catalog_mount || !response || !Array.isArray(response.items)) {
      return false;
    }
    header_element = catalog_mount.querySelector('[data-wpbc-booking-resources-header]');
    rows_element = catalog_mount.querySelector('[data-wpbc-booking-resources-rows]');
    pagination_element = catalog_mount.querySelector('[data-wpbc-booking-resources-pagination]');
    if (!header_element || !rows_element || !pagination_element) {
      return false;
    }
    columns = get_columns(config, response.display || {}, true, response.sorting || {});
    is_cards_pack = 'cards' === String(response.display && response.display.template_pack || '');
    hierarchy_enabled = !!(response.hierarchy && response.hierarchy.enabled);
    hierarchy_is_expanded = hierarchy_enabled && window.wpbc_ui_catalog_hierarchy && 'function' === typeof window.wpbc_ui_catalog_hierarchy.get_initial_expanded && window.wpbc_ui_catalog_hierarchy.get_initial_expanded(response.hierarchy || {});
    header_element.innerHTML = render_component(config, 'header', {
      all_expanded: hierarchy_is_expanded,
      columns: columns,
      hierarchy_enabled: hierarchy_enabled,
      i18n: config.i18n || {},
      selection_enabled: !!(config.features && config.features.selection)
    });
    rows_element.innerHTML = '';
    if (hierarchy_enabled) {
      response.items.forEach(function (resource) {
        if (resource.hierarchy && 'parent' === resource.hierarchy.type) {
          parent_resources[String(resource.id)] = resource;
        } else if (resource.hierarchy && 'child' === resource.hierarchy.type) {
          var parent_id = String(resource.hierarchy.parent_id || '');
          children_by_parent[parent_id] = children_by_parent[parent_id] || [];
          children_by_parent[parent_id].push(resource);
        }
      });
    }
    response.items.forEach(function (resource) {
      var action_target;
      var classic_label_classes = {
        child: 'wpbc_label_resource_child',
        cost: 'wpbc_label_cost',
        'default-form': 'wpbc_label_resource_default_form',
        owner: 'wpbc_label_user_owner',
        parent: 'wpbc_label_resource_parent',
        single: 'wpbc_label_resource_single'
      };
      var label_target;
      var price_target;
      var description = resource.description || config.i18n.no_description || '';
      var hierarchy = Object.assign({}, resource.hierarchy || {});
      var parent_context_label = '';
      var row_variant = hierarchy_enabled && ('parent' === hierarchy.type || 'child' === hierarchy.type) ? hierarchy.type : 'single';
      var row_template_role = 'parent' === row_variant ? 'parent_row' : 'child' === row_variant ? 'child_row' : 'row';
      var type_badge_label = config.i18n.independent_label || '';
      hierarchy.expandable = !!(hierarchy_enabled && hierarchy.expandable);
      if (hierarchy.parent_title) {
        parent_context_label = format_message(config.i18n.child_of || '', [hierarchy.parent_title]);
      }
      if ('parent' === row_variant) {
        var rendered_child_count = Math.max(0, Number(hierarchy.rendered_children_count) || 0);
        var parent_children_template = 1 === rendered_child_count ? config.i18n.parent_child_label || '%1$s · %2$s child' : config.i18n.parent_children_label || '%1$s · %2$s children';
        type_badge_label = format_message(parent_children_template, [config.i18n.parent_label || '', rendered_child_count]);
      } else if ('child' === row_variant) {
        type_badge_label = config.i18n.child_label || '';
      }
      var resource_row_data = Object.assign({}, resource, {
        parent_context_label: parent_context_label,
        collapse_label: format_message(config.i18n.collapse_children_for || config.i18n.collapse_children || '', [resource.title || '']),
        columns: columns,
        expand_label: format_message(config.i18n.expand_children_for || config.i18n.expand_children || '', [resource.title || '']),
        hierarchy: hierarchy,
        i18n: config.i18n || {},
        is_expanded: hierarchy_is_expanded,
        parent_label: config.i18n.parent_label || '',
        row_variant: row_variant,
        selection_label: format_message(config.i18n.select_resource || '', [resource.title || '']),
        selection_enabled: !!(config.features && config.features.selection),
        thumbnail_label: format_message(config.i18n.thumbnail_tooltip || '', [resource.title || '', description]),
        type_badge_label: type_badge_label
      });
      var resource_row_html = render_component(config, row_template_role, resource_row_data);
      var resource_row;
      if (!resource_row_html) {
        return;
      }
      if (is_cards_pack && 'parent' === row_variant) {
        var child_resources = children_by_parent[String(resource.id)] || [];
        var child_count = Math.max(child_resources.length, Number(hierarchy.rendered_children_count) || 0);
        var card_group_html = render_component(config, 'card_group', {
          children_description: format_message(config.i18n.children_belong_to || '', [resource.title || '']),
          children_heading: format_message(config.i18n.children_of_count || '', [resource.title || '', child_count]),
          collapse_label: resource_row_data.collapse_label,
          expand_label: resource_row_data.expand_label,
          is_expanded: hierarchy_is_expanded,
          parent_id: resource.id,
          parent_node_id: hierarchy.node_id,
          stack_items: child_resources.slice(0, 3)
        });
        if (!card_group_html) {
          return;
        }
        rows_element.insertAdjacentHTML('beforeend', card_group_html);
        card_groups[String(resource.id)] = rows_element.lastElementChild;
        var parent_slot = card_groups[String(resource.id)].querySelector('[data-wpbc-booking-resource-card-parent-slot]');
        parent_slot.insertAdjacentHTML('beforeend', resource_row_html);
        resource_row = parent_slot.lastElementChild;
      } else if (is_cards_pack && 'child' === row_variant && card_groups[String(hierarchy.parent_id)]) {
        var children_slot = card_groups[String(hierarchy.parent_id)].querySelector('[data-wpbc-booking-resource-card-children-slot]');
        children_slot.insertAdjacentHTML('beforeend', resource_row_html);
        resource_row = children_slot.lastElementChild;
      } else {
        rows_element.insertAdjacentHTML('beforeend', resource_row_html);
        resource_row = rows_element.lastElementChild;
      }
      if (!resource_row) {
        return;
      }
      label_target = resource_row.querySelector('[data-wpbc-booking-resource-labels]');
      price_target = resource_row.querySelector('[data-wpbc-booking-resource-price]');
      action_target = resource_row.querySelector('[data-wpbc-booking-resource-actions]');
      if (label_target) {
        label_target.innerHTML = render_component(config, 'labels', {
          aria_label: config.i18n.column_labels || '',
          empty_label: config.i18n.no_labels || '',
          labels: Array.isArray(resource.labels) ? resource.labels.map(function (label) {
            return Object.assign({}, label, {
              class_name: classic_label_classes[label.kind] || ''
            });
          }) : []
        });
      }
      if (price_target) {
        price_target.innerHTML = render_component(config, 'price', {
          empty_label: config.i18n.price_unavailable || '',
          price: resource.price || {}
        });
      }
      if (action_target) {
        action_target.innerHTML = render_component(config, 'action_menu', {
          actions: Array.isArray(resource.action_items) ? resource.action_items.map(function (action) {
            var action_classes = {
              adjust_capacity: 'capacity',
              delete_resource: 'delete',
              edit_resource: 'edit',
              publish_resource: 'publish'
            };
            var action_id = String(action.id || '');
            return Object.assign({}, action, {
              class_name: 'wpbc_booking_resources__action_' + (action_classes[action_id] || action_id)
            });
          }) : [],
          aria_label: format_message(config.i18n.actions_for || '', [resource.title || '']),
          empty_label: config.i18n.no_actions || '',
          menu_id: 'wpbc_' + config.id + '_actions_' + String(resource.id),
          resource_id: resource.id
        });
      }
      if (hierarchy_enabled && 'child' === row_variant && hierarchy.is_last_sibling) {
        var parent_resource = parent_resources[String(hierarchy.parent_id)];
        if (parent_resource) {
          var summary_target = is_cards_pack && card_groups[String(hierarchy.parent_id)] ? card_groups[String(hierarchy.parent_id)].querySelector('[data-wpbc-booking-resource-card-parent-slot]') : rows_element;
          summary_target.insertAdjacentHTML('beforeend', render_component(config, 'child_summary', {
            children_label: get_children_summary_label(parent_resource, config.i18n || {}),
            collapse_label: format_message(config.i18n.collapse_children_for || config.i18n.collapse_children || '', [parent_resource.title || '']),
            columns: columns,
            expand_label: format_message(config.i18n.expand_children_for || config.i18n.expand_children || '', [parent_resource.title || '']),
            is_expanded: hierarchy_is_expanded,
            parent_id: hierarchy.parent_id,
            parent_node_id: parent_resource.hierarchy.node_id,
            selection_enabled: !!(config.features && config.features.selection)
          }));
        }
      }
    });
    synchronize_overflow_tooltips(catalog_mount);
    pagination = response.pagination || {};
    pagination_element.innerHTML = render_component(config, 'pagination', {
      aria_label: config.i18n.pagination_label || '',
      has_next: Number(pagination.page_number) < Number(pagination.total_pages),
      has_previous: 1 < Number(pagination.page_number),
      items_per_page: Number(pagination.items_per_page),
      items_per_page_options: config.items_per_page && Array.isArray(config.items_per_page.options) ? config.items_per_page.options : [],
      next_label: config.i18n.next_page || '',
      next_page: Math.min(Number(pagination.total_pages), Number(pagination.page_number) + 1),
      page_number: Number(pagination.page_number),
      page_number_label: config.i18n.page_number || '',
      per_page_label: config.i18n.per_page || '',
      previous_label: config.i18n.previous_page || '',
      previous_page: Math.max(1, Number(pagination.page_number) - 1),
      results_status: format_message(config.i18n.results_status || '', [pagination.items_from, pagination.items_to, pagination.total_items]),
      show_label: config.i18n.show || '',
      total_pages: Math.max(1, Number(pagination.total_pages))
    });
    synchronize_booking_resources_toolbar(config, response);
    if (pending_focus_direction) {
      catalog_heading = catalog_mount.querySelector('[data-wpbc-catalog-heading]');
      pending_focus_direction = '';
      if (catalog_heading && 'function' === typeof catalog_heading.focus) {
        catalog_heading.focus();
      }
    }
    return rows_element.querySelectorAll('[data-wpbc-ui-catalog-selectable-row][data-wpbc-booking-resource-id]').length === response.items.length;
  }

  /**
   * Return the number of currently visible cells in one Resource row.
   *
   * @param {HTMLElement} resource_row Rendered Resource table row.
   * @return {number} Safe details-row colspan.
   */
  function get_details_colspan(resource_row) {
    var visible_cells = 0;
    if (resource_row && 'TR' === resource_row.tagName) {
      Array.prototype.forEach.call(resource_row.cells || [], function (cell) {
        if (!cell.hidden && 'none' !== window.getComputedStyle(cell).display) {
          visible_cells += 1;
        }
      });
    }
    return Math.max(1, visible_cells);
  }

  /**
   * Resolve the rendered Resource container that owns an interactive control.
   *
   * Resource controls repeat the Resource ID for event dispatch. Limiting this
   * lookup to selectable row/card containers prevents lazy templates from being
   * inserted beside the control itself.
   *
   * @param {Element|null} source_element Element inside a Resource row or card.
   * @return {HTMLElement|null} Owning Resource row/card, or null when unavailable.
   */
  function get_resource_item_container(source_element) {
    if (!source_element || 'function' !== typeof source_element.closest) {
      return null;
    }
    return source_element.closest('[data-wpbc-ui-catalog-selectable-row][data-wpbc-booking-resource-id]');
  }

  /**
   * Synchronize Cards-pack parent stages with shared hierarchy visibility.
   *
   * Child cards remain normal shared hierarchy nodes. This adapter only mirrors
   * their computed visibility onto the presentation-only tray wrapper.
   *
   * @param {HTMLElement} catalog_mount Mounted Booking Resources catalog.
   * @return {void}
   */
  function synchronize_card_group_panels(catalog_mount) {
    var catalog_root;
    if (!catalog_mount) {
      return;
    }
    catalog_root = catalog_mount.hasAttribute('data-wpbc-catalog-id') ? catalog_mount : catalog_mount.querySelector('[data-wpbc-catalog-id]');

    // The shared controller owns the active-pack attribute on the inner catalog root.
    if (!catalog_root || 'cards' !== catalog_root.getAttribute('data-wpbc-template-pack')) {
      return;
    }
    catalog_root.querySelectorAll('[data-wpbc-booking-resource-card-group]').forEach(function (card_group) {
      var children_panel = card_group.querySelector('[data-wpbc-booking-resource-card-children-panel]');
      var visible_child = card_group.querySelector('[data-wpbc-booking-resource-card-children-slot] [data-wpbc-ui-catalog-parent-node-id]:not([hidden])');
      if (children_panel) {
        children_panel.hidden = !visible_child;
        card_group.classList.toggle('is-expanded', !!visible_child);
      }
    });
  }

  /**
   * Synchronize one details disclosure button.
   *
   * @param {HTMLElement|null} toggle_button Disclosure button.
   * @param {boolean}          is_expanded   Whether its details row is open.
   * @return {void}
   */
  function set_details_toggle_state(toggle_button, is_expanded) {
    var icon;
    var label;
    if (!toggle_button) {
      return;
    }
    label = toggle_button.getAttribute(is_expanded ? 'data-hide-label' : 'data-show-label') || '';
    toggle_button.setAttribute('aria-expanded', is_expanded ? 'true' : 'false');
    toggle_button.setAttribute('aria-label', label);
    toggle_button.setAttribute('title', label);
    icon = toggle_button.querySelector('span[aria-hidden="true"]');
    if (icon) {
      icon.className = is_expanded ? 'wpbc-bi-chevron-up' : 'wpbc-bi-chevron-down';
    }
  }

  /**
   * Close the active details row and cancel its lazy request.
   *
   * @param {boolean} restore_focus Whether to return focus to the disclosure.
   * @return {void}
   */
  function close_details_row(restore_focus) {
    var active_row = document.querySelector('[data-wpbc-booking-resource-details-row]');
    var focus_target = details_toggle_button;
    details_request_sequence += 1;
    if (details_abort_controller && 'function' === typeof details_abort_controller.abort) {
      details_abort_controller.abort();
    }
    details_abort_controller = null;
    if (active_row && active_row.parentNode) {
      active_row.parentNode.removeChild(active_row);
    }
    set_details_toggle_state(details_toggle_button, false);
    if (details_toggle_button) {
      var source_row = get_resource_item_container(details_toggle_button);
      if (source_row) {
        source_row.classList.remove('is-details-expanded');
      }
    }
    details_resource_id = 0;
    details_toggle_button = null;
    if (restore_focus && focus_target && document.documentElement.contains(focus_target) && 'function' === typeof focus_target.focus) {
      focus_target.focus();
    }
  }

  /**
   * Replace the active details row through the registered WP template.
   *
   * @param {Object}      config       Catalog configuration.
   * @param {HTMLElement} resource_row Source Resource row.
   * @param {Object}      template_data Presentation-only template state.
   * @return {HTMLElement|null} Rendered details row.
   */
  function render_details_row(config, resource_row, template_data) {
    var active_row = document.querySelector('[data-wpbc-booking-resource-details-row]');
    var card_group = resource_row ? resource_row.closest('[data-wpbc-booking-resource-card-group]') : null;
    var details_html = render_component(config, 'details', template_data);
    var insertion_target = card_group || resource_row;
    if (!details_html || !insertion_target || !insertion_target.parentNode) {
      return null;
    }
    if (active_row && active_row.parentNode) {
      active_row.parentNode.removeChild(active_row);
    }
    insertion_target.insertAdjacentHTML('afterend', details_html);
    return insertion_target.nextElementSibling;
  }

  /**
   * Return a safe message from a normalized details error response.
   *
   * @param {Object} response Normalized endpoint response.
   * @param {string} fallback Localized fallback message.
   * @return {string} Safe plain-text message.
   */
  function get_details_error_message(response, fallback) {
    return response && response.error && response.error.message ? String(response.error.message) : String(fallback || '');
  }

  /**
   * Open one details row and lazily request its authorized DTO.
   *
   * @param {Object}      config        Catalog configuration.
   * @param {HTMLElement} toggle_button Details disclosure button.
   * @param {HTMLElement} resource_row  Source Resource row.
   * @param {number}      resource_id   Booking Resource ID.
   * @return {void}
   */
  function open_details_row(config, toggle_button, resource_row, resource_id) {
    var details_request_id;
    var request_body;
    var resource_title_element = resource_row.querySelector('.wpbc_ui_listing__item_title');
    var resource_title = resource_title_element ? resource_title_element.textContent.trim() : '';
    var template_base;
    close_details_row(false);
    details_resource_id = resource_id;
    details_toggle_button = toggle_button;
    details_request_id = ++details_request_sequence;
    set_details_toggle_state(toggle_button, true);
    resource_row.classList.add('is-details-expanded');
    template_base = {
      colspan: get_details_colspan(resource_row),
      resource_id: resource_id,
      title: resource_title
    };
    render_details_row(config, resource_row, Object.assign({}, template_base, {
      loading_label: config.i18n.details_loading || config.i18n.loading || '',
      state: 'loading'
    }));
    request_body = new window.URLSearchParams();
    request_body.append('action', config.details_action);
    request_body.append('nonce', config.nonce || '');
    request_body.append('request_id', String(details_request_id));
    request_body.append('resource_id', String(resource_id));
    details_abort_controller = 'function' === typeof window.AbortController ? new window.AbortController() : null;
    window.fetch(config.ajax_url, {
      body: request_body.toString(),
      credentials: 'same-origin',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
      },
      method: 'POST',
      signal: details_abort_controller ? details_abort_controller.signal : undefined
    }).then(function (response) {
      return response.json();
    }).then(function (response) {
      if (details_request_id !== details_request_sequence || resource_id !== details_resource_id) {
        return;
      }
      if (!response || true !== response.success || Number(response.request_id) !== details_request_id || Number(response.resource_id) !== resource_id || !response.details || !Array.isArray(response.details.sections)) {
        render_details_row(config, resource_row, Object.assign({}, template_base, {
          error_message: get_details_error_message(response, config.i18n.details_load_failed),
          state: 'error'
        }));
        return;
      }
      render_details_row(config, resource_row, Object.assign({}, template_base, response.details, {
        colspan: get_details_colspan(resource_row),
        state: 'ready'
      }));
      var catalog_mount = document.getElementById(config.mount_id);
      synchronize_overflow_tooltips(catalog_mount);
      initialize_details_tooltips(catalog_mount);
    }).catch(function (error) {
      if (error && 'AbortError' === error.name) {
        return;
      }
      if (details_request_id === details_request_sequence && resource_id === details_resource_id) {
        render_details_row(config, resource_row, Object.assign({}, template_base, {
          error_message: config.i18n.details_load_failed || '',
          state: 'error'
        }));
      }
    }).then(function () {
      if (details_request_id === details_request_sequence) {
        details_abort_controller = null;
      }
    });
  }

  /**
   * Copy one details value without navigating or mutating Resource state.
   *
   * @param {string}      copy_value    Plain text to copy.
   * @param {HTMLElement} action_button Copy button used to locate status text.
   * @param {Object}      config        Catalog configuration.
   * @return {void}
   */
  function copy_details_value(copy_value, action_button, config) {
    var details_row = action_button.closest('[data-wpbc-booking-resource-details-row]');
    var resource_id = Number(action_button.getAttribute('data-wpbc-booking-resource-id') || 0);
    var status_element = details_row ? details_row.querySelector('[data-wpbc-booking-resource-copy-status]') : document.querySelector('[data-wpbc-booking-resource-copy-status="' + String(resource_id) + '"]');
    var copy_promise;
    if (window.navigator.clipboard && 'function' === typeof window.navigator.clipboard.writeText) {
      copy_promise = window.navigator.clipboard.writeText(copy_value);
    } else {
      copy_promise = new window.Promise(function (resolve, reject) {
        var copy_input = document.createElement('textarea');
        copy_input.value = copy_value;
        copy_input.setAttribute('readonly', 'readonly');
        copy_input.style.position = 'fixed';
        copy_input.style.opacity = '0';
        document.body.appendChild(copy_input);
        copy_input.select();
        if (document.execCommand('copy')) {
          resolve();
        } else {
          reject();
        }
        document.body.removeChild(copy_input);
      });
    }
    copy_promise.then(function () {
      if (status_element) {
        status_element.classList.remove('has-error');
        status_element.textContent = config.i18n.shortcode_copied || '';
      }
    }).catch(function () {
      if (status_element) {
        status_element.classList.add('has-error');
        status_element.textContent = config.i18n.shortcode_copy_failed || '';
      }
    });
  }

  /**
   * Return the current effective shortcode for one Resource.
   *
   * The active inspector wins so unsaved customizer changes are used by Copy
   * and Publish. A hidden compatibility input is the fallback required by the
   * shared Booking Calendar publish wizard.
   *
   * @param {number}      resource_id  Booking Resource ID.
   * @param {HTMLElement} action_button Optional initiating action.
   * @return {string} Current effective shortcode.
   */
  function get_booking_resource_shortcode(resource_id, action_button) {
    var inspector_shortcode = resource_id === inspector_resource_id ? document.querySelector('[data-wpbc-catalog-resource-inspector-form] .wpbc_catalog_booking_resources__editor_code') : null;
    var hidden_shortcode = document.getElementById('booking_resource_shortcode_' + String(resource_id));
    if (inspector_shortcode) {
      return String(inspector_shortcode.value || '');
    }
    if (action_button && action_button.getAttribute('data-wpbc-booking-resource-shortcode')) {
      return String(action_button.getAttribute('data-wpbc-booking-resource-shortcode') || '');
    }
    return hidden_shortcode ? String(hidden_shortcode.value || '') : '';
  }

  /**
   * Create or update the hidden input consumed by the shared publish wizard.
   *
   * @param {number} resource_id Booking Resource ID.
   * @param {string} shortcode   Effective Booking shortcode.
   * @return {HTMLInputElement|null} Compatibility input or null.
   */
  function synchronize_booking_resource_shortcode_input(resource_id, shortcode) {
    var input;
    if (!resource_id) {
      return null;
    }
    input = document.getElementById('booking_resource_shortcode_' + String(resource_id));
    if (!input) {
      input = document.createElement('input');
      input.type = 'hidden';
      input.id = 'booking_resource_shortcode_' + String(resource_id);
      input.setAttribute('data-wpbc-catalog-shortcode-compatibility', String(resource_id));
      document.body.appendChild(input);
    }
    input.value = String(shortcode || '');
    return input;
  }

  /**
   * Open the shared Booking Calendar shortcode customizer for one Resource.
   *
   * @param {number} resource_id Booking Resource ID.
   * @param {string} shortcode   Current shortcode.
   * @return {void}
   */
  function customize_booking_resource_shortcode(resource_id, shortcode) {
    synchronize_booking_resource_shortcode_input(resource_id, shortcode);
    if ('function' === typeof window.wpbc_resource_page_btn_click) {
      window.wpbc_resource_page_btn_click(resource_id, shortcode);
    }
  }

  /**
   * Open the shared Booking Calendar embed/create-page wizard.
   *
   * @param {number} resource_id Booking Resource ID.
   * @param {string} shortcode   Current shortcode.
   * @return {void}
   */
  function publish_booking_resource_shortcode(resource_id, shortcode, trigger_button) {
    synchronize_booking_resource_shortcode_input(resource_id, shortcode);
    if ('function' === typeof window.wpbc_publish_booking_form__open) {
      window.wpbc_publish_booking_form__open(resource_id, shortcode, trigger_button);
    }
  }

  /**
   * Open the informational Free-edition Booking Resource upgrade dialog.
   *
   * @param {HTMLElement} trigger_button Button that opened the dialog.
   * @return {void}
   */
  function open_booking_resource_upgrade_dialog(trigger_button) {
    var modal_element = document.getElementById('wpbc_catalog_booking_resources__upgrade_modal');
    var upgrade_url = trigger_button ? trigger_button.getAttribute('data-wpbc-catalog-booking-resource-upgrade-url') : '';
    if (modal_element && window.jQuery && 'function' === typeof window.jQuery(modal_element).wpbc_my_modal) {
      window.jQuery(modal_element).off('hidden.wpbc.modal.wpbcCatalogResourceUpgrade hidden.bs.modal.wpbcCatalogResourceUpgrade').one('hidden.wpbc.modal.wpbcCatalogResourceUpgrade hidden.bs.modal.wpbcCatalogResourceUpgrade', function () {
        if (trigger_button && document.contains(trigger_button)) {
          trigger_button.focus();
        }
      }).wpbc_my_modal('show');
      return;
    }
    if (upgrade_url) {
      window.open(upgrade_url, '_blank', 'noopener');
    }
  }

  /**
   * Open the reusable native catalog message dialog.
   *
   * Message content is assigned with textContent so translated or server-provided
   * text cannot become dialog markup. The browser alert remains a resilience
   * fallback when the Booking Calendar modal runtime is unavailable.
   *
   * @param {string}      message        Message shown in the dialog body.
   * @param {string}      title          Optional dialog heading.
   * @param {HTMLElement} trigger_button Control that opened the dialog.
   * @return {boolean} True when the native dialog opened; otherwise false.
   */
  function open_booking_resource_message_dialog(message, title, trigger_button) {
    var modal_element = document.getElementById('wpbc_catalog_booking_resources__message_modal');
    var title_element = document.getElementById('wpbc_catalog_booking_resources__message_modal_title');
    var description_element = document.getElementById('wpbc_catalog_booking_resources__message_modal_description');
    if (message && modal_element && description_element && window.jQuery && 'function' === typeof window.jQuery(modal_element).wpbc_my_modal) {
      description_element.textContent = message;
      if (title_element) {
        title_element.textContent = title || title_element.getAttribute('data-wpbc-default-title') || '';
      }
      window.jQuery(modal_element).off('hidden.wpbc.modal.wpbcCatalogResourceMessage hidden.bs.modal.wpbcCatalogResourceMessage').one('hidden.wpbc.modal.wpbcCatalogResourceMessage hidden.bs.modal.wpbcCatalogResourceMessage', function () {
        if (trigger_button && document.contains(trigger_button)) {
          trigger_button.focus();
        }
      }).wpbc_my_modal('show');
      return true;
    }
    if (message && 'function' === typeof window.alert) {
      window.alert(message);
    }
    return false;
  }

  /**
   * Return the template-driven inspector host.
   *
   * @return {HTMLElement|null} Inspector host or null.
   */
  function get_inspector_host() {
    return document.querySelector('[data-wpbc-catalog-booking-resources-inspector-host]');
  }

  /**
   * Return the sticky native-sidebar footer.
   *
   * @return {HTMLElement|null} Footer element or null.
   */
  function get_inspector_footer() {
    return document.querySelector('[data-wpbc-ui-catalog-inspector-footer]');
  }

  /**
   * Return the shared native inspector state workflow.
   *
   * @param {Object} config Catalog configuration.
   * @return {Object|false} Shared inspector workflow or false.
   */
  function get_inspector_workflow(config) {
    if (inspector_workflow_controller) {
      return inspector_workflow_controller;
    }
    if (!window.wpbc_ui_catalog || 'function' !== typeof window.wpbc_ui_catalog.create_inspector_workflow) {
      return false;
    }
    inspector_workflow_controller = window.wpbc_ui_catalog.create_inspector_workflow({
      expand: expand_inspector_sidebar,
      get_footer: get_inspector_footer,
      get_host: get_inspector_host,
      render_shell: function (shell_data) {
        return render_component(config, 'inspector', shell_data);
      },
      shell_data: {
        catalog_id: config.id,
        empty_icon: 'wpbc-bi-pencil-square',
        empty_message: config.i18n.inspector_empty_message || '',
        empty_title: config.i18n.inspector_empty_title || '',
        loading_label: config.i18n.inspector_loading || config.i18n.loading || ''
      }
    });
    return inspector_workflow_controller;
  }

  /**
   * Render the shared inspector fallback-state shell once.
   *
   * @param {Object} config Catalog configuration.
   * @return {boolean} True when the shell is available.
   */
  function mount_inspector_shell(config) {
    var inspector_workflow = get_inspector_workflow(config);
    return !!inspector_workflow && inspector_workflow.mount();
  }

  /**
   * Expand the native right sidebar after an explicit editor action.
   *
   * @return {void}
   */
  function expand_inspector_sidebar() {
    synchronize_inspector_width();
    if ('function' === typeof window.wpbc_admin_ui__sidebar_right__do_max) {
      window.wpbc_admin_ui__sidebar_right__do_max();
    }
    document.dispatchEvent(new CustomEvent('wpbc_setup_wizard_layout_changed'));
  }

  /**
   * Apply the established wider sidebar only while creating Resources.
   *
   * The new catalog owns its class and styling so it does not depend on the old
   * listing assets while retaining the same native-sidebar width contract.
   *
   * @return {void}
   */
  function synchronize_inspector_width() {
    var host = get_inspector_host();
    var sidebar = host ? host.closest('.wpbc_ui_el__vert_right_bar__wrapper') : null;
    if (sidebar) {
      sidebar.classList.toggle('wpbc_catalog_booking_resources__inspector_width--wide', 'create' === inspector_mode);
    }
  }

  /**
   * Mark only the Resource currently owned by the inspector.
   *
   * @param {number} resource_id Resource ID or zero to clear highlighting.
   * @return {void}
   */
  function mark_inspector_resource_row(resource_id) {
    document.querySelectorAll('[data-wpbc-booking-resource-id].is-editor-active').forEach(function (row) {
      row.classList.remove('is-editor-active');
    });
    if (resource_id) {
      var row = document.querySelector('[data-wpbc-booking-resource-id="' + String(resource_id) + '"]');
      if (row) {
        row.classList.add('is-editor-active');
        row.scrollIntoView({
          block: 'nearest',
          behavior: 'smooth'
        });
      }
    }
  }

  /**
   * Synchronize shared empty, loading, error, form, and footer states.
   *
   * @param {string} state Empty, loading, error, or form.
   * @param {string} message Optional safe error message.
   * @return {void}
   */
  function set_inspector_state(state, message) {
    if (inspector_workflow_controller) {
      inspector_workflow_controller.set_state(state, message);
    }
  }

  /**
   * Serialize current editable field values for dirty-state comparison.
   *
   * @return {string} Stable JSON field snapshot.
   */
  function serialize_inspector_fields() {
    var fields = {};
    document.querySelectorAll('[data-wpbc-catalog-resource-inspector-form] [data-wpbc-catalog-resource-radio-field]:checked').forEach(function (field) {
      fields[field.getAttribute('data-wpbc-catalog-resource-radio-field') || ''] = field.value;
    });
    document.querySelectorAll('[data-wpbc-catalog-resource-inspector-form] [data-wpbc-catalog-resource-field]').forEach(function (field) {
      fields[field.getAttribute('data-wpbc-catalog-resource-field') || ''] = field.value;
    });
    return JSON.stringify(fields);
  }

  /**
   * Return the currently selected Resource creation mode.
   *
   * @return {string} Independent or children.
   */
  function get_inspector_creation_mode() {
    var selected_mode = document.querySelector('[data-wpbc-catalog-resource-radio-field="creation_mode"]:checked');
    var hidden_mode = document.querySelector('[data-wpbc-catalog-resource-field="creation_mode"]');
    return String(selected_mode ? selected_mode.value : hidden_mode ? hidden_mode.value : 'independent');
  }

  /**
   * Synchronize create-only conditional fields and radio-card presentation.
   *
   * These controls improve clarity only. The create service independently
   * authorizes the selected parent and derives all inherited values.
   *
   * @return {void}
   */
  function synchronize_create_inspector_controls() {
    if ('create' !== inspector_mode) {
      return;
    }
    var creation_mode = get_inspector_creation_mode();
    var parent_wrap = document.querySelector('[data-wpbc-catalog-resource-field-wrap="parent_id"]');
    document.querySelectorAll('[data-wpbc-catalog-resource-radio-field="creation_mode"]').forEach(function (radio) {
      var choice = radio.closest('label');
      if (choice) {
        choice.classList.toggle('is-selected', radio.checked);
      }
    });
    if (parent_wrap) {
      parent_wrap.hidden = 'children' !== creation_mode;
      parent_wrap.classList.toggle('is-conditionally-hidden', 'children' !== creation_mode);
    }
    ['base_cost', 'default_form', 'owner_user_id'].forEach(function (field_key) {
      var field_wrap = document.querySelector('[data-wpbc-catalog-resource-field-wrap="' + field_key + '"]');
      if (field_wrap) {
        field_wrap.hidden = 'children' === creation_mode;
        field_wrap.classList.toggle('is-conditionally-hidden', 'children' === creation_mode);
      }
    });
  }

  /**
   * Synchronize Resource-specific changeover controls with the selected mode.
   *
   * Visibility is a usability aid only. The Resource mutation service reloads
   * and validates the policy, ownership, edition, and exact time values.
   *
   * @return {void}
   */
  function synchronize_changeover_policy_controls() {
    var form = document.querySelector('[data-wpbc-catalog-resource-inspector-form][data-mode="edit"]');
    var policy_field = form ? form.querySelector('[data-wpbc-catalog-resource-field="changeover_policy_mode"]') : null;
    var is_custom = !!policy_field && 'custom' === String(policy_field.value || '');
    var global_times_wrap = form ? form.querySelector('[data-wpbc-catalog-resource-field-wrap="changeover_global_times"]') : null;
    if (global_times_wrap) {
      global_times_wrap.hidden = !policy_field || 'global' !== String(policy_field.value || '');
      global_times_wrap.classList.toggle('is-conditionally-hidden', global_times_wrap.hidden);
    }
    ['changeover_check_in_time', 'changeover_check_out_time'].forEach(function (field_key) {
      var field_wrap = form ? form.querySelector('[data-wpbc-catalog-resource-field-wrap="' + field_key + '"]') : null;
      var field = form ? form.querySelector('[data-wpbc-catalog-resource-field="' + field_key + '"]') : null;
      if (field_wrap) {
        field_wrap.hidden = !is_custom;
        field_wrap.classList.toggle('is-conditionally-hidden', !is_custom);
      }
      if (field) {
        field.disabled = !is_custom;
        field.required = is_custom;
      }
    });
  }

  /**
   * Record a deliberate change to the Resource changeover fields.
   *
   * Untouched Inspector submissions preserve absent metadata and therefore do
   * not migrate the legacy exception list merely because another field changed.
   *
   * @param {HTMLElement} field Changed control.
   * @return {void}
   */
  function mark_changeover_policy_touched(field) {
    var field_key = field ? String(field.getAttribute('data-wpbc-catalog-resource-field') || '') : '';
    var touched_field;
    if (-1 === ['changeover_policy_mode', 'changeover_check_in_time', 'changeover_check_out_time'].indexOf(field_key)) {
      return;
    }
    touched_field = document.querySelector('[data-wpbc-catalog-resource-inspector-form] [data-wpbc-catalog-resource-field="changeover_policy_touched"]');
    if (touched_field) {
      touched_field.value = '1';
    }
  }

  /**
   * Synchronize dirty state and the sticky primary action.
   *
   * @return {void}
   */
  function synchronize_inspector_dirty_state() {
    var save_button = document.querySelector('[data-wpbc-ui-catalog-inspector-save]');
    var form = document.querySelector('[data-wpbc-catalog-resource-inspector-form]');
    var save_is_busy = !!save_button && save_button.classList.contains('is-busy');
    inspector_dirty = !!form && serialize_inspector_fields() !== inspector_original_fields;
    if (save_button) {
      var title_field = form ? form.querySelector('[data-wpbc-catalog-resource-field="title"]') : null;
      var parent_field = form ? form.querySelector('[data-wpbc-catalog-resource-field="parent_id"]') : null;
      var create_is_valid = 'create' !== inspector_mode || form && 'true' === form.getAttribute('data-can-create') && title_field && '' !== String(title_field.value || '').trim() && ('children' !== get_inspector_creation_mode() || parent_field && Number(parent_field.value) > 0);
      save_button.disabled = save_is_busy || !form || !inspector_dirty || !create_is_valid;
    }
  }

  /**
   * Confirm whether the active inspector may be replaced or closed.
   *
   * @param {Object} config Catalog configuration.
   * @return {boolean} True when navigation may continue.
   */
  function can_discard_inspector(config) {
    if (inspector_mutation_in_progress) {
      return false;
    }
    return !inspector_dirty || window.confirm(config.i18n.inspector_discard || '');
  }

  /**
   * Close the inspector without changing catalog checkbox selection.
   *
   * @param {Object}  config         Catalog configuration.
   * @param {boolean} confirm_discard Whether dirty state needs confirmation.
   * @return {boolean} True when closed.
   */
  function close_inspector(config, confirm_discard) {
    if (confirm_discard && !can_discard_inspector(config)) {
      return false;
    }
    inspector_request_sequence += 1;
    inspector_dirty = false;
    inspector_mode = '';
    inspector_original_fields = '';
    inspector_resource_id = 0;
    inspector_resource_ids = [];
    inspector_bulk_operations = {};
    inspector_review_token = '';
    inspector_selection_stale = false;
    inspector_tracks_selection = false;
    inspector_capacity_context = null;
    inspector_capacity_detach_ids = [];
    inspector_capacity_decrease_action = 'detach';
    inspector_capacity_target = 0;
    synchronize_inspector_width();
    set_inspector_state('empty', '');
    mark_inspector_resource_row(0);
    if ('function' === typeof window.wpbc_admin_ui__sidebar_right__do_hide) {
      window.wpbc_admin_ui__sidebar_right__do_hide();
    }
    document.dispatchEvent(new CustomEvent('wpbc_setup_wizard_layout_changed'));
    if (inspector_focus_target && document.documentElement.contains(inspector_focus_target) && 'function' === typeof inspector_focus_target.focus) {
      inspector_focus_target.focus();
    }
    inspector_focus_target = null;
    return true;
  }

  /**
   * Post one authenticated inspector request.
   *
   * @param {Object} config Catalog configuration.
   * @param {string} action AJAX action.
   * @param {Object} values Request values.
   * @return {Promise<Object>} Parsed WordPress response.
   */
  function request_inspector(config, action, values) {
    var body = new window.URLSearchParams();
    body.append('action', action);
    body.append('nonce', config.nonce || '');
    Object.keys(values || {}).forEach(function (key) {
      body.append(key, String(values[key]));
    });
    return window.fetch(config.ajax_url, {
      body: body.toString(),
      credentials: 'same-origin',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
      },
      method: 'POST'
    }).then(function (response) {
      return response.json();
    });
  }

  /**
   * Return the explicit selection owned by the shared catalog controller.
   *
   * @param {Object} config Catalog configuration.
   * @return {Array<number>} Selected positive Resource IDs.
   */
  function get_selected_resource_ids(config) {
    var mount = document.getElementById(config.mount_id);
    var selection = mount && mount._wpbc_ui_catalog_selection_controller;
    var selected_ids = selection && 'function' === typeof selection.get_selected_ids ? selection.get_selected_ids() : [];
    return selected_ids.map(Number).filter(function (resource_id) {
      return resource_id > 0;
    });
  }

  /**
   * Compare two Resource-ID selections without relying on event ordering.
   *
   * @param {Array<number|string>} first_ids  First ID list.
   * @param {Array<number|string>} second_ids Second ID list.
   * @return {boolean} True when both lists contain the same Resource IDs.
   */
  function resource_id_lists_match(first_ids, second_ids) {
    var normalize_ids = function (resource_ids) {
      return (resource_ids || []).map(Number).filter(function (resource_id) {
        return resource_id > 0;
      }).sort(function (first_id, second_id) {
        return first_id - second_id;
      });
    };
    return JSON.stringify(normalize_ids(first_ids)) === JSON.stringify(normalize_ids(second_ids));
  }

  /**
   * Return a localized selection-count label.
   *
   * @param {Object} config Catalog configuration.
   * @param {number} count  Number of selected Resources.
   * @return {string} Count and localized noun.
   */
  function get_selection_count_label(config, count) {
    return String(count) + ' ' + (1 === Number(count) ? config.i18n.resource_selected || '' : config.i18n.resources_selected || '');
  }

  /**
   * Configure the native sticky footer for the active inspector workflow.
   *
   * @param {string} form_id      Form receiving the submit action.
   * @param {string} button_label Localized primary label.
   * @param {boolean} destructive Whether the action permanently deletes data.
   * @param {boolean} disabled    Whether submission starts disabled.
   * @return {void}
   */
  function configure_inspector_footer(form_id, button_label, destructive, disabled) {
    var footer = get_inspector_footer();
    var save_button = footer ? footer.querySelector('[data-wpbc-ui-catalog-inspector-save]') : null;
    var cancel_button = footer ? footer.querySelector('[data-wpbc-ui-catalog-inspector-cancel]') : null;
    var delete_workflow;
    if (save_button) {
      save_button.classList.remove('is-busy', 'button-link-delete', 'wpbc_catalog_booking_resources__delete_submit', 'wpbc_ui_listing__inspector_action--destructive', 'wpbc_booking_resources__delete_confirm_button', 'wpbc_ui_catalog_delete_review__apply');
      save_button.classList.toggle('button-primary', !destructive);
      save_button.classList.toggle('button-secondary', !!destructive);
      save_button.classList.toggle('wpbc_ui_listing__inspector_action--destructive', !!destructive);
      save_button.classList.toggle('wpbc_booking_resources__delete_confirm_button', !!destructive);
      save_button.textContent = button_label || '';
      save_button.setAttribute('form', form_id);
      save_button.disabled = !!disabled;
    }
    if (cancel_button) {
      cancel_button.textContent = window.wpbc_catalog_booking_resources_config && window.wpbc_catalog_booking_resources_config.i18n ? window.wpbc_catalog_booking_resources_config.i18n.cancel || cancel_button.textContent : cancel_button.textContent;
      cancel_button.disabled = false;
    }
    if (destructive && 'wpbc_catalog_booking_resources_delete_form' === form_id) {
      delete_workflow = get_delete_review_workflow();
      if (delete_workflow) {
        delete_workflow.configure_footer({
          can_apply: true,
          footer: footer,
          form_id: form_id,
          label: button_label
        });
      }
    }
  }

  /**
   * Emphasize the permanent-deletion acknowledgement.
   *
   * Restarting the finite animation mirrors the established Booking Resource
   * editor behavior when a deletion review opens or acknowledgement is cleared.
   *
   * @param {HTMLElement|null} acknowledgement Deletion acknowledgement label.
   * @return {void}
   */
  function pulse_delete_acknowledgement(acknowledgement) {
    var delete_workflow = get_delete_review_workflow();
    if (acknowledgement && acknowledgement.matches('.wpbc_ui_catalog_delete_review__acknowledgement') && delete_workflow) {
      delete_workflow.pulse_acknowledgement();
      return;
    }
    if (!acknowledgement) {
      return;
    }
    acknowledgement.classList.remove('wpbc_booking_resources__delete_acknowledgement--attention');
    void acknowledgement.offsetWidth;
    acknowledgement.classList.add('wpbc_booking_resources__delete_acknowledgement--attention');
  }

  /**
   * Return a safe message from a WordPress inspector response.
   *
   * @param {Object} response Response payload.
   * @param {string} fallback Fallback message.
   * @return {string} Plain message.
   */
  function get_inspector_response_message(response, fallback) {
    return response && response.data && response.data.message ? String(response.data.message) : String(fallback || '');
  }

  /**
   * Show a success or error notice in the active inspector.
   *
   * @param {HTMLFormElement} form     Inspector form.
   * @param {string}          message  Safe server or localized message.
   * @param {boolean}         is_error Whether the notice represents an error.
   * @return {void}
   */
  function show_inspector_message(form, message, is_error) {
    var notice = form ? form.querySelector('[data-wpbc-catalog-resource-inspector-message]') : null;
    if (!notice) {
      return;
    }
    notice.classList.toggle('notice-error', !!is_error);
    notice.classList.toggle('notice-success', !is_error);
    notice.hidden = !message;
    var notice_text = notice.querySelector('p');
    if (notice_text) {
      notice_text.textContent = message || '';
    }
  }

  /**
   * Move keyboard focus to the heading of a newly rendered reviewed inspector.
   *
   * @param {HTMLFormElement|null} form Rendered inspector form.
   * @return {void}
   */
  function focus_inspector_heading(form) {
    var heading = form ? form.querySelector('[data-wpbc-catalog-resource-inspector-heading]') : null;
    if (!heading) {
      return;
    }
    window.setTimeout(function () {
      if (document.documentElement.contains(heading) && 'function' === typeof heading.focus) {
        heading.focus();
      }
    }, 0);
  }

  /**
   * Show a standard Booking Calendar administration notice.
   *
   * Successful mutations use the shared top-right notice area used by the
   * Booking Calendar settings pages. The boolean return lets callers retain
   * an inline fallback when that shared helper is unavailable.
   *
   * @param {string} message      Safe server or localized message.
   * @param {string} message_type Notice type accepted by the shared helper.
   * @param {number} delay        Notice visibility duration in milliseconds.
   * @return {boolean} True when the message was displayed.
   */
  function show_admin_message(message, message_type, delay) {
    var config;
    var mount_element;
    var notice;
    var notice_text;
    if (!message) {
      return false;
    }
    if ('function' === typeof window.wpbc_admin_show_message) {
      window.wpbc_admin_show_message(message, message_type || 'info', delay || 4000, false);
      return true;
    }
    config = window.wpbc_catalog_booking_resources_config || {};
    mount_element = config.mount_id ? document.getElementById(config.mount_id) : null;
    mount_element = mount_element && mount_element.parentNode ? mount_element.parentNode : document.getElementById('wpbody-content') || document.body;
    if (!mount_element) {
      return false;
    }
    notice = document.createElement('div');
    notice.className = 'notice notice-' + ('error' === message_type ? 'error' : 'success') + ' wpbc_catalog_booking_resources__mutation_notice';
    notice.setAttribute('role', 'error' === message_type ? 'alert' : 'status');
    notice.setAttribute('aria-live', 'error' === message_type ? 'assertive' : 'polite');
    notice_text = document.createElement('p');
    notice_text.textContent = message;
    notice.appendChild(notice_text);
    mount_element.insertBefore(notice, mount_element.firstChild);
    window.setTimeout(function () {
      if (notice.parentNode) {
        notice.parentNode.removeChild(notice);
      }
    }, delay || 4000);
    return true;
  }

  /**
   * Open and focus one server-defined edit-inspector section.
   *
   * The shared collapsible controller keeps exclusive group state and ARIA
   * attributes synchronized. The header click is retained as a compatibility
   * fallback for older administration bundles.
   *
   * @param {string} section_id Inspector section identifier.
   * @return {boolean} True when the requested section exists.
   */
  function activate_inspector_section(section_id) {
    var form;
    var group;
    var root;
    var controller;
    var header;
    section_id = String(section_id || '');
    if (!/^[a-z0-9_]+$/.test(section_id)) {
      return false;
    }
    form = document.querySelector('[data-wpbc-catalog-resource-inspector-form][data-mode="edit"]');
    group = form ? form.querySelector('[data-group="catalog-booking-resource-' + section_id + '"]') : null;
    header = group ? group.querySelector('.group__header') : null;
    if (!group || !header) {
      return false;
    }
    root = group.closest('.wpbc_collapsible');
    controller = root && root.__wpbc_collapsible_instance ? root.__wpbc_collapsible_instance : null;
    if (controller && 'function' === typeof controller.expand) {
      controller.expand(group);
    } else if (!group.classList.contains('is-open')) {
      header.click();
    }
    window.setTimeout(function () {
      group.scrollIntoView({
        behavior: 'smooth',
        block: 'start'
      });
      header.focus();
    }, 120);
    return true;
  }

  /**
   * Render one server-authoritative create or edit schema.
   *
   * @param {Object} config Catalog configuration.
   * @param {Object} schema Inspector schema.
   * @param {boolean} focus_title Whether the title control receives focus.
   * @return {boolean} True when rendered.
   */
  function render_inspector_schema(config, schema, focus_title) {
    var host = get_inspector_host();
    var form_target = host ? host.querySelector('[data-wpbc-ui-catalog-inspector-form]') : null;
    var footer = get_inspector_footer();
    var save_button = footer ? footer.querySelector('[data-wpbc-ui-catalog-inspector-save]') : null;
    var template_role = 'create' === schema.mode ? 'inspector_create' : 'inspector_edit';
    if (!form_target) {
      return false;
    }
    form_target.innerHTML = render_component(config, template_role, {
      i18n: config.i18n || {},
      schema: schema
    });
    var form = form_target.querySelector('[data-wpbc-catalog-resource-inspector-form]');
    if (!form) {
      return false;
    }
    if ('create' === schema.mode) {
      form.setAttribute('data-can-create', schema.can_create ? 'true' : 'false');
    }
    inspector_mode = schema.mode;
    inspector_resource_id = Number(schema.resource_id) || 0;
    if (inspector_resource_id) {
      var shortcode_field = form.querySelector('.wpbc_catalog_booking_resources__editor_code');
      if (shortcode_field) {
        synchronize_booking_resource_shortcode_input(inspector_resource_id, shortcode_field.value);
      }
    }
    set_inspector_state('form', '');
    if (save_button) {
      configure_inspector_footer('wpbc_catalog_booking_resource_inspector_form', 'create' === inspector_mode ? config.i18n.add_resource || '' : config.i18n.save_changes || '', false, true);
    }
    var cancel_button = footer ? footer.querySelector('[data-wpbc-ui-catalog-inspector-cancel]') : null;
    if (cancel_button) {
      cancel_button.disabled = false;
    }
    if ('function' === typeof window.WPBC_Collapsible_AutoInit) {
      window.WPBC_Collapsible_AutoInit();
    }
    synchronize_all_inspector_numeric_ranges();
    synchronize_create_inspector_controls();
    synchronize_changeover_policy_controls();
    inspector_original_fields = serialize_inspector_fields();
    inspector_dirty = false;
    synchronize_inspector_dirty_state();
    mark_inspector_resource_row(inspector_resource_id);
    if (false !== focus_title) {
      window.setTimeout(function () {
        var title_field = form.querySelector('[data-wpbc-catalog-resource-field="title"]');
        if (title_field && 'function' === typeof title_field.focus) {
          title_field.focus();
        }
      }, 120);
    }
    return true;
  }

  /**
   * Load and open one create or edit inspector.
   *
   * @param {Object}      config       Catalog configuration.
   * @param {string}      mode         Create or edit.
   * @param {number}      resource_id  Resource ID for edit.
   * @param {HTMLElement} focus_target Initiating control.
   * @param {string}      section_id   Optional edit section to open and focus.
   * @return {void}
   */
  function open_inspector(config, mode, resource_id, focus_target, section_id) {
    var request_sequence;
    var action;
    resource_id = Number(resource_id) || 0;
    if ('edit' === mode && resource_id === inspector_resource_id && document.querySelector('[data-wpbc-catalog-resource-inspector-form][data-mode="edit"]')) {
      expand_inspector_sidebar();
      mark_inspector_resource_row(resource_id);
      if (section_id) {
        activate_inspector_section(section_id);
      }
      return;
    }
    if (!can_discard_inspector(config) || !mount_inspector_shell(config)) {
      return;
    }
    close_details_row(false);
    inspector_focus_target = focus_target || document.activeElement;
    request_sequence = ++inspector_request_sequence;
    inspector_dirty = false;
    inspector_mode = mode;
    inspector_resource_id = resource_id;
    synchronize_inspector_width();
    action = 'create' === mode ? config.inspector_create_schema_action : config.inspector_edit_schema_action;
    set_inspector_state('loading', '');
    mark_inspector_resource_row(inspector_resource_id);
    expand_inspector_sidebar();
    request_inspector(config, action, 'edit' === mode ? {
      resource_id: inspector_resource_id
    } : {}).then(function (response) {
      if (request_sequence !== inspector_request_sequence) {
        return;
      }
      if (!response || true !== response.success || !response.data || !response.data.schema || !render_inspector_schema(config, response.data.schema, !section_id)) {
        set_inspector_state('error', get_inspector_response_message(response, config.i18n.inspector_load_failed));
      } else if (section_id) {
        activate_inspector_section(section_id);
      }
    }).catch(function () {
      if (request_sequence === inspector_request_sequence) {
        set_inspector_state('error', config.i18n.inspector_load_failed || '');
      }
    });
  }

  /**
   * Render the server-generated common-field bulk editor.
   *
   * @param {Object} config Catalog configuration.
   * @param {Object} schema Authorized bulk schema.
   * @return {boolean} True when the form rendered.
   */
  function render_bulk_editor(config, schema) {
    var host = get_inspector_host();
    var target = host ? host.querySelector('[data-wpbc-ui-catalog-inspector-form]') : null;
    if (!target) {
      return false;
    }
    target.innerHTML = render_component(config, 'inspector_bulk_edit', {
      i18n: config.i18n || {},
      schema: schema,
      selection_label: get_selection_count_label(config, schema.selection_count)
    });
    if (!target.querySelector('[data-wpbc-catalog-resource-bulk-form]')) {
      return false;
    }
    inspector_mode = 'bulk_edit';
    inspector_resource_id = 0;
    inspector_resource_ids = (schema.resource_ids || []).map(Number);
    inspector_bulk_operations = {};
    inspector_review_token = '';
    inspector_selection_stale = false;
    inspector_tracks_selection = true;
    inspector_dirty = false;
    set_inspector_state('form', '');
    configure_inspector_footer('wpbc_catalog_booking_resources_bulk_form', config.i18n.review_changes_button || '', false, true);
    if ('function' === typeof window.WPBC_Collapsible_AutoInit) {
      window.WPBC_Collapsible_AutoInit();
    }
    mark_inspector_resource_row(0);
    focus_inspector_heading(target.querySelector('[data-wpbc-catalog-resource-bulk-form]'));
    return true;
  }

  /**
   * Open a bulk editor for the current explicit selection.
   *
   * @param {Object}      config       Catalog configuration.
   * @param {HTMLElement} focus_target Initiating control.
   * @return {void}
   */
  function open_bulk_editor(config, focus_target) {
    var resource_ids = get_selected_resource_ids(config);
    var request_sequence;
    if (!resource_ids.length || !can_discard_inspector(config) || !mount_inspector_shell(config)) {
      return;
    }
    close_details_row(false);
    inspector_focus_target = focus_target || document.activeElement;
    request_sequence = ++inspector_request_sequence;
    inspector_mode = 'bulk_edit';
    inspector_resource_ids = resource_ids.slice();
    inspector_dirty = false;
    inspector_tracks_selection = true;
    synchronize_inspector_width();
    set_inspector_state('loading', '');
    expand_inspector_sidebar();
    request_inspector(config, config.bulk_schema_action, {
      resource_ids: JSON.stringify(resource_ids)
    }).then(function (response) {
      if (request_sequence !== inspector_request_sequence) {
        return;
      }
      if (!response || true !== response.success || !response.data || !response.data.schema || !render_bulk_editor(config, response.data.schema)) {
        set_inspector_state('error', get_inspector_response_message(response, config.i18n.bulk_load_failed));
      } else if (!resource_id_lists_match(inspector_resource_ids, get_selected_resource_ids(config))) {
        handle_inspector_selection_change(null, config);
      }
    }).catch(function () {
      if (request_sequence === inspector_request_sequence) {
        set_inspector_state('error', config.i18n.bulk_load_failed || '');
      }
    });
  }

  /**
   * Return only explicitly enabled bulk operations.
   *
   * @return {Object} Operation envelope keyed by field.
   */
  function collect_bulk_operations() {
    var operations = {};
    document.querySelectorAll('[data-wpbc-catalog-resource-bulk-enable]:checked').forEach(function (enabled_control) {
      var field_key = enabled_control.getAttribute('data-wpbc-catalog-resource-bulk-enable') || '';
      var operation = document.querySelector('[data-wpbc-catalog-resource-bulk-operation="' + field_key + '"]');
      var field_value = document.querySelector('[data-wpbc-catalog-resource-bulk-value="' + field_key + '"]');
      if (field_key && operation && field_value) {
        operations[field_key] = {
          operation: operation.value,
          value: field_value.value
        };
      }
    });
    return operations;
  }

  /**
   * Synchronize one optional bulk field and the review action.
   *
   * @param {HTMLElement|null} changed_control Control that changed, when available.
   * @return {void}
   */
  function synchronize_bulk_editor(changed_control) {
    var field_wrap = changed_control ? changed_control.closest('[data-wpbc-catalog-resource-bulk-field]') : null;
    var save_button = document.querySelector('[data-wpbc-ui-catalog-inspector-save]');
    if (field_wrap) {
      var enabled_control = field_wrap.querySelector('[data-wpbc-catalog-resource-bulk-enable]');
      var operation_control = field_wrap.querySelector('[data-wpbc-catalog-resource-bulk-operation]');
      var prefix_element = field_wrap.querySelector('[data-wpbc-catalog-resource-bulk-prefix]');
      var suffix_element = field_wrap.querySelector('[data-wpbc-catalog-resource-bulk-suffix]');
      var enabled = !!enabled_control && enabled_control.checked;
      var operation_id = operation_control ? String(operation_control.value || '') : '';
      var is_percent = -1 !== operation_id.indexOf('percent');
      field_wrap.classList.toggle('is-enabled', enabled);
      field_wrap.querySelectorAll('[data-wpbc-catalog-resource-bulk-operation], [data-wpbc-catalog-resource-bulk-value], [data-wpbc-catalog-resource-bulk-range]').forEach(function (control) {
        control.disabled = !enabled;
      });
      if (prefix_element) {
        prefix_element.textContent = is_percent ? '' : field_wrap.getAttribute('data-wpbc-catalog-resource-bulk-prefix') || '';
      }
      if (suffix_element) {
        suffix_element.textContent = is_percent ? '%' : field_wrap.getAttribute('data-wpbc-catalog-resource-bulk-suffix') || '';
      }
      if (changed_control && changed_control.matches('[data-wpbc-catalog-resource-bulk-range]')) {
        var number_control = field_wrap.querySelector('[data-wpbc-catalog-resource-bulk-value]');
        if (number_control) {
          number_control.value = changed_control.value;
        }
      } else {
        var range_control = field_wrap.querySelector('[data-wpbc-catalog-resource-bulk-range]');
        var field_value_control = field_wrap.querySelector('[data-wpbc-catalog-resource-bulk-value]');
        if (range_control && field_value_control && '' !== field_value_control.value) {
          range_control.value = field_value_control.value;
        }
      }
    }
    inspector_bulk_operations = collect_bulk_operations();
    inspector_dirty = Object.keys(inspector_bulk_operations).length > 0;
    if (save_button) {
      save_button.disabled = inspector_selection_stale || !inspector_dirty;
    }
  }

  /**
   * Render a signed bulk-edit review without performing a mutation.
   *
   * @param {Object} config  Catalog configuration.
   * @param {Object} preview Server-authoritative preview.
   * @return {boolean} True when rendered.
   */
  function render_bulk_review(config, preview) {
    var host = get_inspector_host();
    var review_workflow = get_inline_review_workflow();
    var review_model;
    var target = host ? host.querySelector('[data-wpbc-ui-catalog-inspector-form]') : null;
    if (!target) {
      return false;
    }
    review_model = review_workflow ? review_workflow.prepare(preview.review || {}, {
      changed_label: get_selection_count_label(config, preview.schema.resource_ids.length),
      description: config.i18n.inline_review_description || '',
      form_id: 'wpbc_catalog_booking_resources_bulk_review_form',
      mode: 'bulk_review',
      pending_message: config.i18n.review_changes_help || '',
      title: config.i18n.review_changes || config.i18n.edit_booking_resources || ''
    }) : {};
    target.innerHTML = render_component(config, 'inspector_bulk_review', review_model);
    if (!target.querySelector('[data-wpbc-catalog-resource-bulk-review-form]')) {
      return false;
    }
    inspector_mode = 'bulk_review';
    inspector_review_token = String(preview.review_token || '');
    inspector_dirty = true;
    set_inspector_state('form', '');
    configure_inspector_footer('wpbc_catalog_booking_resources_bulk_review_form', config.i18n.apply_changes || '', false, inspector_selection_stale);
    if (review_workflow) {
      review_workflow.synchronize({
        busy: false,
        can_apply: !inspector_selection_stale && !!inspector_review_token
      });
    }
    focus_inspector_heading(target.querySelector('[data-wpbc-catalog-resource-bulk-review-form]'));
    return true;
  }

  /**
   * Render the signed deletion impact and explicit acknowledgement.
   *
   * @param {Object} config  Catalog configuration.
   * @param {Object} preview Server-authoritative deletion preview.
   * @return {boolean} True when rendered.
   */
  function render_delete_review(config, preview) {
    var host = get_inspector_host();
    var target = host ? host.querySelector('[data-wpbc-ui-catalog-inspector-form]') : null;
    var acknowledgement;
    if (!target) {
      return false;
    }
    var delete_i18n = preview.i18n || {};
    target.innerHTML = render_component(config, 'inspector_delete', {
      delete_i18n: {
        acknowledgement: delete_i18n.acknowledgement || config.i18n.delete_acknowledgement || '',
        actions_heading: delete_i18n.actions_heading || '',
        bookings_retained_warning: delete_i18n.bookings_retained_warning || config.i18n.bookings_retained_warning || '',
        resources_to_delete: delete_i18n.resources_to_delete || config.i18n.resources_to_delete || '',
        review_help: delete_i18n.review_help || config.i18n.delete_review_help || '',
        title: delete_i18n.title || config.i18n.delete_booking_resources || '',
        warning: delete_i18n.warning || config.i18n.delete_warning || ''
      },
      i18n: config.i18n || {},
      preview: preview,
      selection_label: delete_i18n.selection_label || get_selection_count_label(config, preview.selection_count)
    });
    if (!target.querySelector('[data-wpbc-catalog-resource-delete-form]')) {
      return false;
    }
    inspector_mode = 'delete_review';
    inspector_resource_ids = (preview.resources || []).map(function (resource) {
      return Number(resource.id);
    });
    inspector_resource_id = !inspector_tracks_selection && 1 === inspector_resource_ids.length ? inspector_resource_ids[0] : 0;
    inspector_review_token = String(preview.review_token || '');
    inspector_selection_stale = false;
    inspector_dirty = false;
    set_inspector_state('form', '');
    configure_inspector_footer('wpbc_catalog_booking_resources_delete_form', delete_i18n.delete_button || format_message(1 === Number(preview.selection_count) ? config.i18n.delete_resource || '' : config.i18n.delete_resources || '', [preview.selection_count]), true, true);
    acknowledgement = target.querySelector('.wpbc_booking_resources__delete_acknowledgement');
    pulse_delete_acknowledgement(acknowledgement);
    mark_inspector_resource_row(inspector_resource_id);
    focus_inspector_heading(target.querySelector('[data-wpbc-catalog-resource-delete-form]'));
    return true;
  }

  /**
   * Open the independent deletion review for explicit Resource IDs.
   *
   * @param {Object}        config       Catalog configuration.
   * @param {Array<number>} resource_ids Resource IDs selected for deletion.
   * @param {HTMLElement}   focus_target Initiating control.
   * @param {boolean}       track_selection Whether deletion owns the checkbox selection.
   * @return {void}
   */
  function open_delete_review(config, resource_ids, focus_target, track_selection) {
    var request_sequence;
    resource_ids = (resource_ids || []).map(Number).filter(function (resource_id) {
      return resource_id > 0;
    });
    if (!resource_ids.length || !can_discard_inspector(config) || !mount_inspector_shell(config)) {
      return;
    }
    close_details_row(false);
    inspector_focus_target = focus_target || document.activeElement;
    request_sequence = ++inspector_request_sequence;
    inspector_mode = 'delete_review';
    inspector_resource_ids = resource_ids.slice();
    inspector_resource_id = !track_selection && 1 === inspector_resource_ids.length ? inspector_resource_ids[0] : 0;
    inspector_dirty = false;
    inspector_tracks_selection = !!track_selection;
    synchronize_inspector_width();
    set_inspector_state('loading', '');
    mark_inspector_resource_row(inspector_resource_id);
    expand_inspector_sidebar();
    request_inspector(config, config.delete_preview_action, {
      resource_ids: JSON.stringify(resource_ids)
    }).then(function (response) {
      if (request_sequence !== inspector_request_sequence) {
        return;
      }
      if (!response || true !== response.success || !response.data || !response.data.preview || !render_delete_review(config, response.data.preview)) {
        set_inspector_state('error', get_inspector_response_message(response, config.i18n.delete_load_failed));
      } else if (inspector_tracks_selection && !resource_id_lists_match(inspector_resource_ids, get_selected_resource_ids(config))) {
        handle_inspector_selection_change(null, config);
      }
    }).catch(function () {
      if (request_sequence === inspector_request_sequence) {
        set_inspector_state('error', config.i18n.delete_load_failed || '');
      }
    });
  }

  /**
   * Return one localized capacity count label.
   *
   * @param {Object} config       Catalog configuration.
   * @param {string} singular_key Singular translation key.
   * @param {string} plural_key   Plural translation key.
   * @param {number} count        Non-negative count.
   * @return {string} Localized label.
   */
  function get_capacity_count_label(config, singular_key, plural_key, count) {
    var template = 1 === Number(count) ? config.i18n[singular_key] : config.i18n[plural_key];
    return format_message(template || '', [count]);
  }

  /**
   * Build presentation-only data for the capacity WP template.
   *
   * @param {Object} config  Catalog configuration.
   * @param {Object} context Server-authoritative capacity context.
   * @return {Object} Template view data.
   */
  function get_capacity_editor_view(config, context) {
    var current_capacity = Number(context.current_capacity) || 1;
    var target_capacity = inspector_capacity_target || current_capacity;
    var operation = target_capacity > current_capacity ? 'increase' : target_capacity < current_capacity ? 'decrease' : 'unchanged';
    var keep_count = 'decrease' === operation ? target_capacity : current_capacity;
    var create_count = Math.max(0, target_capacity - current_capacity);
    var decrease_count = Math.max(0, current_capacity - target_capacity);
    var delete_action = 'decrease' === operation && 'delete' === inspector_capacity_decrease_action;
    return {
      children: (context.children || []).map(function (child) {
        child = Object.assign({}, child);
        child.selected = -1 !== inspector_capacity_detach_ids.indexOf(Number(child.id));
        return child;
      }),
      context_label: (config.i18n.resource_id || 'ID') + ': ' + String(context.resource_id),
      current_capacity: current_capacity,
      decrease_action: inspector_capacity_decrease_action,
      decrease_heading: get_capacity_count_label(config, delete_action ? 'choose_delete_unit' : 'choose_detach_unit', delete_action ? 'choose_delete_units' : 'choose_detach_units', decrease_count),
      decrease_help: delete_action ? config.i18n.delete_units_help || '' : config.i18n.select_detach_help || '',
      decrease_outcome_label: delete_action ? config.i18n.will_be_deleted || '' : config.i18n.make_independent || '',
      description: config.i18n.capacity_description || '',
      create_label: get_capacity_count_label(config, 'create_new_unit', 'create_new_units', create_count),
      keep_label: get_capacity_count_label(config, 'keep_existing_unit', 'keep_existing_units', keep_count),
      maximum_capacity: Number(context.maximum_capacity) || current_capacity,
      minimum_capacity: Number(context.minimum_capacity) || 1,
      mode: 'capacity',
      operation: operation,
      target_capacity: target_capacity,
      title: config.i18n.adjust_capacity || ''
    };
  }

  /**
   * Synchronize the live capacity plan without replacing focused controls.
   *
   * @param {Object} config Catalog configuration.
   * @return {void}
   */
  function synchronize_capacity_editor(config) {
    var form = document.querySelector('[data-wpbc-catalog-resource-capacity-form][data-mode="capacity"]');
    var save_button = document.querySelector('[data-wpbc-ui-catalog-inspector-save]');
    var context = inspector_capacity_context || {};
    var current_capacity = Number(context.current_capacity) || 1;
    var target_capacity = inspector_capacity_target || current_capacity;
    var operation = target_capacity > current_capacity ? 'increase' : target_capacity < current_capacity ? 'decrease' : 'unchanged';
    var required_detach_count = Math.max(0, current_capacity - target_capacity);
    var target_number = form ? form.querySelector('[data-wpbc-catalog-capacity-target]') : null;
    var target_range = form ? form.querySelector('[data-wpbc-catalog-capacity-range]') : null;
    if (!form) {
      return;
    }
    if ('decrease' !== operation) {
      inspector_capacity_detach_ids = [];
      inspector_capacity_decrease_action = 'detach';
    } else if (inspector_capacity_detach_ids.length > required_detach_count) {
      inspector_capacity_detach_ids = inspector_capacity_detach_ids.slice(0, required_detach_count);
    }
    if (target_number) {
      target_number.value = String(target_capacity);
    }
    if (target_range) {
      target_range.value = String(target_capacity);
    }
    var after_value = form.querySelector('[data-wpbc-catalog-capacity-after]');
    var keep_label = form.querySelector('[data-wpbc-catalog-capacity-keep-label]');
    var create_label = form.querySelector('[data-wpbc-catalog-capacity-create-label]');
    var increase_row = form.querySelector('[data-wpbc-catalog-capacity-increase-row]');
    var decrease_panel = form.querySelector('[data-wpbc-catalog-capacity-decrease]');
    var decrease_heading = form.querySelector('[data-wpbc-catalog-capacity-decrease-heading]');
    var decrease_help = form.querySelector('[data-wpbc-catalog-capacity-decrease-help]');
    var delete_action = 'decrease' === operation && 'delete' === inspector_capacity_decrease_action;
    if (after_value) {
      after_value.textContent = String(target_capacity);
    }
    if (keep_label) {
      keep_label.textContent = get_capacity_count_label(config, 'keep_existing_unit', 'keep_existing_units', 'decrease' === operation ? target_capacity : current_capacity);
    }
    if (create_label) {
      create_label.textContent = get_capacity_count_label(config, 'create_new_unit', 'create_new_units', Math.max(0, target_capacity - current_capacity));
    }
    if (increase_row) {
      increase_row.hidden = 'increase' !== operation;
    }
    if (decrease_panel) {
      decrease_panel.hidden = 'decrease' !== operation;
    }
    if (decrease_heading) {
      decrease_heading.textContent = get_capacity_count_label(config, delete_action ? 'choose_delete_unit' : 'choose_detach_unit', delete_action ? 'choose_delete_units' : 'choose_detach_units', required_detach_count);
    }
    if (decrease_help) {
      decrease_help.textContent = delete_action ? config.i18n.delete_units_help || '' : config.i18n.select_detach_help || '';
    }
    form.querySelectorAll('[data-wpbc-catalog-capacity-decrease-action]').forEach(function (action_control) {
      var action_selected = action_control.value === inspector_capacity_decrease_action;
      action_control.checked = action_selected;
      if (action_control.closest('label')) {
        action_control.closest('label').classList.toggle('is-selected', action_selected);
      }
    });
    form.querySelectorAll('[data-wpbc-catalog-capacity-detach]').forEach(function (checkbox) {
      var selected = -1 !== inspector_capacity_detach_ids.indexOf(Number(checkbox.value));
      var unit = checkbox.closest('.wpbc_booking_resources__capacity_unit');
      var outcome = unit ? unit.querySelector('.wpbc_booking_resources__capacity_unit_outcome') : null;
      checkbox.checked = selected;
      checkbox.disabled = !selected && inspector_capacity_detach_ids.length >= required_detach_count;
      if (unit) {
        unit.classList.toggle('is-selected', selected);
      }
      if (outcome) {
        outcome.hidden = !selected;
        outcome.textContent = delete_action ? config.i18n.will_be_deleted || '' : config.i18n.make_independent || '';
        outcome.classList.toggle('is-destructive', delete_action);
      }
    });
    inspector_dirty = target_capacity !== current_capacity;
    if (save_button) {
      save_button.disabled = 'unchanged' === operation || 'decrease' === operation && inspector_capacity_detach_ids.length !== required_detach_count;
    }
  }

  /**
   * Render an authorized capacity editor context.
   *
   * @param {Object} config  Catalog configuration.
   * @param {Object} context Server capacity context.
   * @return {boolean} True when the template rendered.
   */
  function render_capacity_editor(config, context) {
    var host = get_inspector_host();
    var target = host ? host.querySelector('[data-wpbc-ui-catalog-inspector-form]') : null;
    if (!target) {
      return false;
    }
    inspector_capacity_context = context;
    inspector_capacity_target = Number(context.current_capacity) || 1;
    inspector_capacity_detach_ids = [];
    inspector_capacity_decrease_action = 'detach';
    target.innerHTML = render_component(config, 'inspector_capacity', {
      i18n: config.i18n || {},
      view: get_capacity_editor_view(config, context)
    });
    if (!target.querySelector('[data-wpbc-catalog-resource-capacity-form]')) {
      return false;
    }
    inspector_mode = 'capacity';
    inspector_resource_id = Number(context.resource_id) || 0;
    inspector_review_token = '';
    inspector_dirty = false;
    set_inspector_state('form', '');
    configure_inspector_footer('wpbc_catalog_booking_resource_capacity_form', config.i18n.review_capacity_change || '', false, true);
    mark_inspector_resource_row(inspector_resource_id);
    focus_inspector_heading(target.querySelector('[data-wpbc-catalog-resource-capacity-form]'));
    return true;
  }

  /**
   * Open capacity context from either row action entry point.
   *
   * @param {Object}      config       Catalog configuration.
   * @param {number}      resource_id  Root or child Resource ID.
   * @param {HTMLElement} focus_target Initiating control.
   * @return {void}
   */
  function open_capacity_editor(config, resource_id, focus_target) {
    var request_sequence;
    if (!resource_id || !can_discard_inspector(config) || !mount_inspector_shell(config)) {
      return;
    }
    close_details_row(false);
    inspector_focus_target = focus_target || document.activeElement;
    request_sequence = ++inspector_request_sequence;
    inspector_mode = 'capacity';
    inspector_resource_id = resource_id;
    inspector_dirty = false;
    inspector_tracks_selection = false;
    synchronize_inspector_width();
    set_inspector_state('loading', '');
    mark_inspector_resource_row(resource_id);
    expand_inspector_sidebar();
    request_inspector(config, config.capacity_context_action, {
      resource_id: resource_id
    }).then(function (response) {
      if (request_sequence !== inspector_request_sequence) {
        return;
      }
      if (!response || true !== response.success || !response.data || !response.data.context || !render_capacity_editor(config, response.data.context)) {
        set_inspector_state('error', get_inspector_response_message(response, config.i18n.capacity_load_failed));
      }
    }).catch(function () {
      if (request_sequence === inspector_request_sequence) {
        set_inspector_state('error', config.i18n.capacity_load_failed || '');
      }
    });
  }

  /**
   * Render a signed capacity review returned by the domain service.
   *
   * @param {Object} config  Catalog configuration.
   * @param {Object} preview Signed preview.
   * @return {boolean} True when rendered.
   */
  function render_capacity_review(config, preview) {
    var host = get_inspector_host();
    var target = host ? host.querySelector('[data-wpbc-ui-catalog-inspector-form]') : null;
    var increase = 'increase' === preview.operation;
    var delete_action = 'delete' === preview.decrease_action;
    var view;
    if (!target) {
      return false;
    }
    view = {
      context_label: (config.i18n.resource_id || 'ID') + ': ' + String(preview.resource_id),
      current_capacity: Number(preview.current_capacity),
      decrease_action: preview.decrease_action || 'detach',
      delete_has_bookings: true === preview.delete_has_bookings,
      description: config.i18n.review_capacity_help || '',
      mode: 'capacity_review',
      operation: preview.operation,
      operation_help: increase ? config.i18n.create_units_help || '' : delete_action ? config.i18n.delete_units_help || '' : config.i18n.select_detach_help || '',
      operation_label: increase ? get_capacity_count_label(config, 'create_new_unit', 'create_new_units', Number(preview.create_count)) : get_capacity_count_label(config, 'keep_existing_unit', 'keep_existing_units', Number(preview.target_capacity)),
      resources: increase ? preview.create_resources || [] : preview.detach_resources || [],
      target_capacity: Number(preview.target_capacity),
      title: config.i18n.review_capacity_title || ''
    };
    target.innerHTML = render_component(config, 'inspector_capacity', {
      i18n: config.i18n || {},
      view: view
    });
    if (!target.querySelector('[data-wpbc-catalog-resource-capacity-form]')) {
      return false;
    }
    inspector_mode = 'capacity_review';
    inspector_resource_id = Number(preview.resource_id) || 0;
    inspector_review_token = String(preview.review_token || '');
    inspector_capacity_decrease_action = preview.decrease_action || 'detach';
    inspector_dirty = true;
    set_inspector_state('form', '');
    configure_inspector_footer('wpbc_catalog_booking_resource_capacity_form', config.i18n.apply_capacity_change || '', delete_action, delete_action);
    if (delete_action) {
      var acknowledgement = target.querySelector('[data-wpbc-catalog-capacity-delete-acknowledgement]');
      pulse_delete_acknowledgement(acknowledgement ? acknowledgement.closest('.wpbc_booking_resources__delete_acknowledgement') : null);
    }
    var cancel_button = document.querySelector('[data-wpbc-ui-catalog-inspector-cancel]');
    if (cancel_button) {
      cancel_button.textContent = config.i18n.back || '';
    }
    focus_inspector_heading(target.querySelector('[data-wpbc-catalog-resource-capacity-form]'));
    return true;
  }

  /**
   * Synchronize the Resource image preview after Media Library changes.
   *
   * @param {HTMLElement} field Picture URL field.
   * @return {void}
   */
  function synchronize_inspector_image(field) {
    var field_wrap = field ? field.closest('[data-wpbc-catalog-resource-field-wrap]') : null;
    var preview = field_wrap ? field_wrap.querySelector('[data-wpbc-catalog-resource-image-preview]') : null;
    var placeholder = field_wrap ? field_wrap.querySelector('[data-wpbc-catalog-resource-image-placeholder]') : null;
    var remove_button = field_wrap ? field_wrap.querySelector('[data-wpbc-catalog-resource-remove-image]') : null;
    var picture_url = field ? String(field.value || '').trim() : '';
    if (preview) {
      preview.src = picture_url;
      preview.hidden = !picture_url;
    }
    if (placeholder) {
      placeholder.hidden = !!picture_url;
    }
    if (remove_button) {
      remove_button.disabled = !picture_url;
    }
  }

  /**
   * Synchronize one numeric slider with its precise number field.
   *
   * Suggested slider bounds remain convenient for ordinary values. Price keeps
   * its product-defined 0-1000 slider while the authoritative number field can
   * still preserve a legacy price above 1000. Other numeric controls can expand
   * to represent stored values outside their suggested range.
   *
   * @param {string} field_key Numeric inspector field key.
   * @return {void}
   */
  function synchronize_inspector_numeric_range(field_key) {
    var number_field = document.querySelector('[data-wpbc-catalog-resource-field="' + field_key + '"][type="number"]');
    var range = document.querySelector('[data-wpbc-catalog-resource-range="' + field_key + '"]');
    var number_value;
    var default_min;
    var default_max;
    var hard_min;
    var hard_max;
    var range_min;
    var range_max;
    if (!number_field || !range) {
      return;
    }
    number_value = Number(number_field.value);
    if (!isFinite(number_value)) {
      return;
    }
    default_min = Number(range.getAttribute('data-wpbc-catalog-resource-range-default-min'));
    default_max = Number(range.getAttribute('data-wpbc-catalog-resource-range-default-max'));
    hard_min = '' === String(number_field.getAttribute('min') || '') ? null : Number(number_field.getAttribute('min'));
    hard_max = '' === String(number_field.getAttribute('max') || '') ? null : Number(number_field.getAttribute('max'));
    if ('base_cost' === field_key) {
      range_min = isFinite(default_min) ? default_min : 0;
      range_max = isFinite(default_max) ? default_max : 1000;
    } else {
      range_min = null !== hard_min && isFinite(hard_min) ? hard_min : Math.min(isFinite(default_min) ? default_min : number_value, number_value);
      range_max = null !== hard_max && isFinite(hard_max) ? hard_max : Math.max(isFinite(default_max) ? default_max : number_value, number_value);
    }
    range.min = String(range_min);
    range.max = String(range_max);
    range.value = String(number_value);
  }

  /**
   * Synchronize every rendered inspector numeric slider.
   *
   * @return {void}
   */
  function synchronize_all_inspector_numeric_ranges() {
    document.querySelectorAll('[data-wpbc-catalog-resource-range]').forEach(function (range) {
      synchronize_inspector_numeric_range(range.getAttribute('data-wpbc-catalog-resource-range') || '');
    });
  }

  /**
   * Copy a slider value into its authoritative number field.
   *
   * The number field remains the only serialized control and emits one native
   * input event so validation and dirty-state behavior stay centralized.
   *
   * @param {HTMLElement} range Numeric range control.
   * @return {void}
   */
  function synchronize_inspector_number_from_range(range) {
    var field_key = range ? range.getAttribute('data-wpbc-catalog-resource-range') || '' : '';
    var number_field = field_key ? document.querySelector('[data-wpbc-catalog-resource-field="' + field_key + '"][type="number"]') : null;
    if (!number_field) {
      return;
    }
    number_field.value = range.value;
    number_field.dispatchEvent(new Event('input', {
      bubbles: true
    }));
  }

  /**
   * Confirm that a form submit originated from the inspector save action.
   *
   * WordPress Media Library controls may temporarily coexist with the sidebar
   * form. Rejecting their submitters prevents an image insertion from starting
   * a Resource mutation. The active inspector field fallback preserves native
   * Enter-key submission in browsers without SubmitEvent.submitter support.
   *
   * @param {SubmitEvent} event Form submission.
   * @param {HTMLElement} form  Active inspector form.
   * @return {boolean} True only for an intentional inspector submission.
   */
  function is_expected_inspector_submit(event, form) {
    var submitter = event.submitter || document.activeElement;
    if (submitter && submitter.matches && submitter.matches('[data-wpbc-ui-catalog-inspector-save]')) {
      return true;
    }
    return !event.submitter && submitter && form.contains(submitter) && submitter.matches && submitter.matches('input:not([type="button"]):not([type="submit"]), select');
  }

  /**
   * Report inspector validity without rejecting an existing decimal price.
   *
   * Price controls intentionally use a one-unit spinner step. Existing prices
   * may still contain decimals, so their step constraint is relaxed only while
   * native form validity is evaluated. Server-side price validation remains the
   * authoritative boundary.
   *
   * @param {HTMLFormElement} form Inspector form.
   * @return {boolean} True when the form passes native validity checks.
   */
  function report_inspector_validity(form) {
    var price_fields = form.querySelectorAll('[data-wpbc-catalog-resource-field="base_cost"], [data-wpbc-catalog-resource-bulk-value="base_cost"]');
    var price_steps = [];
    var is_valid;
    price_fields.forEach(function (price_field) {
      price_steps.push({
        field: price_field,
        step: price_field.getAttribute('step')
      });
      price_field.setAttribute('step', 'any');
    });
    is_valid = form.reportValidity();
    price_steps.forEach(function (price_step) {
      if (null === price_step.step) {
        price_step.field.removeAttribute('step');
      } else {
        price_step.field.setAttribute('step', price_step.step);
      }
    });
    return is_valid;
  }

  /**
   * Save the active inspector through its independent mutation endpoint.
   *
   * @param {SubmitEvent} event Form submission.
   * @param {Object}      config Catalog configuration.
   * @return {void}
   */
  function submit_inspector(event, config) {
    var form = event.target;
    var save_button = document.querySelector('[data-wpbc-ui-catalog-inspector-save]');
    var cancel_button = document.querySelector('[data-wpbc-ui-catalog-inspector-cancel]');
    var mutation_request_sequence;
    var fields;
    var action;
    var submitted_mode;
    var request_values;
    var control_disabled_states = [];
    var success_message;
    var success_message_is_global;
    var submitted_form_is_active;
    event.preventDefault();
    if (!is_expected_inspector_submit(event, form) || save_button && save_button.classList.contains('is-busy') || !report_inspector_validity(form)) {
      return;
    }
    mutation_request_sequence = ++inspector_mutation_request_sequence;
    inspector_mutation_in_progress = true;
    fields = JSON.parse(serialize_inspector_fields() || '{}');
    action = 'create' === inspector_mode ? config.inspector_create_action : config.inspector_update_action;
    submitted_mode = inspector_mode;
    request_values = {
      fields: JSON.stringify(fields)
    };
    if ('edit' === inspector_mode) {
      request_values.resource_id = inspector_resource_id;
    }
    if (save_button) {
      save_button.disabled = true;
      save_button.classList.add('is-busy');
    }
    if (cancel_button) {
      cancel_button.disabled = true;
    }
    form.classList.add('is-saving');
    form.setAttribute('aria-busy', 'true');
    form.querySelectorAll('input, select, textarea, button').forEach(function (control) {
      control_disabled_states.push({
        control: control,
        disabled: control.disabled
      });
      control.disabled = true;
    });
    request_inspector(config, action, request_values).then(function (response) {
      if (mutation_request_sequence !== inspector_mutation_request_sequence) {
        return;
      }
      if (!response || true !== response.success || !response.data) {
        throw new Error(get_inspector_response_message(response, config.i18n.inspector_save_failed));
      }
      pending_highlight_ids = Array.isArray(response.data.resource_ids) ? response.data.resource_ids.map(String) : [];
      inspector_dirty = false;
      success_message = get_inspector_response_message(response, '');
      success_message_is_global = show_admin_message(success_message, 'success', 3000);
      submitted_form_is_active = document.documentElement.contains(form);
      if ('create' === submitted_mode) {
        inspector_mutation_in_progress = false;
        if (submitted_form_is_active) {
          close_inspector(config, false);
        }
        if (catalog_controller) {
          catalog_controller.load({
            page_number: 1
          });
        }
        return;
      }
      if (!submitted_form_is_active) {
        if (catalog_controller) {
          catalog_controller.load();
        }
        inspector_mutation_in_progress = false;
        return;
      }
      if (response.data.schema && render_inspector_schema(config, response.data.schema, false)) {
        form = document.querySelector('[data-wpbc-catalog-resource-inspector-form]');
        show_inspector_message(form, success_message_is_global ? '' : success_message, false);
      } else {
        form.classList.remove('is-saving');
        form.removeAttribute('aria-busy');
        control_disabled_states.forEach(function (control_state) {
          if (document.documentElement.contains(control_state.control)) {
            control_state.control.disabled = control_state.disabled;
          }
        });
        if (save_button) {
          save_button.classList.remove('is-busy');
        }
        if (cancel_button) {
          cancel_button.disabled = false;
        }
        inspector_original_fields = serialize_inspector_fields();
        synchronize_inspector_dirty_state();
        show_inspector_message(form, success_message_is_global ? '' : success_message, false);
      }
      if (catalog_controller) {
        catalog_controller.load();
      }
      inspector_mutation_in_progress = false;
    }).catch(function (error) {
      if (mutation_request_sequence !== inspector_mutation_request_sequence) {
        return;
      }
      inspector_mutation_in_progress = false;
      var message = error && error.message ? error.message : config.i18n.inspector_save_failed || '';
      if (!document.documentElement.contains(form)) {
        show_admin_message(message, 'error', 5000);
        return;
      }
      form.classList.remove('is-saving');
      form.removeAttribute('aria-busy');
      show_inspector_message(form, message, true);
      control_disabled_states.forEach(function (control_state) {
        if (document.documentElement.contains(control_state.control)) {
          control_state.control.disabled = control_state.disabled;
        }
      });
      if (save_button) {
        save_button.classList.remove('is-busy');
      }
      if (cancel_button) {
        cancel_button.disabled = false;
      }
      synchronize_inspector_dirty_state();
    });
  }

  /**
   * Submit bulk, permanent-delete, and capacity review inspector states.
   *
   * Mutations remain impossible from selection alone: bulk editing requires a
   * signed preview, and deletion additionally requires explicit acknowledgement.
   *
   * @param {SubmitEvent} event  Inspector form submission.
   * @param {Object}      config Catalog configuration.
   * @return {void}
   */
  function submit_reviewed_inspector(event, config) {
    var form = event.target;
    var save_button = document.querySelector('[data-wpbc-ui-catalog-inspector-save]');
    var cancel_button = document.querySelector('[data-wpbc-ui-catalog-inspector-cancel]');
    var request_sequence;
    var action;
    var values;
    var fallback;
    var is_mutation;
    var submitted_mode;
    var submitted_resource_ids;
    var submitted_tracks_selection;
    event.preventDefault();
    if (inspector_selection_stale || !is_expected_inspector_submit(event, form) || save_button && (save_button.disabled || save_button.classList.contains('is-busy')) || !report_inspector_validity(form)) {
      return;
    }
    submitted_mode = inspector_mode;
    submitted_resource_ids = inspector_resource_ids.slice();
    submitted_tracks_selection = inspector_tracks_selection;
    if ('bulk_edit' === submitted_mode) {
      inspector_bulk_operations = collect_bulk_operations();
      action = config.bulk_preview_action;
      values = {
        resource_ids: JSON.stringify(inspector_resource_ids),
        operations: JSON.stringify(inspector_bulk_operations)
      };
      fallback = config.i18n.bulk_review_failed;
    } else if ('bulk_review' === submitted_mode) {
      action = config.bulk_apply_action;
      values = {
        resource_ids: JSON.stringify(inspector_resource_ids),
        operations: JSON.stringify(inspector_bulk_operations),
        review_token: inspector_review_token
      };
      fallback = config.i18n.bulk_apply_failed;
    } else if ('delete_review' === submitted_mode) {
      action = config.delete_apply_action;
      values = {
        acknowledged: '1',
        resource_ids: JSON.stringify(inspector_resource_ids),
        review_token: inspector_review_token
      };
      fallback = config.i18n.delete_apply_failed;
    } else if ('capacity' === submitted_mode) {
      action = config.capacity_preview_action;
      values = {
        resource_id: inspector_resource_id,
        target_capacity: inspector_capacity_target,
        detach_resource_ids: JSON.stringify(inspector_capacity_detach_ids),
        decrease_action: inspector_capacity_decrease_action
      };
      fallback = config.i18n.capacity_review_failed;
    } else if ('capacity_review' === submitted_mode) {
      action = config.capacity_apply_action;
      var capacity_acknowledgement = form.querySelector('[data-wpbc-catalog-capacity-delete-acknowledgement]');
      values = {
        resource_id: inspector_resource_id,
        target_capacity: inspector_capacity_target,
        detach_resource_ids: JSON.stringify(inspector_capacity_detach_ids),
        decrease_action: inspector_capacity_decrease_action,
        acknowledged: capacity_acknowledgement && capacity_acknowledgement.checked ? '1' : '0',
        review_token: inspector_review_token
      };
      fallback = config.i18n.capacity_apply_failed;
    } else {
      return;
    }
    is_mutation = 'bulk_review' === submitted_mode || 'delete_review' === submitted_mode || 'capacity_review' === submitted_mode;
    request_sequence = is_mutation ? ++inspector_mutation_request_sequence : ++inspector_request_sequence;
    if (is_mutation) {
      inspector_mutation_in_progress = true;
    }
    if ('bulk_review' === submitted_mode && get_inline_review_workflow()) {
      get_inline_review_workflow().synchronize({
        busy: true,
        can_apply: true
      });
    }
    if ('delete_review' === submitted_mode && get_delete_review_workflow()) {
      get_delete_review_workflow().synchronize({
        busy: true,
        can_apply: true
      });
    }
    if (save_button) {
      save_button.disabled = true;
      save_button.classList.add('is-busy');
    }
    if (cancel_button) {
      cancel_button.disabled = true;
    }
    form.classList.add('is-saving');
    form.setAttribute('aria-busy', 'true');
    request_inspector(config, action, values).then(function (response) {
      if (request_sequence !== (is_mutation ? inspector_mutation_request_sequence : inspector_request_sequence)) {
        return;
      }
      if (!response || true !== response.success || !response.data) {
        throw new Error(get_inspector_response_message(response, fallback));
      }
      if ('bulk_edit' === submitted_mode) {
        if (!response.data.preview || !render_bulk_review(config, response.data.preview)) {
          throw new Error(fallback || '');
        }
        return;
      }
      if ('capacity' === submitted_mode) {
        if (!response.data.preview || !render_capacity_review(config, response.data.preview)) {
          throw new Error(fallback || '');
        }
        return;
      }
      if ('bulk_review' === submitted_mode) {
        pending_highlight_ids = Array.isArray(response.data.updated_ids) ? response.data.updated_ids.map(String) : [];
      } else if ('capacity_review' === submitted_mode) {
        pending_highlight_ids = Array.isArray(response.data.affected_ids) ? response.data.affected_ids.map(String) : [];
      } else {
        var mount = document.getElementById(config.mount_id);
        var selection = mount && mount._wpbc_ui_catalog_selection_controller;
        var selected_resource_ids = get_selected_resource_ids(config);
        var deleted_selected_resource = submitted_resource_ids.some(function (resource_id) {
          return -1 !== selected_resource_ids.indexOf(Number(resource_id));
        });
        if (selection && 'function' === typeof selection.clear && (submitted_tracks_selection || deleted_selected_resource)) {
          selection.clear();
        }
      }
      show_admin_message(get_inspector_response_message(response, ''), 'success', 4000);
      inspector_dirty = false;
      inspector_mutation_in_progress = false;
      if (document.documentElement.contains(form)) {
        close_inspector(config, false);
      }
      if (catalog_controller) {
        catalog_controller.load();
      }
    }).catch(function (error) {
      if (request_sequence !== (is_mutation ? inspector_mutation_request_sequence : inspector_request_sequence)) {
        return;
      }
      if (is_mutation) {
        inspector_mutation_in_progress = false;
      }
      if (!document.documentElement.contains(form)) {
        show_admin_message(error && error.message ? error.message : fallback || '', 'error', 5000);
        return;
      }
      form.classList.remove('is-saving');
      form.removeAttribute('aria-busy');
      show_inspector_message(form, error && error.message ? error.message : fallback || '', true);
      if (save_button) {
        save_button.classList.remove('is-busy');
        save_button.disabled = inspector_selection_stale || 'delete_review' === inspector_mode && !form.querySelector('[data-wpbc-catalog-resource-delete-acknowledgement]:checked');
      }
      if (cancel_button) {
        cancel_button.disabled = false;
      }
      if ('bulk_review' === submitted_mode && get_inline_review_workflow()) {
        get_inline_review_workflow().synchronize({
          busy: false,
          can_apply: !inspector_selection_stale
        });
      }
      if ('delete_review' === submitted_mode && get_delete_review_workflow()) {
        get_delete_review_workflow().synchronize({
          busy: false,
          can_apply: !inspector_selection_stale
        });
      }
    });
  }

  /**
   * Invalidate an open selection-owned inspector when its selection changes.
   *
   * @param {CustomEvent} event  Shared selection lifecycle event.
   * @param {Object}      config Catalog configuration.
   * @return {void}
   */
  function handle_inspector_selection_change(event, config) {
    var selected_ids = event && event.detail && Array.isArray(event.detail.selected_ids) ? event.detail.selected_ids : get_selected_resource_ids(config);
    var form;
    var save_button;
    if (!inspector_tracks_selection || -1 === ['bulk_edit', 'bulk_review', 'delete_review'].indexOf(inspector_mode)) {
      return;
    }
    form = document.querySelector('[data-wpbc-catalog-resource-bulk-form], [data-wpbc-catalog-resource-bulk-review-form], [data-wpbc-catalog-resource-delete-form]');
    save_button = document.querySelector('[data-wpbc-ui-catalog-inspector-save]');
    if (resource_id_lists_match(inspector_resource_ids, selected_ids)) {
      if (!inspector_selection_stale) {
        return;
      }
      inspector_selection_stale = false;
      show_inspector_message(form, '', false);
      if ('delete_review' === inspector_mode && get_delete_review_workflow()) {
        get_delete_review_workflow().synchronize({
          busy: false,
          can_apply: true
        });
      }
      if ('bulk_edit' === inspector_mode) {
        synchronize_bulk_editor(null);
      } else if (save_button) {
        var acknowledgement = form ? form.querySelector('[data-wpbc-catalog-resource-delete-acknowledgement]') : null;
        save_button.disabled = 'delete_review' === inspector_mode && (!acknowledgement || !acknowledgement.checked);
      }
      return;
    }
    inspector_selection_stale = true;
    show_inspector_message(form, config.i18n.selection_changed || '', true);
    if ('delete_review' === inspector_mode && get_delete_review_workflow()) {
      get_delete_review_workflow().synchronize({
        busy: false,
        can_apply: false
      });
    }
    if (save_button) {
      save_button.disabled = true;
    }
  }

  /**
   * Apply the post-mutation highlight after an AJAX catalog refresh.
   *
   * @return {void}
   */
  function apply_pending_highlights() {
    var first_row = null;
    pending_highlight_ids.forEach(function (resource_id) {
      var row = document.querySelector('[data-wpbc-booking-resource-id="' + resource_id + '"]');
      if (row) {
        row.classList.add('is-recently-saved');
        first_row = first_row || row;
      }
    });
    if (first_row) {
      first_row.scrollIntoView({
        block: 'nearest',
        behavior: 'smooth'
      });
    }
    window.setTimeout(function () {
      document.querySelectorAll('.wpbc_booking_resources__item.is-recently-saved').forEach(function (row) {
        row.classList.remove('is-recently-saved');
      });
    }, 5000);
    pending_highlight_ids = [];
  }

  /**
   * Remove a consumed read-only launch parameter without changing navigation.
   *
   * This prevents a normal reload from reopening the guided action menu. The catalog's
   * other request-local display arguments remain in place and no preference
   * save action is introduced.
   *
   * @param {Object} config Catalog configuration.
   * @return {void}
   */
  function consume_initial_launch_parameter(config) {
    var launch_intent = config && config.launch_intent ? config.launch_intent : {};
    var query_parameter = String(launch_intent.query_parameter || '');
    var current_url;
    if (!query_parameter || !window.history || 'function' !== typeof window.history.replaceState || 'function' !== typeof window.URL) {
      return;
    }
    try {
      current_url = new window.URL(window.location.href);
      current_url.searchParams.delete(query_parameter);
      window.history.replaceState(window.history.state, '', current_url.toString());
    } catch (error) {
      // A malformed administration URL must not prevent normal catalog use.
    }
  }

  /**
   * Determine whether one authorized Resource DTO exposes a named action.
   *
   * @param {Object} resource  Authorized Resource list DTO.
   * @param {string} action_id Stable domain action identifier.
   * @return {boolean} True when the server-authorized action is present.
   */
  function resource_exposes_action(resource, action_id) {
    var action_items = resource && Array.isArray(resource.action_items) ? resource.action_items : [];
    var action_index;
    for (action_index = 0; action_index < action_items.length; action_index += 1) {
      if (action_id === String(action_items[action_index].id || '')) {
        return true;
      }
    }
    return false;
  }

  /**
   * Open one rendered Resource action menu and focus a named action.
   *
   * The shared controller owns menu mechanics. This domain adapter selects
   * only a Resource and action already authorized in the server response.
   *
   * @param {Object} config      Registered catalog configuration.
   * @param {number} resource_id Authorized Booking Resource ID.
   * @param {string} action_id   Authorized domain action identifier.
   * @return {boolean} True when the menu opened and the action received focus.
   */
  function open_resource_action_menu(config, resource_id, action_id) {
    var mount_element = config && config.mount_id ? document.getElementById(config.mount_id) : null;
    var actions_controller = mount_element && mount_element._wpbc_ui_catalog_actions_controller ? mount_element._wpbc_ui_catalog_actions_controller : null;
    if (!actions_controller || 'function' !== typeof actions_controller.open_item) {
      return false;
    }
    return actions_controller.open_item(String(resource_id), String(action_id || ''));
  }

  /**
   * Open the action menu for the first server-authorized Resource DTO.
   *
   * The URL carries presentation intent only. It never supplies a trusted
   * Resource ID. Activating the focused Publish action follows the normal
   * domain handler, which repeats capability and ownership checks before
   * opening publishing tools.
   *
   * @param {Object} config   Catalog configuration.
   * @param {Object} response Normalized authorized catalog response.
   * @return {void}
   */
  function open_initial_launch_intent(config, response) {
    var action_id = config && config.launch_intent ? String(config.launch_intent.action || '') : '';
    var resource;
    var resource_index;
    if (launch_intent_handled || 'publish_resource' !== action_id || !response || !Array.isArray(response.items)) {
      return;
    }
    launch_intent_handled = true;
    consume_initial_launch_parameter(config);
    for (resource_index = 0; resource_index < response.items.length; resource_index += 1) {
      if (resource_exposes_action(response.items[resource_index], action_id)) {
        resource = response.items[resource_index];
        break;
      }
    }
    if (!resource || !Number(resource.id)) {
      show_admin_message(config && config.i18n ? config.i18n.publishing_launch_unavailable || '' : '', 'error', 7000);
      return;
    }
    window.setTimeout(function () {
      if (!open_resource_action_menu(config, Number(resource.id), action_id)) {
        show_admin_message(config && config.i18n ? config.i18n.publishing_launch_unavailable || '' : '', 'error', 7000);
      }
    }, 0);
  }

  /**
   * Handle completed shared renders for this Resource catalog only.
   *
   * @param {CustomEvent} event Shared catalog lifecycle event.
   * @return {void}
   */
  function handle_catalog_rendered(event) {
    var config = window.wpbc_catalog_booking_resources_config;
    var event_detail = event && event.detail ? event.detail : {};
    if (!config || event_detail.catalog_id !== config.id || !event_detail.response) {
      return;
    }
    catalog_response = event_detail.response;
    synchronize_booking_resources_toolbar(config, catalog_response);
    render_booking_resources_response(config, catalog_response);
    render_inline_bar(config);
    synchronize_inline_controls(config);
    open_initial_launch_intent(config, catalog_response);
    if (inspector_resource_id) {
      mark_inspector_resource_row(inspector_resource_id);
    }
    apply_pending_highlights();
  }

  /**
   * Request a selected pagination page through the shared controller.
   *
   * @param {MouseEvent} event Catalog click event.
   * @return {void}
   */
  function handle_catalog_click(event) {
    var action_button = event.target.closest('[data-wpbc-booking-resource-action]');
    var action_details;
    var catalog_mount;
    var config = window.wpbc_catalog_booking_resources_config;
    var page_button = event.target.closest('[data-wpbc-ui-catalog-page]');
    var resource_action_event;
    if (inline_state.active && action_button) {
      event.preventDefault();
      return;
    }
    if (page_button && !page_button.disabled) {
      pending_focus_direction = page_button.getAttribute('data-wpbc-ui-catalog-page-direction') || 'page';
    }
    if (!action_button || !config) {
      return;
    }
    var action_id = action_button.getAttribute('data-wpbc-booking-resource-action') || '';
    if ('toggle_details' === action_id) {
      var resource_id = Number(action_button.getAttribute('data-wpbc-booking-resource-id') || 0);
      var resource_row = get_resource_item_container(action_button);
      event.preventDefault();
      if (resource_id && resource_row && config.details_action) {
        if (resource_id === details_resource_id) {
          close_details_row(true);
        } else {
          open_details_row(config, action_button, resource_row, resource_id);
        }
      }
      return;
    }
    if ('copy_details_value' === action_id) {
      event.preventDefault();
      copy_details_value(action_button.getAttribute('data-wpbc-booking-resource-copy-value') || '', action_button, config);
      return;
    }
    action_details = action_button.closest('details');
    if (action_details) {
      action_details.removeAttribute('open');
    }
    catalog_mount = document.getElementById(config.mount_id);
    if (catalog_mount) {
      if ('function' === typeof window.CustomEvent) {
        resource_action_event = new window.CustomEvent('wpbc:booking-resource-action', {
          bubbles: true,
          detail: {
            action: action_button.getAttribute('data-wpbc-booking-resource-action') || '',
            resource_id: Number(action_button.getAttribute('data-wpbc-booking-resource-id') || 0)
          }
        });
      } else {
        resource_action_event = document.createEvent('CustomEvent');
        resource_action_event.initCustomEvent('wpbc:booking-resource-action', true, false, {
          action: action_button.getAttribute('data-wpbc-booking-resource-action') || '',
          resource_id: Number(action_button.getAttribute('data-wpbc-booking-resource-id') || 0)
        });
      }
      catalog_mount.dispatchEvent(resource_action_event);
    }
  }

  /**
   * Close expanded details with Escape and restore disclosure focus.
   *
   * @param {KeyboardEvent} event Catalog keyboard event.
   * @return {void}
   */
  function handle_catalog_keydown(event) {
    var config = window.wpbc_catalog_booking_resources_config;
    if ('Escape' === event.key && inline_state.active && 'inline_review' !== inspector_mode) {
      synchronize_inline_drafts(config);
      if (!inline_state.changed_rows.length || window.confirm(config.i18n.inline_discard || '')) {
        event.preventDefault();
        leave_inline_mode(config, true, '');
      }
      return;
    }
    if ('Escape' === event.key && event.target.closest('[data-wpbc-booking-resource-details-row]')) {
      event.preventDefault();
      close_details_row(true);
    }
  }

  /**
   * Block Booking Resource image changes in public demo installations.
   *
   * This capture-phase guard runs before the shared delegated media-uploader
   * handler, preventing the WordPress media modal from opening. Server-side
   * create validation independently rejects a forged picture URL.
   *
   * @param {MouseEvent} event  Browser click event.
   * @param {Object}     config Catalog configuration.
   * @return {void}
   */
  function protect_demo_resource_image_change(event, config) {
    var media_button;
    var inspector_form;
    var message;
    var message_title;
    if (!config || !is_true_flag(config.is_demo) || !event.target || 'function' !== typeof event.target.closest) {
      return;
    }
    media_button = event.target.closest('.wpbc_media_upload_button, [data-wpbc-catalog-resource-remove-image]');
    inspector_form = media_button ? media_button.closest('[data-wpbc-catalog-resource-inspector-form]') : null;
    if (!media_button || !inspector_form) {
      return;
    }
    event.preventDefault();
    event.stopPropagation();
    if ('function' === typeof event.stopImmediatePropagation) {
      event.stopImmediatePropagation();
    }
    message = config.i18n && config.i18n.demo_image_change_unavailable ? config.i18n.demo_image_change_unavailable : '';
    message_title = config.i18n && config.i18n.demo_image_change_unavailable_title ? config.i18n.demo_image_change_unavailable_title : '';
    open_booking_resource_message_dialog(message, message_title, media_button);
  }

  /**
   * Mount the localized catalog configuration after the document is ready.
   *
   * @return {void}
   */
  function mount_booking_resources_catalog() {
    var config = window.wpbc_catalog_booking_resources_config;
    var mount_element;
    if (!config || !window.wpbc_ui_catalog || 'function' !== typeof window.wpbc_ui_catalog.mount) {
      return;
    }
    mount_element = document.getElementById(config.mount_id);
    if (!mount_element) {
      return;
    }
    mount_element.addEventListener('wpbc:ui-catalog-rendered', handle_catalog_rendered);
    mount_element.addEventListener('wpbc:ui-catalog-before-render', function () {
      close_details_row(false);
    });
    mount_element.addEventListener('wpbc:ui-catalog-hierarchy-change', function () {
      close_details_row(false);
      synchronize_card_group_panels(mount_element);
    });
    mount_element.addEventListener('wpbc:ui-catalog-selection-change', function (event) {
      handle_inspector_selection_change(event, config);
    });
    mount_element.addEventListener('wpbc:ui-catalog-selection-restored', function (event) {
      handle_inspector_selection_change(event, config);
    });
    mount_element.addEventListener('click', protect_inline_drafts_from_catalog_controls, true);
    mount_element.addEventListener('change', protect_inline_drafts_from_catalog_controls, true);
    mount_element.addEventListener('input', protect_inline_drafts_from_catalog_controls, true);
    mount_element.addEventListener('click', handle_catalog_click);
    mount_element.addEventListener('keydown', handle_catalog_keydown);
    window.addEventListener('resize', function () {
      synchronize_overflow_tooltips(mount_element);
    });
    catalog_controller = window.wpbc_ui_catalog.mount(config);
    if (catalog_controller) {
      if ('function' === typeof window.wpbc_ui_catalog.create_inline_editing_workflow) {
        inline_workflow_controller = window.wpbc_ui_catalog.create_inline_editing_workflow(mount_element, {
          bar_selector: '[data-wpbc-catalog-inline-bar]',
          cancel_selector: '[data-wpbc-catalog-inline-cancel]',
          controls_root: document,
          count_selector: '[data-wpbc-catalog-inline-count]',
          page_element: mount_element.matches('.wpbc_booking_resources_page') ? mount_element : mount_element.querySelector('.wpbc_booking_resources_page'),
          protected_selector: '[data-wpbc-catalog-booking-resource-create]',
          review_selector: '[data-wpbc-catalog-inline-review]',
          toggle_label_selector: '[data-wpbc-catalog-inline-toggle-label]',
          toggle_selector: '[data-wpbc-catalog-inline-toggle]'
        });
      }
      render_booking_resources_filters(config);
      render_booking_resources_toolbar(config);
      render_inline_bar(config);
      synchronize_inline_controls(config);
      mount_inspector_shell(config);
    }
    if ('function' === typeof window.wpbc_define_tippy_tooltips) {
      window.wpbc_define_tippy_tooltips('[data-wpbc-catalog-booking-resource-upgrade]');
    }
    document.addEventListener('wpbc:booking-resource-action', function (event) {
      var detail = event && event.detail ? event.detail : {};
      if ('edit_resource' === detail.action) {
        open_inspector(config, 'edit', Number(detail.resource_id) || 0, document.activeElement);
      } else if ('publish_resource' === detail.action) {
        open_inspector(config, 'edit', Number(detail.resource_id) || 0, document.activeElement, 'shortcode_publishing');
      } else if ('adjust_capacity' === detail.action) {
        open_capacity_editor(config, Number(detail.resource_id) || 0, document.activeElement);
      } else if ('delete_resource' === detail.action) {
        open_delete_review(config, [Number(detail.resource_id) || 0], document.activeElement, false);
      }
    });
    document.addEventListener('click', function (event) {
      protect_demo_resource_image_change(event, config);
    }, true);
    document.addEventListener('click', function (event) {
      var inline_toggle = event.target.closest('[data-wpbc-catalog-inline-toggle]');
      var inline_cancel = event.target.closest('[data-wpbc-catalog-inline-cancel]');
      var inline_review = event.target.closest('[data-wpbc-catalog-inline-review]');
      var create_button = event.target.closest('[data-wpbc-catalog-booking-resource-create]');
      var upgrade_button = event.target.closest('[data-wpbc-catalog-booking-resource-upgrade]');
      var cancel_button = event.target.closest('[data-wpbc-ui-catalog-inspector-cancel]');
      var remove_image_button = event.target.closest('[data-wpbc-catalog-resource-remove-image]');
      var shortcode_button = event.target.closest('[data-wpbc-booking-resource-shortcode-command]');
      var resource_row = get_resource_item_container(event.target);
      var selection_action = event.target.closest('[data-wpbc-catalog-selection-action]');
      if (inline_toggle) {
        event.preventDefault();
        start_inline_mode(config);
        return;
      }
      if (inline_cancel) {
        event.preventDefault();
        synchronize_inline_drafts(config);
        if (!inline_state.changed_rows.length || window.confirm(config.i18n.inline_discard || '')) {
          leave_inline_mode(config, true, '');
        }
        return;
      }
      if (inline_review) {
        event.preventDefault();
        preview_inline_changes(config, inline_review);
        return;
      }
      if (selection_action) {
        event.preventDefault();
        if ('bulk_edit' === selection_action.getAttribute('data-wpbc-catalog-selection-action')) {
          open_bulk_editor(config, selection_action);
        } else {
          open_delete_review(config, get_selected_resource_ids(config), selection_action, true);
        }
        return;
      }
      if (shortcode_button) {
        event.preventDefault();
        var shortcode_resource_id = Number(shortcode_button.getAttribute('data-wpbc-booking-resource-id') || 0);
        var shortcode_command = shortcode_button.getAttribute('data-wpbc-booking-resource-shortcode-command') || '';
        var shortcode_value = get_booking_resource_shortcode(shortcode_resource_id, shortcode_button);
        if ('copy' === shortcode_command) {
          copy_details_value(shortcode_value, shortcode_button, config);
        } else if ('customize' === shortcode_command) {
          customize_booking_resource_shortcode(shortcode_resource_id, shortcode_value);
        } else if ('publish' === shortcode_command) {
          publish_booking_resource_shortcode(shortcode_resource_id, shortcode_value, shortcode_button);
        }
        return;
      }
      if (create_button) {
        event.preventDefault();
        open_inspector(config, 'create', 0, create_button);
        return;
      }
      if (upgrade_button) {
        event.preventDefault();
        open_booking_resource_upgrade_dialog(upgrade_button);
        return;
      }
      if (cancel_button) {
        event.preventDefault();
        if ('inline_review' === inspector_mode) {
          inspector_dirty = false;
          close_inspector(config, false);
          inline_state.review_token = '';
          synchronize_inline_controls(config);
        } else if ('capacity_review' === inspector_mode && inspector_capacity_context) {
          var reviewed_target_capacity = inspector_capacity_target;
          var reviewed_detach_ids = inspector_capacity_detach_ids.slice();
          var reviewed_decrease_action = inspector_capacity_decrease_action;
          inspector_dirty = false;
          render_capacity_editor(config, inspector_capacity_context);
          inspector_capacity_target = reviewed_target_capacity;
          inspector_capacity_detach_ids = reviewed_detach_ids;
          inspector_capacity_decrease_action = reviewed_decrease_action;
          synchronize_capacity_editor(config);
        } else {
          close_inspector(config, true);
        }
        return;
      }
      if (remove_image_button) {
        event.preventDefault();
        var image_field = remove_image_button.closest('[data-wpbc-catalog-resource-field-wrap]').querySelector('[data-wpbc-catalog-resource-field="picture_url"]');
        if (image_field) {
          image_field.value = '';
          synchronize_inspector_image(image_field);
          synchronize_inspector_dirty_state();
        }
        return;
      }
      if (!inline_state.active && resource_row && !event.target.closest('a, button, input, select, textarea, summary, details, label')) {
        open_inspector(config, 'edit', Number(resource_row.getAttribute('data-wpbc-booking-resource-id')) || 0, resource_row);
      }
    });
    document.addEventListener('input', function (event) {
      if (event.target.matches('[data-wpbc-catalog-capacity-target], [data-wpbc-catalog-capacity-range]')) {
        var context = inspector_capacity_context || {};
        var minimum = Number(context.minimum_capacity) || 1;
        var maximum = Number(context.maximum_capacity) || minimum;
        var requested_capacity = Math.round(Number(event.target.value) || minimum);
        inspector_capacity_target = Math.max(minimum, Math.min(maximum, requested_capacity));
        synchronize_capacity_editor(config);
        return;
      }
      if (event.target.matches('[data-wpbc-catalog-inline-field]')) {
        synchronize_inline_drafts(config);
        return;
      }
      if (event.target.matches('[data-wpbc-catalog-resource-bulk-value], [data-wpbc-catalog-resource-bulk-range]')) {
        synchronize_bulk_editor(event.target);
        return;
      }
      if (event.target.matches('[data-wpbc-catalog-resource-range]')) {
        synchronize_inspector_number_from_range(event.target);
        return;
      }
      if (event.target.matches('[data-wpbc-catalog-resource-inspector-form] [data-wpbc-catalog-resource-field]')) {
        mark_changeover_policy_touched(event.target);
        if ('picture_url' === event.target.getAttribute('data-wpbc-catalog-resource-field')) {
          synchronize_inspector_image(event.target);
        }
        if ('number' === event.target.type) {
          synchronize_inspector_numeric_range(event.target.getAttribute('data-wpbc-catalog-resource-field') || '');
        }
        synchronize_inspector_dirty_state();
      }
    });
    document.addEventListener('change', function (event) {
      if (get_delete_review_workflow() && get_delete_review_workflow().handle_change(event)) {
        return;
      }
      if (event.target.matches('[data-wpbc-catalog-capacity-decrease-action]')) {
        inspector_capacity_decrease_action = 'delete' === event.target.value ? 'delete' : 'detach';
        synchronize_capacity_editor(config);
        return;
      }
      if (event.target.matches('[data-wpbc-catalog-capacity-detach]')) {
        var detach_id = Number(event.target.value) || 0;
        if (event.target.checked) {
          if (-1 === inspector_capacity_detach_ids.indexOf(detach_id)) {
            inspector_capacity_detach_ids.push(detach_id);
          }
        } else {
          inspector_capacity_detach_ids = inspector_capacity_detach_ids.filter(function (resource_id) {
            return resource_id !== detach_id;
          });
        }
        synchronize_capacity_editor(config);
        return;
      }
      if (event.target.matches('[data-wpbc-catalog-inline-field]')) {
        synchronize_inline_drafts(config);
        return;
      }
      if (event.target.matches('[data-wpbc-catalog-resource-bulk-enable], [data-wpbc-catalog-resource-bulk-operation], [data-wpbc-catalog-resource-bulk-value], [data-wpbc-catalog-resource-bulk-range]')) {
        synchronize_bulk_editor(event.target);
        return;
      }
      if (event.target.matches('[data-wpbc-catalog-capacity-delete-acknowledgement]')) {
        var capacity_delete_button = document.querySelector('[data-wpbc-ui-catalog-inspector-save]');
        var capacity_acknowledgement = event.target.closest('.wpbc_booking_resources__delete_acknowledgement');
        if (event.target.checked && capacity_acknowledgement) {
          capacity_acknowledgement.classList.remove('wpbc_booking_resources__delete_acknowledgement--attention');
        } else {
          pulse_delete_acknowledgement(capacity_acknowledgement);
        }
        if (capacity_delete_button) {
          capacity_delete_button.disabled = !event.target.checked;
        }
        return;
      }
      if (event.target.matches('[data-wpbc-catalog-resource-range]')) {
        synchronize_inspector_number_from_range(event.target);
        return;
      }
      if (event.target.matches('[data-wpbc-catalog-resource-radio-field="creation_mode"]')) {
        synchronize_create_inspector_controls();
        synchronize_inspector_dirty_state();
        return;
      }
      if (event.target.matches('[data-wpbc-catalog-resource-inspector-form] [data-wpbc-catalog-resource-field]')) {
        mark_changeover_policy_touched(event.target);
        if ('create' === inspector_mode) {
          synchronize_create_inspector_controls();
        }
        synchronize_changeover_policy_controls();
        synchronize_inspector_dirty_state();
      }
    });
    if (window.jQuery) {
      window.jQuery('.wpbc_settings_page_wrapper').on('wpbc:right-sidebar-before-content-collapse.wpbcCatalogBookingResources', function (event) {
        var closing_mode = inspector_mode;
        if (inspector_mode && !close_inspector(config, true)) {
          event.preventDefault();
          return;
        }
        if ('inline_review' === closing_mode) {
          inline_state.review_token = '';
          synchronize_inline_controls(config);
        }
      });
      window.jQuery(document).on('wpbc_media_upload_url_set', '[data-wpbc-catalog-resource-field="picture_url"]', function () {
        synchronize_inspector_image(this);
        synchronize_inspector_dirty_state();
      });
      window.jQuery(document).on('wpbc:resource-shortcode-selected', function (event, selection) {
        var selected_resource_id = Number(selection && selection.resource_id ? selection.resource_id : 0);
        var selected_shortcode = String(selection && selection.shortcode ? selection.shortcode : '');
        var inspector_shortcode;
        if (!selected_resource_id) {
          return;
        }
        synchronize_booking_resource_shortcode_input(selected_resource_id, selected_shortcode);
        document.querySelectorAll('[data-wpbc-booking-resource-id="' + String(selected_resource_id) + '"][data-wpbc-booking-resource-shortcode-command]').forEach(function (action_button) {
          action_button.setAttribute('data-wpbc-booking-resource-shortcode', selected_shortcode);
        });
        var details_row = document.querySelector('[data-wpbc-booking-resource-details-row="' + String(selected_resource_id) + '"]');
        var details_code = details_row ? details_row.querySelector('[data-wpbc-booking-resource-details-section="booking_page"] code') : null;
        if (details_code) {
          details_code.textContent = selected_shortcode;
          details_code.setAttribute('data-wpbc-ui-catalog-overflow-tooltip', selected_shortcode);
          synchronize_overflow_tooltips(document.getElementById(config.mount_id));
        }
        if (selected_resource_id === inspector_resource_id) {
          inspector_shortcode = document.querySelector('[data-wpbc-catalog-resource-inspector-form] .wpbc_catalog_booking_resources__editor_code');
          if (inspector_shortcode) {
            inspector_shortcode.value = selected_shortcode;
            synchronize_inspector_dirty_state();
          }
        }
      });
    }
    document.addEventListener('submit', function (event) {
      if (event.target.matches('[data-wpbc-catalog-inline-review-form]')) {
        apply_inline_changes(event, config);
      } else if (event.target.matches('[data-wpbc-catalog-resource-inspector-form]')) {
        submit_inspector(event, config);
      } else if (event.target.matches('[data-wpbc-catalog-resource-bulk-form], [data-wpbc-catalog-resource-bulk-review-form], [data-wpbc-catalog-resource-delete-form], [data-wpbc-catalog-resource-capacity-form]')) {
        submit_reviewed_inspector(event, config);
      }
    });
    window.addEventListener('beforeunload', function (event) {
      if (inspector_dirty || inspector_mutation_in_progress || inline_state.active && inline_state.changed_rows.length) {
        event.preventDefault();
        event.returnValue = '';
      }
    });
  }
  if ('loading' === document.readyState) {
    document.addEventListener('DOMContentLoaded', mount_booking_resources_catalog);
  } else {
    mount_booking_resources_catalog();
  }
})(window, document);
//# sourceMappingURL=data:application/json;charset=utf8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiaW5jbHVkZXMvcGFnZS1jYXRhbG9nLWJvb2tpbmctcmVzb3VyY2VzL19vdXQvYm9va2luZ19yZXNvdXJjZXNfY2F0YWxvZy5qcyIsIm5hbWVzIjpbIndpbmRvdyIsImRvY3VtZW50IiwiY2F0YWxvZ19jb250cm9sbGVyIiwiaW5saW5lX3dvcmtmbG93X2NvbnRyb2xsZXIiLCJpbmxpbmVfcmV2aWV3X3dvcmtmbG93X2NvbnRyb2xsZXIiLCJkZWxldGVfcmV2aWV3X3dvcmtmbG93X2NvbnRyb2xsZXIiLCJpbnNwZWN0b3Jfd29ya2Zsb3dfY29udHJvbGxlciIsImNhdGFsb2dfcmVzcG9uc2UiLCJkZXRhaWxzX2Fib3J0X2NvbnRyb2xsZXIiLCJkZXRhaWxzX3JlcXVlc3Rfc2VxdWVuY2UiLCJkZXRhaWxzX3Jlc291cmNlX2lkIiwiZGV0YWlsc190b2dnbGVfYnV0dG9uIiwicGVuZGluZ19mb2N1c19kaXJlY3Rpb24iLCJpbnNwZWN0b3JfZGlydHkiLCJpbnNwZWN0b3JfZm9jdXNfdGFyZ2V0IiwiaW5zcGVjdG9yX21vZGUiLCJpbnNwZWN0b3JfbXV0YXRpb25faW5fcHJvZ3Jlc3MiLCJpbnNwZWN0b3JfbXV0YXRpb25fcmVxdWVzdF9zZXF1ZW5jZSIsImluc3BlY3Rvcl9vcmlnaW5hbF9maWVsZHMiLCJpbnNwZWN0b3JfcmVxdWVzdF9zZXF1ZW5jZSIsImluc3BlY3Rvcl9yZXNvdXJjZV9pZCIsImluc3BlY3Rvcl9yZXNvdXJjZV9pZHMiLCJpbnNwZWN0b3JfYnVsa19vcGVyYXRpb25zIiwiaW5zcGVjdG9yX3Jldmlld190b2tlbiIsImluc3BlY3Rvcl9zZWxlY3Rpb25fc3RhbGUiLCJpbnNwZWN0b3JfdHJhY2tzX3NlbGVjdGlvbiIsImluc3BlY3Rvcl9jYXBhY2l0eV9jb250ZXh0IiwiaW5zcGVjdG9yX2NhcGFjaXR5X2RldGFjaF9pZHMiLCJpbnNwZWN0b3JfY2FwYWNpdHlfZGVjcmVhc2VfYWN0aW9uIiwiaW5zcGVjdG9yX2NhcGFjaXR5X3RhcmdldCIsInBlbmRpbmdfaGlnaGxpZ2h0X2lkcyIsImxhdW5jaF9pbnRlbnRfaGFuZGxlZCIsImlubGluZV9zdGF0ZSIsImFjdGl2ZSIsImNoYW5nZWRfcm93cyIsImxvYWRpbmciLCJyZXF1ZXN0X3NlcXVlbmNlIiwicmV2aWV3X3Rva2VuIiwiaXNfdHJ1ZV9mbGFnIiwiZmxhZ192YWx1ZSIsIlN0cmluZyIsInRvTG93ZXJDYXNlIiwiZm9ybWF0X21lc3NhZ2UiLCJ0ZW1wbGF0ZSIsInZhbHVlcyIsIm1lc3NhZ2UiLCJmb3JFYWNoIiwicmVwbGFjZW1lbnQiLCJyZXBsYWNlbWVudF9pbmRleCIsInBsYWNlaG9sZGVyIiwiUmVnRXhwIiwicmVwbGFjZSIsImdldF9pbmxpbmVfcmV2aWV3X3dvcmtmbG93Iiwid3BiY191aV9jYXRhbG9nIiwiY3JlYXRlX2lubGluZV9yZXZpZXdfd29ya2Zsb3ciLCJhcHBseV9zZWxlY3RvciIsImNhbmNlbF9zZWxlY3RvciIsInJvb3QiLCJnZXRfZGVsZXRlX3Jldmlld193b3JrZmxvdyIsImNyZWF0ZV9kZWxldGVfcmV2aWV3X3dvcmtmbG93IiwiYWNrbm93bGVkZ2VtZW50X3NlbGVjdG9yIiwiZ2V0X2NoaWxkcmVuX3N1bW1hcnlfbGFiZWwiLCJwYXJlbnRfcmVzb3VyY2UiLCJpMThuIiwiaGllcmFyY2h5Iiwic2VydmVyX2xhYmVsIiwiY2hpbGRyZW5fbGFiZWwiLCJ0cmltIiwiY2hpbGRfY291bnQiLCJNYXRoIiwibWF4IiwiTnVtYmVyIiwicmVuZGVyZWRfY2hpbGRyZW5fY291bnQiLCJsYWJlbF90ZW1wbGF0ZSIsImNoaWxkX2NvdW50X3Npbmd1bGFyIiwiY2hpbGRfY291bnRfcGx1cmFsIiwicmVuZGVyX2NvbXBvbmVudCIsImNvbmZpZyIsInRlbXBsYXRlX3JvbGUiLCJ0ZW1wbGF0ZV9kYXRhIiwiY29tcG9uZW50X3RlbXBsYXRlIiwibG9hZF90ZW1wbGF0ZSIsImVycm9yIiwiZ2V0X2NvbHVtbnMiLCJkaXNwbGF5X3N0YXRlIiwidmlzaWJsZV9vbmx5Iiwic29ydGluZ19zdGF0ZSIsImNvbHVtbl9jb25maWciLCJjb2x1bW5zIiwiZGVmaW5pdGlvbnMiLCJkZWZhdWx0X29yZGVyIiwiQXJyYXkiLCJpc0FycmF5Iiwib3JkZXIiLCJjb2x1bW5fb3JkZXIiLCJzbGljZSIsInZpc2libGVfY29sdW1ucyIsImRlZmF1bHRfdmlzaWJsZSIsImNvbHVtbl9pZCIsImluZGV4T2YiLCJwdXNoIiwiZmlsdGVyIiwibWFwIiwiY29sdW1uX2luZGV4IiwiZGVmaW5pdGlvbiIsImlzX3NvcnRlZCIsInNvcnRfa2V5Iiwic29ydF9ieSIsImFyaWFfc29ydCIsInNvcnRfb3JkZXIiLCJjbGFzc19uYW1lIiwiY2xhc3MiLCJkZWZhdWx0X2luZGV4IiwiaWQiLCJsYWJlbCIsIm1vdmVfbGFiZWwiLCJtb3ZlX2NvbHVtbiIsInJlb3JkZXJhYmxlIiwicmVxdWlyZWQiLCJzb3J0X2ljb24iLCJ2aXNpYmxlIiwiZ2V0X2FjdGl2ZV92aWV3Iiwidmlld19kZWZpbml0aW9ucyIsInZpZXdzIiwiY3VycmVudF92aXNpYmxlIiwibWF0Y2hpbmdfdmlldyIsIk9iamVjdCIsImtleXMiLCJzb21lIiwidmlld19pZCIsInZpZXdfZmllbGRzIiwiZmllbGRzIiwiSlNPTiIsInN0cmluZ2lmeSIsImdldF92aWV3X2RlZmluaXRpb25zIiwiZ2V0X3RlbXBsYXRlX3BhY2tfZGVmaW5pdGlvbnMiLCJsYWJlbHMiLCJjYXJkcyIsImxheW91dF9jYXJkcyIsImNvbXBhY3QiLCJsYXlvdXRfY29tcGFjdCIsInRhYmxlIiwibGF5b3V0X3RhYmxlIiwidGVtcGxhdGVfcGFja3MiLCJ0ZW1wbGF0ZV9wYWNrX2lkIiwicmVuZGVyX2Jvb2tpbmdfcmVzb3VyY2VzX2ZpbHRlcnMiLCJpbml0aWFsX3JlcXVlc3QiLCJtb3VudF9lbGVtZW50IiwiZ2V0RWxlbWVudEJ5SWQiLCJtb3VudF9pZCIsImZpbHRlcnNfdGFyZ2V0IiwicXVlcnlTZWxlY3RvciIsImlubmVySFRNTCIsInJlc291cmNlX3R5cGUiLCJzZWFyY2giLCJzaG93X2ZpbHRlcnMiLCJmZWF0dXJlcyIsInJlc291cmNlX2ZpbHRlcnMiLCJzaG93X3Jlc291cmNlX3R5cGVfZmlsdGVyIiwicmVzb3VyY2VfdHlwZV9maWx0ZXIiLCJyZW5kZXJfYm9va2luZ19yZXNvdXJjZXNfdG9vbGJhciIsInRvb2xiYXJfdGFyZ2V0IiwiYWN0aXZlX3RlbXBsYXRlX3BhY2siLCJ0ZW1wbGF0ZV9wYWNrIiwiZGVmYXVsdF90ZW1wbGF0ZV9wYWNrIiwiYWN0aXZlX3ZpZXciLCJyZWZyZXNoX2NvbnRyb2xzIiwiZmlyc3RFbGVtZW50Q2hpbGQiLCJzeW5jaHJvbml6ZV9vdmVyZmxvd190b29sdGlwcyIsImNhdGFsb2dfbW91bnQiLCJpbml0aWFsaXplX2RldGFpbHNfdG9vbHRpcHMiLCJ0b29sdGlwX3NlbGVjdG9yIiwid3BiY19kZWZpbmVfdGlwcHlfdG9vbHRpcHMiLCJzeW5jaHJvbml6ZV9ib29raW5nX3Jlc291cmNlc190b29sYmFyIiwicmVzcG9uc2UiLCJkaXNwbGF5Iiwic29ydGluZyIsImNvbHVtbl9saXN0Iiwic2VhcmNoX2NvbnRyb2wiLCJ0ZW1wbGF0ZV9wYWNrX2NvbnRyb2wiLCJ0eXBlX2NvbnRyb2wiLCJ2aWV3X2NvbnRyb2wiLCJhY3RpdmVFbGVtZW50IiwidmFsdWUiLCJmaWx0ZXJzIiwiY29sdW1uIiwiY29sdW1uX2NvbnRyb2wiLCJjb2x1bW5faXRlbSIsImNoZWNrZWQiLCJhcHBlbmRDaGlsZCIsInJlbmRlcl9pbmxpbmVfYmFyIiwiaW5saW5lX2hvc3QiLCJyZWdpc3Rlcl9zdGlja3lfYmFyIiwic3luY2hyb25pemVfaW5saW5lX2NvbnRyb2xzIiwiY2hhbmdlZF9jb3VudCIsImxlbmd0aCIsImNvdW50X2xhYmVsIiwiaW5saW5lX2NoYW5nZWRfcm93IiwiaW5saW5lX2NoYW5nZWRfcm93cyIsInN5bmNocm9uaXplIiwiYWN0aXZlX3RvZ2dsZV90ZXh0IiwiaW5saW5lX2VkaXRpbmdfcm93cyIsImJ1c3kiLCJjb3VudF90ZXh0IiwiaW5saW5lX2xvYWRpbmciLCJoYXNfaXRlbXMiLCJpbmFjdGl2ZV90b2dnbGVfdGV4dCIsImVkaXRfcm93cyIsInByb3RlY3RfaW5saW5lX2RyYWZ0c19mcm9tX2NhdGFsb2dfY29udHJvbHMiLCJldmVudCIsInByb3RlY3RfZXZlbnQiLCJzaG93X2lubGluZV9tZXNzYWdlIiwibm90aWNlIiwiaGlkZGVuIiwidGV4dCIsInRleHRDb250ZW50IiwicmVuZGVyX2lubGluZV9yb3ciLCJyb3dfc2NoZW1hIiwicmVzb3VyY2VfaWQiLCJyb3ciLCJyZXNvdXJjZV9maWVsZHMiLCJmaWVsZCIsImNlbGwiLCJjb3B5Iiwid3JhcHBlciIsImNyZWF0ZUVsZW1lbnQiLCJjbGFzc05hbWUiLCJpbnNlcnRBZGphY2VudEhUTUwiLCJyZXBsYWNlV2l0aCIsImNvbGxlY3RfaW5saW5lX2RyYWZ0cyIsInF1ZXJ5U2VsZWN0b3JBbGwiLCJoYXNfY2hhbmdlcyIsImluZGljYXRvcl9ob3N0IiwiY29udHJvbCIsImZpZWxkX2tleSIsImdldEF0dHJpYnV0ZSIsImNsYXNzTGlzdCIsInRvZ2dsZSIsInNldF9yb3dfY2hhbmdlZCIsImlubGluZV9jaGFuZ2VkIiwic3luY2hyb25pemVfaW5saW5lX2RyYWZ0cyIsImxlYXZlX2lubGluZV9tb2RlIiwicmVsb2FkIiwiY2xvc2VfaW5zcGVjdG9yIiwic2hvd19hZG1pbl9tZXNzYWdlIiwibG9hZCIsInN0YXJ0X2lubGluZV9tb2RlIiwicmVzb3VyY2VfaWRzIiwiY29uZmlybSIsImlubGluZV9kaXNjYXJkIiwiY2FuX2Rpc2NhcmRfaW5zcGVjdG9yIiwiY3VzdG9taXplciIsInJlbW92ZUF0dHJpYnV0ZSIsImNsb3NlX2RldGFpbHNfcm93IiwicmVxdWVzdF9pbnNwZWN0b3IiLCJpbmxpbmVfc2NoZW1hX2FjdGlvbiIsInRoZW4iLCJzdWNjZXNzIiwiZGF0YSIsInNjaGVtYSIsIkVycm9yIiwiZ2V0X2luc3BlY3Rvcl9yZXNwb25zZV9tZXNzYWdlIiwiaW5saW5lX2xvYWRfZmFpbGVkIiwicm93cyIsImZpcnN0X2ZpZWxkIiwiZm9jdXMiLCJjYXRjaCIsInByZXZpZXdfaW5saW5lX2NoYW5nZXMiLCJmb2N1c190YXJnZXQiLCJpbnNwZWN0b3Jfd29ya2Zsb3ciLCJnZXRfaW5zcGVjdG9yX3dvcmtmbG93IiwibW91bnQiLCJvcGVuX2xvYWRpbmciLCJpbmxpbmVfcHJldmlld19hY3Rpb24iLCJyZXZpZXdfd29ya2Zsb3ciLCJyZXZpZXdfbW9kZWwiLCJ0YXJnZXQiLCJwcmV2aWV3IiwiaW5saW5lX3Jldmlld19mYWlsZWQiLCJnZXRfaW5zcGVjdG9yX2hvc3QiLCJwcmVwYXJlIiwicmV2aWV3IiwiY2hhbmdlZF9sYWJlbCIsImRlc2NyaXB0aW9uIiwiaW5saW5lX3Jldmlld19kZXNjcmlwdGlvbiIsImZvcm1faWQiLCJtb2RlIiwicGVuZGluZ19tZXNzYWdlIiwicmV2aWV3X2NoYW5nZXNfaGVscCIsInRpdGxlIiwiaW5saW5lX3Jldmlld190aXRsZSIsInNldF9pbnNwZWN0b3Jfc3RhdGUiLCJjb25maWd1cmVfaW5zcGVjdG9yX2Zvb3RlciIsImFwcGx5X2NoYW5nZXMiLCJjYW5fYXBwbHkiLCJmb2N1c19pbnNwZWN0b3JfaGVhZGluZyIsImFwcGx5X2lubGluZV9jaGFuZ2VzIiwiZm9ybSIsInNhdmVfYnV0dG9uIiwicHJldmVudERlZmF1bHQiLCJkaXNhYmxlZCIsImFkZCIsImlubGluZV9hcHBseV9hY3Rpb24iLCJpbmxpbmVfYXBwbHlfZmFpbGVkIiwidXBkYXRlZF9pZHMiLCJkb2N1bWVudEVsZW1lbnQiLCJjb250YWlucyIsInNob3dfaW5zcGVjdG9yX21lc3NhZ2UiLCJyZW1vdmUiLCJyZW5kZXJfYm9va2luZ19yZXNvdXJjZXNfcmVzcG9uc2UiLCJjYXRhbG9nX2hlYWRpbmciLCJjYXJkX2dyb3VwcyIsImNoaWxkcmVuX2J5X3BhcmVudCIsImhlYWRlcl9lbGVtZW50IiwiaGllcmFyY2h5X2VuYWJsZWQiLCJoaWVyYXJjaHlfaXNfZXhwYW5kZWQiLCJpc19jYXJkc19wYWNrIiwicGFyZW50X3Jlc291cmNlcyIsInBhZ2luYXRpb24iLCJwYWdpbmF0aW9uX2VsZW1lbnQiLCJyb3dzX2VsZW1lbnQiLCJpdGVtcyIsImVuYWJsZWQiLCJ3cGJjX3VpX2NhdGFsb2dfaGllcmFyY2h5IiwiZ2V0X2luaXRpYWxfZXhwYW5kZWQiLCJhbGxfZXhwYW5kZWQiLCJzZWxlY3Rpb25fZW5hYmxlZCIsInNlbGVjdGlvbiIsInJlc291cmNlIiwidHlwZSIsInBhcmVudF9pZCIsImFjdGlvbl90YXJnZXQiLCJjbGFzc2ljX2xhYmVsX2NsYXNzZXMiLCJjaGlsZCIsImNvc3QiLCJvd25lciIsInBhcmVudCIsInNpbmdsZSIsImxhYmVsX3RhcmdldCIsInByaWNlX3RhcmdldCIsIm5vX2Rlc2NyaXB0aW9uIiwiYXNzaWduIiwicGFyZW50X2NvbnRleHRfbGFiZWwiLCJyb3dfdmFyaWFudCIsInJvd190ZW1wbGF0ZV9yb2xlIiwidHlwZV9iYWRnZV9sYWJlbCIsImluZGVwZW5kZW50X2xhYmVsIiwiZXhwYW5kYWJsZSIsInBhcmVudF90aXRsZSIsImNoaWxkX29mIiwicmVuZGVyZWRfY2hpbGRfY291bnQiLCJwYXJlbnRfY2hpbGRyZW5fdGVtcGxhdGUiLCJwYXJlbnRfY2hpbGRfbGFiZWwiLCJwYXJlbnRfY2hpbGRyZW5fbGFiZWwiLCJwYXJlbnRfbGFiZWwiLCJjaGlsZF9sYWJlbCIsInJlc291cmNlX3Jvd19kYXRhIiwiY29sbGFwc2VfbGFiZWwiLCJjb2xsYXBzZV9jaGlsZHJlbl9mb3IiLCJjb2xsYXBzZV9jaGlsZHJlbiIsImV4cGFuZF9sYWJlbCIsImV4cGFuZF9jaGlsZHJlbl9mb3IiLCJleHBhbmRfY2hpbGRyZW4iLCJpc19leHBhbmRlZCIsInNlbGVjdGlvbl9sYWJlbCIsInNlbGVjdF9yZXNvdXJjZSIsInRodW1ibmFpbF9sYWJlbCIsInRodW1ibmFpbF90b29sdGlwIiwicmVzb3VyY2Vfcm93X2h0bWwiLCJyZXNvdXJjZV9yb3ciLCJjaGlsZF9yZXNvdXJjZXMiLCJjYXJkX2dyb3VwX2h0bWwiLCJjaGlsZHJlbl9kZXNjcmlwdGlvbiIsImNoaWxkcmVuX2JlbG9uZ190byIsImNoaWxkcmVuX2hlYWRpbmciLCJjaGlsZHJlbl9vZl9jb3VudCIsInBhcmVudF9ub2RlX2lkIiwibm9kZV9pZCIsInN0YWNrX2l0ZW1zIiwibGFzdEVsZW1lbnRDaGlsZCIsInBhcmVudF9zbG90IiwiY2hpbGRyZW5fc2xvdCIsImFyaWFfbGFiZWwiLCJjb2x1bW5fbGFiZWxzIiwiZW1wdHlfbGFiZWwiLCJub19sYWJlbHMiLCJraW5kIiwicHJpY2VfdW5hdmFpbGFibGUiLCJwcmljZSIsImFjdGlvbnMiLCJhY3Rpb25faXRlbXMiLCJhY3Rpb24iLCJhY3Rpb25fY2xhc3NlcyIsImFkanVzdF9jYXBhY2l0eSIsImRlbGV0ZV9yZXNvdXJjZSIsImVkaXRfcmVzb3VyY2UiLCJwdWJsaXNoX3Jlc291cmNlIiwiYWN0aW9uX2lkIiwiYWN0aW9uc19mb3IiLCJub19hY3Rpb25zIiwibWVudV9pZCIsImlzX2xhc3Rfc2libGluZyIsInN1bW1hcnlfdGFyZ2V0IiwicGFnaW5hdGlvbl9sYWJlbCIsImhhc19uZXh0IiwicGFnZV9udW1iZXIiLCJ0b3RhbF9wYWdlcyIsImhhc19wcmV2aW91cyIsIml0ZW1zX3Blcl9wYWdlIiwiaXRlbXNfcGVyX3BhZ2Vfb3B0aW9ucyIsIm9wdGlvbnMiLCJuZXh0X2xhYmVsIiwibmV4dF9wYWdlIiwibWluIiwicGFnZV9udW1iZXJfbGFiZWwiLCJwZXJfcGFnZV9sYWJlbCIsInBlcl9wYWdlIiwicHJldmlvdXNfbGFiZWwiLCJwcmV2aW91c19wYWdlIiwicmVzdWx0c19zdGF0dXMiLCJpdGVtc19mcm9tIiwiaXRlbXNfdG8iLCJ0b3RhbF9pdGVtcyIsInNob3dfbGFiZWwiLCJzaG93IiwiZ2V0X2RldGFpbHNfY29sc3BhbiIsInZpc2libGVfY2VsbHMiLCJ0YWdOYW1lIiwicHJvdG90eXBlIiwiY2FsbCIsImNlbGxzIiwiZ2V0Q29tcHV0ZWRTdHlsZSIsImdldF9yZXNvdXJjZV9pdGVtX2NvbnRhaW5lciIsInNvdXJjZV9lbGVtZW50IiwiY2xvc2VzdCIsInN5bmNocm9uaXplX2NhcmRfZ3JvdXBfcGFuZWxzIiwiY2F0YWxvZ19yb290IiwiaGFzQXR0cmlidXRlIiwiY2FyZF9ncm91cCIsImNoaWxkcmVuX3BhbmVsIiwidmlzaWJsZV9jaGlsZCIsInNldF9kZXRhaWxzX3RvZ2dsZV9zdGF0ZSIsInRvZ2dsZV9idXR0b24iLCJpY29uIiwic2V0QXR0cmlidXRlIiwicmVzdG9yZV9mb2N1cyIsImFjdGl2ZV9yb3ciLCJhYm9ydCIsInBhcmVudE5vZGUiLCJyZW1vdmVDaGlsZCIsInNvdXJjZV9yb3ciLCJyZW5kZXJfZGV0YWlsc19yb3ciLCJkZXRhaWxzX2h0bWwiLCJpbnNlcnRpb25fdGFyZ2V0IiwibmV4dEVsZW1lbnRTaWJsaW5nIiwiZ2V0X2RldGFpbHNfZXJyb3JfbWVzc2FnZSIsImZhbGxiYWNrIiwib3Blbl9kZXRhaWxzX3JvdyIsImRldGFpbHNfcmVxdWVzdF9pZCIsInJlcXVlc3RfYm9keSIsInJlc291cmNlX3RpdGxlX2VsZW1lbnQiLCJyZXNvdXJjZV90aXRsZSIsInRlbXBsYXRlX2Jhc2UiLCJjb2xzcGFuIiwibG9hZGluZ19sYWJlbCIsImRldGFpbHNfbG9hZGluZyIsInN0YXRlIiwiVVJMU2VhcmNoUGFyYW1zIiwiYXBwZW5kIiwiZGV0YWlsc19hY3Rpb24iLCJub25jZSIsIkFib3J0Q29udHJvbGxlciIsImZldGNoIiwiYWpheF91cmwiLCJib2R5IiwidG9TdHJpbmciLCJjcmVkZW50aWFscyIsImhlYWRlcnMiLCJtZXRob2QiLCJzaWduYWwiLCJ1bmRlZmluZWQiLCJqc29uIiwicmVxdWVzdF9pZCIsImRldGFpbHMiLCJzZWN0aW9ucyIsImVycm9yX21lc3NhZ2UiLCJkZXRhaWxzX2xvYWRfZmFpbGVkIiwibmFtZSIsImNvcHlfZGV0YWlsc192YWx1ZSIsImNvcHlfdmFsdWUiLCJhY3Rpb25fYnV0dG9uIiwiZGV0YWlsc19yb3ciLCJzdGF0dXNfZWxlbWVudCIsImNvcHlfcHJvbWlzZSIsIm5hdmlnYXRvciIsImNsaXBib2FyZCIsIndyaXRlVGV4dCIsIlByb21pc2UiLCJyZXNvbHZlIiwicmVqZWN0IiwiY29weV9pbnB1dCIsInN0eWxlIiwicG9zaXRpb24iLCJvcGFjaXR5Iiwic2VsZWN0IiwiZXhlY0NvbW1hbmQiLCJzaG9ydGNvZGVfY29waWVkIiwic2hvcnRjb2RlX2NvcHlfZmFpbGVkIiwiZ2V0X2Jvb2tpbmdfcmVzb3VyY2Vfc2hvcnRjb2RlIiwiaW5zcGVjdG9yX3Nob3J0Y29kZSIsImhpZGRlbl9zaG9ydGNvZGUiLCJzeW5jaHJvbml6ZV9ib29raW5nX3Jlc291cmNlX3Nob3J0Y29kZV9pbnB1dCIsInNob3J0Y29kZSIsImlucHV0IiwiY3VzdG9taXplX2Jvb2tpbmdfcmVzb3VyY2Vfc2hvcnRjb2RlIiwid3BiY19yZXNvdXJjZV9wYWdlX2J0bl9jbGljayIsInB1Ymxpc2hfYm9va2luZ19yZXNvdXJjZV9zaG9ydGNvZGUiLCJ0cmlnZ2VyX2J1dHRvbiIsIndwYmNfcHVibGlzaF9ib29raW5nX2Zvcm1fX29wZW4iLCJvcGVuX2Jvb2tpbmdfcmVzb3VyY2VfdXBncmFkZV9kaWFsb2ciLCJtb2RhbF9lbGVtZW50IiwidXBncmFkZV91cmwiLCJqUXVlcnkiLCJ3cGJjX215X21vZGFsIiwib2ZmIiwib25lIiwib3BlbiIsIm9wZW5fYm9va2luZ19yZXNvdXJjZV9tZXNzYWdlX2RpYWxvZyIsInRpdGxlX2VsZW1lbnQiLCJkZXNjcmlwdGlvbl9lbGVtZW50IiwiYWxlcnQiLCJnZXRfaW5zcGVjdG9yX2Zvb3RlciIsImNyZWF0ZV9pbnNwZWN0b3Jfd29ya2Zsb3ciLCJleHBhbmQiLCJleHBhbmRfaW5zcGVjdG9yX3NpZGViYXIiLCJnZXRfZm9vdGVyIiwiZ2V0X2hvc3QiLCJyZW5kZXJfc2hlbGwiLCJzaGVsbF9kYXRhIiwiY2F0YWxvZ19pZCIsImVtcHR5X2ljb24iLCJlbXB0eV9tZXNzYWdlIiwiaW5zcGVjdG9yX2VtcHR5X21lc3NhZ2UiLCJlbXB0eV90aXRsZSIsImluc3BlY3Rvcl9lbXB0eV90aXRsZSIsImluc3BlY3Rvcl9sb2FkaW5nIiwibW91bnRfaW5zcGVjdG9yX3NoZWxsIiwic3luY2hyb25pemVfaW5zcGVjdG9yX3dpZHRoIiwid3BiY19hZG1pbl91aV9fc2lkZWJhcl9yaWdodF9fZG9fbWF4IiwiZGlzcGF0Y2hFdmVudCIsIkN1c3RvbUV2ZW50IiwiaG9zdCIsInNpZGViYXIiLCJtYXJrX2luc3BlY3Rvcl9yZXNvdXJjZV9yb3ciLCJzY3JvbGxJbnRvVmlldyIsImJsb2NrIiwiYmVoYXZpb3IiLCJzZXRfc3RhdGUiLCJzZXJpYWxpemVfaW5zcGVjdG9yX2ZpZWxkcyIsImdldF9pbnNwZWN0b3JfY3JlYXRpb25fbW9kZSIsInNlbGVjdGVkX21vZGUiLCJoaWRkZW5fbW9kZSIsInN5bmNocm9uaXplX2NyZWF0ZV9pbnNwZWN0b3JfY29udHJvbHMiLCJjcmVhdGlvbl9tb2RlIiwicGFyZW50X3dyYXAiLCJyYWRpbyIsImNob2ljZSIsImZpZWxkX3dyYXAiLCJzeW5jaHJvbml6ZV9jaGFuZ2VvdmVyX3BvbGljeV9jb250cm9scyIsInBvbGljeV9maWVsZCIsImlzX2N1c3RvbSIsImdsb2JhbF90aW1lc193cmFwIiwibWFya19jaGFuZ2VvdmVyX3BvbGljeV90b3VjaGVkIiwidG91Y2hlZF9maWVsZCIsInN5bmNocm9uaXplX2luc3BlY3Rvcl9kaXJ0eV9zdGF0ZSIsInNhdmVfaXNfYnVzeSIsInRpdGxlX2ZpZWxkIiwicGFyZW50X2ZpZWxkIiwiY3JlYXRlX2lzX3ZhbGlkIiwiaW5zcGVjdG9yX2Rpc2NhcmQiLCJjb25maXJtX2Rpc2NhcmQiLCJ3cGJjX2FkbWluX3VpX19zaWRlYmFyX3JpZ2h0X19kb19oaWRlIiwia2V5IiwiZ2V0X3NlbGVjdGVkX3Jlc291cmNlX2lkcyIsIl93cGJjX3VpX2NhdGFsb2dfc2VsZWN0aW9uX2NvbnRyb2xsZXIiLCJzZWxlY3RlZF9pZHMiLCJnZXRfc2VsZWN0ZWRfaWRzIiwicmVzb3VyY2VfaWRfbGlzdHNfbWF0Y2giLCJmaXJzdF9pZHMiLCJzZWNvbmRfaWRzIiwibm9ybWFsaXplX2lkcyIsInNvcnQiLCJmaXJzdF9pZCIsInNlY29uZF9pZCIsImdldF9zZWxlY3Rpb25fY291bnRfbGFiZWwiLCJjb3VudCIsInJlc291cmNlX3NlbGVjdGVkIiwicmVzb3VyY2VzX3NlbGVjdGVkIiwiYnV0dG9uX2xhYmVsIiwiZGVzdHJ1Y3RpdmUiLCJmb290ZXIiLCJjYW5jZWxfYnV0dG9uIiwiZGVsZXRlX3dvcmtmbG93Iiwid3BiY19jYXRhbG9nX2Jvb2tpbmdfcmVzb3VyY2VzX2NvbmZpZyIsImNhbmNlbCIsImNvbmZpZ3VyZV9mb290ZXIiLCJwdWxzZV9kZWxldGVfYWNrbm93bGVkZ2VtZW50IiwiYWNrbm93bGVkZ2VtZW50IiwibWF0Y2hlcyIsInB1bHNlX2Fja25vd2xlZGdlbWVudCIsIm9mZnNldFdpZHRoIiwiaXNfZXJyb3IiLCJub3RpY2VfdGV4dCIsImhlYWRpbmciLCJzZXRUaW1lb3V0IiwibWVzc2FnZV90eXBlIiwiZGVsYXkiLCJ3cGJjX2FkbWluX3Nob3dfbWVzc2FnZSIsImluc2VydEJlZm9yZSIsImZpcnN0Q2hpbGQiLCJhY3RpdmF0ZV9pbnNwZWN0b3Jfc2VjdGlvbiIsInNlY3Rpb25faWQiLCJncm91cCIsImNvbnRyb2xsZXIiLCJoZWFkZXIiLCJ0ZXN0IiwiX193cGJjX2NvbGxhcHNpYmxlX2luc3RhbmNlIiwiY2xpY2siLCJyZW5kZXJfaW5zcGVjdG9yX3NjaGVtYSIsImZvY3VzX3RpdGxlIiwiZm9ybV90YXJnZXQiLCJjYW5fY3JlYXRlIiwic2hvcnRjb2RlX2ZpZWxkIiwiYWRkX3Jlc291cmNlIiwic2F2ZV9jaGFuZ2VzIiwiV1BCQ19Db2xsYXBzaWJsZV9BdXRvSW5pdCIsInN5bmNocm9uaXplX2FsbF9pbnNwZWN0b3JfbnVtZXJpY19yYW5nZXMiLCJvcGVuX2luc3BlY3RvciIsImluc3BlY3Rvcl9jcmVhdGVfc2NoZW1hX2FjdGlvbiIsImluc3BlY3Rvcl9lZGl0X3NjaGVtYV9hY3Rpb24iLCJpbnNwZWN0b3JfbG9hZF9mYWlsZWQiLCJyZW5kZXJfYnVsa19lZGl0b3IiLCJzZWxlY3Rpb25fY291bnQiLCJyZXZpZXdfY2hhbmdlc19idXR0b24iLCJvcGVuX2J1bGtfZWRpdG9yIiwiYnVsa19zY2hlbWFfYWN0aW9uIiwiYnVsa19sb2FkX2ZhaWxlZCIsImhhbmRsZV9pbnNwZWN0b3Jfc2VsZWN0aW9uX2NoYW5nZSIsImNvbGxlY3RfYnVsa19vcGVyYXRpb25zIiwib3BlcmF0aW9ucyIsImVuYWJsZWRfY29udHJvbCIsIm9wZXJhdGlvbiIsImZpZWxkX3ZhbHVlIiwic3luY2hyb25pemVfYnVsa19lZGl0b3IiLCJjaGFuZ2VkX2NvbnRyb2wiLCJvcGVyYXRpb25fY29udHJvbCIsInByZWZpeF9lbGVtZW50Iiwic3VmZml4X2VsZW1lbnQiLCJvcGVyYXRpb25faWQiLCJpc19wZXJjZW50IiwibnVtYmVyX2NvbnRyb2wiLCJyYW5nZV9jb250cm9sIiwiZmllbGRfdmFsdWVfY29udHJvbCIsInJlbmRlcl9idWxrX3JldmlldyIsInJldmlld19jaGFuZ2VzIiwiZWRpdF9ib29raW5nX3Jlc291cmNlcyIsInJlbmRlcl9kZWxldGVfcmV2aWV3IiwiZGVsZXRlX2kxOG4iLCJkZWxldGVfYWNrbm93bGVkZ2VtZW50IiwiYWN0aW9uc19oZWFkaW5nIiwiYm9va2luZ3NfcmV0YWluZWRfd2FybmluZyIsInJlc291cmNlc190b19kZWxldGUiLCJyZXZpZXdfaGVscCIsImRlbGV0ZV9yZXZpZXdfaGVscCIsImRlbGV0ZV9ib29raW5nX3Jlc291cmNlcyIsIndhcm5pbmciLCJkZWxldGVfd2FybmluZyIsInJlc291cmNlcyIsImRlbGV0ZV9idXR0b24iLCJkZWxldGVfcmVzb3VyY2VzIiwib3Blbl9kZWxldGVfcmV2aWV3IiwidHJhY2tfc2VsZWN0aW9uIiwiZGVsZXRlX3ByZXZpZXdfYWN0aW9uIiwiZGVsZXRlX2xvYWRfZmFpbGVkIiwiZ2V0X2NhcGFjaXR5X2NvdW50X2xhYmVsIiwic2luZ3VsYXJfa2V5IiwicGx1cmFsX2tleSIsImdldF9jYXBhY2l0eV9lZGl0b3JfdmlldyIsImNvbnRleHQiLCJjdXJyZW50X2NhcGFjaXR5IiwidGFyZ2V0X2NhcGFjaXR5Iiwia2VlcF9jb3VudCIsImNyZWF0ZV9jb3VudCIsImRlY3JlYXNlX2NvdW50IiwiZGVsZXRlX2FjdGlvbiIsImNoaWxkcmVuIiwic2VsZWN0ZWQiLCJjb250ZXh0X2xhYmVsIiwiZGVjcmVhc2VfYWN0aW9uIiwiZGVjcmVhc2VfaGVhZGluZyIsImRlY3JlYXNlX2hlbHAiLCJkZWxldGVfdW5pdHNfaGVscCIsInNlbGVjdF9kZXRhY2hfaGVscCIsImRlY3JlYXNlX291dGNvbWVfbGFiZWwiLCJ3aWxsX2JlX2RlbGV0ZWQiLCJtYWtlX2luZGVwZW5kZW50IiwiY2FwYWNpdHlfZGVzY3JpcHRpb24iLCJjcmVhdGVfbGFiZWwiLCJrZWVwX2xhYmVsIiwibWF4aW11bV9jYXBhY2l0eSIsIm1pbmltdW1fY2FwYWNpdHkiLCJzeW5jaHJvbml6ZV9jYXBhY2l0eV9lZGl0b3IiLCJyZXF1aXJlZF9kZXRhY2hfY291bnQiLCJ0YXJnZXRfbnVtYmVyIiwidGFyZ2V0X3JhbmdlIiwiYWZ0ZXJfdmFsdWUiLCJpbmNyZWFzZV9yb3ciLCJkZWNyZWFzZV9wYW5lbCIsImFjdGlvbl9jb250cm9sIiwiYWN0aW9uX3NlbGVjdGVkIiwiY2hlY2tib3giLCJ1bml0Iiwib3V0Y29tZSIsInJlbmRlcl9jYXBhY2l0eV9lZGl0b3IiLCJ2aWV3IiwicmV2aWV3X2NhcGFjaXR5X2NoYW5nZSIsIm9wZW5fY2FwYWNpdHlfZWRpdG9yIiwiY2FwYWNpdHlfY29udGV4dF9hY3Rpb24iLCJjYXBhY2l0eV9sb2FkX2ZhaWxlZCIsInJlbmRlcl9jYXBhY2l0eV9yZXZpZXciLCJpbmNyZWFzZSIsImRlbGV0ZV9oYXNfYm9va2luZ3MiLCJyZXZpZXdfY2FwYWNpdHlfaGVscCIsIm9wZXJhdGlvbl9oZWxwIiwiY3JlYXRlX3VuaXRzX2hlbHAiLCJvcGVyYXRpb25fbGFiZWwiLCJjcmVhdGVfcmVzb3VyY2VzIiwiZGV0YWNoX3Jlc291cmNlcyIsInJldmlld19jYXBhY2l0eV90aXRsZSIsImFwcGx5X2NhcGFjaXR5X2NoYW5nZSIsImJhY2siLCJzeW5jaHJvbml6ZV9pbnNwZWN0b3JfaW1hZ2UiLCJyZW1vdmVfYnV0dG9uIiwicGljdHVyZV91cmwiLCJzcmMiLCJzeW5jaHJvbml6ZV9pbnNwZWN0b3JfbnVtZXJpY19yYW5nZSIsIm51bWJlcl9maWVsZCIsInJhbmdlIiwibnVtYmVyX3ZhbHVlIiwiZGVmYXVsdF9taW4iLCJkZWZhdWx0X21heCIsImhhcmRfbWluIiwiaGFyZF9tYXgiLCJyYW5nZV9taW4iLCJyYW5nZV9tYXgiLCJpc0Zpbml0ZSIsInN5bmNocm9uaXplX2luc3BlY3Rvcl9udW1iZXJfZnJvbV9yYW5nZSIsIkV2ZW50IiwiYnViYmxlcyIsImlzX2V4cGVjdGVkX2luc3BlY3Rvcl9zdWJtaXQiLCJzdWJtaXR0ZXIiLCJyZXBvcnRfaW5zcGVjdG9yX3ZhbGlkaXR5IiwicHJpY2VfZmllbGRzIiwicHJpY2Vfc3RlcHMiLCJpc192YWxpZCIsInByaWNlX2ZpZWxkIiwic3RlcCIsInJlcG9ydFZhbGlkaXR5IiwicHJpY2Vfc3RlcCIsInN1Ym1pdF9pbnNwZWN0b3IiLCJtdXRhdGlvbl9yZXF1ZXN0X3NlcXVlbmNlIiwic3VibWl0dGVkX21vZGUiLCJyZXF1ZXN0X3ZhbHVlcyIsImNvbnRyb2xfZGlzYWJsZWRfc3RhdGVzIiwic3VjY2Vzc19tZXNzYWdlIiwic3VjY2Vzc19tZXNzYWdlX2lzX2dsb2JhbCIsInN1Ym1pdHRlZF9mb3JtX2lzX2FjdGl2ZSIsInBhcnNlIiwiaW5zcGVjdG9yX2NyZWF0ZV9hY3Rpb24iLCJpbnNwZWN0b3JfdXBkYXRlX2FjdGlvbiIsImluc3BlY3Rvcl9zYXZlX2ZhaWxlZCIsImNvbnRyb2xfc3RhdGUiLCJzdWJtaXRfcmV2aWV3ZWRfaW5zcGVjdG9yIiwiaXNfbXV0YXRpb24iLCJzdWJtaXR0ZWRfcmVzb3VyY2VfaWRzIiwic3VibWl0dGVkX3RyYWNrc19zZWxlY3Rpb24iLCJidWxrX3ByZXZpZXdfYWN0aW9uIiwiYnVsa19yZXZpZXdfZmFpbGVkIiwiYnVsa19hcHBseV9hY3Rpb24iLCJidWxrX2FwcGx5X2ZhaWxlZCIsImRlbGV0ZV9hcHBseV9hY3Rpb24iLCJhY2tub3dsZWRnZWQiLCJkZWxldGVfYXBwbHlfZmFpbGVkIiwiY2FwYWNpdHlfcHJldmlld19hY3Rpb24iLCJkZXRhY2hfcmVzb3VyY2VfaWRzIiwiY2FwYWNpdHlfcmV2aWV3X2ZhaWxlZCIsImNhcGFjaXR5X2FwcGx5X2FjdGlvbiIsImNhcGFjaXR5X2Fja25vd2xlZGdlbWVudCIsImNhcGFjaXR5X2FwcGx5X2ZhaWxlZCIsImFmZmVjdGVkX2lkcyIsInNlbGVjdGVkX3Jlc291cmNlX2lkcyIsImRlbGV0ZWRfc2VsZWN0ZWRfcmVzb3VyY2UiLCJjbGVhciIsImRldGFpbCIsInNlbGVjdGlvbl9jaGFuZ2VkIiwiYXBwbHlfcGVuZGluZ19oaWdobGlnaHRzIiwiZmlyc3Rfcm93IiwiY29uc3VtZV9pbml0aWFsX2xhdW5jaF9wYXJhbWV0ZXIiLCJsYXVuY2hfaW50ZW50IiwicXVlcnlfcGFyYW1ldGVyIiwiY3VycmVudF91cmwiLCJoaXN0b3J5IiwicmVwbGFjZVN0YXRlIiwiVVJMIiwibG9jYXRpb24iLCJocmVmIiwic2VhcmNoUGFyYW1zIiwiZGVsZXRlIiwicmVzb3VyY2VfZXhwb3Nlc19hY3Rpb24iLCJhY3Rpb25faW5kZXgiLCJvcGVuX3Jlc291cmNlX2FjdGlvbl9tZW51IiwiYWN0aW9uc19jb250cm9sbGVyIiwiX3dwYmNfdWlfY2F0YWxvZ19hY3Rpb25zX2NvbnRyb2xsZXIiLCJvcGVuX2l0ZW0iLCJvcGVuX2luaXRpYWxfbGF1bmNoX2ludGVudCIsInJlc291cmNlX2luZGV4IiwicHVibGlzaGluZ19sYXVuY2hfdW5hdmFpbGFibGUiLCJoYW5kbGVfY2F0YWxvZ19yZW5kZXJlZCIsImV2ZW50X2RldGFpbCIsImhhbmRsZV9jYXRhbG9nX2NsaWNrIiwiYWN0aW9uX2RldGFpbHMiLCJwYWdlX2J1dHRvbiIsInJlc291cmNlX2FjdGlvbl9ldmVudCIsImNyZWF0ZUV2ZW50IiwiaW5pdEN1c3RvbUV2ZW50IiwiaGFuZGxlX2NhdGFsb2dfa2V5ZG93biIsInByb3RlY3RfZGVtb19yZXNvdXJjZV9pbWFnZV9jaGFuZ2UiLCJtZWRpYV9idXR0b24iLCJpbnNwZWN0b3JfZm9ybSIsIm1lc3NhZ2VfdGl0bGUiLCJpc19kZW1vIiwic3RvcFByb3BhZ2F0aW9uIiwic3RvcEltbWVkaWF0ZVByb3BhZ2F0aW9uIiwiZGVtb19pbWFnZV9jaGFuZ2VfdW5hdmFpbGFibGUiLCJkZW1vX2ltYWdlX2NoYW5nZV91bmF2YWlsYWJsZV90aXRsZSIsIm1vdW50X2Jvb2tpbmdfcmVzb3VyY2VzX2NhdGFsb2ciLCJhZGRFdmVudExpc3RlbmVyIiwiY3JlYXRlX2lubGluZV9lZGl0aW5nX3dvcmtmbG93IiwiYmFyX3NlbGVjdG9yIiwiY29udHJvbHNfcm9vdCIsImNvdW50X3NlbGVjdG9yIiwicGFnZV9lbGVtZW50IiwicHJvdGVjdGVkX3NlbGVjdG9yIiwicmV2aWV3X3NlbGVjdG9yIiwidG9nZ2xlX2xhYmVsX3NlbGVjdG9yIiwidG9nZ2xlX3NlbGVjdG9yIiwiaW5saW5lX3RvZ2dsZSIsImlubGluZV9jYW5jZWwiLCJpbmxpbmVfcmV2aWV3IiwiY3JlYXRlX2J1dHRvbiIsInVwZ3JhZGVfYnV0dG9uIiwicmVtb3ZlX2ltYWdlX2J1dHRvbiIsInNob3J0Y29kZV9idXR0b24iLCJzZWxlY3Rpb25fYWN0aW9uIiwic2hvcnRjb2RlX3Jlc291cmNlX2lkIiwic2hvcnRjb2RlX2NvbW1hbmQiLCJzaG9ydGNvZGVfdmFsdWUiLCJyZXZpZXdlZF90YXJnZXRfY2FwYWNpdHkiLCJyZXZpZXdlZF9kZXRhY2hfaWRzIiwicmV2aWV3ZWRfZGVjcmVhc2VfYWN0aW9uIiwiaW1hZ2VfZmllbGQiLCJtaW5pbXVtIiwibWF4aW11bSIsInJlcXVlc3RlZF9jYXBhY2l0eSIsInJvdW5kIiwiaGFuZGxlX2NoYW5nZSIsImRldGFjaF9pZCIsImNhcGFjaXR5X2RlbGV0ZV9idXR0b24iLCJvbiIsImNsb3NpbmdfbW9kZSIsInNlbGVjdGVkX3Jlc291cmNlX2lkIiwic2VsZWN0ZWRfc2hvcnRjb2RlIiwiZGV0YWlsc19jb2RlIiwicmV0dXJuVmFsdWUiLCJyZWFkeVN0YXRlIl0sInNvdXJjZXMiOlsiaW5jbHVkZXMvcGFnZS1jYXRhbG9nLWJvb2tpbmctcmVzb3VyY2VzL19zcmMvYm9va2luZ19yZXNvdXJjZXNfY2F0YWxvZy5qcyJdLCJzb3VyY2VzQ29udGVudCI6WyIvKipcbiAqIFJlbmRlciBub3JtYWxpemVkIEJvb2tpbmcgUmVzb3VyY2UgRFRPcyB0aHJvdWdoIGlkZW50aWZpYWJsZSBXUCB0ZW1wbGF0ZXMuXG4gKlxuICogQHNpbmNlIDExLjYuMFxuICovXG4oIGZ1bmN0aW9uICggd2luZG93LCBkb2N1bWVudCApIHtcblx0J3VzZSBzdHJpY3QnO1xuXG5cdHZhciBjYXRhbG9nX2NvbnRyb2xsZXIgPSBudWxsO1xuXHR2YXIgaW5saW5lX3dvcmtmbG93X2NvbnRyb2xsZXIgPSBudWxsO1xuXHR2YXIgaW5saW5lX3Jldmlld193b3JrZmxvd19jb250cm9sbGVyID0gbnVsbDtcblx0dmFyIGRlbGV0ZV9yZXZpZXdfd29ya2Zsb3dfY29udHJvbGxlciA9IG51bGw7XG5cdHZhciBpbnNwZWN0b3Jfd29ya2Zsb3dfY29udHJvbGxlciA9IG51bGw7XG5cdHZhciBjYXRhbG9nX3Jlc3BvbnNlID0gbnVsbDtcblx0dmFyIGRldGFpbHNfYWJvcnRfY29udHJvbGxlciA9IG51bGw7XG5cdHZhciBkZXRhaWxzX3JlcXVlc3Rfc2VxdWVuY2UgPSAwO1xuXHR2YXIgZGV0YWlsc19yZXNvdXJjZV9pZCA9IDA7XG5cdHZhciBkZXRhaWxzX3RvZ2dsZV9idXR0b24gPSBudWxsO1xuXHR2YXIgcGVuZGluZ19mb2N1c19kaXJlY3Rpb24gPSAnJztcblx0dmFyIGluc3BlY3Rvcl9kaXJ0eSA9IGZhbHNlO1xuXHR2YXIgaW5zcGVjdG9yX2ZvY3VzX3RhcmdldCA9IG51bGw7XG5cdHZhciBpbnNwZWN0b3JfbW9kZSA9ICcnO1xuXHR2YXIgaW5zcGVjdG9yX211dGF0aW9uX2luX3Byb2dyZXNzID0gZmFsc2U7XG5cdHZhciBpbnNwZWN0b3JfbXV0YXRpb25fcmVxdWVzdF9zZXF1ZW5jZSA9IDA7XG5cdHZhciBpbnNwZWN0b3Jfb3JpZ2luYWxfZmllbGRzID0gJyc7XG5cdHZhciBpbnNwZWN0b3JfcmVxdWVzdF9zZXF1ZW5jZSA9IDA7XG5cdHZhciBpbnNwZWN0b3JfcmVzb3VyY2VfaWQgPSAwO1xuXHR2YXIgaW5zcGVjdG9yX3Jlc291cmNlX2lkcyA9IFtdO1xuXHR2YXIgaW5zcGVjdG9yX2J1bGtfb3BlcmF0aW9ucyA9IHt9O1xuXHR2YXIgaW5zcGVjdG9yX3Jldmlld190b2tlbiA9ICcnO1xuXHR2YXIgaW5zcGVjdG9yX3NlbGVjdGlvbl9zdGFsZSA9IGZhbHNlO1xuXHR2YXIgaW5zcGVjdG9yX3RyYWNrc19zZWxlY3Rpb24gPSBmYWxzZTtcblx0dmFyIGluc3BlY3Rvcl9jYXBhY2l0eV9jb250ZXh0ID0gbnVsbDtcblx0dmFyIGluc3BlY3Rvcl9jYXBhY2l0eV9kZXRhY2hfaWRzID0gW107XG5cdHZhciBpbnNwZWN0b3JfY2FwYWNpdHlfZGVjcmVhc2VfYWN0aW9uID0gJ2RldGFjaCc7XG5cdHZhciBpbnNwZWN0b3JfY2FwYWNpdHlfdGFyZ2V0ID0gMDtcblx0dmFyIHBlbmRpbmdfaGlnaGxpZ2h0X2lkcyA9IFtdO1xuXHR2YXIgbGF1bmNoX2ludGVudF9oYW5kbGVkID0gZmFsc2U7XG5cdHZhciBpbmxpbmVfc3RhdGUgPSB7XG5cdFx0YWN0aXZlOiBmYWxzZSxcblx0XHRjaGFuZ2VkX3Jvd3M6IFtdLFxuXHRcdGxvYWRpbmc6IGZhbHNlLFxuXHRcdHJlcXVlc3Rfc2VxdWVuY2U6IDAsXG5cdFx0cmV2aWV3X3Rva2VuOiAnJ1xuXHR9O1xuXG5cdC8qKlxuXHQgKiBOb3JtYWxpemUgYSBsb2NhbGl6ZWQgV29yZFByZXNzIGZsYWcgdG8gYSBzdHJpY3QgYm9vbGVhbi5cblx0ICpcblx0ICogQHBhcmFtIHsqfSBmbGFnX3ZhbHVlIExvY2FsaXplZCBmbGFnIHZhbHVlLlxuXHQgKiBAcmV0dXJuIHtib29sZWFufSBUcnVlIG9ubHkgZm9yIGFuIGV4cGxpY2l0bHkgZW5hYmxlZCBmbGFnLlxuXHQgKi9cblx0ZnVuY3Rpb24gaXNfdHJ1ZV9mbGFnKCBmbGFnX3ZhbHVlICkge1xuXHRcdHJldHVybiB0cnVlID09PSBmbGFnX3ZhbHVlIHx8IDEgPT09IGZsYWdfdmFsdWUgfHwgJzEnID09PSBmbGFnX3ZhbHVlIHx8ICd0cnVlJyA9PT0gU3RyaW5nKCBmbGFnX3ZhbHVlICkudG9Mb3dlckNhc2UoKTtcblx0fVxuXG5cdC8qKlxuXHQgKiBGb3JtYXQgYSBsb2NhbGl6ZWQgcG9zaXRpb25hbC1wbGFjZWhvbGRlciBzdHJpbmcuXG5cdCAqXG5cdCAqIEBwYXJhbSB7c3RyaW5nfSB0ZW1wbGF0ZSBMb2NhbGl6ZWQgc3RyaW5nIGNvbnRhaW5pbmcgYCUxJHNgIHBsYWNlaG9sZGVycy5cblx0ICogQHBhcmFtIHtBcnJheTwqPn0gdmFsdWVzIFNjYWxhciByZXBsYWNlbWVudCB2YWx1ZXMuXG5cdCAqIEByZXR1cm4ge3N0cmluZ30gRm9ybWF0dGVkIHBsYWluIHRleHQuXG5cdCAqL1xuXHRmdW5jdGlvbiBmb3JtYXRfbWVzc2FnZSggdGVtcGxhdGUsIHZhbHVlcyApIHtcblx0XHR2YXIgbWVzc2FnZSA9IFN0cmluZyggdGVtcGxhdGUgfHwgJycgKTtcblxuXHRcdHZhbHVlcy5mb3JFYWNoKCBmdW5jdGlvbiAoIHJlcGxhY2VtZW50LCByZXBsYWNlbWVudF9pbmRleCApIHtcblx0XHRcdHZhciBwbGFjZWhvbGRlciA9IG5ldyBSZWdFeHAoICclJyArICggcmVwbGFjZW1lbnRfaW5kZXggKyAxICkgKyAnXFxcXCRzJywgJ2cnICk7XG5cdFx0XHRtZXNzYWdlID0gbWVzc2FnZS5yZXBsYWNlKCBwbGFjZWhvbGRlciwgU3RyaW5nKCByZXBsYWNlbWVudCApICk7XG5cdFx0fSApO1xuXG5cdFx0cmV0dXJuIG1lc3NhZ2U7XG5cdH1cblxuXHQvKipcblx0ICogUmV0dXJuIHRoZSBzaGFyZWQgc2lnbmVkLXJldmlldyBwcmVzZW50YXRpb24gY29udHJvbGxlci5cblx0ICpcblx0ICogQHJldHVybiB7T2JqZWN0fGZhbHNlfSBTaGFyZWQgcmV2aWV3IGNvbnRyb2xsZXIgb3IgZmFsc2Ugd2hlbiB1bmF2YWlsYWJsZS5cblx0ICovXG5cdGZ1bmN0aW9uIGdldF9pbmxpbmVfcmV2aWV3X3dvcmtmbG93KCkge1xuXHRcdGlmICggaW5saW5lX3Jldmlld193b3JrZmxvd19jb250cm9sbGVyICkge1xuXHRcdFx0cmV0dXJuIGlubGluZV9yZXZpZXdfd29ya2Zsb3dfY29udHJvbGxlcjtcblx0XHR9XG5cdFx0aWYgKCAhIHdpbmRvdy53cGJjX3VpX2NhdGFsb2cgfHwgJ2Z1bmN0aW9uJyAhPT0gdHlwZW9mIHdpbmRvdy53cGJjX3VpX2NhdGFsb2cuY3JlYXRlX2lubGluZV9yZXZpZXdfd29ya2Zsb3cgKSB7XG5cdFx0XHRyZXR1cm4gZmFsc2U7XG5cdFx0fVxuXHRcdGlubGluZV9yZXZpZXdfd29ya2Zsb3dfY29udHJvbGxlciA9IHdpbmRvdy53cGJjX3VpX2NhdGFsb2cuY3JlYXRlX2lubGluZV9yZXZpZXdfd29ya2Zsb3coIHtcblx0XHRcdGFwcGx5X3NlbGVjdG9yOiAnW2RhdGEtd3BiYy11aS1jYXRhbG9nLWluc3BlY3Rvci1zYXZlXScsXG5cdFx0XHRjYW5jZWxfc2VsZWN0b3I6ICdbZGF0YS13cGJjLXVpLWNhdGFsb2ctaW5zcGVjdG9yLWNhbmNlbF0nLFxuXHRcdFx0cm9vdDogZG9jdW1lbnRcblx0XHR9ICk7XG5cblx0XHRyZXR1cm4gaW5saW5lX3Jldmlld193b3JrZmxvd19jb250cm9sbGVyO1xuXHR9XG5cblx0LyoqXG5cdCAqIFJldHVybiB0aGUgc2hhcmVkIHBlcm1hbmVudC1kZWxldGlvbiBwcmVzZW50YXRpb24gY29udHJvbGxlci5cblx0ICpcblx0ICogQHJldHVybiB7T2JqZWN0fGZhbHNlfSBTaGFyZWQgZGVsZXRpb24gY29udHJvbGxlciBvciBmYWxzZSB3aGVuIHVuYXZhaWxhYmxlLlxuXHQgKi9cblx0ZnVuY3Rpb24gZ2V0X2RlbGV0ZV9yZXZpZXdfd29ya2Zsb3coKSB7XG5cdFx0aWYgKCBkZWxldGVfcmV2aWV3X3dvcmtmbG93X2NvbnRyb2xsZXIgKSB7XG5cdFx0XHRyZXR1cm4gZGVsZXRlX3Jldmlld193b3JrZmxvd19jb250cm9sbGVyO1xuXHRcdH1cblx0XHRpZiAoICEgd2luZG93LndwYmNfdWlfY2F0YWxvZyB8fCAnZnVuY3Rpb24nICE9PSB0eXBlb2Ygd2luZG93LndwYmNfdWlfY2F0YWxvZy5jcmVhdGVfZGVsZXRlX3Jldmlld193b3JrZmxvdyApIHtcblx0XHRcdHJldHVybiBmYWxzZTtcblx0XHR9XG5cdFx0ZGVsZXRlX3Jldmlld193b3JrZmxvd19jb250cm9sbGVyID0gd2luZG93LndwYmNfdWlfY2F0YWxvZy5jcmVhdGVfZGVsZXRlX3Jldmlld193b3JrZmxvdygge1xuXHRcdFx0YWNrbm93bGVkZ2VtZW50X3NlbGVjdG9yOiAnW2RhdGEtd3BiYy1jYXRhbG9nLXJlc291cmNlLWRlbGV0ZS1hY2tub3dsZWRnZW1lbnRdJyxcblx0XHRcdGFwcGx5X3NlbGVjdG9yOiAnW2RhdGEtd3BiYy11aS1jYXRhbG9nLWluc3BlY3Rvci1zYXZlXScsXG5cdFx0XHRjYW5jZWxfc2VsZWN0b3I6ICdbZGF0YS13cGJjLXVpLWNhdGFsb2ctaW5zcGVjdG9yLWNhbmNlbF0nLFxuXHRcdFx0cm9vdDogZG9jdW1lbnRcblx0XHR9ICk7XG5cblx0XHRyZXR1cm4gZGVsZXRlX3Jldmlld193b3JrZmxvd19jb250cm9sbGVyO1xuXHR9XG5cblx0LyoqXG5cdCAqIFJldHVybiB0aGUgbG9jYWxpemVkIGNvdW50IHNob3duIGluIGEgY29sbGFwc2VkIGNoaWxkLWdyb3VwIHN1bW1hcnkuXG5cdCAqXG5cdCAqIFRoZSBEVE8gbGFiZWwgcmVtYWlucyBhdXRob3JpdGF0aXZlIGJlY2F1c2UgUEhQIGFwcGxpZXMgV29yZFByZXNzIGxvY2FsZVxuXHQgKiBwbHVyYWwgcnVsZXMuIFRoZSBudW1lcmljIGZhbGxiYWNrIGNvbnN1bWVzIHRoZSBzaGFyZWQgZGlyZWN0LWNoaWxkIGNvdW50XG5cdCAqIHdoZW4gYW4gb2xkZXIgb3IgY3VzdG9tIGRvbWFpbiBEVE8gZG9lcyBub3QgaW5jbHVkZSB0aGUgcHJlcGFyZWQgbGFiZWwuXG5cdCAqXG5cdCAqIEBwYXJhbSB7T2JqZWN0fSBwYXJlbnRfcmVzb3VyY2UgUGFyZW50IEJvb2tpbmcgUmVzb3VyY2UgRFRPLlxuXHQgKiBAcGFyYW0ge09iamVjdH0gaTE4biAgICAgICAgICAgIExvY2FsaXplZCBjYXRhbG9nIHN0cmluZ3MuXG5cdCAqIEByZXR1cm4ge3N0cmluZ30gTG9jYWxpemVkIGNoaWxkLWNvdW50IGxhYmVsLlxuXHQgKi9cblx0ZnVuY3Rpb24gZ2V0X2NoaWxkcmVuX3N1bW1hcnlfbGFiZWwoIHBhcmVudF9yZXNvdXJjZSwgaTE4biApIHtcblx0XHR2YXIgaGllcmFyY2h5ID0gcGFyZW50X3Jlc291cmNlICYmIHBhcmVudF9yZXNvdXJjZS5oaWVyYXJjaHkgPyBwYXJlbnRfcmVzb3VyY2UuaGllcmFyY2h5IDoge307XG5cdFx0dmFyIHNlcnZlcl9sYWJlbCA9IFN0cmluZyggaGllcmFyY2h5LmNoaWxkcmVuX2xhYmVsIHx8ICcnICkudHJpbSgpO1xuXHRcdHZhciBjaGlsZF9jb3VudCA9IE1hdGgubWF4KCAwLCBOdW1iZXIoIGhpZXJhcmNoeS5yZW5kZXJlZF9jaGlsZHJlbl9jb3VudCApIHx8IDAgKTtcblx0XHR2YXIgbGFiZWxfdGVtcGxhdGU7XG5cblx0XHRpZiAoIHNlcnZlcl9sYWJlbCApIHtcblx0XHRcdHJldHVybiBzZXJ2ZXJfbGFiZWw7XG5cdFx0fVxuXG5cdFx0bGFiZWxfdGVtcGxhdGUgPSAxID09PSBjaGlsZF9jb3VudFxuXHRcdFx0PyBpMThuLmNoaWxkX2NvdW50X3Npbmd1bGFyIHx8ICclMSRzIGNoaWxkIHJlc291cmNlJ1xuXHRcdFx0OiBpMThuLmNoaWxkX2NvdW50X3BsdXJhbCB8fCAnJTEkcyBjaGlsZCByZXNvdXJjZXMnO1xuXG5cdFx0cmV0dXJuIGZvcm1hdF9tZXNzYWdlKCBsYWJlbF90ZW1wbGF0ZSwgWyBjaGlsZF9jb3VudCBdICk7XG5cdH1cblxuXHQvKipcblx0ICogUmVuZGVyIG9uZSBhbGxvdy1saXN0ZWQgUmVzb3VyY2UgcHJlc2VudGF0aW9uIHRlbXBsYXRlLlxuXHQgKlxuXHQgKiBAcGFyYW0ge09iamVjdH0gY29uZmlnICAgICAgICBSZWdpc3RlcmVkIGNhdGFsb2cgY29uZmlndXJhdGlvbi5cblx0ICogQHBhcmFtIHtzdHJpbmd9IHRlbXBsYXRlX3JvbGUgUmVnaXN0ZXJlZCB0ZW1wbGF0ZSByb2xlLlxuXHQgKiBAcGFyYW0ge09iamVjdH0gdGVtcGxhdGVfZGF0YSBOb3JtYWxpemVkIERUTyBvciBwcmVzZW50YXRpb24gZGF0YS5cblx0ICogQHJldHVybiB7c3RyaW5nfSBFc2NhcGVkIHRlbXBsYXRlIEhUTUwgb3IgYW4gZW1wdHkgc3RyaW5nLlxuXHQgKi9cblx0ZnVuY3Rpb24gcmVuZGVyX2NvbXBvbmVudCggY29uZmlnLCB0ZW1wbGF0ZV9yb2xlLCB0ZW1wbGF0ZV9kYXRhICkge1xuXHRcdHZhciBjb21wb25lbnRfdGVtcGxhdGUgPSB3aW5kb3cud3BiY191aV9jYXRhbG9nLmxvYWRfdGVtcGxhdGUoIGNvbmZpZywgdGVtcGxhdGVfcm9sZSApO1xuXG5cdFx0aWYgKCAhIGNvbXBvbmVudF90ZW1wbGF0ZSApIHtcblx0XHRcdHJldHVybiAnJztcblx0XHR9XG5cblx0XHR0cnkge1xuXHRcdFx0cmV0dXJuIGNvbXBvbmVudF90ZW1wbGF0ZSggdGVtcGxhdGVfZGF0YSB8fCB7fSApO1xuXHRcdH0gY2F0Y2ggKCBlcnJvciApIHtcblx0XHRcdHJldHVybiAnJztcblx0XHR9XG5cdH1cblxuXHQvKipcblx0ICogUmV0dXJuIGNvbXBsZXRlIGNvbHVtbiBwcmVzZW50YXRpb24gcmVjb3JkcyBpbiB0aGUgYWN0aXZlIG9yZGVyLlxuXHQgKlxuXHQgKiBAcGFyYW0ge09iamVjdH0gY29uZmlnICAgICAgICAgUmVnaXN0ZXJlZCBjYXRhbG9nIGNvbmZpZ3VyYXRpb24uXG5cdCAqIEBwYXJhbSB7T2JqZWN0fSBkaXNwbGF5X3N0YXRlICBOb3JtYWxpemVkIGRpc3BsYXkgcmVxdWVzdCBvciByZXNwb25zZS5cblx0ICogQHBhcmFtIHtib29sZWFufSB2aXNpYmxlX29ubHkgIFdoZXRoZXIgaGlkZGVuIGNvbHVtbnMgbXVzdCBiZSBvbWl0dGVkLlxuXHQgKiBAcGFyYW0ge09iamVjdH0gc29ydGluZ19zdGF0ZSAgTm9ybWFsaXplZCBzb3J0aW5nIHJlc3BvbnNlLlxuXHQgKiBAcmV0dXJuIHtBcnJheTxPYmplY3Q+fSBPcmRlcmVkIHByZXNlbnRhdGlvbi1vbmx5IGNvbHVtbiByZWNvcmRzLlxuXHQgKi9cblx0ZnVuY3Rpb24gZ2V0X2NvbHVtbnMoIGNvbmZpZywgZGlzcGxheV9zdGF0ZSwgdmlzaWJsZV9vbmx5LCBzb3J0aW5nX3N0YXRlICkge1xuXHRcdHZhciBjb2x1bW5fY29uZmlnID0gY29uZmlnLmNvbHVtbnMgfHwge307XG5cdFx0dmFyIGRlZmluaXRpb25zID0gY29sdW1uX2NvbmZpZy5kZWZpbml0aW9ucyB8fCB7fTtcblx0XHR2YXIgZGVmYXVsdF9vcmRlciA9IEFycmF5LmlzQXJyYXkoIGNvbHVtbl9jb25maWcuZGVmYXVsdF9vcmRlciApID8gY29sdW1uX2NvbmZpZy5kZWZhdWx0X29yZGVyIDogW107XG5cdFx0dmFyIG9yZGVyID0gZGlzcGxheV9zdGF0ZSAmJiBBcnJheS5pc0FycmF5KCBkaXNwbGF5X3N0YXRlLmNvbHVtbl9vcmRlciApID8gZGlzcGxheV9zdGF0ZS5jb2x1bW5fb3JkZXIuc2xpY2UoKSA6IGRlZmF1bHRfb3JkZXIuc2xpY2UoKTtcblx0XHR2YXIgdmlzaWJsZV9jb2x1bW5zID0gZGlzcGxheV9zdGF0ZSAmJiBBcnJheS5pc0FycmF5KCBkaXNwbGF5X3N0YXRlLnZpc2libGVfY29sdW1ucyApID8gZGlzcGxheV9zdGF0ZS52aXNpYmxlX2NvbHVtbnMgOiBjb2x1bW5fY29uZmlnLmRlZmF1bHRfdmlzaWJsZSB8fCBbXTtcblxuXHRcdGRlZmF1bHRfb3JkZXIuZm9yRWFjaCggZnVuY3Rpb24gKCBjb2x1bW5faWQgKSB7XG5cdFx0XHRpZiAoIC0xID09PSBvcmRlci5pbmRleE9mKCBjb2x1bW5faWQgKSApIHtcblx0XHRcdFx0b3JkZXIucHVzaCggY29sdW1uX2lkICk7XG5cdFx0XHR9XG5cdFx0fSApO1xuXG5cdFx0cmV0dXJuIG9yZGVyLmZpbHRlciggZnVuY3Rpb24gKCBjb2x1bW5faWQgKSB7XG5cdFx0XHRyZXR1cm4gZGVmaW5pdGlvbnNbIGNvbHVtbl9pZCBdICYmICggISB2aXNpYmxlX29ubHkgfHwgLTEgIT09IHZpc2libGVfY29sdW1ucy5pbmRleE9mKCBjb2x1bW5faWQgKSApO1xuXHRcdH0gKS5tYXAoIGZ1bmN0aW9uICggY29sdW1uX2lkLCBjb2x1bW5faW5kZXggKSB7XG5cdFx0XHR2YXIgZGVmaW5pdGlvbiA9IGRlZmluaXRpb25zWyBjb2x1bW5faWQgXTtcblx0XHRcdHZhciBpc19zb3J0ZWQgPSAhISBkZWZpbml0aW9uLnNvcnRfa2V5ICYmIHNvcnRpbmdfc3RhdGUgJiYgZGVmaW5pdGlvbi5zb3J0X2tleSA9PT0gc29ydGluZ19zdGF0ZS5zb3J0X2J5O1xuXHRcdFx0cmV0dXJuIHtcblx0XHRcdFx0YXJpYV9zb3J0OiBpc19zb3J0ZWQgPyAoICdkZXNjJyA9PT0gc29ydGluZ19zdGF0ZS5zb3J0X29yZGVyID8gJ2Rlc2NlbmRpbmcnIDogJ2FzY2VuZGluZycgKSA6ICdub25lJyxcblx0XHRcdFx0Y2xhc3NfbmFtZTogZGVmaW5pdGlvbi5jbGFzcyB8fCAnY29sdW1uLScgKyBjb2x1bW5faWQsXG5cdFx0XHRcdGRlZmF1bHRfaW5kZXg6IGRlZmF1bHRfb3JkZXIuaW5kZXhPZiggY29sdW1uX2lkICksXG5cdFx0XHRcdGlkOiBjb2x1bW5faWQsXG5cdFx0XHRcdGlzX3NvcnRlZDogaXNfc29ydGVkLFxuXHRcdFx0XHRsYWJlbDogZGVmaW5pdGlvbi5sYWJlbCB8fCBjb2x1bW5faWQsXG5cdFx0XHRcdG1vdmVfbGFiZWw6IGZvcm1hdF9tZXNzYWdlKCBjb25maWcuaTE4bi5tb3ZlX2NvbHVtbiB8fCAnJywgWyBkZWZpbml0aW9uLmxhYmVsIHx8IGNvbHVtbl9pZCBdICksXG5cdFx0XHRcdHJlb3JkZXJhYmxlOiBmYWxzZSAhPT0gZGVmaW5pdGlvbi5yZW9yZGVyYWJsZSxcblx0XHRcdFx0cmVxdWlyZWQ6ICEhIGRlZmluaXRpb24ucmVxdWlyZWQsXG5cdFx0XHRcdHNvcnRfaWNvbjogaXNfc29ydGVkID8gKCAnZGVzYycgPT09IHNvcnRpbmdfc3RhdGUuc29ydF9vcmRlciA/ICd3cGJjLWJpLWFycm93LWRvd24nIDogJ3dwYmMtYmktYXJyb3ctdXAnICkgOiAnd3BiY19pY25faW1wb3J0X2V4cG9ydCcsXG5cdFx0XHRcdHNvcnRfa2V5OiBkZWZpbml0aW9uLnNvcnRfa2V5IHx8ICcnLFxuXHRcdFx0XHR2aXNpYmxlOiAtMSAhPT0gdmlzaWJsZV9jb2x1bW5zLmluZGV4T2YoIGNvbHVtbl9pZCApXG5cdFx0XHR9O1xuXHRcdH0gKTtcblx0fVxuXG5cdC8qKlxuXHQgKiBEZXRlcm1pbmUgd2hldGhlciBkaXNwbGF5IHZhbHVlcyBtYXRjaCB0aGUgT3ZlcnZpZXcgZGVmYXVsdHMuXG5cdCAqXG5cdCAqIEBwYXJhbSB7T2JqZWN0fSBjb25maWcgICAgICAgIFJlZ2lzdGVyZWQgY2F0YWxvZyBjb25maWd1cmF0aW9uLlxuXHQgKiBAcGFyYW0ge09iamVjdH0gZGlzcGxheV9zdGF0ZSBDdXJyZW50IG5vcm1hbGl6ZWQgZGlzcGxheSBzdGF0ZS5cblx0ICogQHJldHVybiB7c3RyaW5nfSBvdmVydmlldyBvciBjdXN0b20uXG5cdCAqL1xuXHRmdW5jdGlvbiBnZXRfYWN0aXZlX3ZpZXcoIGNvbmZpZywgZGlzcGxheV9zdGF0ZSApIHtcblx0XHR2YXIgdmlld19kZWZpbml0aW9ucyA9IGNvbmZpZy52aWV3cyAmJiBjb25maWcudmlld3MuZGVmaW5pdGlvbnMgPyBjb25maWcudmlld3MuZGVmaW5pdGlvbnMgOiB7fTtcblx0XHR2YXIgY3VycmVudF92aXNpYmxlID0gZGlzcGxheV9zdGF0ZSAmJiBBcnJheS5pc0FycmF5KCBkaXNwbGF5X3N0YXRlLnZpc2libGVfY29sdW1ucyApID8gZGlzcGxheV9zdGF0ZS52aXNpYmxlX2NvbHVtbnMgOiBbXTtcblx0XHR2YXIgbWF0Y2hpbmdfdmlldyA9ICcnO1xuXG5cdFx0T2JqZWN0LmtleXMoIHZpZXdfZGVmaW5pdGlvbnMgKS5zb21lKCBmdW5jdGlvbiAoIHZpZXdfaWQgKSB7XG5cdFx0XHR2YXIgdmlld19maWVsZHMgPSBBcnJheS5pc0FycmF5KCB2aWV3X2RlZmluaXRpb25zWyB2aWV3X2lkIF0uZmllbGRzICkgPyB2aWV3X2RlZmluaXRpb25zWyB2aWV3X2lkIF0uZmllbGRzIDogW107XG5cdFx0XHRpZiAoIEpTT04uc3RyaW5naWZ5KCBjdXJyZW50X3Zpc2libGUgKSA9PT0gSlNPTi5zdHJpbmdpZnkoIHZpZXdfZmllbGRzICkgKSB7XG5cdFx0XHRcdG1hdGNoaW5nX3ZpZXcgPSB2aWV3X2lkO1xuXHRcdFx0XHRyZXR1cm4gdHJ1ZTtcblx0XHRcdH1cblx0XHRcdHJldHVybiBmYWxzZTtcblx0XHR9ICk7XG5cblx0XHRyZXR1cm4gbWF0Y2hpbmdfdmlldyB8fCAnY3VzdG9tJztcblx0fVxuXG5cdC8qKlxuXHQgKiBSZXR1cm4gb3JkZXJlZCB2aWV3IHByZXNldHMgZGVjbGFyZWQgYnkgdGhlIGluZGVwZW5kZW50IFBIUCBjb25maWd1cmF0aW9uLlxuXHQgKlxuXHQgKiBAcGFyYW0ge09iamVjdH0gY29uZmlnIFJlZ2lzdGVyZWQgY2F0YWxvZyBjb25maWd1cmF0aW9uLlxuXHQgKiBAcmV0dXJuIHtBcnJheTxPYmplY3Q+fSBCcm93c2VyLXNhZmUgdmlldyBkZWZpbml0aW9ucy5cblx0ICovXG5cdGZ1bmN0aW9uIGdldF92aWV3X2RlZmluaXRpb25zKCBjb25maWcgKSB7XG5cdFx0dmFyIGRlZmluaXRpb25zID0gY29uZmlnLnZpZXdzICYmIGNvbmZpZy52aWV3cy5kZWZpbml0aW9ucyA/IGNvbmZpZy52aWV3cy5kZWZpbml0aW9ucyA6IHt9O1xuXG5cdFx0cmV0dXJuIE9iamVjdC5rZXlzKCBkZWZpbml0aW9ucyApLm1hcCggZnVuY3Rpb24gKCB2aWV3X2lkICkge1xuXHRcdFx0cmV0dXJuIGRlZmluaXRpb25zWyB2aWV3X2lkIF07XG5cdFx0fSApO1xuXHR9XG5cblx0LyoqXG5cdCAqIFJldHVybiB0aGUgYWxsb3ctbGlzdGVkIHByZXNlbnRhdGlvbiBwYWNrcyBkZWNsYXJlZCBieSBQSFAuXG5cdCAqXG5cdCAqIExhYmVscyByZW1haW4gZG9tYWluLW93bmVkIHdoaWxlIHRoZSBzaGFyZWQgY29udHJvbGxlciB2YWxpZGF0ZXMgYW5kXG5cdCAqIHBlcnNpc3RzIG9ubHkgcGFjayBpZGVudGlmaWVycyByZWdpc3RlcmVkIGluIHRoZSBjYXRhbG9nIGNvbmZpZ3VyYXRpb24uXG5cdCAqXG5cdCAqIEBwYXJhbSB7T2JqZWN0fSBjb25maWcgUmVnaXN0ZXJlZCBjYXRhbG9nIGNvbmZpZ3VyYXRpb24uXG5cdCAqIEByZXR1cm4ge0FycmF5PE9iamVjdD59IE9yZGVyZWQgYnJvd3Nlci1zYWZlIHBhY2sgb3B0aW9ucy5cblx0ICovXG5cdGZ1bmN0aW9uIGdldF90ZW1wbGF0ZV9wYWNrX2RlZmluaXRpb25zKCBjb25maWcgKSB7XG5cdFx0dmFyIGxhYmVscyA9IHtcblx0XHRcdGNhcmRzOiBjb25maWcuaTE4bi5sYXlvdXRfY2FyZHMgfHwgJycsXG5cdFx0XHRjb21wYWN0OiBjb25maWcuaTE4bi5sYXlvdXRfY29tcGFjdCB8fCAnJyxcblx0XHRcdHRhYmxlOiBjb25maWcuaTE4bi5sYXlvdXRfdGFibGUgfHwgJydcblx0XHR9O1xuXHRcdHZhciB0ZW1wbGF0ZV9wYWNrcyA9IGNvbmZpZy50ZW1wbGF0ZV9wYWNrcyB8fCB7fTtcblxuXHRcdHJldHVybiBPYmplY3Qua2V5cyggdGVtcGxhdGVfcGFja3MgKS5tYXAoIGZ1bmN0aW9uICggdGVtcGxhdGVfcGFja19pZCApIHtcblx0XHRcdHJldHVybiB7XG5cdFx0XHRcdGlkOiB0ZW1wbGF0ZV9wYWNrX2lkLFxuXHRcdFx0XHRsYWJlbDogbGFiZWxzWyB0ZW1wbGF0ZV9wYWNrX2lkIF0gfHwgdGVtcGxhdGVfcGFja19pZFxuXHRcdFx0fTtcblx0XHR9ICk7XG5cdH1cblxuXHQvKipcblx0ICogUmVuZGVyIHRoZSBlc3RhYmxpc2hlZCBSZXNvdXJjZSBmaWx0ZXJzIGFib3ZlIHRoZSBib3JkZXJlZCBsaXN0aW5nLlxuXHQgKlxuXHQgKiBGcmVlIGVkaXRpb25zIGludGVudGlvbmFsbHkgcmVuZGVyIG5vIGZpbHRlciBmb3JtLCBtYXRjaGluZyB0aGUgZXhpc3Rpbmdcblx0ICogcGFnZSB3aGVyZSBhIHNpbmdsZSBkZWZhdWx0IFJlc291cmNlIG1ha2VzIHRoZXNlIGNvbnRyb2xzIHVubmVjZXNzYXJ5LlxuXHQgKlxuXHQgKiBAcGFyYW0ge09iamVjdH0gY29uZmlnIFJlZ2lzdGVyZWQgY2F0YWxvZyBjb25maWd1cmF0aW9uLlxuXHQgKiBAcmV0dXJuIHtib29sZWFufSBUcnVlIHdoZW4gdGhlIGZpbHRlcnMgdGFyZ2V0IHdhcyBmb3VuZC5cblx0ICovXG5cdGZ1bmN0aW9uIHJlbmRlcl9ib29raW5nX3Jlc291cmNlc19maWx0ZXJzKCBjb25maWcgKSB7XG5cdFx0dmFyIGluaXRpYWxfcmVxdWVzdCA9IGNvbmZpZy5pbml0aWFsX3JlcXVlc3QgfHwge307XG5cdFx0dmFyIG1vdW50X2VsZW1lbnQgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCggY29uZmlnLm1vdW50X2lkICk7XG5cdFx0dmFyIGZpbHRlcnNfdGFyZ2V0ID0gbW91bnRfZWxlbWVudCA/IG1vdW50X2VsZW1lbnQucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtYm9va2luZy1yZXNvdXJjZXMtZmlsdGVyc10nICkgOiBudWxsO1xuXG5cdFx0aWYgKCAhIGZpbHRlcnNfdGFyZ2V0ICkge1xuXHRcdFx0cmV0dXJuIGZhbHNlO1xuXHRcdH1cblx0XHRmaWx0ZXJzX3RhcmdldC5pbm5lckhUTUwgPSByZW5kZXJfY29tcG9uZW50KCBjb25maWcsICdmaWx0ZXJzJywge1xuXHRcdFx0aTE4bjogY29uZmlnLmkxOG4gfHwge30sXG5cdFx0XHRyZXNvdXJjZV90eXBlOiBpbml0aWFsX3JlcXVlc3QucmVzb3VyY2VfdHlwZSB8fCAnYWxsJyxcblx0XHRcdHNlYXJjaDogaW5pdGlhbF9yZXF1ZXN0LnNlYXJjaCB8fCAnJyxcblx0XHRcdHNob3dfZmlsdGVyczogISEgKCBjb25maWcuZmVhdHVyZXMgJiYgY29uZmlnLmZlYXR1cmVzLnJlc291cmNlX2ZpbHRlcnMgKSxcblx0XHRcdHNob3dfcmVzb3VyY2VfdHlwZV9maWx0ZXI6ICEhICggY29uZmlnLmZlYXR1cmVzICYmIGNvbmZpZy5mZWF0dXJlcy5yZXNvdXJjZV90eXBlX2ZpbHRlciApXG5cdFx0fSApO1xuXG5cdFx0cmV0dXJuIHRydWU7XG5cdH1cblxuXHQvKipcblx0ICogUmVuZGVyIHBlcnNpc3RlbnQgZmlsdGVycyBhbmQgZGlzcGxheSBjb250cm9scyBvdXRzaWRlIHJlc3BvbnNlIGNvbnRlbnQuXG5cdCAqXG5cdCAqIEBwYXJhbSB7T2JqZWN0fSBjb25maWcgUmVnaXN0ZXJlZCBjYXRhbG9nIGNvbmZpZ3VyYXRpb24uXG5cdCAqIEByZXR1cm4ge2Jvb2xlYW59IFRydWUgd2hlbiB0aGUgdG9vbGJhciB0YXJnZXQgd2FzIHBvcHVsYXRlZC5cblx0ICovXG5cdGZ1bmN0aW9uIHJlbmRlcl9ib29raW5nX3Jlc291cmNlc190b29sYmFyKCBjb25maWcgKSB7XG5cdFx0dmFyIGluaXRpYWxfcmVxdWVzdCA9IGNvbmZpZy5pbml0aWFsX3JlcXVlc3QgfHwge307XG5cdFx0dmFyIG1vdW50X2VsZW1lbnQgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCggY29uZmlnLm1vdW50X2lkICk7XG5cdFx0dmFyIHRvb2xiYXJfdGFyZ2V0ID0gbW91bnRfZWxlbWVudCA/IG1vdW50X2VsZW1lbnQucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtYm9va2luZy1yZXNvdXJjZXMtdG9vbGJhcl0nICkgOiBudWxsO1xuXG5cdFx0aWYgKCAhIHRvb2xiYXJfdGFyZ2V0ICkge1xuXHRcdFx0cmV0dXJuIGZhbHNlO1xuXHRcdH1cblx0XHR0b29sYmFyX3RhcmdldC5pbm5lckhUTUwgPSByZW5kZXJfY29tcG9uZW50KCBjb25maWcsICd0b29sYmFyJywge1xuXHRcdFx0YWN0aXZlX3RlbXBsYXRlX3BhY2s6IGluaXRpYWxfcmVxdWVzdC50ZW1wbGF0ZV9wYWNrIHx8IGNvbmZpZy5kZWZhdWx0X3RlbXBsYXRlX3BhY2sgfHwgJ3RhYmxlJyxcblx0XHRcdGFjdGl2ZV92aWV3OiBnZXRfYWN0aXZlX3ZpZXcoIGNvbmZpZywgaW5pdGlhbF9yZXF1ZXN0ICksXG5cdFx0XHRjb2x1bW5zOiBnZXRfY29sdW1ucyggY29uZmlnLCBpbml0aWFsX3JlcXVlc3QsIGZhbHNlLCBpbml0aWFsX3JlcXVlc3QgKSxcblx0XHRcdGkxOG46IGNvbmZpZy5pMThuIHx8IHt9LFxuXHRcdFx0dGVtcGxhdGVfcGFja3M6IGdldF90ZW1wbGF0ZV9wYWNrX2RlZmluaXRpb25zKCBjb25maWcgKSxcblx0XHRcdHZpZXdzOiBnZXRfdmlld19kZWZpbml0aW9ucyggY29uZmlnIClcblx0XHR9ICk7XG5cdFx0aWYgKCBjYXRhbG9nX2NvbnRyb2xsZXIgJiYgJ2Z1bmN0aW9uJyA9PT0gdHlwZW9mIGNhdGFsb2dfY29udHJvbGxlci5yZWZyZXNoX2NvbnRyb2xzICkge1xuXHRcdFx0Y2F0YWxvZ19jb250cm9sbGVyLnJlZnJlc2hfY29udHJvbHMoKTtcblx0XHR9XG5cblx0XHRyZXR1cm4gISEgdG9vbGJhcl90YXJnZXQuZmlyc3RFbGVtZW50Q2hpbGQ7XG5cdH1cblxuXHQvKipcblx0ICogQWRkIGZ1bGwtdGV4dCB0b29sdGlwcyBvbmx5IHRvIGVsZW1lbnRzIHdob3NlIHJlbmRlcmVkIHRleHQgaXMgY2xpcHBlZC5cblx0ICpcblx0ICogTmF0aXZlIHRpdGxlIHRleHQgcmVtYWlucyBhdmFpbGFibGUgd2l0aG91dCBhIEphdmFTY3JpcHQgdG9vbHRpcCBsaWJyYXJ5O1xuXHQgKiB0aGUgZXN0YWJsaXNoZWQgdG9vbHRpcCBhdHRyaWJ1dGVzIGFyZSBhbHNvIHN1cHBsaWVkIGZvciBCb29raW5nIENhbGVuZGFyXG5cdCAqIGFkbWluIHRoZW1lcyB0aGF0IGluaXRpYWxpemUgdGhlbSBnbG9iYWxseS5cblx0ICpcblx0ICogQHBhcmFtIHtIVE1MRWxlbWVudH0gY2F0YWxvZ19tb3VudCBDYXRhbG9nIG1vdW50IGVsZW1lbnQuXG5cdCAqIEByZXR1cm4ge3ZvaWR9XG5cdCAqL1xuXHRmdW5jdGlvbiBzeW5jaHJvbml6ZV9vdmVyZmxvd190b29sdGlwcyggY2F0YWxvZ19tb3VudCApIHtcblx0XHRpZiAoICEgd2luZG93LndwYmNfdWlfY2F0YWxvZyB8fCAnZnVuY3Rpb24nICE9PSB0eXBlb2Ygd2luZG93LndwYmNfdWlfY2F0YWxvZy5zeW5jaHJvbml6ZV9vdmVyZmxvd190b29sdGlwcyApIHtcblx0XHRcdHJldHVybjtcblx0XHR9XG5cblx0XHR3aW5kb3cud3BiY191aV9jYXRhbG9nLnN5bmNocm9uaXplX292ZXJmbG93X3Rvb2x0aXBzKCBjYXRhbG9nX21vdW50ICk7XG5cdH1cblxuXHQvKipcblx0ICogSW5pdGlhbGl6ZSB0b29sdGlwcyBmb3IgY29tcGFjdCBjb250cm9scyBpbnNlcnRlZCBieSBhIGxhenkgZGV0YWlscyByZW5kZXIuXG5cdCAqXG5cdCAqIFRoZSBuYXRpdmUgdGl0bGUgcmVtYWlucyBhcyBhIGZhbGxiYWNrIHdoZW4gdGhlIGVzdGFibGlzaGVkIEJvb2tpbmdcblx0ICogQ2FsZW5kYXIgdG9vbHRpcCBoZWxwZXIgaXMgdW5hdmFpbGFibGUuXG5cdCAqXG5cdCAqIEBwYXJhbSB7SFRNTEVsZW1lbnR9IGNhdGFsb2dfbW91bnQgQ2F0YWxvZyBtb3VudCBlbGVtZW50LlxuXHQgKiBAcmV0dXJuIHt2b2lkfVxuXHQgKi9cblx0ZnVuY3Rpb24gaW5pdGlhbGl6ZV9kZXRhaWxzX3Rvb2x0aXBzKCBjYXRhbG9nX21vdW50ICkge1xuXHRcdHZhciB0b29sdGlwX3NlbGVjdG9yO1xuXG5cdFx0aWYgKCAhIGNhdGFsb2dfbW91bnQgfHwgISBjYXRhbG9nX21vdW50LmlkIHx8ICdmdW5jdGlvbicgIT09IHR5cGVvZiB3aW5kb3cud3BiY19kZWZpbmVfdGlwcHlfdG9vbHRpcHMgKSB7XG5cdFx0XHRyZXR1cm47XG5cdFx0fVxuXHRcdHRvb2x0aXBfc2VsZWN0b3IgPSAnIycgKyBjYXRhbG9nX21vdW50LmlkICsgJyBbZGF0YS13cGJjLXVpLWNhdGFsb2ctZGV0YWlscy10b29sdGlwXSc7XG5cdFx0d2luZG93LndwYmNfZGVmaW5lX3RpcHB5X3Rvb2x0aXBzKCB0b29sdGlwX3NlbGVjdG9yICk7XG5cdH1cblxuXHQvKipcblx0ICogU3luY2hyb25pemUgcGVyc2lzdGVudCBjb250cm9scyB3aXRoIHNlcnZlci1hdXRob3JpdGF0aXZlIHJlc3BvbnNlIHN0YXRlLlxuXHQgKlxuXHQgKiBAcGFyYW0ge09iamVjdH0gY29uZmlnICAgUmVnaXN0ZXJlZCBjYXRhbG9nIGNvbmZpZ3VyYXRpb24uXG5cdCAqIEBwYXJhbSB7T2JqZWN0fSByZXNwb25zZSBOb3JtYWxpemVkIGNhdGFsb2cgcmVzcG9uc2UuXG5cdCAqIEByZXR1cm4ge3ZvaWR9XG5cdCAqL1xuXHRmdW5jdGlvbiBzeW5jaHJvbml6ZV9ib29raW5nX3Jlc291cmNlc190b29sYmFyKCBjb25maWcsIHJlc3BvbnNlICkge1xuXHRcdHZhciBjb2x1bW5zID0gZ2V0X2NvbHVtbnMoIGNvbmZpZywgcmVzcG9uc2UuZGlzcGxheSB8fCB7fSwgZmFsc2UsIHJlc3BvbnNlLnNvcnRpbmcgfHwge30gKTtcblx0XHR2YXIgbW91bnRfZWxlbWVudCA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCBjb25maWcubW91bnRfaWQgKTtcblx0XHR2YXIgY29sdW1uX2xpc3QgPSBtb3VudF9lbGVtZW50ID8gbW91bnRfZWxlbWVudC5xdWVyeVNlbGVjdG9yKCAnW2RhdGEtd3BiYy11aS1jYXRhbG9nLWNvbHVtbi1saXN0XScgKSA6IG51bGw7XG5cdFx0dmFyIHNlYXJjaF9jb250cm9sID0gbW91bnRfZWxlbWVudCA/IG1vdW50X2VsZW1lbnQucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtdWktY2F0YWxvZy1zZWFyY2hdJyApIDogbnVsbDtcblx0XHR2YXIgdGVtcGxhdGVfcGFja19jb250cm9sID0gbW91bnRfZWxlbWVudCA/IG1vdW50X2VsZW1lbnQucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtdWktY2F0YWxvZy10ZW1wbGF0ZS1wYWNrXScgKSA6IG51bGw7XG5cdFx0dmFyIHR5cGVfY29udHJvbCA9IG1vdW50X2VsZW1lbnQgPyBtb3VudF9lbGVtZW50LnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLXVpLWNhdGFsb2ctZmlsdGVyPVwicmVzb3VyY2VfdHlwZVwiXScgKSA6IG51bGw7XG5cdFx0dmFyIHZpZXdfY29udHJvbCA9IG1vdW50X2VsZW1lbnQgPyBtb3VudF9lbGVtZW50LnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLXVpLWNhdGFsb2ctdmlld10nICkgOiBudWxsO1xuXG5cdFx0aWYgKCBzZWFyY2hfY29udHJvbCAmJiBkb2N1bWVudC5hY3RpdmVFbGVtZW50ICE9PSBzZWFyY2hfY29udHJvbCApIHtcblx0XHRcdHNlYXJjaF9jb250cm9sLnZhbHVlID0gcmVzcG9uc2UuZmlsdGVycy5zZWFyY2ggfHwgJyc7XG5cdFx0fVxuXHRcdGlmICggdHlwZV9jb250cm9sICkge1xuXHRcdFx0dHlwZV9jb250cm9sLnZhbHVlID0gcmVzcG9uc2UuZmlsdGVycy5yZXNvdXJjZV90eXBlIHx8ICdhbGwnO1xuXHRcdH1cblx0XHRpZiAoIHRlbXBsYXRlX3BhY2tfY29udHJvbCAmJiByZXNwb25zZS5kaXNwbGF5ICYmIHJlc3BvbnNlLmRpc3BsYXkudGVtcGxhdGVfcGFjayApIHtcblx0XHRcdHRlbXBsYXRlX3BhY2tfY29udHJvbC52YWx1ZSA9IHJlc3BvbnNlLmRpc3BsYXkudGVtcGxhdGVfcGFjaztcblx0XHR9XG5cdFx0Y29sdW1ucy5mb3JFYWNoKCBmdW5jdGlvbiAoIGNvbHVtbiApIHtcblx0XHRcdHZhciBjb2x1bW5fY29udHJvbCA9IG1vdW50X2VsZW1lbnQucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtdWktY2F0YWxvZy1jb2x1bW4tdmlzaWJsZV1bdmFsdWU9XCInICsgY29sdW1uLmlkICsgJ1wiXScgKTtcblx0XHRcdHZhciBjb2x1bW5faXRlbSA9IG1vdW50X2VsZW1lbnQucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtdWktY2F0YWxvZy1jb2x1bW4taXRlbT1cIicgKyBjb2x1bW4uaWQgKyAnXCJdJyApO1xuXHRcdFx0aWYgKCBjb2x1bW5fY29udHJvbCApIHtcblx0XHRcdFx0Y29sdW1uX2NvbnRyb2wuY2hlY2tlZCA9IGNvbHVtbi52aXNpYmxlO1xuXHRcdFx0fVxuXHRcdFx0aWYgKCBjb2x1bW5fbGlzdCAmJiBjb2x1bW5faXRlbSApIHtcblx0XHRcdFx0Y29sdW1uX2xpc3QuYXBwZW5kQ2hpbGQoIGNvbHVtbl9pdGVtICk7XG5cdFx0XHR9XG5cdFx0fSApO1xuXHRcdGlmICggdmlld19jb250cm9sICkge1xuXHRcdFx0dmlld19jb250cm9sLnZhbHVlID0gZ2V0X2FjdGl2ZV92aWV3KCBjb25maWcsIHJlc3BvbnNlLmRpc3BsYXkgfHwge30gKTtcblx0XHR9XG5cdH1cblxuXHQvKipcblx0ICogUmVuZGVyIHRoZSBwZXJzaXN0ZW50IGlubGluZS1lZGl0IHN0YXR1cyBiYXIgZnJvbSBpdHMgcmVnaXN0ZXJlZCB0ZW1wbGF0ZS5cblx0ICpcblx0ICogQHBhcmFtIHtPYmplY3R9IGNvbmZpZyBDYXRhbG9nIGNvbmZpZ3VyYXRpb24uXG5cdCAqIEByZXR1cm4ge3ZvaWR9XG5cdCAqL1xuXHRmdW5jdGlvbiByZW5kZXJfaW5saW5lX2JhciggY29uZmlnICkge1xuXHRcdHZhciBtb3VudF9lbGVtZW50ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoIGNvbmZpZy5tb3VudF9pZCApO1xuXHRcdHZhciBpbmxpbmVfaG9zdCA9IG1vdW50X2VsZW1lbnQgPyBtb3VudF9lbGVtZW50LnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLWNhdGFsb2ctaW5saW5lLWJhci1ob3N0XScgKSA6IG51bGw7XG5cblx0XHRpZiAoIGlubGluZV9ob3N0ICYmICEgaW5saW5lX2hvc3QuZmlyc3RFbGVtZW50Q2hpbGQgKSB7XG5cdFx0XHRpbmxpbmVfaG9zdC5pbm5lckhUTUwgPSByZW5kZXJfY29tcG9uZW50KCBjb25maWcsICdpbmxpbmVfYmFyJywgeyBpMThuOiBjb25maWcuaTE4biB8fCB7fSB9ICk7XG5cdFx0fVxuXHRcdGlmICggaW5saW5lX3dvcmtmbG93X2NvbnRyb2xsZXIgKSB7XG5cdFx0XHRpbmxpbmVfd29ya2Zsb3dfY29udHJvbGxlci5yZWdpc3Rlcl9zdGlja3lfYmFyKCk7XG5cdFx0fVxuXHR9XG5cblx0LyoqXG5cdCAqIFN5bmNocm9uaXplIGlubGluZSBhY3RpdmF0aW9uLCBjaGFuZ2VkIGNvdW50LCBhbmQgZGlzYWJsZWQgbmF2aWdhdGlvbi5cblx0ICpcblx0ICogQHBhcmFtIHtPYmplY3R9IGNvbmZpZyBDYXRhbG9nIGNvbmZpZ3VyYXRpb24uXG5cdCAqIEByZXR1cm4ge3ZvaWR9XG5cdCAqL1xuXHRmdW5jdGlvbiBzeW5jaHJvbml6ZV9pbmxpbmVfY29udHJvbHMoIGNvbmZpZyApIHtcblx0XHR2YXIgbW91bnRfZWxlbWVudCA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCBjb25maWcubW91bnRfaWQgKTtcblx0XHR2YXIgY2hhbmdlZF9jb3VudCA9IGlubGluZV9zdGF0ZS5jaGFuZ2VkX3Jvd3MubGVuZ3RoO1xuXHRcdHZhciBjb3VudF9sYWJlbCA9IDEgPT09IGNoYW5nZWRfY291bnQgPyBjb25maWcuaTE4bi5pbmxpbmVfY2hhbmdlZF9yb3cgOiBjb25maWcuaTE4bi5pbmxpbmVfY2hhbmdlZF9yb3dzO1xuXG5cdFx0aWYgKCAhIG1vdW50X2VsZW1lbnQgfHwgISBpbmxpbmVfd29ya2Zsb3dfY29udHJvbGxlciApIHtcblx0XHRcdHJldHVybjtcblx0XHR9XG5cblx0XHRpbmxpbmVfd29ya2Zsb3dfY29udHJvbGxlci5zeW5jaHJvbml6ZSgge1xuXHRcdFx0YWN0aXZlOiBpbmxpbmVfc3RhdGUuYWN0aXZlLFxuXHRcdFx0YWN0aXZlX3RvZ2dsZV90ZXh0OiBjb25maWcuaTE4bi5pbmxpbmVfZWRpdGluZ19yb3dzIHx8ICcnLFxuXHRcdFx0YnVzeTogaW5saW5lX3N0YXRlLmxvYWRpbmcsXG5cdFx0XHRjaGFuZ2VkX2NvdW50OiBjaGFuZ2VkX2NvdW50LFxuXHRcdFx0Y291bnRfdGV4dDogaW5saW5lX3N0YXRlLmxvYWRpbmdcblx0XHRcdFx0PyBjb25maWcuaTE4bi5pbmxpbmVfbG9hZGluZyB8fCAnJ1xuXHRcdFx0XHQ6IGZvcm1hdF9tZXNzYWdlKCBjb3VudF9sYWJlbCB8fCAnJTEkcyBjaGFuZ2VkIHJvd3MnLCBbIGNoYW5nZWRfY291bnQgXSApLFxuXHRcdFx0aGFzX2l0ZW1zOiAhISBtb3VudF9lbGVtZW50LnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLWJvb2tpbmctcmVzb3VyY2UtaWRdJyApLFxuXHRcdFx0aW5hY3RpdmVfdG9nZ2xlX3RleHQ6IGNvbmZpZy5pMThuLmVkaXRfcm93cyB8fCAnJ1xuXHRcdH0gKTtcblx0fVxuXG5cdC8qKlxuXHQgKiBCbG9jayBwYWdlLWNoYW5naW5nIGNhdGFsb2cgY29udHJvbHMgYmVmb3JlIHNoYXJlZCBoYW5kbGVycyBjYW4gZGlzY2FyZCBkcmFmdHMuXG5cdCAqXG5cdCAqIE5hdGl2ZSBzdW1tYXJ5IGVsZW1lbnRzIGRvIG5vdCBob25vciBhIGRpc2FibGVkIHByb3BlcnR5LCBzbyB0aGlzIGNhcHR1cmVcblx0ICogZ3VhcmQgY29tcGxlbWVudHMgdGhlIHZpc3VhbCBkaXNhYmxlZCBzdGF0ZSB3aGlsZSBpbmxpbmUgZWRpdGluZyBpcyBhY3RpdmUuXG5cdCAqXG5cdCAqIEBwYXJhbSB7RXZlbnR9IGV2ZW50IENhcHR1cmVkIGNhdGFsb2cgZXZlbnQuXG5cdCAqIEByZXR1cm4ge3ZvaWR9XG5cdCAqL1xuXHRmdW5jdGlvbiBwcm90ZWN0X2lubGluZV9kcmFmdHNfZnJvbV9jYXRhbG9nX2NvbnRyb2xzKCBldmVudCApIHtcblx0XHRpZiAoIGlubGluZV93b3JrZmxvd19jb250cm9sbGVyICkge1xuXHRcdFx0aW5saW5lX3dvcmtmbG93X2NvbnRyb2xsZXIucHJvdGVjdF9ldmVudCggZXZlbnQsIGlubGluZV9zdGF0ZS5hY3RpdmUgKTtcblx0XHR9XG5cdH1cblxuXHQvKipcblx0ICogU2hvdyBvciBjbGVhciBhbiBpbmxpbmUgd29ya2Zsb3cgZXJyb3IuXG5cdCAqXG5cdCAqIEBwYXJhbSB7T2JqZWN0fSBjb25maWcgQ2F0YWxvZyBjb25maWd1cmF0aW9uLlxuXHQgKiBAcGFyYW0ge3N0cmluZ30gbWVzc2FnZSBTYWZlIG1lc3NhZ2Ugb3IgZW1wdHkgc3RyaW5nLlxuXHQgKiBAcmV0dXJuIHt2b2lkfVxuXHQgKi9cblx0ZnVuY3Rpb24gc2hvd19pbmxpbmVfbWVzc2FnZSggY29uZmlnLCBtZXNzYWdlICkge1xuXHRcdHZhciBtb3VudF9lbGVtZW50ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoIGNvbmZpZy5tb3VudF9pZCApO1xuXHRcdHZhciBub3RpY2UgPSBtb3VudF9lbGVtZW50ID8gbW91bnRfZWxlbWVudC5xdWVyeVNlbGVjdG9yKCAnW2RhdGEtd3BiYy1jYXRhbG9nLWlubGluZS1tZXNzYWdlXScgKSA6IG51bGw7XG5cblx0XHRpZiAoIG5vdGljZSApIHtcblx0XHRcdG5vdGljZS5oaWRkZW4gPSAhIG1lc3NhZ2U7XG5cdFx0XHR2YXIgdGV4dCA9IG5vdGljZS5xdWVyeVNlbGVjdG9yKCAncCcgKTtcblx0XHRcdGlmICggdGV4dCApIHtcblx0XHRcdFx0dGV4dC50ZXh0Q29udGVudCA9IG1lc3NhZ2UgfHwgJyc7XG5cdFx0XHR9XG5cdFx0fVxuXHR9XG5cblx0LyoqXG5cdCAqIFJlbmRlciBvbmUgc2VydmVyLWRlY2xhcmVkIGZpZWxkIHRocm91Z2ggdGhlIGlubGluZSBXUCB0ZW1wbGF0ZS5cblx0ICpcblx0ICogQHBhcmFtIHtPYmplY3R9IGNvbmZpZyBDYXRhbG9nIGNvbmZpZ3VyYXRpb24uXG5cdCAqIEBwYXJhbSB7T2JqZWN0fSByb3dfc2NoZW1hIFJvdyBzY2hlbWEuXG5cdCAqIEByZXR1cm4ge3ZvaWR9XG5cdCAqL1xuXHRmdW5jdGlvbiByZW5kZXJfaW5saW5lX3JvdyggY29uZmlnLCByb3dfc2NoZW1hICkge1xuXHRcdHZhciBtb3VudF9lbGVtZW50ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoIGNvbmZpZy5tb3VudF9pZCApO1xuXHRcdHZhciByZXNvdXJjZV9pZCA9IE51bWJlciggcm93X3NjaGVtYS5yZXNvdXJjZV9pZCApIHx8IDA7XG5cdFx0dmFyIHJvdyA9IG1vdW50X2VsZW1lbnQgPyBtb3VudF9lbGVtZW50LnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLWJvb2tpbmctcmVzb3VyY2UtaWQ9XCInICsgcmVzb3VyY2VfaWQgKyAnXCJdJyApIDogbnVsbDtcblx0XHR2YXIgcmVzb3VyY2VfZmllbGRzID0gW107XG5cblx0XHRpZiAoICEgcm93ICkge1xuXHRcdFx0cmV0dXJuO1xuXHRcdH1cblx0XHQoIHJvd19zY2hlbWEuZmllbGRzIHx8IFtdICkuZm9yRWFjaCggZnVuY3Rpb24gKCBmaWVsZCApIHtcblx0XHRcdHZhciBjZWxsID0gcm93LnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLXVpLWNhdGFsb2ctZmllbGQ9XCInICsgU3RyaW5nKCBmaWVsZC5jb2x1bW4gfHwgJycgKSArICdcIl0nICk7XG5cdFx0XHRpZiAoICEgY2VsbCB8fCBjZWxsLmhpZGRlbiApIHtcblx0XHRcdFx0cmV0dXJuO1xuXHRcdFx0fVxuXHRcdFx0aWYgKCAncmVzb3VyY2UnID09PSBmaWVsZC5jb2x1bW4gKSB7XG5cdFx0XHRcdHJlc291cmNlX2ZpZWxkcy5wdXNoKCBmaWVsZCApO1xuXHRcdFx0XHRyZXR1cm47XG5cdFx0XHR9XG5cdFx0XHRjZWxsLmlubmVySFRNTCA9IHJlbmRlcl9jb21wb25lbnQoIGNvbmZpZywgJ2lubGluZV9maWVsZCcsIHsgZmllbGQ6IGZpZWxkLCByZXNvdXJjZV9pZDogcmVzb3VyY2VfaWQgfSApO1xuXHRcdH0gKTtcblx0XHRpZiAoIHJlc291cmNlX2ZpZWxkcy5sZW5ndGggKSB7XG5cdFx0XHR2YXIgY29weSA9IHJvdy5xdWVyeVNlbGVjdG9yKCAnW2RhdGEtd3BiYy11aS1jYXRhbG9nLWZpZWxkPVwicmVzb3VyY2VcIl0gLndwYmNfdWlfbGlzdGluZ19faXRlbV9jb3B5JyApO1xuXHRcdFx0aWYgKCBjb3B5ICkge1xuXHRcdFx0XHR2YXIgd3JhcHBlciA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoICdzcGFuJyApO1xuXHRcdFx0XHR3cmFwcGVyLmNsYXNzTmFtZSA9ICd3cGJjX2Jvb2tpbmdfcmVzb3VyY2VzX19pbmxpbmVfaWRlbnRpdHlfZmllbGRzJztcblx0XHRcdFx0cmVzb3VyY2VfZmllbGRzLmZvckVhY2goIGZ1bmN0aW9uICggZmllbGQgKSB7XG5cdFx0XHRcdFx0d3JhcHBlci5pbnNlcnRBZGphY2VudEhUTUwoICdiZWZvcmVlbmQnLCByZW5kZXJfY29tcG9uZW50KCBjb25maWcsICdpbmxpbmVfZmllbGQnLCB7IGZpZWxkOiBmaWVsZCwgcmVzb3VyY2VfaWQ6IHJlc291cmNlX2lkIH0gKSApO1xuXHRcdFx0XHR9ICk7XG5cdFx0XHRcdGNvcHkucmVwbGFjZVdpdGgoIHdyYXBwZXIgKTtcblx0XHRcdH1cblx0XHR9XG5cdH1cblxuXHQvKipcblx0ICogQ29sbGVjdCBvbmx5IGNoYW5nZWQgcm93IGZpZWxkcyB3aGlsZSBwcmVzZXJ2aW5nIHZpc2libGUgY2F0YWxvZyBvcmRlci5cblx0ICpcblx0ICogQHBhcmFtIHtPYmplY3R9IGNvbmZpZyBDYXRhbG9nIGNvbmZpZ3VyYXRpb24uXG5cdCAqIEByZXR1cm4ge0FycmF5PE9iamVjdD59IENoYW5nZWQgcm93IGVudmVsb3Blcy5cblx0ICovXG5cdGZ1bmN0aW9uIGNvbGxlY3RfaW5saW5lX2RyYWZ0cyggY29uZmlnICkge1xuXHRcdHZhciBtb3VudF9lbGVtZW50ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoIGNvbmZpZy5tb3VudF9pZCApO1xuXHRcdHZhciBjaGFuZ2VkX3Jvd3MgPSBbXTtcblxuXHRcdGlmICggISBtb3VudF9lbGVtZW50ICkge1xuXHRcdFx0cmV0dXJuIGNoYW5nZWRfcm93cztcblx0XHR9XG5cdFx0bW91bnRfZWxlbWVudC5xdWVyeVNlbGVjdG9yQWxsKCAnLndwYmNfYm9va2luZ19yZXNvdXJjZXNfX2l0ZW1bZGF0YS13cGJjLWJvb2tpbmctcmVzb3VyY2UtaWRdJyApLmZvckVhY2goIGZ1bmN0aW9uICggcm93ICkge1xuXHRcdFx0dmFyIGZpZWxkcyA9IHt9O1xuXHRcdFx0dmFyIGhhc19jaGFuZ2VzO1xuXHRcdFx0dmFyIGluZGljYXRvcl9ob3N0O1xuXG5cdFx0XHRyb3cucXVlcnlTZWxlY3RvckFsbCggJ1tkYXRhLXdwYmMtY2F0YWxvZy1pbmxpbmUtZmllbGRdJyApLmZvckVhY2goIGZ1bmN0aW9uICggY29udHJvbCApIHtcblx0XHRcdFx0dmFyIGZpZWxkX2tleSA9IGNvbnRyb2wuZ2V0QXR0cmlidXRlKCAnZGF0YS13cGJjLWNhdGFsb2ctaW5saW5lLWZpZWxkJyApIHx8ICcnO1xuXHRcdFx0XHRpZiAoIGZpZWxkX2tleSAmJiBTdHJpbmcoIGNvbnRyb2wudmFsdWUgfHwgJycgKSAhPT0gU3RyaW5nKCBjb250cm9sLmdldEF0dHJpYnV0ZSggJ2RhdGEtd3BiYy1jYXRhbG9nLWlubGluZS1vcmlnaW5hbCcgKSB8fCAnJyApICkge1xuXHRcdFx0XHRcdGZpZWxkc1sgZmllbGRfa2V5IF0gPSBjb250cm9sLnZhbHVlO1xuXHRcdFx0XHR9XG5cdFx0XHR9ICk7XG5cdFx0XHRoYXNfY2hhbmdlcyA9IDAgPCBPYmplY3Qua2V5cyggZmllbGRzICkubGVuZ3RoO1xuXHRcdFx0aW5kaWNhdG9yX2hvc3QgPSByb3cucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtdWktY2F0YWxvZy1maWVsZD1cInJlc291cmNlXCJdJyApO1xuXHRcdFx0cm93LmNsYXNzTGlzdC50b2dnbGUoICdpcy1pbmxpbmUtZGlydHknLCBoYXNfY2hhbmdlcyApO1xuXHRcdFx0aWYgKCBpbmxpbmVfd29ya2Zsb3dfY29udHJvbGxlciApIHtcblx0XHRcdFx0aW5saW5lX3dvcmtmbG93X2NvbnRyb2xsZXIuc2V0X3Jvd19jaGFuZ2VkKCByb3csIGhhc19jaGFuZ2VzLCBpbmRpY2F0b3JfaG9zdCwgY29uZmlnLmkxOG4uaW5saW5lX2NoYW5nZWQgfHwgJycgKTtcblx0XHRcdH1cblx0XHRcdGlmICggaGFzX2NoYW5nZXMgKSB7XG5cdFx0XHRcdGNoYW5nZWRfcm93cy5wdXNoKCB7IHJlc291cmNlX2lkOiBOdW1iZXIoIHJvdy5nZXRBdHRyaWJ1dGUoICdkYXRhLXdwYmMtYm9va2luZy1yZXNvdXJjZS1pZCcgKSApLCBmaWVsZHM6IGZpZWxkcyB9ICk7XG5cdFx0XHR9XG5cdFx0fSApO1xuXG5cdFx0cmV0dXJuIGNoYW5nZWRfcm93cztcblx0fVxuXG5cdC8qKlxuXHQgKiBJbnZhbGlkYXRlIGEgcHJpb3IgcmV2aWV3IGFuZCBzeW5jaHJvbml6ZSBkcmFmdCBzdGF0ZS5cblx0ICpcblx0ICogQHBhcmFtIHtPYmplY3R9IGNvbmZpZyBDYXRhbG9nIGNvbmZpZ3VyYXRpb24uXG5cdCAqIEByZXR1cm4ge3ZvaWR9XG5cdCAqL1xuXHRmdW5jdGlvbiBzeW5jaHJvbml6ZV9pbmxpbmVfZHJhZnRzKCBjb25maWcgKSB7XG5cdFx0aW5saW5lX3N0YXRlLmNoYW5nZWRfcm93cyA9IGNvbGxlY3RfaW5saW5lX2RyYWZ0cyggY29uZmlnICk7XG5cdFx0aW5saW5lX3N0YXRlLnJldmlld190b2tlbiA9ICcnO1xuXHRcdHNob3dfaW5saW5lX21lc3NhZ2UoIGNvbmZpZywgJycgKTtcblx0XHRzeW5jaHJvbml6ZV9pbmxpbmVfY29udHJvbHMoIGNvbmZpZyApO1xuXHR9XG5cblx0LyoqXG5cdCAqIEV4aXQgaW5saW5lIG1vZGUgYW5kIG9wdGlvbmFsbHkgcmVsb2FkIGNhbm9uaWNhbCByb3dzLlxuXHQgKlxuXHQgKiBAcGFyYW0ge09iamVjdH0gY29uZmlnIENhdGFsb2cgY29uZmlndXJhdGlvbi5cblx0ICogQHBhcmFtIHtib29sZWFufSByZWxvYWQgV2hldGhlciB0byByZWxvYWQgdGhlIGNhdGFsb2cuXG5cdCAqIEBwYXJhbSB7c3RyaW5nfSBtZXNzYWdlIE9wdGlvbmFsIHN1Y2Nlc3MgbWVzc2FnZS5cblx0ICogQHJldHVybiB7dm9pZH1cblx0ICovXG5cdGZ1bmN0aW9uIGxlYXZlX2lubGluZV9tb2RlKCBjb25maWcsIHJlbG9hZCwgbWVzc2FnZSApIHtcblx0XHRpbmxpbmVfc3RhdGUucmVxdWVzdF9zZXF1ZW5jZSArPSAxO1xuXHRcdGlubGluZV9zdGF0ZS5hY3RpdmUgPSBmYWxzZTtcblx0XHRpbmxpbmVfc3RhdGUubG9hZGluZyA9IGZhbHNlO1xuXHRcdGlubGluZV9zdGF0ZS5jaGFuZ2VkX3Jvd3MgPSBbXTtcblx0XHRpbmxpbmVfc3RhdGUucmV2aWV3X3Rva2VuID0gJyc7XG5cdFx0aWYgKCAnaW5saW5lX3JldmlldycgPT09IGluc3BlY3Rvcl9tb2RlICkge1xuXHRcdFx0Y2xvc2VfaW5zcGVjdG9yKCBjb25maWcsIGZhbHNlICk7XG5cdFx0fVxuXHRcdHN5bmNocm9uaXplX2lubGluZV9jb250cm9scyggY29uZmlnICk7XG5cdFx0aWYgKCBtZXNzYWdlICkge1xuXHRcdFx0c2hvd19hZG1pbl9tZXNzYWdlKCBtZXNzYWdlLCAnc3VjY2VzcycsIDQwMDAgKTtcblx0XHR9XG5cdFx0aWYgKCByZWxvYWQgJiYgY2F0YWxvZ19jb250cm9sbGVyICkge1xuXHRcdFx0Y2F0YWxvZ19jb250cm9sbGVyLmxvYWQoKTtcblx0XHR9XG5cdH1cblxuXHQvKipcblx0ICogU3RhcnQgcm93IGVkaXRpbmcgZm9yIHRoZSBjdXJyZW50IHZpc2libGUgUmVzb3VyY2UgcGFnZS5cblx0ICpcblx0ICogQHBhcmFtIHtPYmplY3R9IGNvbmZpZyBDYXRhbG9nIGNvbmZpZ3VyYXRpb24uXG5cdCAqIEByZXR1cm4ge3ZvaWR9XG5cdCAqL1xuXHRmdW5jdGlvbiBzdGFydF9pbmxpbmVfbW9kZSggY29uZmlnICkge1xuXHRcdHZhciBtb3VudF9lbGVtZW50ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoIGNvbmZpZy5tb3VudF9pZCApO1xuXHRcdHZhciByZXNvdXJjZV9pZHMgPSBbXTtcblx0XHR2YXIgcmVxdWVzdF9zZXF1ZW5jZTtcblxuXHRcdGlmICggaW5saW5lX3N0YXRlLmFjdGl2ZSApIHtcblx0XHRcdHN5bmNocm9uaXplX2lubGluZV9kcmFmdHMoIGNvbmZpZyApO1xuXHRcdFx0aWYgKCAhIGlubGluZV9zdGF0ZS5jaGFuZ2VkX3Jvd3MubGVuZ3RoIHx8IHdpbmRvdy5jb25maXJtKCBjb25maWcuaTE4bi5pbmxpbmVfZGlzY2FyZCB8fCAnJyApICkge1xuXHRcdFx0XHRsZWF2ZV9pbmxpbmVfbW9kZSggY29uZmlnLCB0cnVlLCAnJyApO1xuXHRcdFx0fVxuXHRcdFx0cmV0dXJuO1xuXHRcdH1cblx0XHRtb3VudF9lbGVtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoICcud3BiY19ib29raW5nX3Jlc291cmNlc19faXRlbVtkYXRhLXdwYmMtYm9va2luZy1yZXNvdXJjZS1pZF0nICkuZm9yRWFjaCggZnVuY3Rpb24gKCByb3cgKSB7XG5cdFx0XHRyZXNvdXJjZV9pZHMucHVzaCggTnVtYmVyKCByb3cuZ2V0QXR0cmlidXRlKCAnZGF0YS13cGJjLWJvb2tpbmctcmVzb3VyY2UtaWQnICkgKSApO1xuXHRcdH0gKTtcblx0XHRpZiAoICEgcmVzb3VyY2VfaWRzLmxlbmd0aCApIHtcblx0XHRcdHJldHVybjtcblx0XHR9XG5cdFx0aWYgKCAhIGNhbl9kaXNjYXJkX2luc3BlY3RvciggY29uZmlnICkgKSB7XG5cdFx0XHRyZXR1cm47XG5cdFx0fVxuXHRcdGNsb3NlX2luc3BlY3RvciggY29uZmlnLCBmYWxzZSApO1xuXHRcdG1vdW50X2VsZW1lbnQucXVlcnlTZWxlY3RvckFsbCggJ1tkYXRhLXdwYmMtdWktY2F0YWxvZy1kaXNwbGF5LWN1c3RvbWl6ZXJdW29wZW5dJyApLmZvckVhY2goIGZ1bmN0aW9uICggY3VzdG9taXplciApIHtcblx0XHRcdGN1c3RvbWl6ZXIucmVtb3ZlQXR0cmlidXRlKCAnb3BlbicgKTtcblx0XHR9ICk7XG5cdFx0aW5saW5lX3N0YXRlLmFjdGl2ZSA9IHRydWU7XG5cdFx0aW5saW5lX3N0YXRlLmxvYWRpbmcgPSB0cnVlO1xuXHRcdGlubGluZV9zdGF0ZS5jaGFuZ2VkX3Jvd3MgPSBbXTtcblx0XHRyZXF1ZXN0X3NlcXVlbmNlID0gKytpbmxpbmVfc3RhdGUucmVxdWVzdF9zZXF1ZW5jZTtcblx0XHRjbG9zZV9kZXRhaWxzX3JvdyggZmFsc2UgKTtcblx0XHRzeW5jaHJvbml6ZV9pbmxpbmVfY29udHJvbHMoIGNvbmZpZyApO1xuXHRcdHJlcXVlc3RfaW5zcGVjdG9yKCBjb25maWcsIGNvbmZpZy5pbmxpbmVfc2NoZW1hX2FjdGlvbiwgeyByZXNvdXJjZV9pZHM6IEpTT04uc3RyaW5naWZ5KCByZXNvdXJjZV9pZHMgKSB9ICkudGhlbiggZnVuY3Rpb24gKCByZXNwb25zZSApIHtcblx0XHRcdGlmICggcmVxdWVzdF9zZXF1ZW5jZSAhPT0gaW5saW5lX3N0YXRlLnJlcXVlc3Rfc2VxdWVuY2UgfHwgISBpbmxpbmVfc3RhdGUuYWN0aXZlIHx8ICEgcmVzcG9uc2UgfHwgISByZXNwb25zZS5zdWNjZXNzIHx8ICEgcmVzcG9uc2UuZGF0YSB8fCAhIHJlc3BvbnNlLmRhdGEuc2NoZW1hICkge1xuXHRcdFx0XHR0aHJvdyBuZXcgRXJyb3IoIGdldF9pbnNwZWN0b3JfcmVzcG9uc2VfbWVzc2FnZSggcmVzcG9uc2UsIGNvbmZpZy5pMThuLmlubGluZV9sb2FkX2ZhaWxlZCApICk7XG5cdFx0XHR9XG5cdFx0XHQoIHJlc3BvbnNlLmRhdGEuc2NoZW1hLnJvd3MgfHwgW10gKS5mb3JFYWNoKCBmdW5jdGlvbiAoIHJvd19zY2hlbWEgKSB7XG5cdFx0XHRcdHJlbmRlcl9pbmxpbmVfcm93KCBjb25maWcsIHJvd19zY2hlbWEgKTtcblx0XHRcdH0gKTtcblx0XHRcdHZhciBmaXJzdF9maWVsZCA9IG1vdW50X2VsZW1lbnQucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtY2F0YWxvZy1pbmxpbmUtZmllbGRdJyApO1xuXHRcdFx0aWYgKCBmaXJzdF9maWVsZCApIHtcblx0XHRcdFx0Zmlyc3RfZmllbGQuZm9jdXMoKTtcblx0XHRcdH1cblx0XHR9ICkuY2F0Y2goIGZ1bmN0aW9uICggZXJyb3IgKSB7XG5cdFx0XHRpZiAoIHJlcXVlc3Rfc2VxdWVuY2UgPT09IGlubGluZV9zdGF0ZS5yZXF1ZXN0X3NlcXVlbmNlICkge1xuXHRcdFx0XHRzaG93X2FkbWluX21lc3NhZ2UoIGVycm9yLm1lc3NhZ2UgfHwgY29uZmlnLmkxOG4uaW5saW5lX2xvYWRfZmFpbGVkIHx8ICcnLCAnZXJyb3InLCA1MDAwICk7XG5cdFx0XHRcdGlubGluZV9zdGF0ZS5hY3RpdmUgPSBmYWxzZTtcblx0XHRcdFx0aWYgKCBjYXRhbG9nX2NvbnRyb2xsZXIgKSB7XG5cdFx0XHRcdFx0Y2F0YWxvZ19jb250cm9sbGVyLmxvYWQoKTtcblx0XHRcdFx0fVxuXHRcdFx0fVxuXHRcdH0gKS50aGVuKCBmdW5jdGlvbiAoKSB7XG5cdFx0XHRpZiAoIHJlcXVlc3Rfc2VxdWVuY2UgPT09IGlubGluZV9zdGF0ZS5yZXF1ZXN0X3NlcXVlbmNlICkge1xuXHRcdFx0XHRpbmxpbmVfc3RhdGUubG9hZGluZyA9IGZhbHNlO1xuXHRcdFx0XHRzeW5jaHJvbml6ZV9pbmxpbmVfY29udHJvbHMoIGNvbmZpZyApO1xuXHRcdFx0fVxuXHRcdH0gKTtcblx0fVxuXG5cdC8qKlxuXHQgKiBQcmV2aWV3IGN1cnJlbnQgaW5saW5lIGRyYWZ0cyBhbmQgb3BlbiB0aGVpciBzaWduZWQgcmV2aWV3IGluc3BlY3Rvci5cblx0ICpcblx0ICogQHBhcmFtIHtPYmplY3R9IGNvbmZpZyBDYXRhbG9nIGNvbmZpZ3VyYXRpb24uXG5cdCAqIEBwYXJhbSB7SFRNTEVsZW1lbnR9IGZvY3VzX3RhcmdldCBSZXZpZXcgdHJpZ2dlciBmb3IgZm9jdXMgcmVzdG9yYXRpb24uXG5cdCAqIEByZXR1cm4ge3ZvaWR9XG5cdCAqL1xuXHRmdW5jdGlvbiBwcmV2aWV3X2lubGluZV9jaGFuZ2VzKCBjb25maWcsIGZvY3VzX3RhcmdldCApIHtcblx0XHR2YXIgaW5zcGVjdG9yX3dvcmtmbG93O1xuXHRcdHZhciByZXF1ZXN0X3NlcXVlbmNlO1xuXG5cdFx0c3luY2hyb25pemVfaW5saW5lX2RyYWZ0cyggY29uZmlnICk7XG5cdFx0aW5zcGVjdG9yX3dvcmtmbG93ID0gZ2V0X2luc3BlY3Rvcl93b3JrZmxvdyggY29uZmlnICk7XG5cdFx0aWYgKCAhIGlubGluZV9zdGF0ZS5jaGFuZ2VkX3Jvd3MubGVuZ3RoIHx8IGlubGluZV9zdGF0ZS5sb2FkaW5nIHx8ICEgaW5zcGVjdG9yX3dvcmtmbG93IHx8ICEgaW5zcGVjdG9yX3dvcmtmbG93Lm1vdW50KCkgKSB7XG5cdFx0XHRyZXR1cm47XG5cdFx0fVxuXHRcdGlubGluZV9zdGF0ZS5sb2FkaW5nID0gdHJ1ZTtcblx0XHRyZXF1ZXN0X3NlcXVlbmNlID0gKytpbmxpbmVfc3RhdGUucmVxdWVzdF9zZXF1ZW5jZTtcblx0XHRpbnNwZWN0b3JfZm9jdXNfdGFyZ2V0ID0gZm9jdXNfdGFyZ2V0O1xuXHRcdGluc3BlY3Rvcl9tb2RlID0gJ2lubGluZV9yZXZpZXcnO1xuXHRcdGluc3BlY3Rvcl9kaXJ0eSA9IHRydWU7XG5cdFx0aWYgKCAhIGluc3BlY3Rvcl93b3JrZmxvdy5vcGVuX2xvYWRpbmcoKSApIHtcblx0XHRcdGlubGluZV9zdGF0ZS5sb2FkaW5nID0gZmFsc2U7XG5cdFx0XHRyZXR1cm47XG5cdFx0fVxuXHRcdHN5bmNocm9uaXplX2lubGluZV9jb250cm9scyggY29uZmlnICk7XG5cdFx0cmVxdWVzdF9pbnNwZWN0b3IoIGNvbmZpZywgY29uZmlnLmlubGluZV9wcmV2aWV3X2FjdGlvbiwgeyByb3dzOiBKU09OLnN0cmluZ2lmeSggaW5saW5lX3N0YXRlLmNoYW5nZWRfcm93cyApIH0gKS50aGVuKCBmdW5jdGlvbiAoIHJlc3BvbnNlICkge1xuXHRcdFx0dmFyIHJldmlld193b3JrZmxvdztcblx0XHRcdHZhciByZXZpZXdfbW9kZWw7XG5cdFx0XHR2YXIgdGFyZ2V0O1xuXHRcdFx0aWYgKCByZXF1ZXN0X3NlcXVlbmNlICE9PSBpbmxpbmVfc3RhdGUucmVxdWVzdF9zZXF1ZW5jZSApIHtcblx0XHRcdFx0cmV0dXJuO1xuXHRcdFx0fVxuXHRcdFx0aWYgKCAhIHJlc3BvbnNlIHx8ICEgcmVzcG9uc2Uuc3VjY2VzcyB8fCAhIHJlc3BvbnNlLmRhdGEgfHwgISByZXNwb25zZS5kYXRhLnByZXZpZXcgKSB7XG5cdFx0XHRcdHRocm93IG5ldyBFcnJvciggZ2V0X2luc3BlY3Rvcl9yZXNwb25zZV9tZXNzYWdlKCByZXNwb25zZSwgY29uZmlnLmkxOG4uaW5saW5lX3Jldmlld19mYWlsZWQgKSApO1xuXHRcdFx0fVxuXHRcdFx0aW5saW5lX3N0YXRlLnJldmlld190b2tlbiA9IFN0cmluZyggcmVzcG9uc2UuZGF0YS5wcmV2aWV3LnJldmlld190b2tlbiB8fCAnJyApO1xuXHRcdFx0dGFyZ2V0ID0gZ2V0X2luc3BlY3Rvcl9ob3N0KCkucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtdWktY2F0YWxvZy1pbnNwZWN0b3ItZm9ybV0nICk7XG5cdFx0XHRyZXZpZXdfd29ya2Zsb3cgPSBnZXRfaW5saW5lX3Jldmlld193b3JrZmxvdygpO1xuXHRcdFx0cmV2aWV3X21vZGVsID0gcmV2aWV3X3dvcmtmbG93ID8gcmV2aWV3X3dvcmtmbG93LnByZXBhcmUoIHJlc3BvbnNlLmRhdGEucHJldmlldy5yZXZpZXcgfHwge30sIHtcblx0XHRcdFx0Y2hhbmdlZF9sYWJlbDogZm9ybWF0X21lc3NhZ2UoIDEgPT09IGlubGluZV9zdGF0ZS5jaGFuZ2VkX3Jvd3MubGVuZ3RoID8gY29uZmlnLmkxOG4uaW5saW5lX2NoYW5nZWRfcm93IDogY29uZmlnLmkxOG4uaW5saW5lX2NoYW5nZWRfcm93cywgWyBpbmxpbmVfc3RhdGUuY2hhbmdlZF9yb3dzLmxlbmd0aCBdICksXG5cdFx0XHRcdGRlc2NyaXB0aW9uOiBjb25maWcuaTE4bi5pbmxpbmVfcmV2aWV3X2Rlc2NyaXB0aW9uIHx8ICcnLFxuXHRcdFx0XHRmb3JtX2lkOiAnd3BiY19jYXRhbG9nX2Jvb2tpbmdfcmVzb3VyY2VzX2lubGluZV9yZXZpZXdfZm9ybScsXG5cdFx0XHRcdG1vZGU6ICdpbmxpbmVfcmV2aWV3Jyxcblx0XHRcdFx0cGVuZGluZ19tZXNzYWdlOiBjb25maWcuaTE4bi5yZXZpZXdfY2hhbmdlc19oZWxwIHx8ICcnLFxuXHRcdFx0XHR0aXRsZTogY29uZmlnLmkxOG4uaW5saW5lX3Jldmlld190aXRsZSB8fCAnJ1xuXHRcdFx0fSApIDoge307XG5cdFx0XHR0YXJnZXQuaW5uZXJIVE1MID0gcmVuZGVyX2NvbXBvbmVudCggY29uZmlnLCAnaW5zcGVjdG9yX2lubGluZV9yZXZpZXcnLCByZXZpZXdfbW9kZWwgKTtcblx0XHRcdHNldF9pbnNwZWN0b3Jfc3RhdGUoICdmb3JtJywgJycgKTtcblx0XHRcdGNvbmZpZ3VyZV9pbnNwZWN0b3JfZm9vdGVyKCAnd3BiY19jYXRhbG9nX2Jvb2tpbmdfcmVzb3VyY2VzX2lubGluZV9yZXZpZXdfZm9ybScsIGNvbmZpZy5pMThuLmFwcGx5X2NoYW5nZXMgfHwgJycsIGZhbHNlLCAhIGlubGluZV9zdGF0ZS5yZXZpZXdfdG9rZW4gKTtcblx0XHRcdGlmICggcmV2aWV3X3dvcmtmbG93ICkge1xuXHRcdFx0XHRyZXZpZXdfd29ya2Zsb3cuc3luY2hyb25pemUoIHsgYnVzeTogZmFsc2UsIGNhbl9hcHBseTogISEgaW5saW5lX3N0YXRlLnJldmlld190b2tlbiB9ICk7XG5cdFx0XHR9XG5cdFx0XHRmb2N1c19pbnNwZWN0b3JfaGVhZGluZyggdGFyZ2V0LnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLWNhdGFsb2ctaW5saW5lLXJldmlldy1mb3JtXScgKSApO1xuXHRcdH0gKS5jYXRjaCggZnVuY3Rpb24gKCBlcnJvciApIHtcblx0XHRcdGlmICggcmVxdWVzdF9zZXF1ZW5jZSAhPT0gaW5saW5lX3N0YXRlLnJlcXVlc3Rfc2VxdWVuY2UgKSB7XG5cdFx0XHRcdHJldHVybjtcblx0XHRcdH1cblx0XHRcdGlubGluZV9zdGF0ZS5yZXZpZXdfdG9rZW4gPSAnJztcblx0XHRcdGluc3BlY3Rvcl9kaXJ0eSA9IGZhbHNlO1xuXHRcdFx0c2hvd19hZG1pbl9tZXNzYWdlKCBlcnJvci5tZXNzYWdlIHx8IGNvbmZpZy5pMThuLmlubGluZV9yZXZpZXdfZmFpbGVkIHx8ICcnLCAnZXJyb3InLCA1MDAwICk7XG5cdFx0XHRjbG9zZV9pbnNwZWN0b3IoIGNvbmZpZywgZmFsc2UgKTtcblx0XHR9ICkudGhlbiggZnVuY3Rpb24gKCkge1xuXHRcdFx0aWYgKCByZXF1ZXN0X3NlcXVlbmNlID09PSBpbmxpbmVfc3RhdGUucmVxdWVzdF9zZXF1ZW5jZSApIHtcblx0XHRcdFx0aW5saW5lX3N0YXRlLmxvYWRpbmcgPSBmYWxzZTtcblx0XHRcdFx0c3luY2hyb25pemVfaW5saW5lX2NvbnRyb2xzKCBjb25maWcgKTtcblx0XHRcdH1cblx0XHR9ICk7XG5cdH1cblxuXHQvKipcblx0ICogQXBwbHkgdGhlIHJldmlld2VkIGlubGluZSBwbGFuIGFuZCByZXRhaW4gdGhlIGNhdGFsb2cgc2VsZWN0aW9uLlxuXHQgKlxuXHQgKiBAcGFyYW0ge1N1Ym1pdEV2ZW50fSBldmVudCBSZXZpZXcgZm9ybSBzdWJtaXQgZXZlbnQuXG5cdCAqIEBwYXJhbSB7T2JqZWN0fSBjb25maWcgQ2F0YWxvZyBjb25maWd1cmF0aW9uLlxuXHQgKiBAcmV0dXJuIHt2b2lkfVxuXHQgKi9cblx0ZnVuY3Rpb24gYXBwbHlfaW5saW5lX2NoYW5nZXMoIGV2ZW50LCBjb25maWcgKSB7XG5cdFx0dmFyIGZvcm0gPSBldmVudC50YXJnZXQ7XG5cdFx0dmFyIHNhdmVfYnV0dG9uID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtdWktY2F0YWxvZy1pbnNwZWN0b3Itc2F2ZV0nICk7XG5cdFx0dmFyIHJlcXVlc3Rfc2VxdWVuY2U7XG5cblx0XHRldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xuXHRcdGlmICggaW5saW5lX3N0YXRlLmxvYWRpbmcgfHwgISBpbmxpbmVfc3RhdGUucmV2aWV3X3Rva2VuIHx8ICggc2F2ZV9idXR0b24gJiYgc2F2ZV9idXR0b24uZGlzYWJsZWQgKSApIHtcblx0XHRcdHJldHVybjtcblx0XHR9XG5cdFx0aW5saW5lX3N0YXRlLmxvYWRpbmcgPSB0cnVlO1xuXHRcdGluc3BlY3Rvcl9tdXRhdGlvbl9pbl9wcm9ncmVzcyA9IHRydWU7XG5cdFx0aWYgKCBnZXRfaW5saW5lX3Jldmlld193b3JrZmxvdygpICkge1xuXHRcdFx0Z2V0X2lubGluZV9yZXZpZXdfd29ya2Zsb3coKS5zeW5jaHJvbml6ZSggeyBidXN5OiB0cnVlLCBjYW5fYXBwbHk6IHRydWUgfSApO1xuXHRcdH1cblx0XHRyZXF1ZXN0X3NlcXVlbmNlID0gKytpbnNwZWN0b3JfbXV0YXRpb25fcmVxdWVzdF9zZXF1ZW5jZTtcblx0XHRpZiAoIHNhdmVfYnV0dG9uICkge1xuXHRcdFx0c2F2ZV9idXR0b24uZGlzYWJsZWQgPSB0cnVlO1xuXHRcdFx0c2F2ZV9idXR0b24uY2xhc3NMaXN0LmFkZCggJ2lzLWJ1c3knICk7XG5cdFx0fVxuXHRcdHJlcXVlc3RfaW5zcGVjdG9yKCBjb25maWcsIGNvbmZpZy5pbmxpbmVfYXBwbHlfYWN0aW9uLCB7IHJvd3M6IEpTT04uc3RyaW5naWZ5KCBpbmxpbmVfc3RhdGUuY2hhbmdlZF9yb3dzICksIHJldmlld190b2tlbjogaW5saW5lX3N0YXRlLnJldmlld190b2tlbiB9ICkudGhlbiggZnVuY3Rpb24gKCByZXNwb25zZSApIHtcblx0XHRcdGlmICggcmVxdWVzdF9zZXF1ZW5jZSAhPT0gaW5zcGVjdG9yX211dGF0aW9uX3JlcXVlc3Rfc2VxdWVuY2UgfHwgISByZXNwb25zZSB8fCAhIHJlc3BvbnNlLnN1Y2Nlc3MgfHwgISByZXNwb25zZS5kYXRhICkge1xuXHRcdFx0XHR0aHJvdyBuZXcgRXJyb3IoIGdldF9pbnNwZWN0b3JfcmVzcG9uc2VfbWVzc2FnZSggcmVzcG9uc2UsIGNvbmZpZy5pMThuLmlubGluZV9hcHBseV9mYWlsZWQgKSApO1xuXHRcdFx0fVxuXHRcdFx0cGVuZGluZ19oaWdobGlnaHRfaWRzID0gKCByZXNwb25zZS5kYXRhLnVwZGF0ZWRfaWRzIHx8IFtdICkubWFwKCBTdHJpbmcgKTtcblx0XHRcdGluc3BlY3Rvcl9tdXRhdGlvbl9pbl9wcm9ncmVzcyA9IGZhbHNlO1xuXHRcdFx0bGVhdmVfaW5saW5lX21vZGUoIGNvbmZpZywgdHJ1ZSwgZ2V0X2luc3BlY3Rvcl9yZXNwb25zZV9tZXNzYWdlKCByZXNwb25zZSwgJycgKSApO1xuXHRcdH0gKS5jYXRjaCggZnVuY3Rpb24gKCBlcnJvciApIHtcblx0XHRcdGluc3BlY3Rvcl9tdXRhdGlvbl9pbl9wcm9ncmVzcyA9IGZhbHNlO1xuXHRcdFx0aW5saW5lX3N0YXRlLnJldmlld190b2tlbiA9ICcnO1xuXHRcdFx0aWYgKCBnZXRfaW5saW5lX3Jldmlld193b3JrZmxvdygpICkge1xuXHRcdFx0XHRnZXRfaW5saW5lX3Jldmlld193b3JrZmxvdygpLnN5bmNocm9uaXplKCB7IGJ1c3k6IGZhbHNlLCBjYW5fYXBwbHk6IGZhbHNlIH0gKTtcblx0XHRcdH1cblx0XHRcdGlmICggZG9jdW1lbnQuZG9jdW1lbnRFbGVtZW50LmNvbnRhaW5zKCBmb3JtICkgKSB7XG5cdFx0XHRcdHNob3dfaW5zcGVjdG9yX21lc3NhZ2UoIGZvcm0sIGVycm9yLm1lc3NhZ2UgfHwgY29uZmlnLmkxOG4uaW5saW5lX2FwcGx5X2ZhaWxlZCB8fCAnJywgdHJ1ZSApO1xuXHRcdFx0fSBlbHNlIHtcblx0XHRcdFx0c2hvd19hZG1pbl9tZXNzYWdlKCBlcnJvci5tZXNzYWdlIHx8IGNvbmZpZy5pMThuLmlubGluZV9hcHBseV9mYWlsZWQgfHwgJycsICdlcnJvcicsIDUwMDAgKTtcblx0XHRcdH1cblx0XHRcdGlmICggc2F2ZV9idXR0b24gKSB7XG5cdFx0XHRcdHNhdmVfYnV0dG9uLmRpc2FibGVkID0gdHJ1ZTtcblx0XHRcdFx0c2F2ZV9idXR0b24uY2xhc3NMaXN0LnJlbW92ZSggJ2lzLWJ1c3knICk7XG5cdFx0XHR9XG5cdFx0fSApLnRoZW4oIGZ1bmN0aW9uICgpIHtcblx0XHRcdGlubGluZV9zdGF0ZS5sb2FkaW5nID0gZmFsc2U7XG5cdFx0XHRzeW5jaHJvbml6ZV9pbmxpbmVfY29udHJvbHMoIGNvbmZpZyApO1xuXHRcdH0gKTtcblx0fVxuXG5cdC8qKlxuXHQgKiBSZW5kZXIgdGhlIGhlYWRlciwgY29tcGxldGUgUmVzb3VyY2Ugcm93cywgcGFydGlhbHMsIGFuZCBwYWdpbmF0aW9uLlxuXHQgKlxuXHQgKiBAcGFyYW0ge09iamVjdH0gY29uZmlnICAgUmVnaXN0ZXJlZCBjYXRhbG9nIGNvbmZpZ3VyYXRpb24uXG5cdCAqIEBwYXJhbSB7T2JqZWN0fSByZXNwb25zZSBOb3JtYWxpemVkIGNhdGFsb2cgcmVzcG9uc2UuXG5cdCAqIEByZXR1cm4ge2Jvb2xlYW59IFRydWUgd2hlbiBldmVyeSByZXF1aXJlZCBwcmVzZW50YXRpb24gdGFyZ2V0IGV4aXN0cy5cblx0ICovXG5cdGZ1bmN0aW9uIHJlbmRlcl9ib29raW5nX3Jlc291cmNlc19yZXNwb25zZSggY29uZmlnLCByZXNwb25zZSApIHtcblx0XHR2YXIgY2F0YWxvZ19oZWFkaW5nO1xuXHRcdHZhciBjYXRhbG9nX21vdW50ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoIGNvbmZpZy5tb3VudF9pZCApO1xuXHRcdHZhciBjYXJkX2dyb3VwcyA9IHt9O1xuXHRcdHZhciBjaGlsZHJlbl9ieV9wYXJlbnQgPSB7fTtcblx0XHR2YXIgY29sdW1ucztcblx0XHR2YXIgaGVhZGVyX2VsZW1lbnQ7XG5cdFx0dmFyIGhpZXJhcmNoeV9lbmFibGVkO1xuXHRcdHZhciBoaWVyYXJjaHlfaXNfZXhwYW5kZWQ7XG5cdFx0dmFyIGlzX2NhcmRzX3BhY2s7XG5cdFx0dmFyIHBhcmVudF9yZXNvdXJjZXMgPSB7fTtcblx0XHR2YXIgcGFnaW5hdGlvbjtcblx0XHR2YXIgcGFnaW5hdGlvbl9lbGVtZW50O1xuXHRcdHZhciByb3dzX2VsZW1lbnQ7XG5cblx0XHRpZiAoICEgY2F0YWxvZ19tb3VudCB8fCAhIHJlc3BvbnNlIHx8ICEgQXJyYXkuaXNBcnJheSggcmVzcG9uc2UuaXRlbXMgKSApIHtcblx0XHRcdHJldHVybiBmYWxzZTtcblx0XHR9XG5cblx0XHRoZWFkZXJfZWxlbWVudCA9IGNhdGFsb2dfbW91bnQucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtYm9va2luZy1yZXNvdXJjZXMtaGVhZGVyXScgKTtcblx0XHRyb3dzX2VsZW1lbnQgPSBjYXRhbG9nX21vdW50LnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLWJvb2tpbmctcmVzb3VyY2VzLXJvd3NdJyApO1xuXHRcdHBhZ2luYXRpb25fZWxlbWVudCA9IGNhdGFsb2dfbW91bnQucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtYm9va2luZy1yZXNvdXJjZXMtcGFnaW5hdGlvbl0nICk7XG5cdFx0aWYgKCAhIGhlYWRlcl9lbGVtZW50IHx8ICEgcm93c19lbGVtZW50IHx8ICEgcGFnaW5hdGlvbl9lbGVtZW50ICkge1xuXHRcdFx0cmV0dXJuIGZhbHNlO1xuXHRcdH1cblxuXHRcdGNvbHVtbnMgPSBnZXRfY29sdW1ucyggY29uZmlnLCByZXNwb25zZS5kaXNwbGF5IHx8IHt9LCB0cnVlLCByZXNwb25zZS5zb3J0aW5nIHx8IHt9ICk7XG5cdFx0aXNfY2FyZHNfcGFjayA9ICdjYXJkcycgPT09IFN0cmluZyggcmVzcG9uc2UuZGlzcGxheSAmJiByZXNwb25zZS5kaXNwbGF5LnRlbXBsYXRlX3BhY2sgfHwgJycgKTtcblx0XHRoaWVyYXJjaHlfZW5hYmxlZCA9ICEhICggcmVzcG9uc2UuaGllcmFyY2h5ICYmIHJlc3BvbnNlLmhpZXJhcmNoeS5lbmFibGVkICk7XG5cdFx0aGllcmFyY2h5X2lzX2V4cGFuZGVkID0gaGllcmFyY2h5X2VuYWJsZWRcblx0XHRcdCYmIHdpbmRvdy53cGJjX3VpX2NhdGFsb2dfaGllcmFyY2h5XG5cdFx0XHQmJiAnZnVuY3Rpb24nID09PSB0eXBlb2Ygd2luZG93LndwYmNfdWlfY2F0YWxvZ19oaWVyYXJjaHkuZ2V0X2luaXRpYWxfZXhwYW5kZWRcblx0XHRcdCYmIHdpbmRvdy53cGJjX3VpX2NhdGFsb2dfaGllcmFyY2h5LmdldF9pbml0aWFsX2V4cGFuZGVkKCByZXNwb25zZS5oaWVyYXJjaHkgfHwge30gKTtcblx0XHRoZWFkZXJfZWxlbWVudC5pbm5lckhUTUwgPSByZW5kZXJfY29tcG9uZW50KCBjb25maWcsICdoZWFkZXInLCB7XG5cdFx0XHRhbGxfZXhwYW5kZWQ6IGhpZXJhcmNoeV9pc19leHBhbmRlZCxcblx0XHRcdGNvbHVtbnM6IGNvbHVtbnMsXG5cdFx0XHRoaWVyYXJjaHlfZW5hYmxlZDogaGllcmFyY2h5X2VuYWJsZWQsXG5cdFx0XHRpMThuOiBjb25maWcuaTE4biB8fCB7fSxcblx0XHRcdHNlbGVjdGlvbl9lbmFibGVkOiAhISAoIGNvbmZpZy5mZWF0dXJlcyAmJiBjb25maWcuZmVhdHVyZXMuc2VsZWN0aW9uIClcblx0XHR9ICk7XG5cdFx0cm93c19lbGVtZW50LmlubmVySFRNTCA9ICcnO1xuXHRcdGlmICggaGllcmFyY2h5X2VuYWJsZWQgKSB7XG5cdFx0XHRyZXNwb25zZS5pdGVtcy5mb3JFYWNoKCBmdW5jdGlvbiAoIHJlc291cmNlICkge1xuXHRcdFx0XHRpZiAoIHJlc291cmNlLmhpZXJhcmNoeSAmJiAncGFyZW50JyA9PT0gcmVzb3VyY2UuaGllcmFyY2h5LnR5cGUgKSB7XG5cdFx0XHRcdFx0cGFyZW50X3Jlc291cmNlc1sgU3RyaW5nKCByZXNvdXJjZS5pZCApIF0gPSByZXNvdXJjZTtcblx0XHRcdFx0fSBlbHNlIGlmICggcmVzb3VyY2UuaGllcmFyY2h5ICYmICdjaGlsZCcgPT09IHJlc291cmNlLmhpZXJhcmNoeS50eXBlICkge1xuXHRcdFx0XHRcdHZhciBwYXJlbnRfaWQgPSBTdHJpbmcoIHJlc291cmNlLmhpZXJhcmNoeS5wYXJlbnRfaWQgfHwgJycgKTtcblx0XHRcdFx0XHRjaGlsZHJlbl9ieV9wYXJlbnRbIHBhcmVudF9pZCBdID0gY2hpbGRyZW5fYnlfcGFyZW50WyBwYXJlbnRfaWQgXSB8fCBbXTtcblx0XHRcdFx0XHRjaGlsZHJlbl9ieV9wYXJlbnRbIHBhcmVudF9pZCBdLnB1c2goIHJlc291cmNlICk7XG5cdFx0XHRcdH1cblx0XHRcdH0gKTtcblx0XHR9XG5cdFx0cmVzcG9uc2UuaXRlbXMuZm9yRWFjaCggZnVuY3Rpb24gKCByZXNvdXJjZSApIHtcblx0XHRcdHZhciBhY3Rpb25fdGFyZ2V0O1xuXHRcdFx0dmFyIGNsYXNzaWNfbGFiZWxfY2xhc3NlcyA9IHtcblx0XHRcdFx0Y2hpbGQ6ICd3cGJjX2xhYmVsX3Jlc291cmNlX2NoaWxkJyxcblx0XHRcdFx0Y29zdDogJ3dwYmNfbGFiZWxfY29zdCcsXG5cdFx0XHRcdCdkZWZhdWx0LWZvcm0nOiAnd3BiY19sYWJlbF9yZXNvdXJjZV9kZWZhdWx0X2Zvcm0nLFxuXHRcdFx0XHRvd25lcjogJ3dwYmNfbGFiZWxfdXNlcl9vd25lcicsXG5cdFx0XHRcdHBhcmVudDogJ3dwYmNfbGFiZWxfcmVzb3VyY2VfcGFyZW50Jyxcblx0XHRcdFx0c2luZ2xlOiAnd3BiY19sYWJlbF9yZXNvdXJjZV9zaW5nbGUnXG5cdFx0XHR9O1xuXHRcdFx0dmFyIGxhYmVsX3RhcmdldDtcblx0XHRcdHZhciBwcmljZV90YXJnZXQ7XG5cdFx0XHR2YXIgZGVzY3JpcHRpb24gPSByZXNvdXJjZS5kZXNjcmlwdGlvbiB8fCBjb25maWcuaTE4bi5ub19kZXNjcmlwdGlvbiB8fCAnJztcblx0XHRcdHZhciBoaWVyYXJjaHkgPSBPYmplY3QuYXNzaWduKCB7fSwgcmVzb3VyY2UuaGllcmFyY2h5IHx8IHt9ICk7XG5cdFx0XHR2YXIgcGFyZW50X2NvbnRleHRfbGFiZWwgPSAnJztcblx0XHRcdHZhciByb3dfdmFyaWFudCA9IGhpZXJhcmNoeV9lbmFibGVkICYmICggJ3BhcmVudCcgPT09IGhpZXJhcmNoeS50eXBlIHx8ICdjaGlsZCcgPT09IGhpZXJhcmNoeS50eXBlICkgPyBoaWVyYXJjaHkudHlwZSA6ICdzaW5nbGUnO1xuXHRcdFx0dmFyIHJvd190ZW1wbGF0ZV9yb2xlID0gJ3BhcmVudCcgPT09IHJvd192YXJpYW50ID8gJ3BhcmVudF9yb3cnIDogKCAnY2hpbGQnID09PSByb3dfdmFyaWFudCA/ICdjaGlsZF9yb3cnIDogJ3JvdycgKTtcblx0XHRcdHZhciB0eXBlX2JhZGdlX2xhYmVsID0gY29uZmlnLmkxOG4uaW5kZXBlbmRlbnRfbGFiZWwgfHwgJyc7XG5cdFx0XHRoaWVyYXJjaHkuZXhwYW5kYWJsZSA9ICEhICggaGllcmFyY2h5X2VuYWJsZWQgJiYgaGllcmFyY2h5LmV4cGFuZGFibGUgKTtcblx0XHRcdGlmICggaGllcmFyY2h5LnBhcmVudF90aXRsZSApIHtcblx0XHRcdFx0cGFyZW50X2NvbnRleHRfbGFiZWwgPSBmb3JtYXRfbWVzc2FnZSggY29uZmlnLmkxOG4uY2hpbGRfb2YgfHwgJycsIFsgaGllcmFyY2h5LnBhcmVudF90aXRsZSBdICk7XG5cdFx0XHR9XG5cdFx0XHRpZiAoICdwYXJlbnQnID09PSByb3dfdmFyaWFudCApIHtcblx0XHRcdFx0dmFyIHJlbmRlcmVkX2NoaWxkX2NvdW50ID0gTWF0aC5tYXgoIDAsIE51bWJlciggaGllcmFyY2h5LnJlbmRlcmVkX2NoaWxkcmVuX2NvdW50ICkgfHwgMCApO1xuXHRcdFx0XHR2YXIgcGFyZW50X2NoaWxkcmVuX3RlbXBsYXRlID0gMSA9PT0gcmVuZGVyZWRfY2hpbGRfY291bnRcblx0XHRcdFx0XHQ/IGNvbmZpZy5pMThuLnBhcmVudF9jaGlsZF9sYWJlbCB8fCAnJTEkcyDCtyAlMiRzIGNoaWxkJ1xuXHRcdFx0XHRcdDogY29uZmlnLmkxOG4ucGFyZW50X2NoaWxkcmVuX2xhYmVsIHx8ICclMSRzIMK3ICUyJHMgY2hpbGRyZW4nO1xuXHRcdFx0XHR0eXBlX2JhZGdlX2xhYmVsID0gZm9ybWF0X21lc3NhZ2UoIHBhcmVudF9jaGlsZHJlbl90ZW1wbGF0ZSwgW1xuXHRcdFx0XHRcdGNvbmZpZy5pMThuLnBhcmVudF9sYWJlbCB8fCAnJyxcblx0XHRcdFx0XHRyZW5kZXJlZF9jaGlsZF9jb3VudFxuXHRcdFx0XHRdICk7XG5cdFx0XHR9IGVsc2UgaWYgKCAnY2hpbGQnID09PSByb3dfdmFyaWFudCApIHtcblx0XHRcdFx0dHlwZV9iYWRnZV9sYWJlbCA9IGNvbmZpZy5pMThuLmNoaWxkX2xhYmVsIHx8ICcnO1xuXHRcdFx0fVxuXHRcdFx0dmFyIHJlc291cmNlX3Jvd19kYXRhID0gT2JqZWN0LmFzc2lnbigge30sIHJlc291cmNlLCB7XG5cdFx0XHRcdHBhcmVudF9jb250ZXh0X2xhYmVsOiBwYXJlbnRfY29udGV4dF9sYWJlbCxcblx0XHRcdFx0Y29sbGFwc2VfbGFiZWw6IGZvcm1hdF9tZXNzYWdlKCBjb25maWcuaTE4bi5jb2xsYXBzZV9jaGlsZHJlbl9mb3IgfHwgY29uZmlnLmkxOG4uY29sbGFwc2VfY2hpbGRyZW4gfHwgJycsIFsgcmVzb3VyY2UudGl0bGUgfHwgJycgXSApLFxuXHRcdFx0XHRjb2x1bW5zOiBjb2x1bW5zLFxuXHRcdFx0XHRleHBhbmRfbGFiZWw6IGZvcm1hdF9tZXNzYWdlKCBjb25maWcuaTE4bi5leHBhbmRfY2hpbGRyZW5fZm9yIHx8IGNvbmZpZy5pMThuLmV4cGFuZF9jaGlsZHJlbiB8fCAnJywgWyByZXNvdXJjZS50aXRsZSB8fCAnJyBdICksXG5cdFx0XHRcdGhpZXJhcmNoeTogaGllcmFyY2h5LFxuXHRcdFx0XHRpMThuOiBjb25maWcuaTE4biB8fCB7fSxcblx0XHRcdFx0aXNfZXhwYW5kZWQ6IGhpZXJhcmNoeV9pc19leHBhbmRlZCxcblx0XHRcdFx0cGFyZW50X2xhYmVsOiBjb25maWcuaTE4bi5wYXJlbnRfbGFiZWwgfHwgJycsXG5cdFx0XHRcdHJvd192YXJpYW50OiByb3dfdmFyaWFudCxcblx0XHRcdFx0c2VsZWN0aW9uX2xhYmVsOiBmb3JtYXRfbWVzc2FnZSggY29uZmlnLmkxOG4uc2VsZWN0X3Jlc291cmNlIHx8ICcnLCBbIHJlc291cmNlLnRpdGxlIHx8ICcnIF0gKSxcblx0XHRcdFx0c2VsZWN0aW9uX2VuYWJsZWQ6ICEhICggY29uZmlnLmZlYXR1cmVzICYmIGNvbmZpZy5mZWF0dXJlcy5zZWxlY3Rpb24gKSxcblx0XHRcdFx0dGh1bWJuYWlsX2xhYmVsOiBmb3JtYXRfbWVzc2FnZSggY29uZmlnLmkxOG4udGh1bWJuYWlsX3Rvb2x0aXAgfHwgJycsIFsgcmVzb3VyY2UudGl0bGUgfHwgJycsIGRlc2NyaXB0aW9uIF0gKSxcblx0XHRcdFx0dHlwZV9iYWRnZV9sYWJlbDogdHlwZV9iYWRnZV9sYWJlbFxuXHRcdFx0fSApO1xuXHRcdFx0dmFyIHJlc291cmNlX3Jvd19odG1sID0gcmVuZGVyX2NvbXBvbmVudCggY29uZmlnLCByb3dfdGVtcGxhdGVfcm9sZSwgcmVzb3VyY2Vfcm93X2RhdGEgKTtcblx0XHRcdHZhciByZXNvdXJjZV9yb3c7XG5cblx0XHRcdGlmICggISByZXNvdXJjZV9yb3dfaHRtbCApIHtcblx0XHRcdFx0cmV0dXJuO1xuXHRcdFx0fVxuXG5cdFx0XHRpZiAoIGlzX2NhcmRzX3BhY2sgJiYgJ3BhcmVudCcgPT09IHJvd192YXJpYW50ICkge1xuXHRcdFx0XHR2YXIgY2hpbGRfcmVzb3VyY2VzID0gY2hpbGRyZW5fYnlfcGFyZW50WyBTdHJpbmcoIHJlc291cmNlLmlkICkgXSB8fCBbXTtcblx0XHRcdFx0dmFyIGNoaWxkX2NvdW50ID0gTWF0aC5tYXgoIGNoaWxkX3Jlc291cmNlcy5sZW5ndGgsIE51bWJlciggaGllcmFyY2h5LnJlbmRlcmVkX2NoaWxkcmVuX2NvdW50ICkgfHwgMCApO1xuXHRcdFx0XHR2YXIgY2FyZF9ncm91cF9odG1sID0gcmVuZGVyX2NvbXBvbmVudCggY29uZmlnLCAnY2FyZF9ncm91cCcsIHtcblx0XHRcdFx0XHRjaGlsZHJlbl9kZXNjcmlwdGlvbjogZm9ybWF0X21lc3NhZ2UoIGNvbmZpZy5pMThuLmNoaWxkcmVuX2JlbG9uZ190byB8fCAnJywgWyByZXNvdXJjZS50aXRsZSB8fCAnJyBdICksXG5cdFx0XHRcdFx0Y2hpbGRyZW5faGVhZGluZzogZm9ybWF0X21lc3NhZ2UoIGNvbmZpZy5pMThuLmNoaWxkcmVuX29mX2NvdW50IHx8ICcnLCBbIHJlc291cmNlLnRpdGxlIHx8ICcnLCBjaGlsZF9jb3VudCBdICksXG5cdFx0XHRcdFx0Y29sbGFwc2VfbGFiZWw6IHJlc291cmNlX3Jvd19kYXRhLmNvbGxhcHNlX2xhYmVsLFxuXHRcdFx0XHRcdGV4cGFuZF9sYWJlbDogcmVzb3VyY2Vfcm93X2RhdGEuZXhwYW5kX2xhYmVsLFxuXHRcdFx0XHRcdGlzX2V4cGFuZGVkOiBoaWVyYXJjaHlfaXNfZXhwYW5kZWQsXG5cdFx0XHRcdFx0cGFyZW50X2lkOiByZXNvdXJjZS5pZCxcblx0XHRcdFx0XHRwYXJlbnRfbm9kZV9pZDogaGllcmFyY2h5Lm5vZGVfaWQsXG5cdFx0XHRcdFx0c3RhY2tfaXRlbXM6IGNoaWxkX3Jlc291cmNlcy5zbGljZSggMCwgMyApXG5cdFx0XHRcdH0gKTtcblx0XHRcdFx0aWYgKCAhIGNhcmRfZ3JvdXBfaHRtbCApIHtcblx0XHRcdFx0XHRyZXR1cm47XG5cdFx0XHRcdH1cblx0XHRcdFx0cm93c19lbGVtZW50Lmluc2VydEFkamFjZW50SFRNTCggJ2JlZm9yZWVuZCcsIGNhcmRfZ3JvdXBfaHRtbCApO1xuXHRcdFx0XHRjYXJkX2dyb3Vwc1sgU3RyaW5nKCByZXNvdXJjZS5pZCApIF0gPSByb3dzX2VsZW1lbnQubGFzdEVsZW1lbnRDaGlsZDtcblx0XHRcdFx0dmFyIHBhcmVudF9zbG90ID0gY2FyZF9ncm91cHNbIFN0cmluZyggcmVzb3VyY2UuaWQgKSBdLnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLWJvb2tpbmctcmVzb3VyY2UtY2FyZC1wYXJlbnQtc2xvdF0nICk7XG5cdFx0XHRcdHBhcmVudF9zbG90Lmluc2VydEFkamFjZW50SFRNTCggJ2JlZm9yZWVuZCcsIHJlc291cmNlX3Jvd19odG1sICk7XG5cdFx0XHRcdHJlc291cmNlX3JvdyA9IHBhcmVudF9zbG90Lmxhc3RFbGVtZW50Q2hpbGQ7XG5cdFx0XHR9IGVsc2UgaWYgKCBpc19jYXJkc19wYWNrICYmICdjaGlsZCcgPT09IHJvd192YXJpYW50ICYmIGNhcmRfZ3JvdXBzWyBTdHJpbmcoIGhpZXJhcmNoeS5wYXJlbnRfaWQgKSBdICkge1xuXHRcdFx0XHR2YXIgY2hpbGRyZW5fc2xvdCA9IGNhcmRfZ3JvdXBzWyBTdHJpbmcoIGhpZXJhcmNoeS5wYXJlbnRfaWQgKSBdLnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLWJvb2tpbmctcmVzb3VyY2UtY2FyZC1jaGlsZHJlbi1zbG90XScgKTtcblx0XHRcdFx0Y2hpbGRyZW5fc2xvdC5pbnNlcnRBZGphY2VudEhUTUwoICdiZWZvcmVlbmQnLCByZXNvdXJjZV9yb3dfaHRtbCApO1xuXHRcdFx0XHRyZXNvdXJjZV9yb3cgPSBjaGlsZHJlbl9zbG90Lmxhc3RFbGVtZW50Q2hpbGQ7XG5cdFx0XHR9IGVsc2Uge1xuXHRcdFx0XHRyb3dzX2VsZW1lbnQuaW5zZXJ0QWRqYWNlbnRIVE1MKCAnYmVmb3JlZW5kJywgcmVzb3VyY2Vfcm93X2h0bWwgKTtcblx0XHRcdFx0cmVzb3VyY2Vfcm93ID0gcm93c19lbGVtZW50Lmxhc3RFbGVtZW50Q2hpbGQ7XG5cdFx0XHR9XG5cdFx0XHRpZiAoICEgcmVzb3VyY2Vfcm93ICkge1xuXHRcdFx0XHRyZXR1cm47XG5cdFx0XHR9XG5cblx0XHRcdGxhYmVsX3RhcmdldCA9IHJlc291cmNlX3Jvdy5xdWVyeVNlbGVjdG9yKCAnW2RhdGEtd3BiYy1ib29raW5nLXJlc291cmNlLWxhYmVsc10nICk7XG5cdFx0XHRwcmljZV90YXJnZXQgPSByZXNvdXJjZV9yb3cucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtYm9va2luZy1yZXNvdXJjZS1wcmljZV0nICk7XG5cdFx0XHRhY3Rpb25fdGFyZ2V0ID0gcmVzb3VyY2Vfcm93LnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLWJvb2tpbmctcmVzb3VyY2UtYWN0aW9uc10nICk7XG5cdFx0XHRpZiAoIGxhYmVsX3RhcmdldCApIHtcblx0XHRcdFx0bGFiZWxfdGFyZ2V0LmlubmVySFRNTCA9IHJlbmRlcl9jb21wb25lbnQoIGNvbmZpZywgJ2xhYmVscycsIHtcblx0XHRcdFx0XHRhcmlhX2xhYmVsOiBjb25maWcuaTE4bi5jb2x1bW5fbGFiZWxzIHx8ICcnLFxuXHRcdFx0XHRcdGVtcHR5X2xhYmVsOiBjb25maWcuaTE4bi5ub19sYWJlbHMgfHwgJycsXG5cdFx0XHRcdFx0bGFiZWxzOiBBcnJheS5pc0FycmF5KCByZXNvdXJjZS5sYWJlbHMgKSA/IHJlc291cmNlLmxhYmVscy5tYXAoIGZ1bmN0aW9uICggbGFiZWwgKSB7XG5cdFx0XHRcdFx0XHRyZXR1cm4gT2JqZWN0LmFzc2lnbigge30sIGxhYmVsLCB7IGNsYXNzX25hbWU6IGNsYXNzaWNfbGFiZWxfY2xhc3Nlc1sgbGFiZWwua2luZCBdIHx8ICcnIH0gKTtcblx0XHRcdFx0XHR9ICkgOiBbXVxuXHRcdFx0XHR9ICk7XG5cdFx0XHR9XG5cdFx0XHRpZiAoIHByaWNlX3RhcmdldCApIHtcblx0XHRcdFx0cHJpY2VfdGFyZ2V0LmlubmVySFRNTCA9IHJlbmRlcl9jb21wb25lbnQoIGNvbmZpZywgJ3ByaWNlJywge1xuXHRcdFx0XHRcdGVtcHR5X2xhYmVsOiBjb25maWcuaTE4bi5wcmljZV91bmF2YWlsYWJsZSB8fCAnJyxcblx0XHRcdFx0XHRwcmljZTogcmVzb3VyY2UucHJpY2UgfHwge31cblx0XHRcdFx0fSApO1xuXHRcdFx0fVxuXHRcdFx0aWYgKCBhY3Rpb25fdGFyZ2V0ICkge1xuXHRcdFx0XHRhY3Rpb25fdGFyZ2V0LmlubmVySFRNTCA9IHJlbmRlcl9jb21wb25lbnQoIGNvbmZpZywgJ2FjdGlvbl9tZW51Jywge1xuXHRcdFx0XHRcdGFjdGlvbnM6IEFycmF5LmlzQXJyYXkoIHJlc291cmNlLmFjdGlvbl9pdGVtcyApID8gcmVzb3VyY2UuYWN0aW9uX2l0ZW1zLm1hcCggZnVuY3Rpb24gKCBhY3Rpb24gKSB7XG5cdFx0XHRcdFx0XHR2YXIgYWN0aW9uX2NsYXNzZXMgPSB7IGFkanVzdF9jYXBhY2l0eTogJ2NhcGFjaXR5JywgZGVsZXRlX3Jlc291cmNlOiAnZGVsZXRlJywgZWRpdF9yZXNvdXJjZTogJ2VkaXQnLCBwdWJsaXNoX3Jlc291cmNlOiAncHVibGlzaCcgfTtcblx0XHRcdFx0XHRcdHZhciBhY3Rpb25faWQgPSBTdHJpbmcoIGFjdGlvbi5pZCB8fCAnJyApO1xuXHRcdFx0XHRcdFx0cmV0dXJuIE9iamVjdC5hc3NpZ24oIHt9LCBhY3Rpb24sIHsgY2xhc3NfbmFtZTogJ3dwYmNfYm9va2luZ19yZXNvdXJjZXNfX2FjdGlvbl8nICsgKCBhY3Rpb25fY2xhc3Nlc1sgYWN0aW9uX2lkIF0gfHwgYWN0aW9uX2lkICkgfSApO1xuXHRcdFx0XHRcdH0gKSA6IFtdLFxuXHRcdFx0XHRcdGFyaWFfbGFiZWw6IGZvcm1hdF9tZXNzYWdlKCBjb25maWcuaTE4bi5hY3Rpb25zX2ZvciB8fCAnJywgWyByZXNvdXJjZS50aXRsZSB8fCAnJyBdICksXG5cdFx0XHRcdFx0ZW1wdHlfbGFiZWw6IGNvbmZpZy5pMThuLm5vX2FjdGlvbnMgfHwgJycsXG5cdFx0XHRcdFx0bWVudV9pZDogJ3dwYmNfJyArIGNvbmZpZy5pZCArICdfYWN0aW9uc18nICsgU3RyaW5nKCByZXNvdXJjZS5pZCApLFxuXHRcdFx0XHRcdHJlc291cmNlX2lkOiByZXNvdXJjZS5pZFxuXHRcdFx0XHR9ICk7XG5cdFx0XHR9XG5cdFx0XHRpZiAoIGhpZXJhcmNoeV9lbmFibGVkICYmICdjaGlsZCcgPT09IHJvd192YXJpYW50ICYmIGhpZXJhcmNoeS5pc19sYXN0X3NpYmxpbmcgKSB7XG5cdFx0XHRcdHZhciBwYXJlbnRfcmVzb3VyY2UgPSBwYXJlbnRfcmVzb3VyY2VzWyBTdHJpbmcoIGhpZXJhcmNoeS5wYXJlbnRfaWQgKSBdO1xuXHRcdFx0XHRpZiAoIHBhcmVudF9yZXNvdXJjZSApIHtcblx0XHRcdFx0XHR2YXIgc3VtbWFyeV90YXJnZXQgPSBpc19jYXJkc19wYWNrICYmIGNhcmRfZ3JvdXBzWyBTdHJpbmcoIGhpZXJhcmNoeS5wYXJlbnRfaWQgKSBdXG5cdFx0XHRcdFx0XHQ/IGNhcmRfZ3JvdXBzWyBTdHJpbmcoIGhpZXJhcmNoeS5wYXJlbnRfaWQgKSBdLnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLWJvb2tpbmctcmVzb3VyY2UtY2FyZC1wYXJlbnQtc2xvdF0nIClcblx0XHRcdFx0XHRcdDogcm93c19lbGVtZW50O1xuXHRcdFx0XHRcdHN1bW1hcnlfdGFyZ2V0Lmluc2VydEFkamFjZW50SFRNTCggJ2JlZm9yZWVuZCcsIHJlbmRlcl9jb21wb25lbnQoIGNvbmZpZywgJ2NoaWxkX3N1bW1hcnknLCB7XG5cdFx0XHRcdFx0XHRjaGlsZHJlbl9sYWJlbDogZ2V0X2NoaWxkcmVuX3N1bW1hcnlfbGFiZWwoIHBhcmVudF9yZXNvdXJjZSwgY29uZmlnLmkxOG4gfHwge30gKSxcblx0XHRcdFx0XHRcdGNvbGxhcHNlX2xhYmVsOiBmb3JtYXRfbWVzc2FnZSggY29uZmlnLmkxOG4uY29sbGFwc2VfY2hpbGRyZW5fZm9yIHx8IGNvbmZpZy5pMThuLmNvbGxhcHNlX2NoaWxkcmVuIHx8ICcnLCBbIHBhcmVudF9yZXNvdXJjZS50aXRsZSB8fCAnJyBdICksXG5cdFx0XHRcdFx0XHRjb2x1bW5zOiBjb2x1bW5zLFxuXHRcdFx0XHRcdFx0ZXhwYW5kX2xhYmVsOiBmb3JtYXRfbWVzc2FnZSggY29uZmlnLmkxOG4uZXhwYW5kX2NoaWxkcmVuX2ZvciB8fCBjb25maWcuaTE4bi5leHBhbmRfY2hpbGRyZW4gfHwgJycsIFsgcGFyZW50X3Jlc291cmNlLnRpdGxlIHx8ICcnIF0gKSxcblx0XHRcdFx0XHRcdGlzX2V4cGFuZGVkOiBoaWVyYXJjaHlfaXNfZXhwYW5kZWQsXG5cdFx0XHRcdFx0XHRwYXJlbnRfaWQ6IGhpZXJhcmNoeS5wYXJlbnRfaWQsXG5cdFx0XHRcdFx0XHRwYXJlbnRfbm9kZV9pZDogcGFyZW50X3Jlc291cmNlLmhpZXJhcmNoeS5ub2RlX2lkLFxuXHRcdFx0XHRcdFx0c2VsZWN0aW9uX2VuYWJsZWQ6ICEhICggY29uZmlnLmZlYXR1cmVzICYmIGNvbmZpZy5mZWF0dXJlcy5zZWxlY3Rpb24gKVxuXHRcdFx0XHRcdH0gKSApO1xuXHRcdFx0XHR9XG5cdFx0XHR9XG5cdFx0fSApO1xuXHRcdHN5bmNocm9uaXplX292ZXJmbG93X3Rvb2x0aXBzKCBjYXRhbG9nX21vdW50ICk7XG5cblx0XHRwYWdpbmF0aW9uID0gcmVzcG9uc2UucGFnaW5hdGlvbiB8fCB7fTtcblx0XHRwYWdpbmF0aW9uX2VsZW1lbnQuaW5uZXJIVE1MID0gcmVuZGVyX2NvbXBvbmVudCggY29uZmlnLCAncGFnaW5hdGlvbicsIHtcblx0XHRcdGFyaWFfbGFiZWw6IGNvbmZpZy5pMThuLnBhZ2luYXRpb25fbGFiZWwgfHwgJycsXG5cdFx0XHRoYXNfbmV4dDogTnVtYmVyKCBwYWdpbmF0aW9uLnBhZ2VfbnVtYmVyICkgPCBOdW1iZXIoIHBhZ2luYXRpb24udG90YWxfcGFnZXMgKSxcblx0XHRcdGhhc19wcmV2aW91czogMSA8IE51bWJlciggcGFnaW5hdGlvbi5wYWdlX251bWJlciApLFxuXHRcdFx0aXRlbXNfcGVyX3BhZ2U6IE51bWJlciggcGFnaW5hdGlvbi5pdGVtc19wZXJfcGFnZSApLFxuXHRcdFx0aXRlbXNfcGVyX3BhZ2Vfb3B0aW9uczogY29uZmlnLml0ZW1zX3Blcl9wYWdlICYmIEFycmF5LmlzQXJyYXkoIGNvbmZpZy5pdGVtc19wZXJfcGFnZS5vcHRpb25zICkgPyBjb25maWcuaXRlbXNfcGVyX3BhZ2Uub3B0aW9ucyA6IFtdLFxuXHRcdFx0bmV4dF9sYWJlbDogY29uZmlnLmkxOG4ubmV4dF9wYWdlIHx8ICcnLFxuXHRcdFx0bmV4dF9wYWdlOiBNYXRoLm1pbiggTnVtYmVyKCBwYWdpbmF0aW9uLnRvdGFsX3BhZ2VzICksIE51bWJlciggcGFnaW5hdGlvbi5wYWdlX251bWJlciApICsgMSApLFxuXHRcdFx0cGFnZV9udW1iZXI6IE51bWJlciggcGFnaW5hdGlvbi5wYWdlX251bWJlciApLFxuXHRcdFx0cGFnZV9udW1iZXJfbGFiZWw6IGNvbmZpZy5pMThuLnBhZ2VfbnVtYmVyIHx8ICcnLFxuXHRcdFx0cGVyX3BhZ2VfbGFiZWw6IGNvbmZpZy5pMThuLnBlcl9wYWdlIHx8ICcnLFxuXHRcdFx0cHJldmlvdXNfbGFiZWw6IGNvbmZpZy5pMThuLnByZXZpb3VzX3BhZ2UgfHwgJycsXG5cdFx0XHRwcmV2aW91c19wYWdlOiBNYXRoLm1heCggMSwgTnVtYmVyKCBwYWdpbmF0aW9uLnBhZ2VfbnVtYmVyICkgLSAxICksXG5cdFx0XHRyZXN1bHRzX3N0YXR1czogZm9ybWF0X21lc3NhZ2UoIGNvbmZpZy5pMThuLnJlc3VsdHNfc3RhdHVzIHx8ICcnLCBbIHBhZ2luYXRpb24uaXRlbXNfZnJvbSwgcGFnaW5hdGlvbi5pdGVtc190bywgcGFnaW5hdGlvbi50b3RhbF9pdGVtcyBdICksXG5cdFx0XHRzaG93X2xhYmVsOiBjb25maWcuaTE4bi5zaG93IHx8ICcnLFxuXHRcdFx0dG90YWxfcGFnZXM6IE1hdGgubWF4KCAxLCBOdW1iZXIoIHBhZ2luYXRpb24udG90YWxfcGFnZXMgKSApXG5cdFx0fSApO1xuXHRcdHN5bmNocm9uaXplX2Jvb2tpbmdfcmVzb3VyY2VzX3Rvb2xiYXIoIGNvbmZpZywgcmVzcG9uc2UgKTtcblxuXHRcdGlmICggcGVuZGluZ19mb2N1c19kaXJlY3Rpb24gKSB7XG5cdFx0XHRjYXRhbG9nX2hlYWRpbmcgPSBjYXRhbG9nX21vdW50LnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLWNhdGFsb2ctaGVhZGluZ10nICk7XG5cdFx0XHRwZW5kaW5nX2ZvY3VzX2RpcmVjdGlvbiA9ICcnO1xuXHRcdFx0aWYgKCBjYXRhbG9nX2hlYWRpbmcgJiYgJ2Z1bmN0aW9uJyA9PT0gdHlwZW9mIGNhdGFsb2dfaGVhZGluZy5mb2N1cyApIHtcblx0XHRcdFx0Y2F0YWxvZ19oZWFkaW5nLmZvY3VzKCk7XG5cdFx0XHR9XG5cdFx0fVxuXG5cdFx0cmV0dXJuIHJvd3NfZWxlbWVudC5xdWVyeVNlbGVjdG9yQWxsKCAnW2RhdGEtd3BiYy11aS1jYXRhbG9nLXNlbGVjdGFibGUtcm93XVtkYXRhLXdwYmMtYm9va2luZy1yZXNvdXJjZS1pZF0nICkubGVuZ3RoID09PSByZXNwb25zZS5pdGVtcy5sZW5ndGg7XG5cdH1cblxuXHQvKipcblx0ICogUmV0dXJuIHRoZSBudW1iZXIgb2YgY3VycmVudGx5IHZpc2libGUgY2VsbHMgaW4gb25lIFJlc291cmNlIHJvdy5cblx0ICpcblx0ICogQHBhcmFtIHtIVE1MRWxlbWVudH0gcmVzb3VyY2Vfcm93IFJlbmRlcmVkIFJlc291cmNlIHRhYmxlIHJvdy5cblx0ICogQHJldHVybiB7bnVtYmVyfSBTYWZlIGRldGFpbHMtcm93IGNvbHNwYW4uXG5cdCAqL1xuXHRmdW5jdGlvbiBnZXRfZGV0YWlsc19jb2xzcGFuKCByZXNvdXJjZV9yb3cgKSB7XG5cdFx0dmFyIHZpc2libGVfY2VsbHMgPSAwO1xuXG5cdFx0aWYgKCByZXNvdXJjZV9yb3cgJiYgJ1RSJyA9PT0gcmVzb3VyY2Vfcm93LnRhZ05hbWUgKSB7XG5cdFx0XHRBcnJheS5wcm90b3R5cGUuZm9yRWFjaC5jYWxsKCByZXNvdXJjZV9yb3cuY2VsbHMgfHwgW10sIGZ1bmN0aW9uICggY2VsbCApIHtcblx0XHRcdFx0aWYgKCAhIGNlbGwuaGlkZGVuICYmICdub25lJyAhPT0gd2luZG93LmdldENvbXB1dGVkU3R5bGUoIGNlbGwgKS5kaXNwbGF5ICkge1xuXHRcdFx0XHRcdHZpc2libGVfY2VsbHMgKz0gMTtcblx0XHRcdFx0fVxuXHRcdFx0fSApO1xuXHRcdH1cblxuXHRcdHJldHVybiBNYXRoLm1heCggMSwgdmlzaWJsZV9jZWxscyApO1xuXHR9XG5cblx0LyoqXG5cdCAqIFJlc29sdmUgdGhlIHJlbmRlcmVkIFJlc291cmNlIGNvbnRhaW5lciB0aGF0IG93bnMgYW4gaW50ZXJhY3RpdmUgY29udHJvbC5cblx0ICpcblx0ICogUmVzb3VyY2UgY29udHJvbHMgcmVwZWF0IHRoZSBSZXNvdXJjZSBJRCBmb3IgZXZlbnQgZGlzcGF0Y2guIExpbWl0aW5nIHRoaXNcblx0ICogbG9va3VwIHRvIHNlbGVjdGFibGUgcm93L2NhcmQgY29udGFpbmVycyBwcmV2ZW50cyBsYXp5IHRlbXBsYXRlcyBmcm9tIGJlaW5nXG5cdCAqIGluc2VydGVkIGJlc2lkZSB0aGUgY29udHJvbCBpdHNlbGYuXG5cdCAqXG5cdCAqIEBwYXJhbSB7RWxlbWVudHxudWxsfSBzb3VyY2VfZWxlbWVudCBFbGVtZW50IGluc2lkZSBhIFJlc291cmNlIHJvdyBvciBjYXJkLlxuXHQgKiBAcmV0dXJuIHtIVE1MRWxlbWVudHxudWxsfSBPd25pbmcgUmVzb3VyY2Ugcm93L2NhcmQsIG9yIG51bGwgd2hlbiB1bmF2YWlsYWJsZS5cblx0ICovXG5cdGZ1bmN0aW9uIGdldF9yZXNvdXJjZV9pdGVtX2NvbnRhaW5lciggc291cmNlX2VsZW1lbnQgKSB7XG5cdFx0aWYgKCAhIHNvdXJjZV9lbGVtZW50IHx8ICdmdW5jdGlvbicgIT09IHR5cGVvZiBzb3VyY2VfZWxlbWVudC5jbG9zZXN0ICkge1xuXHRcdFx0cmV0dXJuIG51bGw7XG5cdFx0fVxuXG5cdFx0cmV0dXJuIHNvdXJjZV9lbGVtZW50LmNsb3Nlc3QoICdbZGF0YS13cGJjLXVpLWNhdGFsb2ctc2VsZWN0YWJsZS1yb3ddW2RhdGEtd3BiYy1ib29raW5nLXJlc291cmNlLWlkXScgKTtcblx0fVxuXG5cdC8qKlxuXHQgKiBTeW5jaHJvbml6ZSBDYXJkcy1wYWNrIHBhcmVudCBzdGFnZXMgd2l0aCBzaGFyZWQgaGllcmFyY2h5IHZpc2liaWxpdHkuXG5cdCAqXG5cdCAqIENoaWxkIGNhcmRzIHJlbWFpbiBub3JtYWwgc2hhcmVkIGhpZXJhcmNoeSBub2Rlcy4gVGhpcyBhZGFwdGVyIG9ubHkgbWlycm9yc1xuXHQgKiB0aGVpciBjb21wdXRlZCB2aXNpYmlsaXR5IG9udG8gdGhlIHByZXNlbnRhdGlvbi1vbmx5IHRyYXkgd3JhcHBlci5cblx0ICpcblx0ICogQHBhcmFtIHtIVE1MRWxlbWVudH0gY2F0YWxvZ19tb3VudCBNb3VudGVkIEJvb2tpbmcgUmVzb3VyY2VzIGNhdGFsb2cuXG5cdCAqIEByZXR1cm4ge3ZvaWR9XG5cdCAqL1xuXHRmdW5jdGlvbiBzeW5jaHJvbml6ZV9jYXJkX2dyb3VwX3BhbmVscyggY2F0YWxvZ19tb3VudCApIHtcblx0XHR2YXIgY2F0YWxvZ19yb290O1xuXG5cdFx0aWYgKCAhIGNhdGFsb2dfbW91bnQgKSB7XG5cdFx0XHRyZXR1cm47XG5cdFx0fVxuXG5cdFx0Y2F0YWxvZ19yb290ID0gY2F0YWxvZ19tb3VudC5oYXNBdHRyaWJ1dGUoICdkYXRhLXdwYmMtY2F0YWxvZy1pZCcgKVxuXHRcdFx0PyBjYXRhbG9nX21vdW50XG5cdFx0XHQ6IGNhdGFsb2dfbW91bnQucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtY2F0YWxvZy1pZF0nICk7XG5cblx0XHQvLyBUaGUgc2hhcmVkIGNvbnRyb2xsZXIgb3ducyB0aGUgYWN0aXZlLXBhY2sgYXR0cmlidXRlIG9uIHRoZSBpbm5lciBjYXRhbG9nIHJvb3QuXG5cdFx0aWYgKCAhIGNhdGFsb2dfcm9vdCB8fCAnY2FyZHMnICE9PSBjYXRhbG9nX3Jvb3QuZ2V0QXR0cmlidXRlKCAnZGF0YS13cGJjLXRlbXBsYXRlLXBhY2snICkgKSB7XG5cdFx0XHRyZXR1cm47XG5cdFx0fVxuXG5cdFx0Y2F0YWxvZ19yb290LnF1ZXJ5U2VsZWN0b3JBbGwoICdbZGF0YS13cGJjLWJvb2tpbmctcmVzb3VyY2UtY2FyZC1ncm91cF0nICkuZm9yRWFjaCggZnVuY3Rpb24gKCBjYXJkX2dyb3VwICkge1xuXHRcdFx0dmFyIGNoaWxkcmVuX3BhbmVsID0gY2FyZF9ncm91cC5xdWVyeVNlbGVjdG9yKCAnW2RhdGEtd3BiYy1ib29raW5nLXJlc291cmNlLWNhcmQtY2hpbGRyZW4tcGFuZWxdJyApO1xuXHRcdFx0dmFyIHZpc2libGVfY2hpbGQgPSBjYXJkX2dyb3VwLnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLWJvb2tpbmctcmVzb3VyY2UtY2FyZC1jaGlsZHJlbi1zbG90XSBbZGF0YS13cGJjLXVpLWNhdGFsb2ctcGFyZW50LW5vZGUtaWRdOm5vdChbaGlkZGVuXSknICk7XG5cblx0XHRcdGlmICggY2hpbGRyZW5fcGFuZWwgKSB7XG5cdFx0XHRcdGNoaWxkcmVuX3BhbmVsLmhpZGRlbiA9ICEgdmlzaWJsZV9jaGlsZDtcblx0XHRcdFx0Y2FyZF9ncm91cC5jbGFzc0xpc3QudG9nZ2xlKCAnaXMtZXhwYW5kZWQnLCAhISB2aXNpYmxlX2NoaWxkICk7XG5cdFx0XHR9XG5cdFx0fSApO1xuXHR9XG5cblx0LyoqXG5cdCAqIFN5bmNocm9uaXplIG9uZSBkZXRhaWxzIGRpc2Nsb3N1cmUgYnV0dG9uLlxuXHQgKlxuXHQgKiBAcGFyYW0ge0hUTUxFbGVtZW50fG51bGx9IHRvZ2dsZV9idXR0b24gRGlzY2xvc3VyZSBidXR0b24uXG5cdCAqIEBwYXJhbSB7Ym9vbGVhbn0gICAgICAgICAgaXNfZXhwYW5kZWQgICBXaGV0aGVyIGl0cyBkZXRhaWxzIHJvdyBpcyBvcGVuLlxuXHQgKiBAcmV0dXJuIHt2b2lkfVxuXHQgKi9cblx0ZnVuY3Rpb24gc2V0X2RldGFpbHNfdG9nZ2xlX3N0YXRlKCB0b2dnbGVfYnV0dG9uLCBpc19leHBhbmRlZCApIHtcblx0XHR2YXIgaWNvbjtcblx0XHR2YXIgbGFiZWw7XG5cblx0XHRpZiAoICEgdG9nZ2xlX2J1dHRvbiApIHtcblx0XHRcdHJldHVybjtcblx0XHR9XG5cblx0XHRsYWJlbCA9IHRvZ2dsZV9idXR0b24uZ2V0QXR0cmlidXRlKCBpc19leHBhbmRlZCA/ICdkYXRhLWhpZGUtbGFiZWwnIDogJ2RhdGEtc2hvdy1sYWJlbCcgKSB8fCAnJztcblx0XHR0b2dnbGVfYnV0dG9uLnNldEF0dHJpYnV0ZSggJ2FyaWEtZXhwYW5kZWQnLCBpc19leHBhbmRlZCA/ICd0cnVlJyA6ICdmYWxzZScgKTtcblx0XHR0b2dnbGVfYnV0dG9uLnNldEF0dHJpYnV0ZSggJ2FyaWEtbGFiZWwnLCBsYWJlbCApO1xuXHRcdHRvZ2dsZV9idXR0b24uc2V0QXR0cmlidXRlKCAndGl0bGUnLCBsYWJlbCApO1xuXHRcdGljb24gPSB0b2dnbGVfYnV0dG9uLnF1ZXJ5U2VsZWN0b3IoICdzcGFuW2FyaWEtaGlkZGVuPVwidHJ1ZVwiXScgKTtcblx0XHRpZiAoIGljb24gKSB7XG5cdFx0XHRpY29uLmNsYXNzTmFtZSA9IGlzX2V4cGFuZGVkID8gJ3dwYmMtYmktY2hldnJvbi11cCcgOiAnd3BiYy1iaS1jaGV2cm9uLWRvd24nO1xuXHRcdH1cblx0fVxuXG5cdC8qKlxuXHQgKiBDbG9zZSB0aGUgYWN0aXZlIGRldGFpbHMgcm93IGFuZCBjYW5jZWwgaXRzIGxhenkgcmVxdWVzdC5cblx0ICpcblx0ICogQHBhcmFtIHtib29sZWFufSByZXN0b3JlX2ZvY3VzIFdoZXRoZXIgdG8gcmV0dXJuIGZvY3VzIHRvIHRoZSBkaXNjbG9zdXJlLlxuXHQgKiBAcmV0dXJuIHt2b2lkfVxuXHQgKi9cblx0ZnVuY3Rpb24gY2xvc2VfZGV0YWlsc19yb3coIHJlc3RvcmVfZm9jdXMgKSB7XG5cdFx0dmFyIGFjdGl2ZV9yb3cgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCAnW2RhdGEtd3BiYy1ib29raW5nLXJlc291cmNlLWRldGFpbHMtcm93XScgKTtcblx0XHR2YXIgZm9jdXNfdGFyZ2V0ID0gZGV0YWlsc190b2dnbGVfYnV0dG9uO1xuXG5cdFx0ZGV0YWlsc19yZXF1ZXN0X3NlcXVlbmNlICs9IDE7XG5cdFx0aWYgKCBkZXRhaWxzX2Fib3J0X2NvbnRyb2xsZXIgJiYgJ2Z1bmN0aW9uJyA9PT0gdHlwZW9mIGRldGFpbHNfYWJvcnRfY29udHJvbGxlci5hYm9ydCApIHtcblx0XHRcdGRldGFpbHNfYWJvcnRfY29udHJvbGxlci5hYm9ydCgpO1xuXHRcdH1cblx0XHRkZXRhaWxzX2Fib3J0X2NvbnRyb2xsZXIgPSBudWxsO1xuXHRcdGlmICggYWN0aXZlX3JvdyAmJiBhY3RpdmVfcm93LnBhcmVudE5vZGUgKSB7XG5cdFx0XHRhY3RpdmVfcm93LnBhcmVudE5vZGUucmVtb3ZlQ2hpbGQoIGFjdGl2ZV9yb3cgKTtcblx0XHR9XG5cdFx0c2V0X2RldGFpbHNfdG9nZ2xlX3N0YXRlKCBkZXRhaWxzX3RvZ2dsZV9idXR0b24sIGZhbHNlICk7XG5cdFx0aWYgKCBkZXRhaWxzX3RvZ2dsZV9idXR0b24gKSB7XG5cdFx0XHR2YXIgc291cmNlX3JvdyA9IGdldF9yZXNvdXJjZV9pdGVtX2NvbnRhaW5lciggZGV0YWlsc190b2dnbGVfYnV0dG9uICk7XG5cdFx0XHRpZiAoIHNvdXJjZV9yb3cgKSB7XG5cdFx0XHRcdHNvdXJjZV9yb3cuY2xhc3NMaXN0LnJlbW92ZSggJ2lzLWRldGFpbHMtZXhwYW5kZWQnICk7XG5cdFx0XHR9XG5cdFx0fVxuXHRcdGRldGFpbHNfcmVzb3VyY2VfaWQgPSAwO1xuXHRcdGRldGFpbHNfdG9nZ2xlX2J1dHRvbiA9IG51bGw7XG5cblx0XHRpZiAoIHJlc3RvcmVfZm9jdXMgJiYgZm9jdXNfdGFyZ2V0ICYmIGRvY3VtZW50LmRvY3VtZW50RWxlbWVudC5jb250YWlucyggZm9jdXNfdGFyZ2V0ICkgJiYgJ2Z1bmN0aW9uJyA9PT0gdHlwZW9mIGZvY3VzX3RhcmdldC5mb2N1cyApIHtcblx0XHRcdGZvY3VzX3RhcmdldC5mb2N1cygpO1xuXHRcdH1cblx0fVxuXG5cdC8qKlxuXHQgKiBSZXBsYWNlIHRoZSBhY3RpdmUgZGV0YWlscyByb3cgdGhyb3VnaCB0aGUgcmVnaXN0ZXJlZCBXUCB0ZW1wbGF0ZS5cblx0ICpcblx0ICogQHBhcmFtIHtPYmplY3R9ICAgICAgY29uZmlnICAgICAgIENhdGFsb2cgY29uZmlndXJhdGlvbi5cblx0ICogQHBhcmFtIHtIVE1MRWxlbWVudH0gcmVzb3VyY2Vfcm93IFNvdXJjZSBSZXNvdXJjZSByb3cuXG5cdCAqIEBwYXJhbSB7T2JqZWN0fSAgICAgIHRlbXBsYXRlX2RhdGEgUHJlc2VudGF0aW9uLW9ubHkgdGVtcGxhdGUgc3RhdGUuXG5cdCAqIEByZXR1cm4ge0hUTUxFbGVtZW50fG51bGx9IFJlbmRlcmVkIGRldGFpbHMgcm93LlxuXHQgKi9cblx0ZnVuY3Rpb24gcmVuZGVyX2RldGFpbHNfcm93KCBjb25maWcsIHJlc291cmNlX3JvdywgdGVtcGxhdGVfZGF0YSApIHtcblx0XHR2YXIgYWN0aXZlX3JvdyA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLWJvb2tpbmctcmVzb3VyY2UtZGV0YWlscy1yb3ddJyApO1xuXHRcdHZhciBjYXJkX2dyb3VwID0gcmVzb3VyY2Vfcm93ID8gcmVzb3VyY2Vfcm93LmNsb3Nlc3QoICdbZGF0YS13cGJjLWJvb2tpbmctcmVzb3VyY2UtY2FyZC1ncm91cF0nICkgOiBudWxsO1xuXHRcdHZhciBkZXRhaWxzX2h0bWwgPSByZW5kZXJfY29tcG9uZW50KCBjb25maWcsICdkZXRhaWxzJywgdGVtcGxhdGVfZGF0YSApO1xuXHRcdHZhciBpbnNlcnRpb25fdGFyZ2V0ID0gY2FyZF9ncm91cCB8fCByZXNvdXJjZV9yb3c7XG5cblx0XHRpZiAoICEgZGV0YWlsc19odG1sIHx8ICEgaW5zZXJ0aW9uX3RhcmdldCB8fCAhIGluc2VydGlvbl90YXJnZXQucGFyZW50Tm9kZSApIHtcblx0XHRcdHJldHVybiBudWxsO1xuXHRcdH1cblx0XHRpZiAoIGFjdGl2ZV9yb3cgJiYgYWN0aXZlX3Jvdy5wYXJlbnROb2RlICkge1xuXHRcdFx0YWN0aXZlX3Jvdy5wYXJlbnROb2RlLnJlbW92ZUNoaWxkKCBhY3RpdmVfcm93ICk7XG5cdFx0fVxuXHRcdGluc2VydGlvbl90YXJnZXQuaW5zZXJ0QWRqYWNlbnRIVE1MKCAnYWZ0ZXJlbmQnLCBkZXRhaWxzX2h0bWwgKTtcblxuXHRcdHJldHVybiBpbnNlcnRpb25fdGFyZ2V0Lm5leHRFbGVtZW50U2libGluZztcblx0fVxuXG5cdC8qKlxuXHQgKiBSZXR1cm4gYSBzYWZlIG1lc3NhZ2UgZnJvbSBhIG5vcm1hbGl6ZWQgZGV0YWlscyBlcnJvciByZXNwb25zZS5cblx0ICpcblx0ICogQHBhcmFtIHtPYmplY3R9IHJlc3BvbnNlIE5vcm1hbGl6ZWQgZW5kcG9pbnQgcmVzcG9uc2UuXG5cdCAqIEBwYXJhbSB7c3RyaW5nfSBmYWxsYmFjayBMb2NhbGl6ZWQgZmFsbGJhY2sgbWVzc2FnZS5cblx0ICogQHJldHVybiB7c3RyaW5nfSBTYWZlIHBsYWluLXRleHQgbWVzc2FnZS5cblx0ICovXG5cdGZ1bmN0aW9uIGdldF9kZXRhaWxzX2Vycm9yX21lc3NhZ2UoIHJlc3BvbnNlLCBmYWxsYmFjayApIHtcblx0XHRyZXR1cm4gcmVzcG9uc2UgJiYgcmVzcG9uc2UuZXJyb3IgJiYgcmVzcG9uc2UuZXJyb3IubWVzc2FnZVxuXHRcdFx0PyBTdHJpbmcoIHJlc3BvbnNlLmVycm9yLm1lc3NhZ2UgKVxuXHRcdFx0OiBTdHJpbmcoIGZhbGxiYWNrIHx8ICcnICk7XG5cdH1cblxuXHQvKipcblx0ICogT3BlbiBvbmUgZGV0YWlscyByb3cgYW5kIGxhemlseSByZXF1ZXN0IGl0cyBhdXRob3JpemVkIERUTy5cblx0ICpcblx0ICogQHBhcmFtIHtPYmplY3R9ICAgICAgY29uZmlnICAgICAgICBDYXRhbG9nIGNvbmZpZ3VyYXRpb24uXG5cdCAqIEBwYXJhbSB7SFRNTEVsZW1lbnR9IHRvZ2dsZV9idXR0b24gRGV0YWlscyBkaXNjbG9zdXJlIGJ1dHRvbi5cblx0ICogQHBhcmFtIHtIVE1MRWxlbWVudH0gcmVzb3VyY2Vfcm93ICBTb3VyY2UgUmVzb3VyY2Ugcm93LlxuXHQgKiBAcGFyYW0ge251bWJlcn0gICAgICByZXNvdXJjZV9pZCAgIEJvb2tpbmcgUmVzb3VyY2UgSUQuXG5cdCAqIEByZXR1cm4ge3ZvaWR9XG5cdCAqL1xuXHRmdW5jdGlvbiBvcGVuX2RldGFpbHNfcm93KCBjb25maWcsIHRvZ2dsZV9idXR0b24sIHJlc291cmNlX3JvdywgcmVzb3VyY2VfaWQgKSB7XG5cdFx0dmFyIGRldGFpbHNfcmVxdWVzdF9pZDtcblx0XHR2YXIgcmVxdWVzdF9ib2R5O1xuXHRcdHZhciByZXNvdXJjZV90aXRsZV9lbGVtZW50ID0gcmVzb3VyY2Vfcm93LnF1ZXJ5U2VsZWN0b3IoICcud3BiY191aV9saXN0aW5nX19pdGVtX3RpdGxlJyApO1xuXHRcdHZhciByZXNvdXJjZV90aXRsZSA9IHJlc291cmNlX3RpdGxlX2VsZW1lbnQgPyByZXNvdXJjZV90aXRsZV9lbGVtZW50LnRleHRDb250ZW50LnRyaW0oKSA6ICcnO1xuXHRcdHZhciB0ZW1wbGF0ZV9iYXNlO1xuXG5cdFx0Y2xvc2VfZGV0YWlsc19yb3coIGZhbHNlICk7XG5cdFx0ZGV0YWlsc19yZXNvdXJjZV9pZCA9IHJlc291cmNlX2lkO1xuXHRcdGRldGFpbHNfdG9nZ2xlX2J1dHRvbiA9IHRvZ2dsZV9idXR0b247XG5cdFx0ZGV0YWlsc19yZXF1ZXN0X2lkID0gKytkZXRhaWxzX3JlcXVlc3Rfc2VxdWVuY2U7XG5cdFx0c2V0X2RldGFpbHNfdG9nZ2xlX3N0YXRlKCB0b2dnbGVfYnV0dG9uLCB0cnVlICk7XG5cdFx0cmVzb3VyY2Vfcm93LmNsYXNzTGlzdC5hZGQoICdpcy1kZXRhaWxzLWV4cGFuZGVkJyApO1xuXHRcdHRlbXBsYXRlX2Jhc2UgPSB7XG5cdFx0XHRjb2xzcGFuOiBnZXRfZGV0YWlsc19jb2xzcGFuKCByZXNvdXJjZV9yb3cgKSxcblx0XHRcdHJlc291cmNlX2lkOiByZXNvdXJjZV9pZCxcblx0XHRcdHRpdGxlOiByZXNvdXJjZV90aXRsZVxuXHRcdH07XG5cdFx0cmVuZGVyX2RldGFpbHNfcm93KCBjb25maWcsIHJlc291cmNlX3JvdywgT2JqZWN0LmFzc2lnbigge30sIHRlbXBsYXRlX2Jhc2UsIHtcblx0XHRcdGxvYWRpbmdfbGFiZWw6IGNvbmZpZy5pMThuLmRldGFpbHNfbG9hZGluZyB8fCBjb25maWcuaTE4bi5sb2FkaW5nIHx8ICcnLFxuXHRcdFx0c3RhdGU6ICdsb2FkaW5nJ1xuXHRcdH0gKSApO1xuXG5cdFx0cmVxdWVzdF9ib2R5ID0gbmV3IHdpbmRvdy5VUkxTZWFyY2hQYXJhbXMoKTtcblx0XHRyZXF1ZXN0X2JvZHkuYXBwZW5kKCAnYWN0aW9uJywgY29uZmlnLmRldGFpbHNfYWN0aW9uICk7XG5cdFx0cmVxdWVzdF9ib2R5LmFwcGVuZCggJ25vbmNlJywgY29uZmlnLm5vbmNlIHx8ICcnICk7XG5cdFx0cmVxdWVzdF9ib2R5LmFwcGVuZCggJ3JlcXVlc3RfaWQnLCBTdHJpbmcoIGRldGFpbHNfcmVxdWVzdF9pZCApICk7XG5cdFx0cmVxdWVzdF9ib2R5LmFwcGVuZCggJ3Jlc291cmNlX2lkJywgU3RyaW5nKCByZXNvdXJjZV9pZCApICk7XG5cdFx0ZGV0YWlsc19hYm9ydF9jb250cm9sbGVyID0gJ2Z1bmN0aW9uJyA9PT0gdHlwZW9mIHdpbmRvdy5BYm9ydENvbnRyb2xsZXIgPyBuZXcgd2luZG93LkFib3J0Q29udHJvbGxlcigpIDogbnVsbDtcblxuXHRcdHdpbmRvdy5mZXRjaCggY29uZmlnLmFqYXhfdXJsLCB7XG5cdFx0XHRib2R5OiByZXF1ZXN0X2JvZHkudG9TdHJpbmcoKSxcblx0XHRcdGNyZWRlbnRpYWxzOiAnc2FtZS1vcmlnaW4nLFxuXHRcdFx0aGVhZGVyczogeyAnQ29udGVudC1UeXBlJzogJ2FwcGxpY2F0aW9uL3gtd3d3LWZvcm0tdXJsZW5jb2RlZDsgY2hhcnNldD1VVEYtOCcgfSxcblx0XHRcdG1ldGhvZDogJ1BPU1QnLFxuXHRcdFx0c2lnbmFsOiBkZXRhaWxzX2Fib3J0X2NvbnRyb2xsZXIgPyBkZXRhaWxzX2Fib3J0X2NvbnRyb2xsZXIuc2lnbmFsIDogdW5kZWZpbmVkXG5cdFx0fSApLnRoZW4oIGZ1bmN0aW9uICggcmVzcG9uc2UgKSB7XG5cdFx0XHRyZXR1cm4gcmVzcG9uc2UuanNvbigpO1xuXHRcdH0gKS50aGVuKCBmdW5jdGlvbiAoIHJlc3BvbnNlICkge1xuXHRcdFx0aWYgKCBkZXRhaWxzX3JlcXVlc3RfaWQgIT09IGRldGFpbHNfcmVxdWVzdF9zZXF1ZW5jZSB8fCByZXNvdXJjZV9pZCAhPT0gZGV0YWlsc19yZXNvdXJjZV9pZCApIHtcblx0XHRcdFx0cmV0dXJuO1xuXHRcdFx0fVxuXHRcdFx0aWYgKCAhIHJlc3BvbnNlIHx8IHRydWUgIT09IHJlc3BvbnNlLnN1Y2Nlc3MgfHwgTnVtYmVyKCByZXNwb25zZS5yZXF1ZXN0X2lkICkgIT09IGRldGFpbHNfcmVxdWVzdF9pZCB8fCBOdW1iZXIoIHJlc3BvbnNlLnJlc291cmNlX2lkICkgIT09IHJlc291cmNlX2lkIHx8ICEgcmVzcG9uc2UuZGV0YWlscyB8fCAhIEFycmF5LmlzQXJyYXkoIHJlc3BvbnNlLmRldGFpbHMuc2VjdGlvbnMgKSApIHtcblx0XHRcdFx0cmVuZGVyX2RldGFpbHNfcm93KCBjb25maWcsIHJlc291cmNlX3JvdywgT2JqZWN0LmFzc2lnbigge30sIHRlbXBsYXRlX2Jhc2UsIHtcblx0XHRcdFx0XHRlcnJvcl9tZXNzYWdlOiBnZXRfZGV0YWlsc19lcnJvcl9tZXNzYWdlKCByZXNwb25zZSwgY29uZmlnLmkxOG4uZGV0YWlsc19sb2FkX2ZhaWxlZCApLFxuXHRcdFx0XHRcdHN0YXRlOiAnZXJyb3InXG5cdFx0XHRcdH0gKSApO1xuXHRcdFx0XHRyZXR1cm47XG5cdFx0XHR9XG5cdFx0XHRyZW5kZXJfZGV0YWlsc19yb3coIGNvbmZpZywgcmVzb3VyY2Vfcm93LCBPYmplY3QuYXNzaWduKCB7fSwgdGVtcGxhdGVfYmFzZSwgcmVzcG9uc2UuZGV0YWlscywge1xuXHRcdFx0XHRjb2xzcGFuOiBnZXRfZGV0YWlsc19jb2xzcGFuKCByZXNvdXJjZV9yb3cgKSxcblx0XHRcdFx0c3RhdGU6ICdyZWFkeSdcblx0XHRcdH0gKSApO1xuXHRcdFx0dmFyIGNhdGFsb2dfbW91bnQgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCggY29uZmlnLm1vdW50X2lkICk7XG5cdFx0XHRzeW5jaHJvbml6ZV9vdmVyZmxvd190b29sdGlwcyggY2F0YWxvZ19tb3VudCApO1xuXHRcdFx0aW5pdGlhbGl6ZV9kZXRhaWxzX3Rvb2x0aXBzKCBjYXRhbG9nX21vdW50ICk7XG5cdFx0fSApLmNhdGNoKCBmdW5jdGlvbiAoIGVycm9yICkge1xuXHRcdFx0aWYgKCBlcnJvciAmJiAnQWJvcnRFcnJvcicgPT09IGVycm9yLm5hbWUgKSB7XG5cdFx0XHRcdHJldHVybjtcblx0XHRcdH1cblx0XHRcdGlmICggZGV0YWlsc19yZXF1ZXN0X2lkID09PSBkZXRhaWxzX3JlcXVlc3Rfc2VxdWVuY2UgJiYgcmVzb3VyY2VfaWQgPT09IGRldGFpbHNfcmVzb3VyY2VfaWQgKSB7XG5cdFx0XHRcdHJlbmRlcl9kZXRhaWxzX3JvdyggY29uZmlnLCByZXNvdXJjZV9yb3csIE9iamVjdC5hc3NpZ24oIHt9LCB0ZW1wbGF0ZV9iYXNlLCB7XG5cdFx0XHRcdFx0ZXJyb3JfbWVzc2FnZTogY29uZmlnLmkxOG4uZGV0YWlsc19sb2FkX2ZhaWxlZCB8fCAnJyxcblx0XHRcdFx0XHRzdGF0ZTogJ2Vycm9yJ1xuXHRcdFx0XHR9ICkgKTtcblx0XHRcdH1cblx0XHR9ICkudGhlbiggZnVuY3Rpb24gKCkge1xuXHRcdFx0aWYgKCBkZXRhaWxzX3JlcXVlc3RfaWQgPT09IGRldGFpbHNfcmVxdWVzdF9zZXF1ZW5jZSApIHtcblx0XHRcdFx0ZGV0YWlsc19hYm9ydF9jb250cm9sbGVyID0gbnVsbDtcblx0XHRcdH1cblx0XHR9ICk7XG5cdH1cblxuXHQvKipcblx0ICogQ29weSBvbmUgZGV0YWlscyB2YWx1ZSB3aXRob3V0IG5hdmlnYXRpbmcgb3IgbXV0YXRpbmcgUmVzb3VyY2Ugc3RhdGUuXG5cdCAqXG5cdCAqIEBwYXJhbSB7c3RyaW5nfSAgICAgIGNvcHlfdmFsdWUgICAgUGxhaW4gdGV4dCB0byBjb3B5LlxuXHQgKiBAcGFyYW0ge0hUTUxFbGVtZW50fSBhY3Rpb25fYnV0dG9uIENvcHkgYnV0dG9uIHVzZWQgdG8gbG9jYXRlIHN0YXR1cyB0ZXh0LlxuXHQgKiBAcGFyYW0ge09iamVjdH0gICAgICBjb25maWcgICAgICAgIENhdGFsb2cgY29uZmlndXJhdGlvbi5cblx0ICogQHJldHVybiB7dm9pZH1cblx0ICovXG5cdGZ1bmN0aW9uIGNvcHlfZGV0YWlsc192YWx1ZSggY29weV92YWx1ZSwgYWN0aW9uX2J1dHRvbiwgY29uZmlnICkge1xuXHRcdHZhciBkZXRhaWxzX3JvdyA9IGFjdGlvbl9idXR0b24uY2xvc2VzdCggJ1tkYXRhLXdwYmMtYm9va2luZy1yZXNvdXJjZS1kZXRhaWxzLXJvd10nICk7XG5cdFx0dmFyIHJlc291cmNlX2lkID0gTnVtYmVyKCBhY3Rpb25fYnV0dG9uLmdldEF0dHJpYnV0ZSggJ2RhdGEtd3BiYy1ib29raW5nLXJlc291cmNlLWlkJyApIHx8IDAgKTtcblx0XHR2YXIgc3RhdHVzX2VsZW1lbnQgPSBkZXRhaWxzX3Jvd1xuXHRcdFx0PyBkZXRhaWxzX3Jvdy5xdWVyeVNlbGVjdG9yKCAnW2RhdGEtd3BiYy1ib29raW5nLXJlc291cmNlLWNvcHktc3RhdHVzXScgKVxuXHRcdFx0OiBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCAnW2RhdGEtd3BiYy1ib29raW5nLXJlc291cmNlLWNvcHktc3RhdHVzPVwiJyArIFN0cmluZyggcmVzb3VyY2VfaWQgKSArICdcIl0nICk7XG5cdFx0dmFyIGNvcHlfcHJvbWlzZTtcblxuXHRcdGlmICggd2luZG93Lm5hdmlnYXRvci5jbGlwYm9hcmQgJiYgJ2Z1bmN0aW9uJyA9PT0gdHlwZW9mIHdpbmRvdy5uYXZpZ2F0b3IuY2xpcGJvYXJkLndyaXRlVGV4dCApIHtcblx0XHRcdGNvcHlfcHJvbWlzZSA9IHdpbmRvdy5uYXZpZ2F0b3IuY2xpcGJvYXJkLndyaXRlVGV4dCggY29weV92YWx1ZSApO1xuXHRcdH0gZWxzZSB7XG5cdFx0XHRjb3B5X3Byb21pc2UgPSBuZXcgd2luZG93LlByb21pc2UoIGZ1bmN0aW9uICggcmVzb2x2ZSwgcmVqZWN0ICkge1xuXHRcdFx0XHR2YXIgY29weV9pbnB1dCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoICd0ZXh0YXJlYScgKTtcblx0XHRcdFx0Y29weV9pbnB1dC52YWx1ZSA9IGNvcHlfdmFsdWU7XG5cdFx0XHRcdGNvcHlfaW5wdXQuc2V0QXR0cmlidXRlKCAncmVhZG9ubHknLCAncmVhZG9ubHknICk7XG5cdFx0XHRcdGNvcHlfaW5wdXQuc3R5bGUucG9zaXRpb24gPSAnZml4ZWQnO1xuXHRcdFx0XHRjb3B5X2lucHV0LnN0eWxlLm9wYWNpdHkgPSAnMCc7XG5cdFx0XHRcdGRvY3VtZW50LmJvZHkuYXBwZW5kQ2hpbGQoIGNvcHlfaW5wdXQgKTtcblx0XHRcdFx0Y29weV9pbnB1dC5zZWxlY3QoKTtcblx0XHRcdFx0aWYgKCBkb2N1bWVudC5leGVjQ29tbWFuZCggJ2NvcHknICkgKSB7XG5cdFx0XHRcdFx0cmVzb2x2ZSgpO1xuXHRcdFx0XHR9IGVsc2Uge1xuXHRcdFx0XHRcdHJlamVjdCgpO1xuXHRcdFx0XHR9XG5cdFx0XHRcdGRvY3VtZW50LmJvZHkucmVtb3ZlQ2hpbGQoIGNvcHlfaW5wdXQgKTtcblx0XHRcdH0gKTtcblx0XHR9XG5cdFx0Y29weV9wcm9taXNlLnRoZW4oIGZ1bmN0aW9uICgpIHtcblx0XHRcdGlmICggc3RhdHVzX2VsZW1lbnQgKSB7XG5cdFx0XHRcdHN0YXR1c19lbGVtZW50LmNsYXNzTGlzdC5yZW1vdmUoICdoYXMtZXJyb3InICk7XG5cdFx0XHRcdHN0YXR1c19lbGVtZW50LnRleHRDb250ZW50ID0gY29uZmlnLmkxOG4uc2hvcnRjb2RlX2NvcGllZCB8fCAnJztcblx0XHRcdH1cblx0XHR9ICkuY2F0Y2goIGZ1bmN0aW9uICgpIHtcblx0XHRcdGlmICggc3RhdHVzX2VsZW1lbnQgKSB7XG5cdFx0XHRcdHN0YXR1c19lbGVtZW50LmNsYXNzTGlzdC5hZGQoICdoYXMtZXJyb3InICk7XG5cdFx0XHRcdHN0YXR1c19lbGVtZW50LnRleHRDb250ZW50ID0gY29uZmlnLmkxOG4uc2hvcnRjb2RlX2NvcHlfZmFpbGVkIHx8ICcnO1xuXHRcdFx0fVxuXHRcdH0gKTtcblx0fVxuXG5cdC8qKlxuXHQgKiBSZXR1cm4gdGhlIGN1cnJlbnQgZWZmZWN0aXZlIHNob3J0Y29kZSBmb3Igb25lIFJlc291cmNlLlxuXHQgKlxuXHQgKiBUaGUgYWN0aXZlIGluc3BlY3RvciB3aW5zIHNvIHVuc2F2ZWQgY3VzdG9taXplciBjaGFuZ2VzIGFyZSB1c2VkIGJ5IENvcHlcblx0ICogYW5kIFB1Ymxpc2guIEEgaGlkZGVuIGNvbXBhdGliaWxpdHkgaW5wdXQgaXMgdGhlIGZhbGxiYWNrIHJlcXVpcmVkIGJ5IHRoZVxuXHQgKiBzaGFyZWQgQm9va2luZyBDYWxlbmRhciBwdWJsaXNoIHdpemFyZC5cblx0ICpcblx0ICogQHBhcmFtIHtudW1iZXJ9ICAgICAgcmVzb3VyY2VfaWQgIEJvb2tpbmcgUmVzb3VyY2UgSUQuXG5cdCAqIEBwYXJhbSB7SFRNTEVsZW1lbnR9IGFjdGlvbl9idXR0b24gT3B0aW9uYWwgaW5pdGlhdGluZyBhY3Rpb24uXG5cdCAqIEByZXR1cm4ge3N0cmluZ30gQ3VycmVudCBlZmZlY3RpdmUgc2hvcnRjb2RlLlxuXHQgKi9cblx0ZnVuY3Rpb24gZ2V0X2Jvb2tpbmdfcmVzb3VyY2Vfc2hvcnRjb2RlKCByZXNvdXJjZV9pZCwgYWN0aW9uX2J1dHRvbiApIHtcblx0XHR2YXIgaW5zcGVjdG9yX3Nob3J0Y29kZSA9IHJlc291cmNlX2lkID09PSBpbnNwZWN0b3JfcmVzb3VyY2VfaWRcblx0XHRcdD8gZG9jdW1lbnQucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtY2F0YWxvZy1yZXNvdXJjZS1pbnNwZWN0b3ItZm9ybV0gLndwYmNfY2F0YWxvZ19ib29raW5nX3Jlc291cmNlc19fZWRpdG9yX2NvZGUnIClcblx0XHRcdDogbnVsbDtcblx0XHR2YXIgaGlkZGVuX3Nob3J0Y29kZSA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCAnYm9va2luZ19yZXNvdXJjZV9zaG9ydGNvZGVfJyArIFN0cmluZyggcmVzb3VyY2VfaWQgKSApO1xuXG5cdFx0aWYgKCBpbnNwZWN0b3Jfc2hvcnRjb2RlICkge1xuXHRcdFx0cmV0dXJuIFN0cmluZyggaW5zcGVjdG9yX3Nob3J0Y29kZS52YWx1ZSB8fCAnJyApO1xuXHRcdH1cblx0XHRpZiAoIGFjdGlvbl9idXR0b24gJiYgYWN0aW9uX2J1dHRvbi5nZXRBdHRyaWJ1dGUoICdkYXRhLXdwYmMtYm9va2luZy1yZXNvdXJjZS1zaG9ydGNvZGUnICkgKSB7XG5cdFx0XHRyZXR1cm4gU3RyaW5nKCBhY3Rpb25fYnV0dG9uLmdldEF0dHJpYnV0ZSggJ2RhdGEtd3BiYy1ib29raW5nLXJlc291cmNlLXNob3J0Y29kZScgKSB8fCAnJyApO1xuXHRcdH1cblxuXHRcdHJldHVybiBoaWRkZW5fc2hvcnRjb2RlID8gU3RyaW5nKCBoaWRkZW5fc2hvcnRjb2RlLnZhbHVlIHx8ICcnICkgOiAnJztcblx0fVxuXG5cdC8qKlxuXHQgKiBDcmVhdGUgb3IgdXBkYXRlIHRoZSBoaWRkZW4gaW5wdXQgY29uc3VtZWQgYnkgdGhlIHNoYXJlZCBwdWJsaXNoIHdpemFyZC5cblx0ICpcblx0ICogQHBhcmFtIHtudW1iZXJ9IHJlc291cmNlX2lkIEJvb2tpbmcgUmVzb3VyY2UgSUQuXG5cdCAqIEBwYXJhbSB7c3RyaW5nfSBzaG9ydGNvZGUgICBFZmZlY3RpdmUgQm9va2luZyBzaG9ydGNvZGUuXG5cdCAqIEByZXR1cm4ge0hUTUxJbnB1dEVsZW1lbnR8bnVsbH0gQ29tcGF0aWJpbGl0eSBpbnB1dCBvciBudWxsLlxuXHQgKi9cblx0ZnVuY3Rpb24gc3luY2hyb25pemVfYm9va2luZ19yZXNvdXJjZV9zaG9ydGNvZGVfaW5wdXQoIHJlc291cmNlX2lkLCBzaG9ydGNvZGUgKSB7XG5cdFx0dmFyIGlucHV0O1xuXG5cdFx0aWYgKCAhIHJlc291cmNlX2lkICkge1xuXHRcdFx0cmV0dXJuIG51bGw7XG5cdFx0fVxuXHRcdGlucHV0ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoICdib29raW5nX3Jlc291cmNlX3Nob3J0Y29kZV8nICsgU3RyaW5nKCByZXNvdXJjZV9pZCApICk7XG5cdFx0aWYgKCAhIGlucHV0ICkge1xuXHRcdFx0aW5wdXQgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCAnaW5wdXQnICk7XG5cdFx0XHRpbnB1dC50eXBlID0gJ2hpZGRlbic7XG5cdFx0XHRpbnB1dC5pZCA9ICdib29raW5nX3Jlc291cmNlX3Nob3J0Y29kZV8nICsgU3RyaW5nKCByZXNvdXJjZV9pZCApO1xuXHRcdFx0aW5wdXQuc2V0QXR0cmlidXRlKCAnZGF0YS13cGJjLWNhdGFsb2ctc2hvcnRjb2RlLWNvbXBhdGliaWxpdHknLCBTdHJpbmcoIHJlc291cmNlX2lkICkgKTtcblx0XHRcdGRvY3VtZW50LmJvZHkuYXBwZW5kQ2hpbGQoIGlucHV0ICk7XG5cdFx0fVxuXHRcdGlucHV0LnZhbHVlID0gU3RyaW5nKCBzaG9ydGNvZGUgfHwgJycgKTtcblxuXHRcdHJldHVybiBpbnB1dDtcblx0fVxuXG5cdC8qKlxuXHQgKiBPcGVuIHRoZSBzaGFyZWQgQm9va2luZyBDYWxlbmRhciBzaG9ydGNvZGUgY3VzdG9taXplciBmb3Igb25lIFJlc291cmNlLlxuXHQgKlxuXHQgKiBAcGFyYW0ge251bWJlcn0gcmVzb3VyY2VfaWQgQm9va2luZyBSZXNvdXJjZSBJRC5cblx0ICogQHBhcmFtIHtzdHJpbmd9IHNob3J0Y29kZSAgIEN1cnJlbnQgc2hvcnRjb2RlLlxuXHQgKiBAcmV0dXJuIHt2b2lkfVxuXHQgKi9cblx0ZnVuY3Rpb24gY3VzdG9taXplX2Jvb2tpbmdfcmVzb3VyY2Vfc2hvcnRjb2RlKCByZXNvdXJjZV9pZCwgc2hvcnRjb2RlICkge1xuXHRcdHN5bmNocm9uaXplX2Jvb2tpbmdfcmVzb3VyY2Vfc2hvcnRjb2RlX2lucHV0KCByZXNvdXJjZV9pZCwgc2hvcnRjb2RlICk7XG5cdFx0aWYgKCAnZnVuY3Rpb24nID09PSB0eXBlb2Ygd2luZG93LndwYmNfcmVzb3VyY2VfcGFnZV9idG5fY2xpY2sgKSB7XG5cdFx0XHR3aW5kb3cud3BiY19yZXNvdXJjZV9wYWdlX2J0bl9jbGljayggcmVzb3VyY2VfaWQsIHNob3J0Y29kZSApO1xuXHRcdH1cblx0fVxuXG5cdC8qKlxuXHQgKiBPcGVuIHRoZSBzaGFyZWQgQm9va2luZyBDYWxlbmRhciBlbWJlZC9jcmVhdGUtcGFnZSB3aXphcmQuXG5cdCAqXG5cdCAqIEBwYXJhbSB7bnVtYmVyfSByZXNvdXJjZV9pZCBCb29raW5nIFJlc291cmNlIElELlxuXHQgKiBAcGFyYW0ge3N0cmluZ30gc2hvcnRjb2RlICAgQ3VycmVudCBzaG9ydGNvZGUuXG5cdCAqIEByZXR1cm4ge3ZvaWR9XG5cdCAqL1xuXHRmdW5jdGlvbiBwdWJsaXNoX2Jvb2tpbmdfcmVzb3VyY2Vfc2hvcnRjb2RlKCByZXNvdXJjZV9pZCwgc2hvcnRjb2RlLCB0cmlnZ2VyX2J1dHRvbiApIHtcblx0XHRzeW5jaHJvbml6ZV9ib29raW5nX3Jlc291cmNlX3Nob3J0Y29kZV9pbnB1dCggcmVzb3VyY2VfaWQsIHNob3J0Y29kZSApO1xuXHRcdGlmICggJ2Z1bmN0aW9uJyA9PT0gdHlwZW9mIHdpbmRvdy53cGJjX3B1Ymxpc2hfYm9va2luZ19mb3JtX19vcGVuICkge1xuXHRcdFx0d2luZG93LndwYmNfcHVibGlzaF9ib29raW5nX2Zvcm1fX29wZW4oIHJlc291cmNlX2lkLCBzaG9ydGNvZGUsIHRyaWdnZXJfYnV0dG9uICk7XG5cdFx0fVxuXHR9XG5cblx0LyoqXG5cdCAqIE9wZW4gdGhlIGluZm9ybWF0aW9uYWwgRnJlZS1lZGl0aW9uIEJvb2tpbmcgUmVzb3VyY2UgdXBncmFkZSBkaWFsb2cuXG5cdCAqXG5cdCAqIEBwYXJhbSB7SFRNTEVsZW1lbnR9IHRyaWdnZXJfYnV0dG9uIEJ1dHRvbiB0aGF0IG9wZW5lZCB0aGUgZGlhbG9nLlxuXHQgKiBAcmV0dXJuIHt2b2lkfVxuXHQgKi9cblx0ZnVuY3Rpb24gb3Blbl9ib29raW5nX3Jlc291cmNlX3VwZ3JhZGVfZGlhbG9nKCB0cmlnZ2VyX2J1dHRvbiApIHtcblx0XHR2YXIgbW9kYWxfZWxlbWVudCA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCAnd3BiY19jYXRhbG9nX2Jvb2tpbmdfcmVzb3VyY2VzX191cGdyYWRlX21vZGFsJyApO1xuXHRcdHZhciB1cGdyYWRlX3VybCA9IHRyaWdnZXJfYnV0dG9uID8gdHJpZ2dlcl9idXR0b24uZ2V0QXR0cmlidXRlKCAnZGF0YS13cGJjLWNhdGFsb2ctYm9va2luZy1yZXNvdXJjZS11cGdyYWRlLXVybCcgKSA6ICcnO1xuXG5cdFx0aWYgKCBtb2RhbF9lbGVtZW50ICYmIHdpbmRvdy5qUXVlcnkgJiYgJ2Z1bmN0aW9uJyA9PT0gdHlwZW9mIHdpbmRvdy5qUXVlcnkoIG1vZGFsX2VsZW1lbnQgKS53cGJjX215X21vZGFsICkge1xuXHRcdFx0d2luZG93LmpRdWVyeSggbW9kYWxfZWxlbWVudCApXG5cdFx0XHRcdC5vZmYoICdoaWRkZW4ud3BiYy5tb2RhbC53cGJjQ2F0YWxvZ1Jlc291cmNlVXBncmFkZSBoaWRkZW4uYnMubW9kYWwud3BiY0NhdGFsb2dSZXNvdXJjZVVwZ3JhZGUnIClcblx0XHRcdFx0Lm9uZSggJ2hpZGRlbi53cGJjLm1vZGFsLndwYmNDYXRhbG9nUmVzb3VyY2VVcGdyYWRlIGhpZGRlbi5icy5tb2RhbC53cGJjQ2F0YWxvZ1Jlc291cmNlVXBncmFkZScsIGZ1bmN0aW9uICgpIHtcblx0XHRcdFx0XHRpZiAoIHRyaWdnZXJfYnV0dG9uICYmIGRvY3VtZW50LmNvbnRhaW5zKCB0cmlnZ2VyX2J1dHRvbiApICkge1xuXHRcdFx0XHRcdFx0dHJpZ2dlcl9idXR0b24uZm9jdXMoKTtcblx0XHRcdFx0XHR9XG5cdFx0XHRcdH0gKVxuXHRcdFx0XHQud3BiY19teV9tb2RhbCggJ3Nob3cnICk7XG5cdFx0XHRyZXR1cm47XG5cdFx0fVxuXG5cdFx0aWYgKCB1cGdyYWRlX3VybCApIHtcblx0XHRcdHdpbmRvdy5vcGVuKCB1cGdyYWRlX3VybCwgJ19ibGFuaycsICdub29wZW5lcicgKTtcblx0XHR9XG5cdH1cblxuXHQvKipcblx0ICogT3BlbiB0aGUgcmV1c2FibGUgbmF0aXZlIGNhdGFsb2cgbWVzc2FnZSBkaWFsb2cuXG5cdCAqXG5cdCAqIE1lc3NhZ2UgY29udGVudCBpcyBhc3NpZ25lZCB3aXRoIHRleHRDb250ZW50IHNvIHRyYW5zbGF0ZWQgb3Igc2VydmVyLXByb3ZpZGVkXG5cdCAqIHRleHQgY2Fubm90IGJlY29tZSBkaWFsb2cgbWFya3VwLiBUaGUgYnJvd3NlciBhbGVydCByZW1haW5zIGEgcmVzaWxpZW5jZVxuXHQgKiBmYWxsYmFjayB3aGVuIHRoZSBCb29raW5nIENhbGVuZGFyIG1vZGFsIHJ1bnRpbWUgaXMgdW5hdmFpbGFibGUuXG5cdCAqXG5cdCAqIEBwYXJhbSB7c3RyaW5nfSAgICAgIG1lc3NhZ2UgICAgICAgIE1lc3NhZ2Ugc2hvd24gaW4gdGhlIGRpYWxvZyBib2R5LlxuXHQgKiBAcGFyYW0ge3N0cmluZ30gICAgICB0aXRsZSAgICAgICAgICBPcHRpb25hbCBkaWFsb2cgaGVhZGluZy5cblx0ICogQHBhcmFtIHtIVE1MRWxlbWVudH0gdHJpZ2dlcl9idXR0b24gQ29udHJvbCB0aGF0IG9wZW5lZCB0aGUgZGlhbG9nLlxuXHQgKiBAcmV0dXJuIHtib29sZWFufSBUcnVlIHdoZW4gdGhlIG5hdGl2ZSBkaWFsb2cgb3BlbmVkOyBvdGhlcndpc2UgZmFsc2UuXG5cdCAqL1xuXHRmdW5jdGlvbiBvcGVuX2Jvb2tpbmdfcmVzb3VyY2VfbWVzc2FnZV9kaWFsb2coIG1lc3NhZ2UsIHRpdGxlLCB0cmlnZ2VyX2J1dHRvbiApIHtcblx0XHR2YXIgbW9kYWxfZWxlbWVudCA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCAnd3BiY19jYXRhbG9nX2Jvb2tpbmdfcmVzb3VyY2VzX19tZXNzYWdlX21vZGFsJyApO1xuXHRcdHZhciB0aXRsZV9lbGVtZW50ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoICd3cGJjX2NhdGFsb2dfYm9va2luZ19yZXNvdXJjZXNfX21lc3NhZ2VfbW9kYWxfdGl0bGUnICk7XG5cdFx0dmFyIGRlc2NyaXB0aW9uX2VsZW1lbnQgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCggJ3dwYmNfY2F0YWxvZ19ib29raW5nX3Jlc291cmNlc19fbWVzc2FnZV9tb2RhbF9kZXNjcmlwdGlvbicgKTtcblxuXHRcdGlmICggbWVzc2FnZSAmJiBtb2RhbF9lbGVtZW50ICYmIGRlc2NyaXB0aW9uX2VsZW1lbnQgJiYgd2luZG93LmpRdWVyeSAmJiAnZnVuY3Rpb24nID09PSB0eXBlb2Ygd2luZG93LmpRdWVyeSggbW9kYWxfZWxlbWVudCApLndwYmNfbXlfbW9kYWwgKSB7XG5cdFx0XHRkZXNjcmlwdGlvbl9lbGVtZW50LnRleHRDb250ZW50ID0gbWVzc2FnZTtcblx0XHRcdGlmICggdGl0bGVfZWxlbWVudCApIHtcblx0XHRcdFx0dGl0bGVfZWxlbWVudC50ZXh0Q29udGVudCA9IHRpdGxlIHx8IHRpdGxlX2VsZW1lbnQuZ2V0QXR0cmlidXRlKCAnZGF0YS13cGJjLWRlZmF1bHQtdGl0bGUnICkgfHwgJyc7XG5cdFx0XHR9XG5cdFx0XHR3aW5kb3cualF1ZXJ5KCBtb2RhbF9lbGVtZW50IClcblx0XHRcdFx0Lm9mZiggJ2hpZGRlbi53cGJjLm1vZGFsLndwYmNDYXRhbG9nUmVzb3VyY2VNZXNzYWdlIGhpZGRlbi5icy5tb2RhbC53cGJjQ2F0YWxvZ1Jlc291cmNlTWVzc2FnZScgKVxuXHRcdFx0XHQub25lKCAnaGlkZGVuLndwYmMubW9kYWwud3BiY0NhdGFsb2dSZXNvdXJjZU1lc3NhZ2UgaGlkZGVuLmJzLm1vZGFsLndwYmNDYXRhbG9nUmVzb3VyY2VNZXNzYWdlJywgZnVuY3Rpb24gKCkge1xuXHRcdFx0XHRcdGlmICggdHJpZ2dlcl9idXR0b24gJiYgZG9jdW1lbnQuY29udGFpbnMoIHRyaWdnZXJfYnV0dG9uICkgKSB7XG5cdFx0XHRcdFx0XHR0cmlnZ2VyX2J1dHRvbi5mb2N1cygpO1xuXHRcdFx0XHRcdH1cblx0XHRcdFx0fSApXG5cdFx0XHRcdC53cGJjX215X21vZGFsKCAnc2hvdycgKTtcblx0XHRcdHJldHVybiB0cnVlO1xuXHRcdH1cblxuXHRcdGlmICggbWVzc2FnZSAmJiAnZnVuY3Rpb24nID09PSB0eXBlb2Ygd2luZG93LmFsZXJ0ICkge1xuXHRcdFx0d2luZG93LmFsZXJ0KCBtZXNzYWdlICk7XG5cdFx0fVxuXHRcdHJldHVybiBmYWxzZTtcblx0fVxuXG5cdC8qKlxuXHQgKiBSZXR1cm4gdGhlIHRlbXBsYXRlLWRyaXZlbiBpbnNwZWN0b3IgaG9zdC5cblx0ICpcblx0ICogQHJldHVybiB7SFRNTEVsZW1lbnR8bnVsbH0gSW5zcGVjdG9yIGhvc3Qgb3IgbnVsbC5cblx0ICovXG5cdGZ1bmN0aW9uIGdldF9pbnNwZWN0b3JfaG9zdCgpIHtcblx0XHRyZXR1cm4gZG9jdW1lbnQucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtY2F0YWxvZy1ib29raW5nLXJlc291cmNlcy1pbnNwZWN0b3ItaG9zdF0nICk7XG5cdH1cblxuXHQvKipcblx0ICogUmV0dXJuIHRoZSBzdGlja3kgbmF0aXZlLXNpZGViYXIgZm9vdGVyLlxuXHQgKlxuXHQgKiBAcmV0dXJuIHtIVE1MRWxlbWVudHxudWxsfSBGb290ZXIgZWxlbWVudCBvciBudWxsLlxuXHQgKi9cblx0ZnVuY3Rpb24gZ2V0X2luc3BlY3Rvcl9mb290ZXIoKSB7XG5cdFx0cmV0dXJuIGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLXVpLWNhdGFsb2ctaW5zcGVjdG9yLWZvb3Rlcl0nICk7XG5cdH1cblxuXHQvKipcblx0ICogUmV0dXJuIHRoZSBzaGFyZWQgbmF0aXZlIGluc3BlY3RvciBzdGF0ZSB3b3JrZmxvdy5cblx0ICpcblx0ICogQHBhcmFtIHtPYmplY3R9IGNvbmZpZyBDYXRhbG9nIGNvbmZpZ3VyYXRpb24uXG5cdCAqIEByZXR1cm4ge09iamVjdHxmYWxzZX0gU2hhcmVkIGluc3BlY3RvciB3b3JrZmxvdyBvciBmYWxzZS5cblx0ICovXG5cdGZ1bmN0aW9uIGdldF9pbnNwZWN0b3Jfd29ya2Zsb3coIGNvbmZpZyApIHtcblx0XHRpZiAoIGluc3BlY3Rvcl93b3JrZmxvd19jb250cm9sbGVyICkge1xuXHRcdFx0cmV0dXJuIGluc3BlY3Rvcl93b3JrZmxvd19jb250cm9sbGVyO1xuXHRcdH1cblx0XHRpZiAoICEgd2luZG93LndwYmNfdWlfY2F0YWxvZyB8fCAnZnVuY3Rpb24nICE9PSB0eXBlb2Ygd2luZG93LndwYmNfdWlfY2F0YWxvZy5jcmVhdGVfaW5zcGVjdG9yX3dvcmtmbG93ICkge1xuXHRcdFx0cmV0dXJuIGZhbHNlO1xuXHRcdH1cblxuXHRcdGluc3BlY3Rvcl93b3JrZmxvd19jb250cm9sbGVyID0gd2luZG93LndwYmNfdWlfY2F0YWxvZy5jcmVhdGVfaW5zcGVjdG9yX3dvcmtmbG93KCB7XG5cdFx0XHRleHBhbmQ6IGV4cGFuZF9pbnNwZWN0b3Jfc2lkZWJhcixcblx0XHRcdGdldF9mb290ZXI6IGdldF9pbnNwZWN0b3JfZm9vdGVyLFxuXHRcdFx0Z2V0X2hvc3Q6IGdldF9pbnNwZWN0b3JfaG9zdCxcblx0XHRcdHJlbmRlcl9zaGVsbDogZnVuY3Rpb24gKCBzaGVsbF9kYXRhICkgeyByZXR1cm4gcmVuZGVyX2NvbXBvbmVudCggY29uZmlnLCAnaW5zcGVjdG9yJywgc2hlbGxfZGF0YSApOyB9LFxuXHRcdFx0c2hlbGxfZGF0YToge1xuXHRcdFx0XHRjYXRhbG9nX2lkOiBjb25maWcuaWQsXG5cdFx0XHRcdGVtcHR5X2ljb246ICd3cGJjLWJpLXBlbmNpbC1zcXVhcmUnLFxuXHRcdFx0XHRlbXB0eV9tZXNzYWdlOiBjb25maWcuaTE4bi5pbnNwZWN0b3JfZW1wdHlfbWVzc2FnZSB8fCAnJyxcblx0XHRcdFx0ZW1wdHlfdGl0bGU6IGNvbmZpZy5pMThuLmluc3BlY3Rvcl9lbXB0eV90aXRsZSB8fCAnJyxcblx0XHRcdFx0bG9hZGluZ19sYWJlbDogY29uZmlnLmkxOG4uaW5zcGVjdG9yX2xvYWRpbmcgfHwgY29uZmlnLmkxOG4ubG9hZGluZyB8fCAnJ1xuXHRcdFx0fVxuXHRcdH0gKTtcblxuXHRcdHJldHVybiBpbnNwZWN0b3Jfd29ya2Zsb3dfY29udHJvbGxlcjtcblx0fVxuXG5cdC8qKlxuXHQgKiBSZW5kZXIgdGhlIHNoYXJlZCBpbnNwZWN0b3IgZmFsbGJhY2stc3RhdGUgc2hlbGwgb25jZS5cblx0ICpcblx0ICogQHBhcmFtIHtPYmplY3R9IGNvbmZpZyBDYXRhbG9nIGNvbmZpZ3VyYXRpb24uXG5cdCAqIEByZXR1cm4ge2Jvb2xlYW59IFRydWUgd2hlbiB0aGUgc2hlbGwgaXMgYXZhaWxhYmxlLlxuXHQgKi9cblx0ZnVuY3Rpb24gbW91bnRfaW5zcGVjdG9yX3NoZWxsKCBjb25maWcgKSB7XG5cdFx0dmFyIGluc3BlY3Rvcl93b3JrZmxvdyA9IGdldF9pbnNwZWN0b3Jfd29ya2Zsb3coIGNvbmZpZyApO1xuXG5cdFx0cmV0dXJuICEhIGluc3BlY3Rvcl93b3JrZmxvdyAmJiBpbnNwZWN0b3Jfd29ya2Zsb3cubW91bnQoKTtcblx0fVxuXG5cdC8qKlxuXHQgKiBFeHBhbmQgdGhlIG5hdGl2ZSByaWdodCBzaWRlYmFyIGFmdGVyIGFuIGV4cGxpY2l0IGVkaXRvciBhY3Rpb24uXG5cdCAqXG5cdCAqIEByZXR1cm4ge3ZvaWR9XG5cdCAqL1xuXHRmdW5jdGlvbiBleHBhbmRfaW5zcGVjdG9yX3NpZGViYXIoKSB7XG5cdFx0c3luY2hyb25pemVfaW5zcGVjdG9yX3dpZHRoKCk7XG5cdFx0aWYgKCAnZnVuY3Rpb24nID09PSB0eXBlb2Ygd2luZG93LndwYmNfYWRtaW5fdWlfX3NpZGViYXJfcmlnaHRfX2RvX21heCApIHtcblx0XHRcdHdpbmRvdy53cGJjX2FkbWluX3VpX19zaWRlYmFyX3JpZ2h0X19kb19tYXgoKTtcblx0XHR9XG5cdFx0ZG9jdW1lbnQuZGlzcGF0Y2hFdmVudCggbmV3IEN1c3RvbUV2ZW50KCAnd3BiY19zZXR1cF93aXphcmRfbGF5b3V0X2NoYW5nZWQnICkgKTtcblx0fVxuXG5cdC8qKlxuXHQgKiBBcHBseSB0aGUgZXN0YWJsaXNoZWQgd2lkZXIgc2lkZWJhciBvbmx5IHdoaWxlIGNyZWF0aW5nIFJlc291cmNlcy5cblx0ICpcblx0ICogVGhlIG5ldyBjYXRhbG9nIG93bnMgaXRzIGNsYXNzIGFuZCBzdHlsaW5nIHNvIGl0IGRvZXMgbm90IGRlcGVuZCBvbiB0aGUgb2xkXG5cdCAqIGxpc3RpbmcgYXNzZXRzIHdoaWxlIHJldGFpbmluZyB0aGUgc2FtZSBuYXRpdmUtc2lkZWJhciB3aWR0aCBjb250cmFjdC5cblx0ICpcblx0ICogQHJldHVybiB7dm9pZH1cblx0ICovXG5cdGZ1bmN0aW9uIHN5bmNocm9uaXplX2luc3BlY3Rvcl93aWR0aCgpIHtcblx0XHR2YXIgaG9zdCA9IGdldF9pbnNwZWN0b3JfaG9zdCgpO1xuXHRcdHZhciBzaWRlYmFyID0gaG9zdCA/IGhvc3QuY2xvc2VzdCggJy53cGJjX3VpX2VsX192ZXJ0X3JpZ2h0X2Jhcl9fd3JhcHBlcicgKSA6IG51bGw7XG5cblx0XHRpZiAoIHNpZGViYXIgKSB7XG5cdFx0XHRzaWRlYmFyLmNsYXNzTGlzdC50b2dnbGUoICd3cGJjX2NhdGFsb2dfYm9va2luZ19yZXNvdXJjZXNfX2luc3BlY3Rvcl93aWR0aC0td2lkZScsICdjcmVhdGUnID09PSBpbnNwZWN0b3JfbW9kZSApO1xuXHRcdH1cblx0fVxuXG5cdC8qKlxuXHQgKiBNYXJrIG9ubHkgdGhlIFJlc291cmNlIGN1cnJlbnRseSBvd25lZCBieSB0aGUgaW5zcGVjdG9yLlxuXHQgKlxuXHQgKiBAcGFyYW0ge251bWJlcn0gcmVzb3VyY2VfaWQgUmVzb3VyY2UgSUQgb3IgemVybyB0byBjbGVhciBoaWdobGlnaHRpbmcuXG5cdCAqIEByZXR1cm4ge3ZvaWR9XG5cdCAqL1xuXHRmdW5jdGlvbiBtYXJrX2luc3BlY3Rvcl9yZXNvdXJjZV9yb3coIHJlc291cmNlX2lkICkge1xuXHRcdGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoICdbZGF0YS13cGJjLWJvb2tpbmctcmVzb3VyY2UtaWRdLmlzLWVkaXRvci1hY3RpdmUnICkuZm9yRWFjaCggZnVuY3Rpb24gKCByb3cgKSB7XG5cdFx0XHRyb3cuY2xhc3NMaXN0LnJlbW92ZSggJ2lzLWVkaXRvci1hY3RpdmUnICk7XG5cdFx0fSApO1xuXHRcdGlmICggcmVzb3VyY2VfaWQgKSB7XG5cdFx0XHR2YXIgcm93ID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtYm9va2luZy1yZXNvdXJjZS1pZD1cIicgKyBTdHJpbmcoIHJlc291cmNlX2lkICkgKyAnXCJdJyApO1xuXHRcdFx0aWYgKCByb3cgKSB7XG5cdFx0XHRcdHJvdy5jbGFzc0xpc3QuYWRkKCAnaXMtZWRpdG9yLWFjdGl2ZScgKTtcblx0XHRcdFx0cm93LnNjcm9sbEludG9WaWV3KCB7IGJsb2NrOiAnbmVhcmVzdCcsIGJlaGF2aW9yOiAnc21vb3RoJyB9ICk7XG5cdFx0XHR9XG5cdFx0fVxuXHR9XG5cblx0LyoqXG5cdCAqIFN5bmNocm9uaXplIHNoYXJlZCBlbXB0eSwgbG9hZGluZywgZXJyb3IsIGZvcm0sIGFuZCBmb290ZXIgc3RhdGVzLlxuXHQgKlxuXHQgKiBAcGFyYW0ge3N0cmluZ30gc3RhdGUgRW1wdHksIGxvYWRpbmcsIGVycm9yLCBvciBmb3JtLlxuXHQgKiBAcGFyYW0ge3N0cmluZ30gbWVzc2FnZSBPcHRpb25hbCBzYWZlIGVycm9yIG1lc3NhZ2UuXG5cdCAqIEByZXR1cm4ge3ZvaWR9XG5cdCAqL1xuXHRmdW5jdGlvbiBzZXRfaW5zcGVjdG9yX3N0YXRlKCBzdGF0ZSwgbWVzc2FnZSApIHtcblx0XHRpZiAoIGluc3BlY3Rvcl93b3JrZmxvd19jb250cm9sbGVyICkge1xuXHRcdFx0aW5zcGVjdG9yX3dvcmtmbG93X2NvbnRyb2xsZXIuc2V0X3N0YXRlKCBzdGF0ZSwgbWVzc2FnZSApO1xuXHRcdH1cblx0fVxuXG5cdC8qKlxuXHQgKiBTZXJpYWxpemUgY3VycmVudCBlZGl0YWJsZSBmaWVsZCB2YWx1ZXMgZm9yIGRpcnR5LXN0YXRlIGNvbXBhcmlzb24uXG5cdCAqXG5cdCAqIEByZXR1cm4ge3N0cmluZ30gU3RhYmxlIEpTT04gZmllbGQgc25hcHNob3QuXG5cdCAqL1xuXHRmdW5jdGlvbiBzZXJpYWxpemVfaW5zcGVjdG9yX2ZpZWxkcygpIHtcblx0XHR2YXIgZmllbGRzID0ge307XG5cdFx0ZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbCggJ1tkYXRhLXdwYmMtY2F0YWxvZy1yZXNvdXJjZS1pbnNwZWN0b3ItZm9ybV0gW2RhdGEtd3BiYy1jYXRhbG9nLXJlc291cmNlLXJhZGlvLWZpZWxkXTpjaGVja2VkJyApLmZvckVhY2goIGZ1bmN0aW9uICggZmllbGQgKSB7XG5cdFx0XHRmaWVsZHNbIGZpZWxkLmdldEF0dHJpYnV0ZSggJ2RhdGEtd3BiYy1jYXRhbG9nLXJlc291cmNlLXJhZGlvLWZpZWxkJyApIHx8ICcnIF0gPSBmaWVsZC52YWx1ZTtcblx0XHR9ICk7XG5cdFx0ZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbCggJ1tkYXRhLXdwYmMtY2F0YWxvZy1yZXNvdXJjZS1pbnNwZWN0b3ItZm9ybV0gW2RhdGEtd3BiYy1jYXRhbG9nLXJlc291cmNlLWZpZWxkXScgKS5mb3JFYWNoKCBmdW5jdGlvbiAoIGZpZWxkICkge1xuXHRcdFx0ZmllbGRzWyBmaWVsZC5nZXRBdHRyaWJ1dGUoICdkYXRhLXdwYmMtY2F0YWxvZy1yZXNvdXJjZS1maWVsZCcgKSB8fCAnJyBdID0gZmllbGQudmFsdWU7XG5cdFx0fSApO1xuXG5cdFx0cmV0dXJuIEpTT04uc3RyaW5naWZ5KCBmaWVsZHMgKTtcblx0fVxuXG5cdC8qKlxuXHQgKiBSZXR1cm4gdGhlIGN1cnJlbnRseSBzZWxlY3RlZCBSZXNvdXJjZSBjcmVhdGlvbiBtb2RlLlxuXHQgKlxuXHQgKiBAcmV0dXJuIHtzdHJpbmd9IEluZGVwZW5kZW50IG9yIGNoaWxkcmVuLlxuXHQgKi9cblx0ZnVuY3Rpb24gZ2V0X2luc3BlY3Rvcl9jcmVhdGlvbl9tb2RlKCkge1xuXHRcdHZhciBzZWxlY3RlZF9tb2RlID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtY2F0YWxvZy1yZXNvdXJjZS1yYWRpby1maWVsZD1cImNyZWF0aW9uX21vZGVcIl06Y2hlY2tlZCcgKTtcblx0XHR2YXIgaGlkZGVuX21vZGUgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCAnW2RhdGEtd3BiYy1jYXRhbG9nLXJlc291cmNlLWZpZWxkPVwiY3JlYXRpb25fbW9kZVwiXScgKTtcblxuXHRcdHJldHVybiBTdHJpbmcoIHNlbGVjdGVkX21vZGUgPyBzZWxlY3RlZF9tb2RlLnZhbHVlIDogaGlkZGVuX21vZGUgPyBoaWRkZW5fbW9kZS52YWx1ZSA6ICdpbmRlcGVuZGVudCcgKTtcblx0fVxuXG5cdC8qKlxuXHQgKiBTeW5jaHJvbml6ZSBjcmVhdGUtb25seSBjb25kaXRpb25hbCBmaWVsZHMgYW5kIHJhZGlvLWNhcmQgcHJlc2VudGF0aW9uLlxuXHQgKlxuXHQgKiBUaGVzZSBjb250cm9scyBpbXByb3ZlIGNsYXJpdHkgb25seS4gVGhlIGNyZWF0ZSBzZXJ2aWNlIGluZGVwZW5kZW50bHlcblx0ICogYXV0aG9yaXplcyB0aGUgc2VsZWN0ZWQgcGFyZW50IGFuZCBkZXJpdmVzIGFsbCBpbmhlcml0ZWQgdmFsdWVzLlxuXHQgKlxuXHQgKiBAcmV0dXJuIHt2b2lkfVxuXHQgKi9cblx0ZnVuY3Rpb24gc3luY2hyb25pemVfY3JlYXRlX2luc3BlY3Rvcl9jb250cm9scygpIHtcblx0XHRpZiAoICdjcmVhdGUnICE9PSBpbnNwZWN0b3JfbW9kZSApIHtcblx0XHRcdHJldHVybjtcblx0XHR9XG5cblx0XHR2YXIgY3JlYXRpb25fbW9kZSA9IGdldF9pbnNwZWN0b3JfY3JlYXRpb25fbW9kZSgpO1xuXHRcdHZhciBwYXJlbnRfd3JhcCA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLWNhdGFsb2ctcmVzb3VyY2UtZmllbGQtd3JhcD1cInBhcmVudF9pZFwiXScgKTtcblxuXHRcdGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoICdbZGF0YS13cGJjLWNhdGFsb2ctcmVzb3VyY2UtcmFkaW8tZmllbGQ9XCJjcmVhdGlvbl9tb2RlXCJdJyApLmZvckVhY2goIGZ1bmN0aW9uICggcmFkaW8gKSB7XG5cdFx0XHR2YXIgY2hvaWNlID0gcmFkaW8uY2xvc2VzdCggJ2xhYmVsJyApO1xuXHRcdFx0aWYgKCBjaG9pY2UgKSB7XG5cdFx0XHRcdGNob2ljZS5jbGFzc0xpc3QudG9nZ2xlKCAnaXMtc2VsZWN0ZWQnLCByYWRpby5jaGVja2VkICk7XG5cdFx0XHR9XG5cdFx0fSApO1xuXHRcdGlmICggcGFyZW50X3dyYXAgKSB7XG5cdFx0XHRwYXJlbnRfd3JhcC5oaWRkZW4gPSAnY2hpbGRyZW4nICE9PSBjcmVhdGlvbl9tb2RlO1xuXHRcdFx0cGFyZW50X3dyYXAuY2xhc3NMaXN0LnRvZ2dsZSggJ2lzLWNvbmRpdGlvbmFsbHktaGlkZGVuJywgJ2NoaWxkcmVuJyAhPT0gY3JlYXRpb25fbW9kZSApO1xuXHRcdH1cblx0XHRbICdiYXNlX2Nvc3QnLCAnZGVmYXVsdF9mb3JtJywgJ293bmVyX3VzZXJfaWQnIF0uZm9yRWFjaCggZnVuY3Rpb24gKCBmaWVsZF9rZXkgKSB7XG5cdFx0XHR2YXIgZmllbGRfd3JhcCA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLWNhdGFsb2ctcmVzb3VyY2UtZmllbGQtd3JhcD1cIicgKyBmaWVsZF9rZXkgKyAnXCJdJyApO1xuXHRcdFx0aWYgKCBmaWVsZF93cmFwICkge1xuXHRcdFx0XHRmaWVsZF93cmFwLmhpZGRlbiA9ICdjaGlsZHJlbicgPT09IGNyZWF0aW9uX21vZGU7XG5cdFx0XHRcdGZpZWxkX3dyYXAuY2xhc3NMaXN0LnRvZ2dsZSggJ2lzLWNvbmRpdGlvbmFsbHktaGlkZGVuJywgJ2NoaWxkcmVuJyA9PT0gY3JlYXRpb25fbW9kZSApO1xuXHRcdFx0fVxuXHRcdH0gKTtcblx0fVxuXG5cdC8qKlxuXHQgKiBTeW5jaHJvbml6ZSBSZXNvdXJjZS1zcGVjaWZpYyBjaGFuZ2VvdmVyIGNvbnRyb2xzIHdpdGggdGhlIHNlbGVjdGVkIG1vZGUuXG5cdCAqXG5cdCAqIFZpc2liaWxpdHkgaXMgYSB1c2FiaWxpdHkgYWlkIG9ubHkuIFRoZSBSZXNvdXJjZSBtdXRhdGlvbiBzZXJ2aWNlIHJlbG9hZHNcblx0ICogYW5kIHZhbGlkYXRlcyB0aGUgcG9saWN5LCBvd25lcnNoaXAsIGVkaXRpb24sIGFuZCBleGFjdCB0aW1lIHZhbHVlcy5cblx0ICpcblx0ICogQHJldHVybiB7dm9pZH1cblx0ICovXG5cdGZ1bmN0aW9uIHN5bmNocm9uaXplX2NoYW5nZW92ZXJfcG9saWN5X2NvbnRyb2xzKCkge1xuXHRcdHZhciBmb3JtID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtY2F0YWxvZy1yZXNvdXJjZS1pbnNwZWN0b3ItZm9ybV1bZGF0YS1tb2RlPVwiZWRpdFwiXScgKTtcblx0XHR2YXIgcG9saWN5X2ZpZWxkID0gZm9ybSA/IGZvcm0ucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtY2F0YWxvZy1yZXNvdXJjZS1maWVsZD1cImNoYW5nZW92ZXJfcG9saWN5X21vZGVcIl0nICkgOiBudWxsO1xuXHRcdHZhciBpc19jdXN0b20gPSAhISBwb2xpY3lfZmllbGQgJiYgJ2N1c3RvbScgPT09IFN0cmluZyggcG9saWN5X2ZpZWxkLnZhbHVlIHx8ICcnICk7XG5cdFx0dmFyIGdsb2JhbF90aW1lc193cmFwID0gZm9ybSA/IGZvcm0ucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtY2F0YWxvZy1yZXNvdXJjZS1maWVsZC13cmFwPVwiY2hhbmdlb3Zlcl9nbG9iYWxfdGltZXNcIl0nICkgOiBudWxsO1xuXG5cdFx0aWYgKCBnbG9iYWxfdGltZXNfd3JhcCApIHtcblx0XHRcdGdsb2JhbF90aW1lc193cmFwLmhpZGRlbiA9ICEgcG9saWN5X2ZpZWxkIHx8ICdnbG9iYWwnICE9PSBTdHJpbmcoIHBvbGljeV9maWVsZC52YWx1ZSB8fCAnJyApO1xuXHRcdFx0Z2xvYmFsX3RpbWVzX3dyYXAuY2xhc3NMaXN0LnRvZ2dsZSggJ2lzLWNvbmRpdGlvbmFsbHktaGlkZGVuJywgZ2xvYmFsX3RpbWVzX3dyYXAuaGlkZGVuICk7XG5cdFx0fVxuXG5cdFx0WyAnY2hhbmdlb3Zlcl9jaGVja19pbl90aW1lJywgJ2NoYW5nZW92ZXJfY2hlY2tfb3V0X3RpbWUnIF0uZm9yRWFjaCggZnVuY3Rpb24gKCBmaWVsZF9rZXkgKSB7XG5cdFx0XHR2YXIgZmllbGRfd3JhcCA9IGZvcm0gPyBmb3JtLnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLWNhdGFsb2ctcmVzb3VyY2UtZmllbGQtd3JhcD1cIicgKyBmaWVsZF9rZXkgKyAnXCJdJyApIDogbnVsbDtcblx0XHRcdHZhciBmaWVsZCA9IGZvcm0gPyBmb3JtLnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLWNhdGFsb2ctcmVzb3VyY2UtZmllbGQ9XCInICsgZmllbGRfa2V5ICsgJ1wiXScgKSA6IG51bGw7XG5cdFx0XHRpZiAoIGZpZWxkX3dyYXAgKSB7XG5cdFx0XHRcdGZpZWxkX3dyYXAuaGlkZGVuID0gISBpc19jdXN0b207XG5cdFx0XHRcdGZpZWxkX3dyYXAuY2xhc3NMaXN0LnRvZ2dsZSggJ2lzLWNvbmRpdGlvbmFsbHktaGlkZGVuJywgISBpc19jdXN0b20gKTtcblx0XHRcdH1cblx0XHRcdGlmICggZmllbGQgKSB7XG5cdFx0XHRcdGZpZWxkLmRpc2FibGVkID0gISBpc19jdXN0b207XG5cdFx0XHRcdGZpZWxkLnJlcXVpcmVkID0gaXNfY3VzdG9tO1xuXHRcdFx0fVxuXHRcdH0gKTtcblx0fVxuXG5cdC8qKlxuXHQgKiBSZWNvcmQgYSBkZWxpYmVyYXRlIGNoYW5nZSB0byB0aGUgUmVzb3VyY2UgY2hhbmdlb3ZlciBmaWVsZHMuXG5cdCAqXG5cdCAqIFVudG91Y2hlZCBJbnNwZWN0b3Igc3VibWlzc2lvbnMgcHJlc2VydmUgYWJzZW50IG1ldGFkYXRhIGFuZCB0aGVyZWZvcmUgZG9cblx0ICogbm90IG1pZ3JhdGUgdGhlIGxlZ2FjeSBleGNlcHRpb24gbGlzdCBtZXJlbHkgYmVjYXVzZSBhbm90aGVyIGZpZWxkIGNoYW5nZWQuXG5cdCAqXG5cdCAqIEBwYXJhbSB7SFRNTEVsZW1lbnR9IGZpZWxkIENoYW5nZWQgY29udHJvbC5cblx0ICogQHJldHVybiB7dm9pZH1cblx0ICovXG5cdGZ1bmN0aW9uIG1hcmtfY2hhbmdlb3Zlcl9wb2xpY3lfdG91Y2hlZCggZmllbGQgKSB7XG5cdFx0dmFyIGZpZWxkX2tleSA9IGZpZWxkID8gU3RyaW5nKCBmaWVsZC5nZXRBdHRyaWJ1dGUoICdkYXRhLXdwYmMtY2F0YWxvZy1yZXNvdXJjZS1maWVsZCcgKSB8fCAnJyApIDogJyc7XG5cdFx0dmFyIHRvdWNoZWRfZmllbGQ7XG5cblx0XHRpZiAoIC0xID09PSBbICdjaGFuZ2VvdmVyX3BvbGljeV9tb2RlJywgJ2NoYW5nZW92ZXJfY2hlY2tfaW5fdGltZScsICdjaGFuZ2VvdmVyX2NoZWNrX291dF90aW1lJyBdLmluZGV4T2YoIGZpZWxkX2tleSApICkge1xuXHRcdFx0cmV0dXJuO1xuXHRcdH1cblx0XHR0b3VjaGVkX2ZpZWxkID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtY2F0YWxvZy1yZXNvdXJjZS1pbnNwZWN0b3ItZm9ybV0gW2RhdGEtd3BiYy1jYXRhbG9nLXJlc291cmNlLWZpZWxkPVwiY2hhbmdlb3Zlcl9wb2xpY3lfdG91Y2hlZFwiXScgKTtcblx0XHRpZiAoIHRvdWNoZWRfZmllbGQgKSB7XG5cdFx0XHR0b3VjaGVkX2ZpZWxkLnZhbHVlID0gJzEnO1xuXHRcdH1cblx0fVxuXG5cdC8qKlxuXHQgKiBTeW5jaHJvbml6ZSBkaXJ0eSBzdGF0ZSBhbmQgdGhlIHN0aWNreSBwcmltYXJ5IGFjdGlvbi5cblx0ICpcblx0ICogQHJldHVybiB7dm9pZH1cblx0ICovXG5cdGZ1bmN0aW9uIHN5bmNocm9uaXplX2luc3BlY3Rvcl9kaXJ0eV9zdGF0ZSgpIHtcblx0XHR2YXIgc2F2ZV9idXR0b24gPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCAnW2RhdGEtd3BiYy11aS1jYXRhbG9nLWluc3BlY3Rvci1zYXZlXScgKTtcblx0XHR2YXIgZm9ybSA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLWNhdGFsb2ctcmVzb3VyY2UtaW5zcGVjdG9yLWZvcm1dJyApO1xuXHRcdHZhciBzYXZlX2lzX2J1c3kgPSAhISBzYXZlX2J1dHRvbiAmJiBzYXZlX2J1dHRvbi5jbGFzc0xpc3QuY29udGFpbnMoICdpcy1idXN5JyApO1xuXG5cdFx0aW5zcGVjdG9yX2RpcnR5ID0gISEgZm9ybSAmJiBzZXJpYWxpemVfaW5zcGVjdG9yX2ZpZWxkcygpICE9PSBpbnNwZWN0b3Jfb3JpZ2luYWxfZmllbGRzO1xuXHRcdGlmICggc2F2ZV9idXR0b24gKSB7XG5cdFx0XHR2YXIgdGl0bGVfZmllbGQgPSBmb3JtID8gZm9ybS5xdWVyeVNlbGVjdG9yKCAnW2RhdGEtd3BiYy1jYXRhbG9nLXJlc291cmNlLWZpZWxkPVwidGl0bGVcIl0nICkgOiBudWxsO1xuXHRcdFx0dmFyIHBhcmVudF9maWVsZCA9IGZvcm0gPyBmb3JtLnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLWNhdGFsb2ctcmVzb3VyY2UtZmllbGQ9XCJwYXJlbnRfaWRcIl0nICkgOiBudWxsO1xuXHRcdFx0dmFyIGNyZWF0ZV9pc192YWxpZCA9ICdjcmVhdGUnICE9PSBpbnNwZWN0b3JfbW9kZVxuXHRcdFx0XHR8fCAoIGZvcm1cblx0XHRcdFx0XHQmJiAndHJ1ZScgPT09IGZvcm0uZ2V0QXR0cmlidXRlKCAnZGF0YS1jYW4tY3JlYXRlJyApXG5cdFx0XHRcdFx0JiYgdGl0bGVfZmllbGRcblx0XHRcdFx0XHQmJiAnJyAhPT0gU3RyaW5nKCB0aXRsZV9maWVsZC52YWx1ZSB8fCAnJyApLnRyaW0oKVxuXHRcdFx0XHRcdCYmICggJ2NoaWxkcmVuJyAhPT0gZ2V0X2luc3BlY3Rvcl9jcmVhdGlvbl9tb2RlKCkgfHwgKCBwYXJlbnRfZmllbGQgJiYgTnVtYmVyKCBwYXJlbnRfZmllbGQudmFsdWUgKSA+IDAgKSApICk7XG5cblx0XHRcdHNhdmVfYnV0dG9uLmRpc2FibGVkID0gc2F2ZV9pc19idXN5IHx8ICEgZm9ybSB8fCAhIGluc3BlY3Rvcl9kaXJ0eSB8fCAhIGNyZWF0ZV9pc192YWxpZDtcblx0XHR9XG5cdH1cblxuXHQvKipcblx0ICogQ29uZmlybSB3aGV0aGVyIHRoZSBhY3RpdmUgaW5zcGVjdG9yIG1heSBiZSByZXBsYWNlZCBvciBjbG9zZWQuXG5cdCAqXG5cdCAqIEBwYXJhbSB7T2JqZWN0fSBjb25maWcgQ2F0YWxvZyBjb25maWd1cmF0aW9uLlxuXHQgKiBAcmV0dXJuIHtib29sZWFufSBUcnVlIHdoZW4gbmF2aWdhdGlvbiBtYXkgY29udGludWUuXG5cdCAqL1xuXHRmdW5jdGlvbiBjYW5fZGlzY2FyZF9pbnNwZWN0b3IoIGNvbmZpZyApIHtcblx0XHRpZiAoIGluc3BlY3Rvcl9tdXRhdGlvbl9pbl9wcm9ncmVzcyApIHtcblx0XHRcdHJldHVybiBmYWxzZTtcblx0XHR9XG5cblx0XHRyZXR1cm4gISBpbnNwZWN0b3JfZGlydHkgfHwgd2luZG93LmNvbmZpcm0oIGNvbmZpZy5pMThuLmluc3BlY3Rvcl9kaXNjYXJkIHx8ICcnICk7XG5cdH1cblxuXHQvKipcblx0ICogQ2xvc2UgdGhlIGluc3BlY3RvciB3aXRob3V0IGNoYW5naW5nIGNhdGFsb2cgY2hlY2tib3ggc2VsZWN0aW9uLlxuXHQgKlxuXHQgKiBAcGFyYW0ge09iamVjdH0gIGNvbmZpZyAgICAgICAgIENhdGFsb2cgY29uZmlndXJhdGlvbi5cblx0ICogQHBhcmFtIHtib29sZWFufSBjb25maXJtX2Rpc2NhcmQgV2hldGhlciBkaXJ0eSBzdGF0ZSBuZWVkcyBjb25maXJtYXRpb24uXG5cdCAqIEByZXR1cm4ge2Jvb2xlYW59IFRydWUgd2hlbiBjbG9zZWQuXG5cdCAqL1xuXHRmdW5jdGlvbiBjbG9zZV9pbnNwZWN0b3IoIGNvbmZpZywgY29uZmlybV9kaXNjYXJkICkge1xuXHRcdGlmICggY29uZmlybV9kaXNjYXJkICYmICEgY2FuX2Rpc2NhcmRfaW5zcGVjdG9yKCBjb25maWcgKSApIHtcblx0XHRcdHJldHVybiBmYWxzZTtcblx0XHR9XG5cblx0XHRpbnNwZWN0b3JfcmVxdWVzdF9zZXF1ZW5jZSArPSAxO1xuXHRcdGluc3BlY3Rvcl9kaXJ0eSA9IGZhbHNlO1xuXHRcdGluc3BlY3Rvcl9tb2RlID0gJyc7XG5cdFx0aW5zcGVjdG9yX29yaWdpbmFsX2ZpZWxkcyA9ICcnO1xuXHRcdGluc3BlY3Rvcl9yZXNvdXJjZV9pZCA9IDA7XG5cdFx0aW5zcGVjdG9yX3Jlc291cmNlX2lkcyA9IFtdO1xuXHRcdGluc3BlY3Rvcl9idWxrX29wZXJhdGlvbnMgPSB7fTtcblx0XHRpbnNwZWN0b3JfcmV2aWV3X3Rva2VuID0gJyc7XG5cdFx0aW5zcGVjdG9yX3NlbGVjdGlvbl9zdGFsZSA9IGZhbHNlO1xuXHRcdGluc3BlY3Rvcl90cmFja3Nfc2VsZWN0aW9uID0gZmFsc2U7XG5cdFx0aW5zcGVjdG9yX2NhcGFjaXR5X2NvbnRleHQgPSBudWxsO1xuXHRcdGluc3BlY3Rvcl9jYXBhY2l0eV9kZXRhY2hfaWRzID0gW107XG5cdFx0aW5zcGVjdG9yX2NhcGFjaXR5X2RlY3JlYXNlX2FjdGlvbiA9ICdkZXRhY2gnO1xuXHRcdGluc3BlY3Rvcl9jYXBhY2l0eV90YXJnZXQgPSAwO1xuXHRcdHN5bmNocm9uaXplX2luc3BlY3Rvcl93aWR0aCgpO1xuXHRcdHNldF9pbnNwZWN0b3Jfc3RhdGUoICdlbXB0eScsICcnICk7XG5cdFx0bWFya19pbnNwZWN0b3JfcmVzb3VyY2Vfcm93KCAwICk7XG5cdFx0aWYgKCAnZnVuY3Rpb24nID09PSB0eXBlb2Ygd2luZG93LndwYmNfYWRtaW5fdWlfX3NpZGViYXJfcmlnaHRfX2RvX2hpZGUgKSB7XG5cdFx0XHR3aW5kb3cud3BiY19hZG1pbl91aV9fc2lkZWJhcl9yaWdodF9fZG9faGlkZSgpO1xuXHRcdH1cblx0XHRkb2N1bWVudC5kaXNwYXRjaEV2ZW50KCBuZXcgQ3VzdG9tRXZlbnQoICd3cGJjX3NldHVwX3dpemFyZF9sYXlvdXRfY2hhbmdlZCcgKSApO1xuXHRcdGlmICggaW5zcGVjdG9yX2ZvY3VzX3RhcmdldCAmJiBkb2N1bWVudC5kb2N1bWVudEVsZW1lbnQuY29udGFpbnMoIGluc3BlY3Rvcl9mb2N1c190YXJnZXQgKSAmJiAnZnVuY3Rpb24nID09PSB0eXBlb2YgaW5zcGVjdG9yX2ZvY3VzX3RhcmdldC5mb2N1cyApIHtcblx0XHRcdGluc3BlY3Rvcl9mb2N1c190YXJnZXQuZm9jdXMoKTtcblx0XHR9XG5cdFx0aW5zcGVjdG9yX2ZvY3VzX3RhcmdldCA9IG51bGw7XG5cblx0XHRyZXR1cm4gdHJ1ZTtcblx0fVxuXG5cdC8qKlxuXHQgKiBQb3N0IG9uZSBhdXRoZW50aWNhdGVkIGluc3BlY3RvciByZXF1ZXN0LlxuXHQgKlxuXHQgKiBAcGFyYW0ge09iamVjdH0gY29uZmlnIENhdGFsb2cgY29uZmlndXJhdGlvbi5cblx0ICogQHBhcmFtIHtzdHJpbmd9IGFjdGlvbiBBSkFYIGFjdGlvbi5cblx0ICogQHBhcmFtIHtPYmplY3R9IHZhbHVlcyBSZXF1ZXN0IHZhbHVlcy5cblx0ICogQHJldHVybiB7UHJvbWlzZTxPYmplY3Q+fSBQYXJzZWQgV29yZFByZXNzIHJlc3BvbnNlLlxuXHQgKi9cblx0ZnVuY3Rpb24gcmVxdWVzdF9pbnNwZWN0b3IoIGNvbmZpZywgYWN0aW9uLCB2YWx1ZXMgKSB7XG5cdFx0dmFyIGJvZHkgPSBuZXcgd2luZG93LlVSTFNlYXJjaFBhcmFtcygpO1xuXG5cdFx0Ym9keS5hcHBlbmQoICdhY3Rpb24nLCBhY3Rpb24gKTtcblx0XHRib2R5LmFwcGVuZCggJ25vbmNlJywgY29uZmlnLm5vbmNlIHx8ICcnICk7XG5cdFx0T2JqZWN0LmtleXMoIHZhbHVlcyB8fCB7fSApLmZvckVhY2goIGZ1bmN0aW9uICgga2V5ICkge1xuXHRcdFx0Ym9keS5hcHBlbmQoIGtleSwgU3RyaW5nKCB2YWx1ZXNbIGtleSBdICkgKTtcblx0XHR9ICk7XG5cblx0XHRyZXR1cm4gd2luZG93LmZldGNoKCBjb25maWcuYWpheF91cmwsIHtcblx0XHRcdGJvZHk6IGJvZHkudG9TdHJpbmcoKSxcblx0XHRcdGNyZWRlbnRpYWxzOiAnc2FtZS1vcmlnaW4nLFxuXHRcdFx0aGVhZGVyczogeyAnQ29udGVudC1UeXBlJzogJ2FwcGxpY2F0aW9uL3gtd3d3LWZvcm0tdXJsZW5jb2RlZDsgY2hhcnNldD1VVEYtOCcgfSxcblx0XHRcdG1ldGhvZDogJ1BPU1QnXG5cdFx0fSApLnRoZW4oIGZ1bmN0aW9uICggcmVzcG9uc2UgKSB7XG5cdFx0XHRyZXR1cm4gcmVzcG9uc2UuanNvbigpO1xuXHRcdH0gKTtcblx0fVxuXG5cdC8qKlxuXHQgKiBSZXR1cm4gdGhlIGV4cGxpY2l0IHNlbGVjdGlvbiBvd25lZCBieSB0aGUgc2hhcmVkIGNhdGFsb2cgY29udHJvbGxlci5cblx0ICpcblx0ICogQHBhcmFtIHtPYmplY3R9IGNvbmZpZyBDYXRhbG9nIGNvbmZpZ3VyYXRpb24uXG5cdCAqIEByZXR1cm4ge0FycmF5PG51bWJlcj59IFNlbGVjdGVkIHBvc2l0aXZlIFJlc291cmNlIElEcy5cblx0ICovXG5cdGZ1bmN0aW9uIGdldF9zZWxlY3RlZF9yZXNvdXJjZV9pZHMoIGNvbmZpZyApIHtcblx0XHR2YXIgbW91bnQgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCggY29uZmlnLm1vdW50X2lkICk7XG5cdFx0dmFyIHNlbGVjdGlvbiA9IG1vdW50ICYmIG1vdW50Ll93cGJjX3VpX2NhdGFsb2dfc2VsZWN0aW9uX2NvbnRyb2xsZXI7XG5cdFx0dmFyIHNlbGVjdGVkX2lkcyA9IHNlbGVjdGlvbiAmJiAnZnVuY3Rpb24nID09PSB0eXBlb2Ygc2VsZWN0aW9uLmdldF9zZWxlY3RlZF9pZHMgPyBzZWxlY3Rpb24uZ2V0X3NlbGVjdGVkX2lkcygpIDogW107XG5cblx0XHRyZXR1cm4gc2VsZWN0ZWRfaWRzLm1hcCggTnVtYmVyICkuZmlsdGVyKCBmdW5jdGlvbiAoIHJlc291cmNlX2lkICkge1xuXHRcdFx0cmV0dXJuIHJlc291cmNlX2lkID4gMDtcblx0XHR9ICk7XG5cdH1cblxuXHQvKipcblx0ICogQ29tcGFyZSB0d28gUmVzb3VyY2UtSUQgc2VsZWN0aW9ucyB3aXRob3V0IHJlbHlpbmcgb24gZXZlbnQgb3JkZXJpbmcuXG5cdCAqXG5cdCAqIEBwYXJhbSB7QXJyYXk8bnVtYmVyfHN0cmluZz59IGZpcnN0X2lkcyAgRmlyc3QgSUQgbGlzdC5cblx0ICogQHBhcmFtIHtBcnJheTxudW1iZXJ8c3RyaW5nPn0gc2Vjb25kX2lkcyBTZWNvbmQgSUQgbGlzdC5cblx0ICogQHJldHVybiB7Ym9vbGVhbn0gVHJ1ZSB3aGVuIGJvdGggbGlzdHMgY29udGFpbiB0aGUgc2FtZSBSZXNvdXJjZSBJRHMuXG5cdCAqL1xuXHRmdW5jdGlvbiByZXNvdXJjZV9pZF9saXN0c19tYXRjaCggZmlyc3RfaWRzLCBzZWNvbmRfaWRzICkge1xuXHRcdHZhciBub3JtYWxpemVfaWRzID0gZnVuY3Rpb24gKCByZXNvdXJjZV9pZHMgKSB7XG5cdFx0XHRyZXR1cm4gKCByZXNvdXJjZV9pZHMgfHwgW10gKS5tYXAoIE51bWJlciApLmZpbHRlciggZnVuY3Rpb24gKCByZXNvdXJjZV9pZCApIHtcblx0XHRcdFx0cmV0dXJuIHJlc291cmNlX2lkID4gMDtcblx0XHRcdH0gKS5zb3J0KCBmdW5jdGlvbiAoIGZpcnN0X2lkLCBzZWNvbmRfaWQgKSB7XG5cdFx0XHRcdHJldHVybiBmaXJzdF9pZCAtIHNlY29uZF9pZDtcblx0XHRcdH0gKTtcblx0XHR9O1xuXG5cdFx0cmV0dXJuIEpTT04uc3RyaW5naWZ5KCBub3JtYWxpemVfaWRzKCBmaXJzdF9pZHMgKSApID09PSBKU09OLnN0cmluZ2lmeSggbm9ybWFsaXplX2lkcyggc2Vjb25kX2lkcyApICk7XG5cdH1cblxuXHQvKipcblx0ICogUmV0dXJuIGEgbG9jYWxpemVkIHNlbGVjdGlvbi1jb3VudCBsYWJlbC5cblx0ICpcblx0ICogQHBhcmFtIHtPYmplY3R9IGNvbmZpZyBDYXRhbG9nIGNvbmZpZ3VyYXRpb24uXG5cdCAqIEBwYXJhbSB7bnVtYmVyfSBjb3VudCAgTnVtYmVyIG9mIHNlbGVjdGVkIFJlc291cmNlcy5cblx0ICogQHJldHVybiB7c3RyaW5nfSBDb3VudCBhbmQgbG9jYWxpemVkIG5vdW4uXG5cdCAqL1xuXHRmdW5jdGlvbiBnZXRfc2VsZWN0aW9uX2NvdW50X2xhYmVsKCBjb25maWcsIGNvdW50ICkge1xuXHRcdHJldHVybiBTdHJpbmcoIGNvdW50ICkgKyAnICcgKyAoIDEgPT09IE51bWJlciggY291bnQgKSA/IGNvbmZpZy5pMThuLnJlc291cmNlX3NlbGVjdGVkIHx8ICcnIDogY29uZmlnLmkxOG4ucmVzb3VyY2VzX3NlbGVjdGVkIHx8ICcnICk7XG5cdH1cblxuXHQvKipcblx0ICogQ29uZmlndXJlIHRoZSBuYXRpdmUgc3RpY2t5IGZvb3RlciBmb3IgdGhlIGFjdGl2ZSBpbnNwZWN0b3Igd29ya2Zsb3cuXG5cdCAqXG5cdCAqIEBwYXJhbSB7c3RyaW5nfSBmb3JtX2lkICAgICAgRm9ybSByZWNlaXZpbmcgdGhlIHN1Ym1pdCBhY3Rpb24uXG5cdCAqIEBwYXJhbSB7c3RyaW5nfSBidXR0b25fbGFiZWwgTG9jYWxpemVkIHByaW1hcnkgbGFiZWwuXG5cdCAqIEBwYXJhbSB7Ym9vbGVhbn0gZGVzdHJ1Y3RpdmUgV2hldGhlciB0aGUgYWN0aW9uIHBlcm1hbmVudGx5IGRlbGV0ZXMgZGF0YS5cblx0ICogQHBhcmFtIHtib29sZWFufSBkaXNhYmxlZCAgICBXaGV0aGVyIHN1Ym1pc3Npb24gc3RhcnRzIGRpc2FibGVkLlxuXHQgKiBAcmV0dXJuIHt2b2lkfVxuXHQgKi9cblx0ZnVuY3Rpb24gY29uZmlndXJlX2luc3BlY3Rvcl9mb290ZXIoIGZvcm1faWQsIGJ1dHRvbl9sYWJlbCwgZGVzdHJ1Y3RpdmUsIGRpc2FibGVkICkge1xuXHRcdHZhciBmb290ZXIgPSBnZXRfaW5zcGVjdG9yX2Zvb3RlcigpO1xuXHRcdHZhciBzYXZlX2J1dHRvbiA9IGZvb3RlciA/IGZvb3Rlci5xdWVyeVNlbGVjdG9yKCAnW2RhdGEtd3BiYy11aS1jYXRhbG9nLWluc3BlY3Rvci1zYXZlXScgKSA6IG51bGw7XG5cdFx0dmFyIGNhbmNlbF9idXR0b24gPSBmb290ZXIgPyBmb290ZXIucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtdWktY2F0YWxvZy1pbnNwZWN0b3ItY2FuY2VsXScgKSA6IG51bGw7XG5cdFx0dmFyIGRlbGV0ZV93b3JrZmxvdztcblxuXHRcdGlmICggc2F2ZV9idXR0b24gKSB7XG5cdFx0XHRzYXZlX2J1dHRvbi5jbGFzc0xpc3QucmVtb3ZlKCAnaXMtYnVzeScsICdidXR0b24tbGluay1kZWxldGUnLCAnd3BiY19jYXRhbG9nX2Jvb2tpbmdfcmVzb3VyY2VzX19kZWxldGVfc3VibWl0JywgJ3dwYmNfdWlfbGlzdGluZ19faW5zcGVjdG9yX2FjdGlvbi0tZGVzdHJ1Y3RpdmUnLCAnd3BiY19ib29raW5nX3Jlc291cmNlc19fZGVsZXRlX2NvbmZpcm1fYnV0dG9uJywgJ3dwYmNfdWlfY2F0YWxvZ19kZWxldGVfcmV2aWV3X19hcHBseScgKTtcblx0XHRcdHNhdmVfYnV0dG9uLmNsYXNzTGlzdC50b2dnbGUoICdidXR0b24tcHJpbWFyeScsICEgZGVzdHJ1Y3RpdmUgKTtcblx0XHRcdHNhdmVfYnV0dG9uLmNsYXNzTGlzdC50b2dnbGUoICdidXR0b24tc2Vjb25kYXJ5JywgISEgZGVzdHJ1Y3RpdmUgKTtcblx0XHRcdHNhdmVfYnV0dG9uLmNsYXNzTGlzdC50b2dnbGUoICd3cGJjX3VpX2xpc3RpbmdfX2luc3BlY3Rvcl9hY3Rpb24tLWRlc3RydWN0aXZlJywgISEgZGVzdHJ1Y3RpdmUgKTtcblx0XHRcdHNhdmVfYnV0dG9uLmNsYXNzTGlzdC50b2dnbGUoICd3cGJjX2Jvb2tpbmdfcmVzb3VyY2VzX19kZWxldGVfY29uZmlybV9idXR0b24nLCAhISBkZXN0cnVjdGl2ZSApO1xuXHRcdFx0c2F2ZV9idXR0b24udGV4dENvbnRlbnQgPSBidXR0b25fbGFiZWwgfHwgJyc7XG5cdFx0XHRzYXZlX2J1dHRvbi5zZXRBdHRyaWJ1dGUoICdmb3JtJywgZm9ybV9pZCApO1xuXHRcdFx0c2F2ZV9idXR0b24uZGlzYWJsZWQgPSAhISBkaXNhYmxlZDtcblx0XHR9XG5cdFx0aWYgKCBjYW5jZWxfYnV0dG9uICkge1xuXHRcdFx0Y2FuY2VsX2J1dHRvbi50ZXh0Q29udGVudCA9IHdpbmRvdy53cGJjX2NhdGFsb2dfYm9va2luZ19yZXNvdXJjZXNfY29uZmlnICYmIHdpbmRvdy53cGJjX2NhdGFsb2dfYm9va2luZ19yZXNvdXJjZXNfY29uZmlnLmkxOG5cblx0XHRcdFx0PyB3aW5kb3cud3BiY19jYXRhbG9nX2Jvb2tpbmdfcmVzb3VyY2VzX2NvbmZpZy5pMThuLmNhbmNlbCB8fCBjYW5jZWxfYnV0dG9uLnRleHRDb250ZW50XG5cdFx0XHRcdDogY2FuY2VsX2J1dHRvbi50ZXh0Q29udGVudDtcblx0XHRcdGNhbmNlbF9idXR0b24uZGlzYWJsZWQgPSBmYWxzZTtcblx0XHR9XG5cdFx0aWYgKCBkZXN0cnVjdGl2ZSAmJiAnd3BiY19jYXRhbG9nX2Jvb2tpbmdfcmVzb3VyY2VzX2RlbGV0ZV9mb3JtJyA9PT0gZm9ybV9pZCApIHtcblx0XHRcdGRlbGV0ZV93b3JrZmxvdyA9IGdldF9kZWxldGVfcmV2aWV3X3dvcmtmbG93KCk7XG5cdFx0XHRpZiAoIGRlbGV0ZV93b3JrZmxvdyApIHtcblx0XHRcdFx0ZGVsZXRlX3dvcmtmbG93LmNvbmZpZ3VyZV9mb290ZXIoIHtcblx0XHRcdFx0XHRjYW5fYXBwbHk6IHRydWUsXG5cdFx0XHRcdFx0Zm9vdGVyOiBmb290ZXIsXG5cdFx0XHRcdFx0Zm9ybV9pZDogZm9ybV9pZCxcblx0XHRcdFx0XHRsYWJlbDogYnV0dG9uX2xhYmVsXG5cdFx0XHRcdH0gKTtcblx0XHRcdH1cblx0XHR9XG5cdH1cblxuXHQvKipcblx0ICogRW1waGFzaXplIHRoZSBwZXJtYW5lbnQtZGVsZXRpb24gYWNrbm93bGVkZ2VtZW50LlxuXHQgKlxuXHQgKiBSZXN0YXJ0aW5nIHRoZSBmaW5pdGUgYW5pbWF0aW9uIG1pcnJvcnMgdGhlIGVzdGFibGlzaGVkIEJvb2tpbmcgUmVzb3VyY2Vcblx0ICogZWRpdG9yIGJlaGF2aW9yIHdoZW4gYSBkZWxldGlvbiByZXZpZXcgb3BlbnMgb3IgYWNrbm93bGVkZ2VtZW50IGlzIGNsZWFyZWQuXG5cdCAqXG5cdCAqIEBwYXJhbSB7SFRNTEVsZW1lbnR8bnVsbH0gYWNrbm93bGVkZ2VtZW50IERlbGV0aW9uIGFja25vd2xlZGdlbWVudCBsYWJlbC5cblx0ICogQHJldHVybiB7dm9pZH1cblx0ICovXG5cdGZ1bmN0aW9uIHB1bHNlX2RlbGV0ZV9hY2tub3dsZWRnZW1lbnQoIGFja25vd2xlZGdlbWVudCApIHtcblx0XHR2YXIgZGVsZXRlX3dvcmtmbG93ID0gZ2V0X2RlbGV0ZV9yZXZpZXdfd29ya2Zsb3coKTtcblxuXHRcdGlmICggYWNrbm93bGVkZ2VtZW50ICYmIGFja25vd2xlZGdlbWVudC5tYXRjaGVzKCAnLndwYmNfdWlfY2F0YWxvZ19kZWxldGVfcmV2aWV3X19hY2tub3dsZWRnZW1lbnQnICkgJiYgZGVsZXRlX3dvcmtmbG93ICkge1xuXHRcdFx0ZGVsZXRlX3dvcmtmbG93LnB1bHNlX2Fja25vd2xlZGdlbWVudCgpO1xuXHRcdFx0cmV0dXJuO1xuXHRcdH1cblx0XHRpZiAoICEgYWNrbm93bGVkZ2VtZW50ICkge1xuXHRcdFx0cmV0dXJuO1xuXHRcdH1cblxuXHRcdGFja25vd2xlZGdlbWVudC5jbGFzc0xpc3QucmVtb3ZlKCAnd3BiY19ib29raW5nX3Jlc291cmNlc19fZGVsZXRlX2Fja25vd2xlZGdlbWVudC0tYXR0ZW50aW9uJyApO1xuXHRcdHZvaWQgYWNrbm93bGVkZ2VtZW50Lm9mZnNldFdpZHRoO1xuXHRcdGFja25vd2xlZGdlbWVudC5jbGFzc0xpc3QuYWRkKCAnd3BiY19ib29raW5nX3Jlc291cmNlc19fZGVsZXRlX2Fja25vd2xlZGdlbWVudC0tYXR0ZW50aW9uJyApO1xuXHR9XG5cblx0LyoqXG5cdCAqIFJldHVybiBhIHNhZmUgbWVzc2FnZSBmcm9tIGEgV29yZFByZXNzIGluc3BlY3RvciByZXNwb25zZS5cblx0ICpcblx0ICogQHBhcmFtIHtPYmplY3R9IHJlc3BvbnNlIFJlc3BvbnNlIHBheWxvYWQuXG5cdCAqIEBwYXJhbSB7c3RyaW5nfSBmYWxsYmFjayBGYWxsYmFjayBtZXNzYWdlLlxuXHQgKiBAcmV0dXJuIHtzdHJpbmd9IFBsYWluIG1lc3NhZ2UuXG5cdCAqL1xuXHRmdW5jdGlvbiBnZXRfaW5zcGVjdG9yX3Jlc3BvbnNlX21lc3NhZ2UoIHJlc3BvbnNlLCBmYWxsYmFjayApIHtcblx0XHRyZXR1cm4gcmVzcG9uc2UgJiYgcmVzcG9uc2UuZGF0YSAmJiByZXNwb25zZS5kYXRhLm1lc3NhZ2UgPyBTdHJpbmcoIHJlc3BvbnNlLmRhdGEubWVzc2FnZSApIDogU3RyaW5nKCBmYWxsYmFjayB8fCAnJyApO1xuXHR9XG5cblx0LyoqXG5cdCAqIFNob3cgYSBzdWNjZXNzIG9yIGVycm9yIG5vdGljZSBpbiB0aGUgYWN0aXZlIGluc3BlY3Rvci5cblx0ICpcblx0ICogQHBhcmFtIHtIVE1MRm9ybUVsZW1lbnR9IGZvcm0gICAgIEluc3BlY3RvciBmb3JtLlxuXHQgKiBAcGFyYW0ge3N0cmluZ30gICAgICAgICAgbWVzc2FnZSAgU2FmZSBzZXJ2ZXIgb3IgbG9jYWxpemVkIG1lc3NhZ2UuXG5cdCAqIEBwYXJhbSB7Ym9vbGVhbn0gICAgICAgICBpc19lcnJvciBXaGV0aGVyIHRoZSBub3RpY2UgcmVwcmVzZW50cyBhbiBlcnJvci5cblx0ICogQHJldHVybiB7dm9pZH1cblx0ICovXG5cdGZ1bmN0aW9uIHNob3dfaW5zcGVjdG9yX21lc3NhZ2UoIGZvcm0sIG1lc3NhZ2UsIGlzX2Vycm9yICkge1xuXHRcdHZhciBub3RpY2UgPSBmb3JtID8gZm9ybS5xdWVyeVNlbGVjdG9yKCAnW2RhdGEtd3BiYy1jYXRhbG9nLXJlc291cmNlLWluc3BlY3Rvci1tZXNzYWdlXScgKSA6IG51bGw7XG5cblx0XHRpZiAoICEgbm90aWNlICkge1xuXHRcdFx0cmV0dXJuO1xuXHRcdH1cblx0XHRub3RpY2UuY2xhc3NMaXN0LnRvZ2dsZSggJ25vdGljZS1lcnJvcicsICEhIGlzX2Vycm9yICk7XG5cdFx0bm90aWNlLmNsYXNzTGlzdC50b2dnbGUoICdub3RpY2Utc3VjY2VzcycsICEgaXNfZXJyb3IgKTtcblx0XHRub3RpY2UuaGlkZGVuID0gISBtZXNzYWdlO1xuXHRcdHZhciBub3RpY2VfdGV4dCA9IG5vdGljZS5xdWVyeVNlbGVjdG9yKCAncCcgKTtcblx0XHRpZiAoIG5vdGljZV90ZXh0ICkge1xuXHRcdFx0bm90aWNlX3RleHQudGV4dENvbnRlbnQgPSBtZXNzYWdlIHx8ICcnO1xuXHRcdH1cblx0fVxuXG5cdC8qKlxuXHQgKiBNb3ZlIGtleWJvYXJkIGZvY3VzIHRvIHRoZSBoZWFkaW5nIG9mIGEgbmV3bHkgcmVuZGVyZWQgcmV2aWV3ZWQgaW5zcGVjdG9yLlxuXHQgKlxuXHQgKiBAcGFyYW0ge0hUTUxGb3JtRWxlbWVudHxudWxsfSBmb3JtIFJlbmRlcmVkIGluc3BlY3RvciBmb3JtLlxuXHQgKiBAcmV0dXJuIHt2b2lkfVxuXHQgKi9cblx0ZnVuY3Rpb24gZm9jdXNfaW5zcGVjdG9yX2hlYWRpbmcoIGZvcm0gKSB7XG5cdFx0dmFyIGhlYWRpbmcgPSBmb3JtID8gZm9ybS5xdWVyeVNlbGVjdG9yKCAnW2RhdGEtd3BiYy1jYXRhbG9nLXJlc291cmNlLWluc3BlY3Rvci1oZWFkaW5nXScgKSA6IG51bGw7XG5cblx0XHRpZiAoICEgaGVhZGluZyApIHtcblx0XHRcdHJldHVybjtcblx0XHR9XG5cdFx0d2luZG93LnNldFRpbWVvdXQoIGZ1bmN0aW9uICgpIHtcblx0XHRcdGlmICggZG9jdW1lbnQuZG9jdW1lbnRFbGVtZW50LmNvbnRhaW5zKCBoZWFkaW5nICkgJiYgJ2Z1bmN0aW9uJyA9PT0gdHlwZW9mIGhlYWRpbmcuZm9jdXMgKSB7XG5cdFx0XHRcdGhlYWRpbmcuZm9jdXMoKTtcblx0XHRcdH1cblx0XHR9LCAwICk7XG5cdH1cblxuXHQvKipcblx0ICogU2hvdyBhIHN0YW5kYXJkIEJvb2tpbmcgQ2FsZW5kYXIgYWRtaW5pc3RyYXRpb24gbm90aWNlLlxuXHQgKlxuXHQgKiBTdWNjZXNzZnVsIG11dGF0aW9ucyB1c2UgdGhlIHNoYXJlZCB0b3AtcmlnaHQgbm90aWNlIGFyZWEgdXNlZCBieSB0aGVcblx0ICogQm9va2luZyBDYWxlbmRhciBzZXR0aW5ncyBwYWdlcy4gVGhlIGJvb2xlYW4gcmV0dXJuIGxldHMgY2FsbGVycyByZXRhaW5cblx0ICogYW4gaW5saW5lIGZhbGxiYWNrIHdoZW4gdGhhdCBzaGFyZWQgaGVscGVyIGlzIHVuYXZhaWxhYmxlLlxuXHQgKlxuXHQgKiBAcGFyYW0ge3N0cmluZ30gbWVzc2FnZSAgICAgIFNhZmUgc2VydmVyIG9yIGxvY2FsaXplZCBtZXNzYWdlLlxuXHQgKiBAcGFyYW0ge3N0cmluZ30gbWVzc2FnZV90eXBlIE5vdGljZSB0eXBlIGFjY2VwdGVkIGJ5IHRoZSBzaGFyZWQgaGVscGVyLlxuXHQgKiBAcGFyYW0ge251bWJlcn0gZGVsYXkgICAgICAgIE5vdGljZSB2aXNpYmlsaXR5IGR1cmF0aW9uIGluIG1pbGxpc2Vjb25kcy5cblx0ICogQHJldHVybiB7Ym9vbGVhbn0gVHJ1ZSB3aGVuIHRoZSBtZXNzYWdlIHdhcyBkaXNwbGF5ZWQuXG5cdCAqL1xuXHRmdW5jdGlvbiBzaG93X2FkbWluX21lc3NhZ2UoIG1lc3NhZ2UsIG1lc3NhZ2VfdHlwZSwgZGVsYXkgKSB7XG5cdFx0dmFyIGNvbmZpZztcblx0XHR2YXIgbW91bnRfZWxlbWVudDtcblx0XHR2YXIgbm90aWNlO1xuXHRcdHZhciBub3RpY2VfdGV4dDtcblxuXHRcdGlmICggISBtZXNzYWdlICkge1xuXHRcdFx0cmV0dXJuIGZhbHNlO1xuXHRcdH1cblx0XHRpZiAoICdmdW5jdGlvbicgPT09IHR5cGVvZiB3aW5kb3cud3BiY19hZG1pbl9zaG93X21lc3NhZ2UgKSB7XG5cdFx0XHR3aW5kb3cud3BiY19hZG1pbl9zaG93X21lc3NhZ2UoIG1lc3NhZ2UsIG1lc3NhZ2VfdHlwZSB8fCAnaW5mbycsIGRlbGF5IHx8IDQwMDAsIGZhbHNlICk7XG5cdFx0XHRyZXR1cm4gdHJ1ZTtcblx0XHR9XG5cblx0XHRjb25maWcgPSB3aW5kb3cud3BiY19jYXRhbG9nX2Jvb2tpbmdfcmVzb3VyY2VzX2NvbmZpZyB8fCB7fTtcblx0XHRtb3VudF9lbGVtZW50ID0gY29uZmlnLm1vdW50X2lkID8gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoIGNvbmZpZy5tb3VudF9pZCApIDogbnVsbDtcblx0XHRtb3VudF9lbGVtZW50ID0gbW91bnRfZWxlbWVudCAmJiBtb3VudF9lbGVtZW50LnBhcmVudE5vZGUgPyBtb3VudF9lbGVtZW50LnBhcmVudE5vZGUgOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCggJ3dwYm9keS1jb250ZW50JyApIHx8IGRvY3VtZW50LmJvZHk7XG5cdFx0aWYgKCAhIG1vdW50X2VsZW1lbnQgKSB7XG5cdFx0XHRyZXR1cm4gZmFsc2U7XG5cdFx0fVxuXG5cdFx0bm90aWNlID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCggJ2RpdicgKTtcblx0XHRub3RpY2UuY2xhc3NOYW1lID0gJ25vdGljZSBub3RpY2UtJyArICggJ2Vycm9yJyA9PT0gbWVzc2FnZV90eXBlID8gJ2Vycm9yJyA6ICdzdWNjZXNzJyApICsgJyB3cGJjX2NhdGFsb2dfYm9va2luZ19yZXNvdXJjZXNfX211dGF0aW9uX25vdGljZSc7XG5cdFx0bm90aWNlLnNldEF0dHJpYnV0ZSggJ3JvbGUnLCAnZXJyb3InID09PSBtZXNzYWdlX3R5cGUgPyAnYWxlcnQnIDogJ3N0YXR1cycgKTtcblx0XHRub3RpY2Uuc2V0QXR0cmlidXRlKCAnYXJpYS1saXZlJywgJ2Vycm9yJyA9PT0gbWVzc2FnZV90eXBlID8gJ2Fzc2VydGl2ZScgOiAncG9saXRlJyApO1xuXHRcdG5vdGljZV90ZXh0ID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCggJ3AnICk7XG5cdFx0bm90aWNlX3RleHQudGV4dENvbnRlbnQgPSBtZXNzYWdlO1xuXHRcdG5vdGljZS5hcHBlbmRDaGlsZCggbm90aWNlX3RleHQgKTtcblx0XHRtb3VudF9lbGVtZW50Lmluc2VydEJlZm9yZSggbm90aWNlLCBtb3VudF9lbGVtZW50LmZpcnN0Q2hpbGQgKTtcblx0XHR3aW5kb3cuc2V0VGltZW91dCggZnVuY3Rpb24gKCkge1xuXHRcdFx0aWYgKCBub3RpY2UucGFyZW50Tm9kZSApIHtcblx0XHRcdFx0bm90aWNlLnBhcmVudE5vZGUucmVtb3ZlQ2hpbGQoIG5vdGljZSApO1xuXHRcdFx0fVxuXHRcdH0sIGRlbGF5IHx8IDQwMDAgKTtcblxuXHRcdHJldHVybiB0cnVlO1xuXHR9XG5cblx0LyoqXG5cdCAqIE9wZW4gYW5kIGZvY3VzIG9uZSBzZXJ2ZXItZGVmaW5lZCBlZGl0LWluc3BlY3RvciBzZWN0aW9uLlxuXHQgKlxuXHQgKiBUaGUgc2hhcmVkIGNvbGxhcHNpYmxlIGNvbnRyb2xsZXIga2VlcHMgZXhjbHVzaXZlIGdyb3VwIHN0YXRlIGFuZCBBUklBXG5cdCAqIGF0dHJpYnV0ZXMgc3luY2hyb25pemVkLiBUaGUgaGVhZGVyIGNsaWNrIGlzIHJldGFpbmVkIGFzIGEgY29tcGF0aWJpbGl0eVxuXHQgKiBmYWxsYmFjayBmb3Igb2xkZXIgYWRtaW5pc3RyYXRpb24gYnVuZGxlcy5cblx0ICpcblx0ICogQHBhcmFtIHtzdHJpbmd9IHNlY3Rpb25faWQgSW5zcGVjdG9yIHNlY3Rpb24gaWRlbnRpZmllci5cblx0ICogQHJldHVybiB7Ym9vbGVhbn0gVHJ1ZSB3aGVuIHRoZSByZXF1ZXN0ZWQgc2VjdGlvbiBleGlzdHMuXG5cdCAqL1xuXHRmdW5jdGlvbiBhY3RpdmF0ZV9pbnNwZWN0b3Jfc2VjdGlvbiggc2VjdGlvbl9pZCApIHtcblx0XHR2YXIgZm9ybTtcblx0XHR2YXIgZ3JvdXA7XG5cdFx0dmFyIHJvb3Q7XG5cdFx0dmFyIGNvbnRyb2xsZXI7XG5cdFx0dmFyIGhlYWRlcjtcblxuXHRcdHNlY3Rpb25faWQgPSBTdHJpbmcoIHNlY3Rpb25faWQgfHwgJycgKTtcblx0XHRpZiAoICEgL15bYS16MC05X10rJC8udGVzdCggc2VjdGlvbl9pZCApICkge1xuXHRcdFx0cmV0dXJuIGZhbHNlO1xuXHRcdH1cblxuXHRcdGZvcm0gPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCAnW2RhdGEtd3BiYy1jYXRhbG9nLXJlc291cmNlLWluc3BlY3Rvci1mb3JtXVtkYXRhLW1vZGU9XCJlZGl0XCJdJyApO1xuXHRcdGdyb3VwID0gZm9ybSA/IGZvcm0ucXVlcnlTZWxlY3RvciggJ1tkYXRhLWdyb3VwPVwiY2F0YWxvZy1ib29raW5nLXJlc291cmNlLScgKyBzZWN0aW9uX2lkICsgJ1wiXScgKSA6IG51bGw7XG5cdFx0aGVhZGVyID0gZ3JvdXAgPyBncm91cC5xdWVyeVNlbGVjdG9yKCAnLmdyb3VwX19oZWFkZXInICkgOiBudWxsO1xuXHRcdGlmICggISBncm91cCB8fCAhIGhlYWRlciApIHtcblx0XHRcdHJldHVybiBmYWxzZTtcblx0XHR9XG5cblx0XHRyb290ID0gZ3JvdXAuY2xvc2VzdCggJy53cGJjX2NvbGxhcHNpYmxlJyApO1xuXHRcdGNvbnRyb2xsZXIgPSByb290ICYmIHJvb3QuX193cGJjX2NvbGxhcHNpYmxlX2luc3RhbmNlID8gcm9vdC5fX3dwYmNfY29sbGFwc2libGVfaW5zdGFuY2UgOiBudWxsO1xuXHRcdGlmICggY29udHJvbGxlciAmJiAnZnVuY3Rpb24nID09PSB0eXBlb2YgY29udHJvbGxlci5leHBhbmQgKSB7XG5cdFx0XHRjb250cm9sbGVyLmV4cGFuZCggZ3JvdXAgKTtcblx0XHR9IGVsc2UgaWYgKCAhIGdyb3VwLmNsYXNzTGlzdC5jb250YWlucyggJ2lzLW9wZW4nICkgKSB7XG5cdFx0XHRoZWFkZXIuY2xpY2soKTtcblx0XHR9XG5cblx0XHR3aW5kb3cuc2V0VGltZW91dCggZnVuY3Rpb24gKCkge1xuXHRcdFx0Z3JvdXAuc2Nyb2xsSW50b1ZpZXcoIHsgYmVoYXZpb3I6ICdzbW9vdGgnLCBibG9jazogJ3N0YXJ0JyB9ICk7XG5cdFx0XHRoZWFkZXIuZm9jdXMoKTtcblx0XHR9LCAxMjAgKTtcblxuXHRcdHJldHVybiB0cnVlO1xuXHR9XG5cblx0LyoqXG5cdCAqIFJlbmRlciBvbmUgc2VydmVyLWF1dGhvcml0YXRpdmUgY3JlYXRlIG9yIGVkaXQgc2NoZW1hLlxuXHQgKlxuXHQgKiBAcGFyYW0ge09iamVjdH0gY29uZmlnIENhdGFsb2cgY29uZmlndXJhdGlvbi5cblx0ICogQHBhcmFtIHtPYmplY3R9IHNjaGVtYSBJbnNwZWN0b3Igc2NoZW1hLlxuXHQgKiBAcGFyYW0ge2Jvb2xlYW59IGZvY3VzX3RpdGxlIFdoZXRoZXIgdGhlIHRpdGxlIGNvbnRyb2wgcmVjZWl2ZXMgZm9jdXMuXG5cdCAqIEByZXR1cm4ge2Jvb2xlYW59IFRydWUgd2hlbiByZW5kZXJlZC5cblx0ICovXG5cdGZ1bmN0aW9uIHJlbmRlcl9pbnNwZWN0b3Jfc2NoZW1hKCBjb25maWcsIHNjaGVtYSwgZm9jdXNfdGl0bGUgKSB7XG5cdFx0dmFyIGhvc3QgPSBnZXRfaW5zcGVjdG9yX2hvc3QoKTtcblx0XHR2YXIgZm9ybV90YXJnZXQgPSBob3N0ID8gaG9zdC5xdWVyeVNlbGVjdG9yKCAnW2RhdGEtd3BiYy11aS1jYXRhbG9nLWluc3BlY3Rvci1mb3JtXScgKSA6IG51bGw7XG5cdFx0dmFyIGZvb3RlciA9IGdldF9pbnNwZWN0b3JfZm9vdGVyKCk7XG5cdFx0dmFyIHNhdmVfYnV0dG9uID0gZm9vdGVyID8gZm9vdGVyLnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLXVpLWNhdGFsb2ctaW5zcGVjdG9yLXNhdmVdJyApIDogbnVsbDtcblx0XHR2YXIgdGVtcGxhdGVfcm9sZSA9ICdjcmVhdGUnID09PSBzY2hlbWEubW9kZSA/ICdpbnNwZWN0b3JfY3JlYXRlJyA6ICdpbnNwZWN0b3JfZWRpdCc7XG5cblx0XHRpZiAoICEgZm9ybV90YXJnZXQgKSB7XG5cdFx0XHRyZXR1cm4gZmFsc2U7XG5cdFx0fVxuXHRcdGZvcm1fdGFyZ2V0LmlubmVySFRNTCA9IHJlbmRlcl9jb21wb25lbnQoIGNvbmZpZywgdGVtcGxhdGVfcm9sZSwgeyBpMThuOiBjb25maWcuaTE4biB8fCB7fSwgc2NoZW1hOiBzY2hlbWEgfSApO1xuXHRcdHZhciBmb3JtID0gZm9ybV90YXJnZXQucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtY2F0YWxvZy1yZXNvdXJjZS1pbnNwZWN0b3ItZm9ybV0nICk7XG5cdFx0aWYgKCAhIGZvcm0gKSB7XG5cdFx0XHRyZXR1cm4gZmFsc2U7XG5cdFx0fVxuXHRcdGlmICggJ2NyZWF0ZScgPT09IHNjaGVtYS5tb2RlICkge1xuXHRcdFx0Zm9ybS5zZXRBdHRyaWJ1dGUoICdkYXRhLWNhbi1jcmVhdGUnLCBzY2hlbWEuY2FuX2NyZWF0ZSA/ICd0cnVlJyA6ICdmYWxzZScgKTtcblx0XHR9XG5cdFx0aW5zcGVjdG9yX21vZGUgPSBzY2hlbWEubW9kZTtcblx0XHRpbnNwZWN0b3JfcmVzb3VyY2VfaWQgPSBOdW1iZXIoIHNjaGVtYS5yZXNvdXJjZV9pZCApIHx8IDA7XG5cdFx0aWYgKCBpbnNwZWN0b3JfcmVzb3VyY2VfaWQgKSB7XG5cdFx0XHR2YXIgc2hvcnRjb2RlX2ZpZWxkID0gZm9ybS5xdWVyeVNlbGVjdG9yKCAnLndwYmNfY2F0YWxvZ19ib29raW5nX3Jlc291cmNlc19fZWRpdG9yX2NvZGUnICk7XG5cdFx0XHRpZiAoIHNob3J0Y29kZV9maWVsZCApIHtcblx0XHRcdFx0c3luY2hyb25pemVfYm9va2luZ19yZXNvdXJjZV9zaG9ydGNvZGVfaW5wdXQoIGluc3BlY3Rvcl9yZXNvdXJjZV9pZCwgc2hvcnRjb2RlX2ZpZWxkLnZhbHVlICk7XG5cdFx0XHR9XG5cdFx0fVxuXHRcdHNldF9pbnNwZWN0b3Jfc3RhdGUoICdmb3JtJywgJycgKTtcblx0XHRpZiAoIHNhdmVfYnV0dG9uICkge1xuXHRcdFx0Y29uZmlndXJlX2luc3BlY3Rvcl9mb290ZXIoICd3cGJjX2NhdGFsb2dfYm9va2luZ19yZXNvdXJjZV9pbnNwZWN0b3JfZm9ybScsICdjcmVhdGUnID09PSBpbnNwZWN0b3JfbW9kZSA/IGNvbmZpZy5pMThuLmFkZF9yZXNvdXJjZSB8fCAnJyA6IGNvbmZpZy5pMThuLnNhdmVfY2hhbmdlcyB8fCAnJywgZmFsc2UsIHRydWUgKTtcblx0XHR9XG5cdFx0dmFyIGNhbmNlbF9idXR0b24gPSBmb290ZXIgPyBmb290ZXIucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtdWktY2F0YWxvZy1pbnNwZWN0b3ItY2FuY2VsXScgKSA6IG51bGw7XG5cdFx0aWYgKCBjYW5jZWxfYnV0dG9uICkge1xuXHRcdFx0Y2FuY2VsX2J1dHRvbi5kaXNhYmxlZCA9IGZhbHNlO1xuXHRcdH1cblx0XHRpZiAoICdmdW5jdGlvbicgPT09IHR5cGVvZiB3aW5kb3cuV1BCQ19Db2xsYXBzaWJsZV9BdXRvSW5pdCApIHtcblx0XHRcdHdpbmRvdy5XUEJDX0NvbGxhcHNpYmxlX0F1dG9Jbml0KCk7XG5cdFx0fVxuXHRcdHN5bmNocm9uaXplX2FsbF9pbnNwZWN0b3JfbnVtZXJpY19yYW5nZXMoKTtcblx0XHRzeW5jaHJvbml6ZV9jcmVhdGVfaW5zcGVjdG9yX2NvbnRyb2xzKCk7XG5cdFx0c3luY2hyb25pemVfY2hhbmdlb3Zlcl9wb2xpY3lfY29udHJvbHMoKTtcblx0XHRpbnNwZWN0b3Jfb3JpZ2luYWxfZmllbGRzID0gc2VyaWFsaXplX2luc3BlY3Rvcl9maWVsZHMoKTtcblx0XHRpbnNwZWN0b3JfZGlydHkgPSBmYWxzZTtcblx0XHRzeW5jaHJvbml6ZV9pbnNwZWN0b3JfZGlydHlfc3RhdGUoKTtcblx0XHRtYXJrX2luc3BlY3Rvcl9yZXNvdXJjZV9yb3coIGluc3BlY3Rvcl9yZXNvdXJjZV9pZCApO1xuXHRcdGlmICggZmFsc2UgIT09IGZvY3VzX3RpdGxlICkge1xuXHRcdFx0d2luZG93LnNldFRpbWVvdXQoIGZ1bmN0aW9uICgpIHtcblx0XHRcdFx0dmFyIHRpdGxlX2ZpZWxkID0gZm9ybS5xdWVyeVNlbGVjdG9yKCAnW2RhdGEtd3BiYy1jYXRhbG9nLXJlc291cmNlLWZpZWxkPVwidGl0bGVcIl0nICk7XG5cdFx0XHRcdGlmICggdGl0bGVfZmllbGQgJiYgJ2Z1bmN0aW9uJyA9PT0gdHlwZW9mIHRpdGxlX2ZpZWxkLmZvY3VzICkge1xuXHRcdFx0XHRcdHRpdGxlX2ZpZWxkLmZvY3VzKCk7XG5cdFx0XHRcdH1cblx0XHRcdH0sIDEyMCApO1xuXHRcdH1cblxuXHRcdHJldHVybiB0cnVlO1xuXHR9XG5cblx0LyoqXG5cdCAqIExvYWQgYW5kIG9wZW4gb25lIGNyZWF0ZSBvciBlZGl0IGluc3BlY3Rvci5cblx0ICpcblx0ICogQHBhcmFtIHtPYmplY3R9ICAgICAgY29uZmlnICAgICAgIENhdGFsb2cgY29uZmlndXJhdGlvbi5cblx0ICogQHBhcmFtIHtzdHJpbmd9ICAgICAgbW9kZSAgICAgICAgIENyZWF0ZSBvciBlZGl0LlxuXHQgKiBAcGFyYW0ge251bWJlcn0gICAgICByZXNvdXJjZV9pZCAgUmVzb3VyY2UgSUQgZm9yIGVkaXQuXG5cdCAqIEBwYXJhbSB7SFRNTEVsZW1lbnR9IGZvY3VzX3RhcmdldCBJbml0aWF0aW5nIGNvbnRyb2wuXG5cdCAqIEBwYXJhbSB7c3RyaW5nfSAgICAgIHNlY3Rpb25faWQgICBPcHRpb25hbCBlZGl0IHNlY3Rpb24gdG8gb3BlbiBhbmQgZm9jdXMuXG5cdCAqIEByZXR1cm4ge3ZvaWR9XG5cdCAqL1xuXHRmdW5jdGlvbiBvcGVuX2luc3BlY3RvciggY29uZmlnLCBtb2RlLCByZXNvdXJjZV9pZCwgZm9jdXNfdGFyZ2V0LCBzZWN0aW9uX2lkICkge1xuXHRcdHZhciByZXF1ZXN0X3NlcXVlbmNlO1xuXHRcdHZhciBhY3Rpb247XG5cblx0XHRyZXNvdXJjZV9pZCA9IE51bWJlciggcmVzb3VyY2VfaWQgKSB8fCAwO1xuXHRcdGlmICggJ2VkaXQnID09PSBtb2RlICYmIHJlc291cmNlX2lkID09PSBpbnNwZWN0b3JfcmVzb3VyY2VfaWQgJiYgZG9jdW1lbnQucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtY2F0YWxvZy1yZXNvdXJjZS1pbnNwZWN0b3ItZm9ybV1bZGF0YS1tb2RlPVwiZWRpdFwiXScgKSApIHtcblx0XHRcdGV4cGFuZF9pbnNwZWN0b3Jfc2lkZWJhcigpO1xuXHRcdFx0bWFya19pbnNwZWN0b3JfcmVzb3VyY2Vfcm93KCByZXNvdXJjZV9pZCApO1xuXHRcdFx0aWYgKCBzZWN0aW9uX2lkICkge1xuXHRcdFx0XHRhY3RpdmF0ZV9pbnNwZWN0b3Jfc2VjdGlvbiggc2VjdGlvbl9pZCApO1xuXHRcdFx0fVxuXHRcdFx0cmV0dXJuO1xuXHRcdH1cblx0XHRpZiAoICEgY2FuX2Rpc2NhcmRfaW5zcGVjdG9yKCBjb25maWcgKSB8fCAhIG1vdW50X2luc3BlY3Rvcl9zaGVsbCggY29uZmlnICkgKSB7XG5cdFx0XHRyZXR1cm47XG5cdFx0fVxuXHRcdGNsb3NlX2RldGFpbHNfcm93KCBmYWxzZSApO1xuXHRcdGluc3BlY3Rvcl9mb2N1c190YXJnZXQgPSBmb2N1c190YXJnZXQgfHwgZG9jdW1lbnQuYWN0aXZlRWxlbWVudDtcblx0XHRyZXF1ZXN0X3NlcXVlbmNlID0gKytpbnNwZWN0b3JfcmVxdWVzdF9zZXF1ZW5jZTtcblx0XHRpbnNwZWN0b3JfZGlydHkgPSBmYWxzZTtcblx0XHRpbnNwZWN0b3JfbW9kZSA9IG1vZGU7XG5cdFx0aW5zcGVjdG9yX3Jlc291cmNlX2lkID0gcmVzb3VyY2VfaWQ7XG5cdFx0c3luY2hyb25pemVfaW5zcGVjdG9yX3dpZHRoKCk7XG5cdFx0YWN0aW9uID0gJ2NyZWF0ZScgPT09IG1vZGUgPyBjb25maWcuaW5zcGVjdG9yX2NyZWF0ZV9zY2hlbWFfYWN0aW9uIDogY29uZmlnLmluc3BlY3Rvcl9lZGl0X3NjaGVtYV9hY3Rpb247XG5cdFx0c2V0X2luc3BlY3Rvcl9zdGF0ZSggJ2xvYWRpbmcnLCAnJyApO1xuXHRcdG1hcmtfaW5zcGVjdG9yX3Jlc291cmNlX3JvdyggaW5zcGVjdG9yX3Jlc291cmNlX2lkICk7XG5cdFx0ZXhwYW5kX2luc3BlY3Rvcl9zaWRlYmFyKCk7XG5cblx0XHRyZXF1ZXN0X2luc3BlY3RvciggY29uZmlnLCBhY3Rpb24sICdlZGl0JyA9PT0gbW9kZSA/IHsgcmVzb3VyY2VfaWQ6IGluc3BlY3Rvcl9yZXNvdXJjZV9pZCB9IDoge30gKS50aGVuKCBmdW5jdGlvbiAoIHJlc3BvbnNlICkge1xuXHRcdFx0aWYgKCByZXF1ZXN0X3NlcXVlbmNlICE9PSBpbnNwZWN0b3JfcmVxdWVzdF9zZXF1ZW5jZSApIHtcblx0XHRcdFx0cmV0dXJuO1xuXHRcdFx0fVxuXHRcdFx0aWYgKCAhIHJlc3BvbnNlIHx8IHRydWUgIT09IHJlc3BvbnNlLnN1Y2Nlc3MgfHwgISByZXNwb25zZS5kYXRhIHx8ICEgcmVzcG9uc2UuZGF0YS5zY2hlbWEgfHwgISByZW5kZXJfaW5zcGVjdG9yX3NjaGVtYSggY29uZmlnLCByZXNwb25zZS5kYXRhLnNjaGVtYSwgISBzZWN0aW9uX2lkICkgKSB7XG5cdFx0XHRcdHNldF9pbnNwZWN0b3Jfc3RhdGUoICdlcnJvcicsIGdldF9pbnNwZWN0b3JfcmVzcG9uc2VfbWVzc2FnZSggcmVzcG9uc2UsIGNvbmZpZy5pMThuLmluc3BlY3Rvcl9sb2FkX2ZhaWxlZCApICk7XG5cdFx0XHR9IGVsc2UgaWYgKCBzZWN0aW9uX2lkICkge1xuXHRcdFx0XHRhY3RpdmF0ZV9pbnNwZWN0b3Jfc2VjdGlvbiggc2VjdGlvbl9pZCApO1xuXHRcdFx0fVxuXHRcdH0gKS5jYXRjaCggZnVuY3Rpb24gKCkge1xuXHRcdFx0aWYgKCByZXF1ZXN0X3NlcXVlbmNlID09PSBpbnNwZWN0b3JfcmVxdWVzdF9zZXF1ZW5jZSApIHtcblx0XHRcdFx0c2V0X2luc3BlY3Rvcl9zdGF0ZSggJ2Vycm9yJywgY29uZmlnLmkxOG4uaW5zcGVjdG9yX2xvYWRfZmFpbGVkIHx8ICcnICk7XG5cdFx0XHR9XG5cdFx0fSApO1xuXHR9XG5cblx0LyoqXG5cdCAqIFJlbmRlciB0aGUgc2VydmVyLWdlbmVyYXRlZCBjb21tb24tZmllbGQgYnVsayBlZGl0b3IuXG5cdCAqXG5cdCAqIEBwYXJhbSB7T2JqZWN0fSBjb25maWcgQ2F0YWxvZyBjb25maWd1cmF0aW9uLlxuXHQgKiBAcGFyYW0ge09iamVjdH0gc2NoZW1hIEF1dGhvcml6ZWQgYnVsayBzY2hlbWEuXG5cdCAqIEByZXR1cm4ge2Jvb2xlYW59IFRydWUgd2hlbiB0aGUgZm9ybSByZW5kZXJlZC5cblx0ICovXG5cdGZ1bmN0aW9uIHJlbmRlcl9idWxrX2VkaXRvciggY29uZmlnLCBzY2hlbWEgKSB7XG5cdFx0dmFyIGhvc3QgPSBnZXRfaW5zcGVjdG9yX2hvc3QoKTtcblx0XHR2YXIgdGFyZ2V0ID0gaG9zdCA/IGhvc3QucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtdWktY2F0YWxvZy1pbnNwZWN0b3ItZm9ybV0nICkgOiBudWxsO1xuXG5cdFx0aWYgKCAhIHRhcmdldCApIHtcblx0XHRcdHJldHVybiBmYWxzZTtcblx0XHR9XG5cdFx0dGFyZ2V0LmlubmVySFRNTCA9IHJlbmRlcl9jb21wb25lbnQoIGNvbmZpZywgJ2luc3BlY3Rvcl9idWxrX2VkaXQnLCB7IGkxOG46IGNvbmZpZy5pMThuIHx8IHt9LCBzY2hlbWE6IHNjaGVtYSwgc2VsZWN0aW9uX2xhYmVsOiBnZXRfc2VsZWN0aW9uX2NvdW50X2xhYmVsKCBjb25maWcsIHNjaGVtYS5zZWxlY3Rpb25fY291bnQgKSB9ICk7XG5cdFx0aWYgKCAhIHRhcmdldC5xdWVyeVNlbGVjdG9yKCAnW2RhdGEtd3BiYy1jYXRhbG9nLXJlc291cmNlLWJ1bGstZm9ybV0nICkgKSB7XG5cdFx0XHRyZXR1cm4gZmFsc2U7XG5cdFx0fVxuXHRcdGluc3BlY3Rvcl9tb2RlID0gJ2J1bGtfZWRpdCc7XG5cdFx0aW5zcGVjdG9yX3Jlc291cmNlX2lkID0gMDtcblx0XHRpbnNwZWN0b3JfcmVzb3VyY2VfaWRzID0gKCBzY2hlbWEucmVzb3VyY2VfaWRzIHx8IFtdICkubWFwKCBOdW1iZXIgKTtcblx0XHRpbnNwZWN0b3JfYnVsa19vcGVyYXRpb25zID0ge307XG5cdFx0aW5zcGVjdG9yX3Jldmlld190b2tlbiA9ICcnO1xuXHRcdGluc3BlY3Rvcl9zZWxlY3Rpb25fc3RhbGUgPSBmYWxzZTtcblx0XHRpbnNwZWN0b3JfdHJhY2tzX3NlbGVjdGlvbiA9IHRydWU7XG5cdFx0aW5zcGVjdG9yX2RpcnR5ID0gZmFsc2U7XG5cdFx0c2V0X2luc3BlY3Rvcl9zdGF0ZSggJ2Zvcm0nLCAnJyApO1xuXHRcdGNvbmZpZ3VyZV9pbnNwZWN0b3JfZm9vdGVyKCAnd3BiY19jYXRhbG9nX2Jvb2tpbmdfcmVzb3VyY2VzX2J1bGtfZm9ybScsIGNvbmZpZy5pMThuLnJldmlld19jaGFuZ2VzX2J1dHRvbiB8fCAnJywgZmFsc2UsIHRydWUgKTtcblx0XHRpZiAoICdmdW5jdGlvbicgPT09IHR5cGVvZiB3aW5kb3cuV1BCQ19Db2xsYXBzaWJsZV9BdXRvSW5pdCApIHtcblx0XHRcdHdpbmRvdy5XUEJDX0NvbGxhcHNpYmxlX0F1dG9Jbml0KCk7XG5cdFx0fVxuXHRcdG1hcmtfaW5zcGVjdG9yX3Jlc291cmNlX3JvdyggMCApO1xuXHRcdGZvY3VzX2luc3BlY3Rvcl9oZWFkaW5nKCB0YXJnZXQucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtY2F0YWxvZy1yZXNvdXJjZS1idWxrLWZvcm1dJyApICk7XG5cblx0XHRyZXR1cm4gdHJ1ZTtcblx0fVxuXG5cdC8qKlxuXHQgKiBPcGVuIGEgYnVsayBlZGl0b3IgZm9yIHRoZSBjdXJyZW50IGV4cGxpY2l0IHNlbGVjdGlvbi5cblx0ICpcblx0ICogQHBhcmFtIHtPYmplY3R9ICAgICAgY29uZmlnICAgICAgIENhdGFsb2cgY29uZmlndXJhdGlvbi5cblx0ICogQHBhcmFtIHtIVE1MRWxlbWVudH0gZm9jdXNfdGFyZ2V0IEluaXRpYXRpbmcgY29udHJvbC5cblx0ICogQHJldHVybiB7dm9pZH1cblx0ICovXG5cdGZ1bmN0aW9uIG9wZW5fYnVsa19lZGl0b3IoIGNvbmZpZywgZm9jdXNfdGFyZ2V0ICkge1xuXHRcdHZhciByZXNvdXJjZV9pZHMgPSBnZXRfc2VsZWN0ZWRfcmVzb3VyY2VfaWRzKCBjb25maWcgKTtcblx0XHR2YXIgcmVxdWVzdF9zZXF1ZW5jZTtcblxuXHRcdGlmICggISByZXNvdXJjZV9pZHMubGVuZ3RoIHx8ICEgY2FuX2Rpc2NhcmRfaW5zcGVjdG9yKCBjb25maWcgKSB8fCAhIG1vdW50X2luc3BlY3Rvcl9zaGVsbCggY29uZmlnICkgKSB7XG5cdFx0XHRyZXR1cm47XG5cdFx0fVxuXHRcdGNsb3NlX2RldGFpbHNfcm93KCBmYWxzZSApO1xuXHRcdGluc3BlY3Rvcl9mb2N1c190YXJnZXQgPSBmb2N1c190YXJnZXQgfHwgZG9jdW1lbnQuYWN0aXZlRWxlbWVudDtcblx0XHRyZXF1ZXN0X3NlcXVlbmNlID0gKytpbnNwZWN0b3JfcmVxdWVzdF9zZXF1ZW5jZTtcblx0XHRpbnNwZWN0b3JfbW9kZSA9ICdidWxrX2VkaXQnO1xuXHRcdGluc3BlY3Rvcl9yZXNvdXJjZV9pZHMgPSByZXNvdXJjZV9pZHMuc2xpY2UoKTtcblx0XHRpbnNwZWN0b3JfZGlydHkgPSBmYWxzZTtcblx0XHRpbnNwZWN0b3JfdHJhY2tzX3NlbGVjdGlvbiA9IHRydWU7XG5cdFx0c3luY2hyb25pemVfaW5zcGVjdG9yX3dpZHRoKCk7XG5cdFx0c2V0X2luc3BlY3Rvcl9zdGF0ZSggJ2xvYWRpbmcnLCAnJyApO1xuXHRcdGV4cGFuZF9pbnNwZWN0b3Jfc2lkZWJhcigpO1xuXG5cdFx0cmVxdWVzdF9pbnNwZWN0b3IoIGNvbmZpZywgY29uZmlnLmJ1bGtfc2NoZW1hX2FjdGlvbiwgeyByZXNvdXJjZV9pZHM6IEpTT04uc3RyaW5naWZ5KCByZXNvdXJjZV9pZHMgKSB9ICkudGhlbiggZnVuY3Rpb24gKCByZXNwb25zZSApIHtcblx0XHRcdGlmICggcmVxdWVzdF9zZXF1ZW5jZSAhPT0gaW5zcGVjdG9yX3JlcXVlc3Rfc2VxdWVuY2UgKSB7XG5cdFx0XHRcdHJldHVybjtcblx0XHRcdH1cblx0XHRcdGlmICggISByZXNwb25zZSB8fCB0cnVlICE9PSByZXNwb25zZS5zdWNjZXNzIHx8ICEgcmVzcG9uc2UuZGF0YSB8fCAhIHJlc3BvbnNlLmRhdGEuc2NoZW1hIHx8ICEgcmVuZGVyX2J1bGtfZWRpdG9yKCBjb25maWcsIHJlc3BvbnNlLmRhdGEuc2NoZW1hICkgKSB7XG5cdFx0XHRcdHNldF9pbnNwZWN0b3Jfc3RhdGUoICdlcnJvcicsIGdldF9pbnNwZWN0b3JfcmVzcG9uc2VfbWVzc2FnZSggcmVzcG9uc2UsIGNvbmZpZy5pMThuLmJ1bGtfbG9hZF9mYWlsZWQgKSApO1xuXHRcdFx0fSBlbHNlIGlmICggISByZXNvdXJjZV9pZF9saXN0c19tYXRjaCggaW5zcGVjdG9yX3Jlc291cmNlX2lkcywgZ2V0X3NlbGVjdGVkX3Jlc291cmNlX2lkcyggY29uZmlnICkgKSApIHtcblx0XHRcdFx0aGFuZGxlX2luc3BlY3Rvcl9zZWxlY3Rpb25fY2hhbmdlKCBudWxsLCBjb25maWcgKTtcblx0XHRcdH1cblx0XHR9ICkuY2F0Y2goIGZ1bmN0aW9uICgpIHtcblx0XHRcdGlmICggcmVxdWVzdF9zZXF1ZW5jZSA9PT0gaW5zcGVjdG9yX3JlcXVlc3Rfc2VxdWVuY2UgKSB7XG5cdFx0XHRcdHNldF9pbnNwZWN0b3Jfc3RhdGUoICdlcnJvcicsIGNvbmZpZy5pMThuLmJ1bGtfbG9hZF9mYWlsZWQgfHwgJycgKTtcblx0XHRcdH1cblx0XHR9ICk7XG5cdH1cblxuXHQvKipcblx0ICogUmV0dXJuIG9ubHkgZXhwbGljaXRseSBlbmFibGVkIGJ1bGsgb3BlcmF0aW9ucy5cblx0ICpcblx0ICogQHJldHVybiB7T2JqZWN0fSBPcGVyYXRpb24gZW52ZWxvcGUga2V5ZWQgYnkgZmllbGQuXG5cdCAqL1xuXHRmdW5jdGlvbiBjb2xsZWN0X2J1bGtfb3BlcmF0aW9ucygpIHtcblx0XHR2YXIgb3BlcmF0aW9ucyA9IHt9O1xuXG5cdFx0ZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbCggJ1tkYXRhLXdwYmMtY2F0YWxvZy1yZXNvdXJjZS1idWxrLWVuYWJsZV06Y2hlY2tlZCcgKS5mb3JFYWNoKCBmdW5jdGlvbiAoIGVuYWJsZWRfY29udHJvbCApIHtcblx0XHRcdHZhciBmaWVsZF9rZXkgPSBlbmFibGVkX2NvbnRyb2wuZ2V0QXR0cmlidXRlKCAnZGF0YS13cGJjLWNhdGFsb2ctcmVzb3VyY2UtYnVsay1lbmFibGUnICkgfHwgJyc7XG5cdFx0XHR2YXIgb3BlcmF0aW9uID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtY2F0YWxvZy1yZXNvdXJjZS1idWxrLW9wZXJhdGlvbj1cIicgKyBmaWVsZF9rZXkgKyAnXCJdJyApO1xuXHRcdFx0dmFyIGZpZWxkX3ZhbHVlID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtY2F0YWxvZy1yZXNvdXJjZS1idWxrLXZhbHVlPVwiJyArIGZpZWxkX2tleSArICdcIl0nICk7XG5cblx0XHRcdGlmICggZmllbGRfa2V5ICYmIG9wZXJhdGlvbiAmJiBmaWVsZF92YWx1ZSApIHtcblx0XHRcdFx0b3BlcmF0aW9uc1sgZmllbGRfa2V5IF0gPSB7IG9wZXJhdGlvbjogb3BlcmF0aW9uLnZhbHVlLCB2YWx1ZTogZmllbGRfdmFsdWUudmFsdWUgfTtcblx0XHRcdH1cblx0XHR9ICk7XG5cblx0XHRyZXR1cm4gb3BlcmF0aW9ucztcblx0fVxuXG5cdC8qKlxuXHQgKiBTeW5jaHJvbml6ZSBvbmUgb3B0aW9uYWwgYnVsayBmaWVsZCBhbmQgdGhlIHJldmlldyBhY3Rpb24uXG5cdCAqXG5cdCAqIEBwYXJhbSB7SFRNTEVsZW1lbnR8bnVsbH0gY2hhbmdlZF9jb250cm9sIENvbnRyb2wgdGhhdCBjaGFuZ2VkLCB3aGVuIGF2YWlsYWJsZS5cblx0ICogQHJldHVybiB7dm9pZH1cblx0ICovXG5cdGZ1bmN0aW9uIHN5bmNocm9uaXplX2J1bGtfZWRpdG9yKCBjaGFuZ2VkX2NvbnRyb2wgKSB7XG5cdFx0dmFyIGZpZWxkX3dyYXAgPSBjaGFuZ2VkX2NvbnRyb2wgPyBjaGFuZ2VkX2NvbnRyb2wuY2xvc2VzdCggJ1tkYXRhLXdwYmMtY2F0YWxvZy1yZXNvdXJjZS1idWxrLWZpZWxkXScgKSA6IG51bGw7XG5cdFx0dmFyIHNhdmVfYnV0dG9uID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtdWktY2F0YWxvZy1pbnNwZWN0b3Itc2F2ZV0nICk7XG5cblx0XHRpZiAoIGZpZWxkX3dyYXAgKSB7XG5cdFx0XHR2YXIgZW5hYmxlZF9jb250cm9sID0gZmllbGRfd3JhcC5xdWVyeVNlbGVjdG9yKCAnW2RhdGEtd3BiYy1jYXRhbG9nLXJlc291cmNlLWJ1bGstZW5hYmxlXScgKTtcblx0XHRcdHZhciBvcGVyYXRpb25fY29udHJvbCA9IGZpZWxkX3dyYXAucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtY2F0YWxvZy1yZXNvdXJjZS1idWxrLW9wZXJhdGlvbl0nICk7XG5cdFx0XHR2YXIgcHJlZml4X2VsZW1lbnQgPSBmaWVsZF93cmFwLnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLWNhdGFsb2ctcmVzb3VyY2UtYnVsay1wcmVmaXhdJyApO1xuXHRcdFx0dmFyIHN1ZmZpeF9lbGVtZW50ID0gZmllbGRfd3JhcC5xdWVyeVNlbGVjdG9yKCAnW2RhdGEtd3BiYy1jYXRhbG9nLXJlc291cmNlLWJ1bGstc3VmZml4XScgKTtcblx0XHRcdHZhciBlbmFibGVkID0gISEgZW5hYmxlZF9jb250cm9sICYmIGVuYWJsZWRfY29udHJvbC5jaGVja2VkO1xuXHRcdFx0dmFyIG9wZXJhdGlvbl9pZCA9IG9wZXJhdGlvbl9jb250cm9sID8gU3RyaW5nKCBvcGVyYXRpb25fY29udHJvbC52YWx1ZSB8fCAnJyApIDogJyc7XG5cdFx0XHR2YXIgaXNfcGVyY2VudCA9IC0xICE9PSBvcGVyYXRpb25faWQuaW5kZXhPZiggJ3BlcmNlbnQnICk7XG5cdFx0XHRmaWVsZF93cmFwLmNsYXNzTGlzdC50b2dnbGUoICdpcy1lbmFibGVkJywgZW5hYmxlZCApO1xuXHRcdFx0ZmllbGRfd3JhcC5xdWVyeVNlbGVjdG9yQWxsKCAnW2RhdGEtd3BiYy1jYXRhbG9nLXJlc291cmNlLWJ1bGstb3BlcmF0aW9uXSwgW2RhdGEtd3BiYy1jYXRhbG9nLXJlc291cmNlLWJ1bGstdmFsdWVdLCBbZGF0YS13cGJjLWNhdGFsb2ctcmVzb3VyY2UtYnVsay1yYW5nZV0nICkuZm9yRWFjaCggZnVuY3Rpb24gKCBjb250cm9sICkge1xuXHRcdFx0XHRjb250cm9sLmRpc2FibGVkID0gISBlbmFibGVkO1xuXHRcdFx0fSApO1xuXHRcdFx0aWYgKCBwcmVmaXhfZWxlbWVudCApIHtcblx0XHRcdFx0cHJlZml4X2VsZW1lbnQudGV4dENvbnRlbnQgPSBpc19wZXJjZW50ID8gJycgOiBmaWVsZF93cmFwLmdldEF0dHJpYnV0ZSggJ2RhdGEtd3BiYy1jYXRhbG9nLXJlc291cmNlLWJ1bGstcHJlZml4JyApIHx8ICcnO1xuXHRcdFx0fVxuXHRcdFx0aWYgKCBzdWZmaXhfZWxlbWVudCApIHtcblx0XHRcdFx0c3VmZml4X2VsZW1lbnQudGV4dENvbnRlbnQgPSBpc19wZXJjZW50ID8gJyUnIDogZmllbGRfd3JhcC5nZXRBdHRyaWJ1dGUoICdkYXRhLXdwYmMtY2F0YWxvZy1yZXNvdXJjZS1idWxrLXN1ZmZpeCcgKSB8fCAnJztcblx0XHRcdH1cblx0XHRcdGlmICggY2hhbmdlZF9jb250cm9sICYmIGNoYW5nZWRfY29udHJvbC5tYXRjaGVzKCAnW2RhdGEtd3BiYy1jYXRhbG9nLXJlc291cmNlLWJ1bGstcmFuZ2VdJyApICkge1xuXHRcdFx0XHR2YXIgbnVtYmVyX2NvbnRyb2wgPSBmaWVsZF93cmFwLnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLWNhdGFsb2ctcmVzb3VyY2UtYnVsay12YWx1ZV0nICk7XG5cdFx0XHRcdGlmICggbnVtYmVyX2NvbnRyb2wgKSB7XG5cdFx0XHRcdFx0bnVtYmVyX2NvbnRyb2wudmFsdWUgPSBjaGFuZ2VkX2NvbnRyb2wudmFsdWU7XG5cdFx0XHRcdH1cblx0XHRcdH0gZWxzZSB7XG5cdFx0XHRcdHZhciByYW5nZV9jb250cm9sID0gZmllbGRfd3JhcC5xdWVyeVNlbGVjdG9yKCAnW2RhdGEtd3BiYy1jYXRhbG9nLXJlc291cmNlLWJ1bGstcmFuZ2VdJyApO1xuXHRcdFx0XHR2YXIgZmllbGRfdmFsdWVfY29udHJvbCA9IGZpZWxkX3dyYXAucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtY2F0YWxvZy1yZXNvdXJjZS1idWxrLXZhbHVlXScgKTtcblx0XHRcdFx0aWYgKCByYW5nZV9jb250cm9sICYmIGZpZWxkX3ZhbHVlX2NvbnRyb2wgJiYgJycgIT09IGZpZWxkX3ZhbHVlX2NvbnRyb2wudmFsdWUgKSB7XG5cdFx0XHRcdFx0cmFuZ2VfY29udHJvbC52YWx1ZSA9IGZpZWxkX3ZhbHVlX2NvbnRyb2wudmFsdWU7XG5cdFx0XHRcdH1cblx0XHRcdH1cblx0XHR9XG5cdFx0aW5zcGVjdG9yX2J1bGtfb3BlcmF0aW9ucyA9IGNvbGxlY3RfYnVsa19vcGVyYXRpb25zKCk7XG5cdFx0aW5zcGVjdG9yX2RpcnR5ID0gT2JqZWN0LmtleXMoIGluc3BlY3Rvcl9idWxrX29wZXJhdGlvbnMgKS5sZW5ndGggPiAwO1xuXHRcdGlmICggc2F2ZV9idXR0b24gKSB7XG5cdFx0XHRzYXZlX2J1dHRvbi5kaXNhYmxlZCA9IGluc3BlY3Rvcl9zZWxlY3Rpb25fc3RhbGUgfHwgISBpbnNwZWN0b3JfZGlydHk7XG5cdFx0fVxuXHR9XG5cblx0LyoqXG5cdCAqIFJlbmRlciBhIHNpZ25lZCBidWxrLWVkaXQgcmV2aWV3IHdpdGhvdXQgcGVyZm9ybWluZyBhIG11dGF0aW9uLlxuXHQgKlxuXHQgKiBAcGFyYW0ge09iamVjdH0gY29uZmlnICBDYXRhbG9nIGNvbmZpZ3VyYXRpb24uXG5cdCAqIEBwYXJhbSB7T2JqZWN0fSBwcmV2aWV3IFNlcnZlci1hdXRob3JpdGF0aXZlIHByZXZpZXcuXG5cdCAqIEByZXR1cm4ge2Jvb2xlYW59IFRydWUgd2hlbiByZW5kZXJlZC5cblx0ICovXG5cdGZ1bmN0aW9uIHJlbmRlcl9idWxrX3JldmlldyggY29uZmlnLCBwcmV2aWV3ICkge1xuXHRcdHZhciBob3N0ID0gZ2V0X2luc3BlY3Rvcl9ob3N0KCk7XG5cdFx0dmFyIHJldmlld193b3JrZmxvdyA9IGdldF9pbmxpbmVfcmV2aWV3X3dvcmtmbG93KCk7XG5cdFx0dmFyIHJldmlld19tb2RlbDtcblx0XHR2YXIgdGFyZ2V0ID0gaG9zdCA/IGhvc3QucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtdWktY2F0YWxvZy1pbnNwZWN0b3ItZm9ybV0nICkgOiBudWxsO1xuXG5cdFx0aWYgKCAhIHRhcmdldCApIHtcblx0XHRcdHJldHVybiBmYWxzZTtcblx0XHR9XG5cdFx0cmV2aWV3X21vZGVsID0gcmV2aWV3X3dvcmtmbG93ID8gcmV2aWV3X3dvcmtmbG93LnByZXBhcmUoIHByZXZpZXcucmV2aWV3IHx8IHt9LCB7XG5cdFx0XHRjaGFuZ2VkX2xhYmVsOiBnZXRfc2VsZWN0aW9uX2NvdW50X2xhYmVsKCBjb25maWcsIHByZXZpZXcuc2NoZW1hLnJlc291cmNlX2lkcy5sZW5ndGggKSxcblx0XHRcdGRlc2NyaXB0aW9uOiBjb25maWcuaTE4bi5pbmxpbmVfcmV2aWV3X2Rlc2NyaXB0aW9uIHx8ICcnLFxuXHRcdFx0Zm9ybV9pZDogJ3dwYmNfY2F0YWxvZ19ib29raW5nX3Jlc291cmNlc19idWxrX3Jldmlld19mb3JtJyxcblx0XHRcdG1vZGU6ICdidWxrX3JldmlldycsXG5cdFx0XHRwZW5kaW5nX21lc3NhZ2U6IGNvbmZpZy5pMThuLnJldmlld19jaGFuZ2VzX2hlbHAgfHwgJycsXG5cdFx0XHR0aXRsZTogY29uZmlnLmkxOG4ucmV2aWV3X2NoYW5nZXMgfHwgY29uZmlnLmkxOG4uZWRpdF9ib29raW5nX3Jlc291cmNlcyB8fCAnJ1xuXHRcdH0gKSA6IHt9O1xuXHRcdHRhcmdldC5pbm5lckhUTUwgPSByZW5kZXJfY29tcG9uZW50KCBjb25maWcsICdpbnNwZWN0b3JfYnVsa19yZXZpZXcnLCByZXZpZXdfbW9kZWwgKTtcblx0XHRpZiAoICEgdGFyZ2V0LnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLWNhdGFsb2ctcmVzb3VyY2UtYnVsay1yZXZpZXctZm9ybV0nICkgKSB7XG5cdFx0XHRyZXR1cm4gZmFsc2U7XG5cdFx0fVxuXHRcdGluc3BlY3Rvcl9tb2RlID0gJ2J1bGtfcmV2aWV3Jztcblx0XHRpbnNwZWN0b3JfcmV2aWV3X3Rva2VuID0gU3RyaW5nKCBwcmV2aWV3LnJldmlld190b2tlbiB8fCAnJyApO1xuXHRcdGluc3BlY3Rvcl9kaXJ0eSA9IHRydWU7XG5cdFx0c2V0X2luc3BlY3Rvcl9zdGF0ZSggJ2Zvcm0nLCAnJyApO1xuXHRcdGNvbmZpZ3VyZV9pbnNwZWN0b3JfZm9vdGVyKCAnd3BiY19jYXRhbG9nX2Jvb2tpbmdfcmVzb3VyY2VzX2J1bGtfcmV2aWV3X2Zvcm0nLCBjb25maWcuaTE4bi5hcHBseV9jaGFuZ2VzIHx8ICcnLCBmYWxzZSwgaW5zcGVjdG9yX3NlbGVjdGlvbl9zdGFsZSApO1xuXHRcdGlmICggcmV2aWV3X3dvcmtmbG93ICkge1xuXHRcdFx0cmV2aWV3X3dvcmtmbG93LnN5bmNocm9uaXplKCB7IGJ1c3k6IGZhbHNlLCBjYW5fYXBwbHk6ICEgaW5zcGVjdG9yX3NlbGVjdGlvbl9zdGFsZSAmJiAhISBpbnNwZWN0b3JfcmV2aWV3X3Rva2VuIH0gKTtcblx0XHR9XG5cdFx0Zm9jdXNfaW5zcGVjdG9yX2hlYWRpbmcoIHRhcmdldC5xdWVyeVNlbGVjdG9yKCAnW2RhdGEtd3BiYy1jYXRhbG9nLXJlc291cmNlLWJ1bGstcmV2aWV3LWZvcm1dJyApICk7XG5cblx0XHRyZXR1cm4gdHJ1ZTtcblx0fVxuXG5cdC8qKlxuXHQgKiBSZW5kZXIgdGhlIHNpZ25lZCBkZWxldGlvbiBpbXBhY3QgYW5kIGV4cGxpY2l0IGFja25vd2xlZGdlbWVudC5cblx0ICpcblx0ICogQHBhcmFtIHtPYmplY3R9IGNvbmZpZyAgQ2F0YWxvZyBjb25maWd1cmF0aW9uLlxuXHQgKiBAcGFyYW0ge09iamVjdH0gcHJldmlldyBTZXJ2ZXItYXV0aG9yaXRhdGl2ZSBkZWxldGlvbiBwcmV2aWV3LlxuXHQgKiBAcmV0dXJuIHtib29sZWFufSBUcnVlIHdoZW4gcmVuZGVyZWQuXG5cdCAqL1xuXHRmdW5jdGlvbiByZW5kZXJfZGVsZXRlX3JldmlldyggY29uZmlnLCBwcmV2aWV3ICkge1xuXHRcdHZhciBob3N0ID0gZ2V0X2luc3BlY3Rvcl9ob3N0KCk7XG5cdFx0dmFyIHRhcmdldCA9IGhvc3QgPyBob3N0LnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLXVpLWNhdGFsb2ctaW5zcGVjdG9yLWZvcm1dJyApIDogbnVsbDtcblx0XHR2YXIgYWNrbm93bGVkZ2VtZW50O1xuXG5cdFx0aWYgKCAhIHRhcmdldCApIHtcblx0XHRcdHJldHVybiBmYWxzZTtcblx0XHR9XG5cdFx0dmFyIGRlbGV0ZV9pMThuID0gcHJldmlldy5pMThuIHx8IHt9O1xuXG5cdFx0dGFyZ2V0LmlubmVySFRNTCA9IHJlbmRlcl9jb21wb25lbnQoIGNvbmZpZywgJ2luc3BlY3Rvcl9kZWxldGUnLCB7XG5cdFx0XHRkZWxldGVfaTE4bjoge1xuXHRcdFx0XHRhY2tub3dsZWRnZW1lbnQ6IGRlbGV0ZV9pMThuLmFja25vd2xlZGdlbWVudCB8fCBjb25maWcuaTE4bi5kZWxldGVfYWNrbm93bGVkZ2VtZW50IHx8ICcnLFxuXHRcdFx0XHRhY3Rpb25zX2hlYWRpbmc6IGRlbGV0ZV9pMThuLmFjdGlvbnNfaGVhZGluZyB8fCAnJyxcblx0XHRcdFx0Ym9va2luZ3NfcmV0YWluZWRfd2FybmluZzogZGVsZXRlX2kxOG4uYm9va2luZ3NfcmV0YWluZWRfd2FybmluZyB8fCBjb25maWcuaTE4bi5ib29raW5nc19yZXRhaW5lZF93YXJuaW5nIHx8ICcnLFxuXHRcdFx0XHRyZXNvdXJjZXNfdG9fZGVsZXRlOiBkZWxldGVfaTE4bi5yZXNvdXJjZXNfdG9fZGVsZXRlIHx8IGNvbmZpZy5pMThuLnJlc291cmNlc190b19kZWxldGUgfHwgJycsXG5cdFx0XHRcdHJldmlld19oZWxwOiBkZWxldGVfaTE4bi5yZXZpZXdfaGVscCB8fCBjb25maWcuaTE4bi5kZWxldGVfcmV2aWV3X2hlbHAgfHwgJycsXG5cdFx0XHRcdHRpdGxlOiBkZWxldGVfaTE4bi50aXRsZSB8fCBjb25maWcuaTE4bi5kZWxldGVfYm9va2luZ19yZXNvdXJjZXMgfHwgJycsXG5cdFx0XHRcdHdhcm5pbmc6IGRlbGV0ZV9pMThuLndhcm5pbmcgfHwgY29uZmlnLmkxOG4uZGVsZXRlX3dhcm5pbmcgfHwgJydcblx0XHRcdH0sXG5cdFx0XHRpMThuOiBjb25maWcuaTE4biB8fCB7fSxcblx0XHRcdHByZXZpZXc6IHByZXZpZXcsXG5cdFx0XHRzZWxlY3Rpb25fbGFiZWw6IGRlbGV0ZV9pMThuLnNlbGVjdGlvbl9sYWJlbCB8fCBnZXRfc2VsZWN0aW9uX2NvdW50X2xhYmVsKCBjb25maWcsIHByZXZpZXcuc2VsZWN0aW9uX2NvdW50IClcblx0XHR9ICk7XG5cdFx0aWYgKCAhIHRhcmdldC5xdWVyeVNlbGVjdG9yKCAnW2RhdGEtd3BiYy1jYXRhbG9nLXJlc291cmNlLWRlbGV0ZS1mb3JtXScgKSApIHtcblx0XHRcdHJldHVybiBmYWxzZTtcblx0XHR9XG5cdFx0aW5zcGVjdG9yX21vZGUgPSAnZGVsZXRlX3Jldmlldyc7XG5cdFx0aW5zcGVjdG9yX3Jlc291cmNlX2lkcyA9ICggcHJldmlldy5yZXNvdXJjZXMgfHwgW10gKS5tYXAoIGZ1bmN0aW9uICggcmVzb3VyY2UgKSB7IHJldHVybiBOdW1iZXIoIHJlc291cmNlLmlkICk7IH0gKTtcblx0XHRpbnNwZWN0b3JfcmVzb3VyY2VfaWQgPSAhIGluc3BlY3Rvcl90cmFja3Nfc2VsZWN0aW9uICYmIDEgPT09IGluc3BlY3Rvcl9yZXNvdXJjZV9pZHMubGVuZ3RoID8gaW5zcGVjdG9yX3Jlc291cmNlX2lkc1swXSA6IDA7XG5cdFx0aW5zcGVjdG9yX3Jldmlld190b2tlbiA9IFN0cmluZyggcHJldmlldy5yZXZpZXdfdG9rZW4gfHwgJycgKTtcblx0XHRpbnNwZWN0b3Jfc2VsZWN0aW9uX3N0YWxlID0gZmFsc2U7XG5cdFx0aW5zcGVjdG9yX2RpcnR5ID0gZmFsc2U7XG5cdFx0c2V0X2luc3BlY3Rvcl9zdGF0ZSggJ2Zvcm0nLCAnJyApO1xuXHRcdGNvbmZpZ3VyZV9pbnNwZWN0b3JfZm9vdGVyKFxuXHRcdFx0J3dwYmNfY2F0YWxvZ19ib29raW5nX3Jlc291cmNlc19kZWxldGVfZm9ybScsXG5cdFx0XHRkZWxldGVfaTE4bi5kZWxldGVfYnV0dG9uIHx8IGZvcm1hdF9tZXNzYWdlKCAxID09PSBOdW1iZXIoIHByZXZpZXcuc2VsZWN0aW9uX2NvdW50ICkgPyBjb25maWcuaTE4bi5kZWxldGVfcmVzb3VyY2UgfHwgJycgOiBjb25maWcuaTE4bi5kZWxldGVfcmVzb3VyY2VzIHx8ICcnLCBbIHByZXZpZXcuc2VsZWN0aW9uX2NvdW50IF0gKSxcblx0XHRcdHRydWUsXG5cdFx0XHR0cnVlXG5cdFx0KTtcblx0XHRhY2tub3dsZWRnZW1lbnQgPSB0YXJnZXQucXVlcnlTZWxlY3RvciggJy53cGJjX2Jvb2tpbmdfcmVzb3VyY2VzX19kZWxldGVfYWNrbm93bGVkZ2VtZW50JyApO1xuXHRcdHB1bHNlX2RlbGV0ZV9hY2tub3dsZWRnZW1lbnQoIGFja25vd2xlZGdlbWVudCApO1xuXHRcdG1hcmtfaW5zcGVjdG9yX3Jlc291cmNlX3JvdyggaW5zcGVjdG9yX3Jlc291cmNlX2lkICk7XG5cdFx0Zm9jdXNfaW5zcGVjdG9yX2hlYWRpbmcoIHRhcmdldC5xdWVyeVNlbGVjdG9yKCAnW2RhdGEtd3BiYy1jYXRhbG9nLXJlc291cmNlLWRlbGV0ZS1mb3JtXScgKSApO1xuXG5cdFx0cmV0dXJuIHRydWU7XG5cdH1cblxuXHQvKipcblx0ICogT3BlbiB0aGUgaW5kZXBlbmRlbnQgZGVsZXRpb24gcmV2aWV3IGZvciBleHBsaWNpdCBSZXNvdXJjZSBJRHMuXG5cdCAqXG5cdCAqIEBwYXJhbSB7T2JqZWN0fSAgICAgICAgY29uZmlnICAgICAgIENhdGFsb2cgY29uZmlndXJhdGlvbi5cblx0ICogQHBhcmFtIHtBcnJheTxudW1iZXI+fSByZXNvdXJjZV9pZHMgUmVzb3VyY2UgSURzIHNlbGVjdGVkIGZvciBkZWxldGlvbi5cblx0ICogQHBhcmFtIHtIVE1MRWxlbWVudH0gICBmb2N1c190YXJnZXQgSW5pdGlhdGluZyBjb250cm9sLlxuXHQgKiBAcGFyYW0ge2Jvb2xlYW59ICAgICAgIHRyYWNrX3NlbGVjdGlvbiBXaGV0aGVyIGRlbGV0aW9uIG93bnMgdGhlIGNoZWNrYm94IHNlbGVjdGlvbi5cblx0ICogQHJldHVybiB7dm9pZH1cblx0ICovXG5cdGZ1bmN0aW9uIG9wZW5fZGVsZXRlX3JldmlldyggY29uZmlnLCByZXNvdXJjZV9pZHMsIGZvY3VzX3RhcmdldCwgdHJhY2tfc2VsZWN0aW9uICkge1xuXHRcdHZhciByZXF1ZXN0X3NlcXVlbmNlO1xuXG5cdFx0cmVzb3VyY2VfaWRzID0gKCByZXNvdXJjZV9pZHMgfHwgW10gKS5tYXAoIE51bWJlciApLmZpbHRlciggZnVuY3Rpb24gKCByZXNvdXJjZV9pZCApIHsgcmV0dXJuIHJlc291cmNlX2lkID4gMDsgfSApO1xuXHRcdGlmICggISByZXNvdXJjZV9pZHMubGVuZ3RoIHx8ICEgY2FuX2Rpc2NhcmRfaW5zcGVjdG9yKCBjb25maWcgKSB8fCAhIG1vdW50X2luc3BlY3Rvcl9zaGVsbCggY29uZmlnICkgKSB7XG5cdFx0XHRyZXR1cm47XG5cdFx0fVxuXHRcdGNsb3NlX2RldGFpbHNfcm93KCBmYWxzZSApO1xuXHRcdGluc3BlY3Rvcl9mb2N1c190YXJnZXQgPSBmb2N1c190YXJnZXQgfHwgZG9jdW1lbnQuYWN0aXZlRWxlbWVudDtcblx0XHRyZXF1ZXN0X3NlcXVlbmNlID0gKytpbnNwZWN0b3JfcmVxdWVzdF9zZXF1ZW5jZTtcblx0XHRpbnNwZWN0b3JfbW9kZSA9ICdkZWxldGVfcmV2aWV3Jztcblx0XHRpbnNwZWN0b3JfcmVzb3VyY2VfaWRzID0gcmVzb3VyY2VfaWRzLnNsaWNlKCk7XG5cdFx0aW5zcGVjdG9yX3Jlc291cmNlX2lkID0gISB0cmFja19zZWxlY3Rpb24gJiYgMSA9PT0gaW5zcGVjdG9yX3Jlc291cmNlX2lkcy5sZW5ndGggPyBpbnNwZWN0b3JfcmVzb3VyY2VfaWRzWzBdIDogMDtcblx0XHRpbnNwZWN0b3JfZGlydHkgPSBmYWxzZTtcblx0XHRpbnNwZWN0b3JfdHJhY2tzX3NlbGVjdGlvbiA9ICEhIHRyYWNrX3NlbGVjdGlvbjtcblx0XHRzeW5jaHJvbml6ZV9pbnNwZWN0b3Jfd2lkdGgoKTtcblx0XHRzZXRfaW5zcGVjdG9yX3N0YXRlKCAnbG9hZGluZycsICcnICk7XG5cdFx0bWFya19pbnNwZWN0b3JfcmVzb3VyY2Vfcm93KCBpbnNwZWN0b3JfcmVzb3VyY2VfaWQgKTtcblx0XHRleHBhbmRfaW5zcGVjdG9yX3NpZGViYXIoKTtcblxuXHRcdHJlcXVlc3RfaW5zcGVjdG9yKCBjb25maWcsIGNvbmZpZy5kZWxldGVfcHJldmlld19hY3Rpb24sIHsgcmVzb3VyY2VfaWRzOiBKU09OLnN0cmluZ2lmeSggcmVzb3VyY2VfaWRzICkgfSApLnRoZW4oIGZ1bmN0aW9uICggcmVzcG9uc2UgKSB7XG5cdFx0XHRpZiAoIHJlcXVlc3Rfc2VxdWVuY2UgIT09IGluc3BlY3Rvcl9yZXF1ZXN0X3NlcXVlbmNlICkge1xuXHRcdFx0XHRyZXR1cm47XG5cdFx0XHR9XG5cdFx0XHRpZiAoICEgcmVzcG9uc2UgfHwgdHJ1ZSAhPT0gcmVzcG9uc2Uuc3VjY2VzcyB8fCAhIHJlc3BvbnNlLmRhdGEgfHwgISByZXNwb25zZS5kYXRhLnByZXZpZXcgfHwgISByZW5kZXJfZGVsZXRlX3JldmlldyggY29uZmlnLCByZXNwb25zZS5kYXRhLnByZXZpZXcgKSApIHtcblx0XHRcdFx0c2V0X2luc3BlY3Rvcl9zdGF0ZSggJ2Vycm9yJywgZ2V0X2luc3BlY3Rvcl9yZXNwb25zZV9tZXNzYWdlKCByZXNwb25zZSwgY29uZmlnLmkxOG4uZGVsZXRlX2xvYWRfZmFpbGVkICkgKTtcblx0XHRcdH0gZWxzZSBpZiAoIGluc3BlY3Rvcl90cmFja3Nfc2VsZWN0aW9uICYmICEgcmVzb3VyY2VfaWRfbGlzdHNfbWF0Y2goIGluc3BlY3Rvcl9yZXNvdXJjZV9pZHMsIGdldF9zZWxlY3RlZF9yZXNvdXJjZV9pZHMoIGNvbmZpZyApICkgKSB7XG5cdFx0XHRcdGhhbmRsZV9pbnNwZWN0b3Jfc2VsZWN0aW9uX2NoYW5nZSggbnVsbCwgY29uZmlnICk7XG5cdFx0XHR9XG5cdFx0fSApLmNhdGNoKCBmdW5jdGlvbiAoKSB7XG5cdFx0XHRpZiAoIHJlcXVlc3Rfc2VxdWVuY2UgPT09IGluc3BlY3Rvcl9yZXF1ZXN0X3NlcXVlbmNlICkge1xuXHRcdFx0XHRzZXRfaW5zcGVjdG9yX3N0YXRlKCAnZXJyb3InLCBjb25maWcuaTE4bi5kZWxldGVfbG9hZF9mYWlsZWQgfHwgJycgKTtcblx0XHRcdH1cblx0XHR9ICk7XG5cdH1cblxuXHQvKipcblx0ICogUmV0dXJuIG9uZSBsb2NhbGl6ZWQgY2FwYWNpdHkgY291bnQgbGFiZWwuXG5cdCAqXG5cdCAqIEBwYXJhbSB7T2JqZWN0fSBjb25maWcgICAgICAgQ2F0YWxvZyBjb25maWd1cmF0aW9uLlxuXHQgKiBAcGFyYW0ge3N0cmluZ30gc2luZ3VsYXJfa2V5IFNpbmd1bGFyIHRyYW5zbGF0aW9uIGtleS5cblx0ICogQHBhcmFtIHtzdHJpbmd9IHBsdXJhbF9rZXkgICBQbHVyYWwgdHJhbnNsYXRpb24ga2V5LlxuXHQgKiBAcGFyYW0ge251bWJlcn0gY291bnQgICAgICAgIE5vbi1uZWdhdGl2ZSBjb3VudC5cblx0ICogQHJldHVybiB7c3RyaW5nfSBMb2NhbGl6ZWQgbGFiZWwuXG5cdCAqL1xuXHRmdW5jdGlvbiBnZXRfY2FwYWNpdHlfY291bnRfbGFiZWwoIGNvbmZpZywgc2luZ3VsYXJfa2V5LCBwbHVyYWxfa2V5LCBjb3VudCApIHtcblx0XHR2YXIgdGVtcGxhdGUgPSAxID09PSBOdW1iZXIoIGNvdW50ICkgPyBjb25maWcuaTE4blsgc2luZ3VsYXJfa2V5IF0gOiBjb25maWcuaTE4blsgcGx1cmFsX2tleSBdO1xuXG5cdFx0cmV0dXJuIGZvcm1hdF9tZXNzYWdlKCB0ZW1wbGF0ZSB8fCAnJywgWyBjb3VudCBdICk7XG5cdH1cblxuXHQvKipcblx0ICogQnVpbGQgcHJlc2VudGF0aW9uLW9ubHkgZGF0YSBmb3IgdGhlIGNhcGFjaXR5IFdQIHRlbXBsYXRlLlxuXHQgKlxuXHQgKiBAcGFyYW0ge09iamVjdH0gY29uZmlnICBDYXRhbG9nIGNvbmZpZ3VyYXRpb24uXG5cdCAqIEBwYXJhbSB7T2JqZWN0fSBjb250ZXh0IFNlcnZlci1hdXRob3JpdGF0aXZlIGNhcGFjaXR5IGNvbnRleHQuXG5cdCAqIEByZXR1cm4ge09iamVjdH0gVGVtcGxhdGUgdmlldyBkYXRhLlxuXHQgKi9cblx0ZnVuY3Rpb24gZ2V0X2NhcGFjaXR5X2VkaXRvcl92aWV3KCBjb25maWcsIGNvbnRleHQgKSB7XG5cdFx0dmFyIGN1cnJlbnRfY2FwYWNpdHkgPSBOdW1iZXIoIGNvbnRleHQuY3VycmVudF9jYXBhY2l0eSApIHx8IDE7XG5cdFx0dmFyIHRhcmdldF9jYXBhY2l0eSA9IGluc3BlY3Rvcl9jYXBhY2l0eV90YXJnZXQgfHwgY3VycmVudF9jYXBhY2l0eTtcblx0XHR2YXIgb3BlcmF0aW9uID0gdGFyZ2V0X2NhcGFjaXR5ID4gY3VycmVudF9jYXBhY2l0eSA/ICdpbmNyZWFzZScgOiAoIHRhcmdldF9jYXBhY2l0eSA8IGN1cnJlbnRfY2FwYWNpdHkgPyAnZGVjcmVhc2UnIDogJ3VuY2hhbmdlZCcgKTtcblx0XHR2YXIga2VlcF9jb3VudCA9ICdkZWNyZWFzZScgPT09IG9wZXJhdGlvbiA/IHRhcmdldF9jYXBhY2l0eSA6IGN1cnJlbnRfY2FwYWNpdHk7XG5cdFx0dmFyIGNyZWF0ZV9jb3VudCA9IE1hdGgubWF4KCAwLCB0YXJnZXRfY2FwYWNpdHkgLSBjdXJyZW50X2NhcGFjaXR5ICk7XG5cdFx0dmFyIGRlY3JlYXNlX2NvdW50ID0gTWF0aC5tYXgoIDAsIGN1cnJlbnRfY2FwYWNpdHkgLSB0YXJnZXRfY2FwYWNpdHkgKTtcblx0XHR2YXIgZGVsZXRlX2FjdGlvbiA9ICdkZWNyZWFzZScgPT09IG9wZXJhdGlvbiAmJiAnZGVsZXRlJyA9PT0gaW5zcGVjdG9yX2NhcGFjaXR5X2RlY3JlYXNlX2FjdGlvbjtcblxuXHRcdHJldHVybiB7XG5cdFx0XHRjaGlsZHJlbjogKCBjb250ZXh0LmNoaWxkcmVuIHx8IFtdICkubWFwKCBmdW5jdGlvbiAoIGNoaWxkICkge1xuXHRcdFx0XHRjaGlsZCA9IE9iamVjdC5hc3NpZ24oIHt9LCBjaGlsZCApO1xuXHRcdFx0XHRjaGlsZC5zZWxlY3RlZCA9IC0xICE9PSBpbnNwZWN0b3JfY2FwYWNpdHlfZGV0YWNoX2lkcy5pbmRleE9mKCBOdW1iZXIoIGNoaWxkLmlkICkgKTtcblx0XHRcdFx0cmV0dXJuIGNoaWxkO1xuXHRcdFx0fSApLFxuXHRcdFx0Y29udGV4dF9sYWJlbDogKCBjb25maWcuaTE4bi5yZXNvdXJjZV9pZCB8fCAnSUQnICkgKyAnOiAnICsgU3RyaW5nKCBjb250ZXh0LnJlc291cmNlX2lkICksXG5cdFx0XHRjdXJyZW50X2NhcGFjaXR5OiBjdXJyZW50X2NhcGFjaXR5LFxuXHRcdFx0ZGVjcmVhc2VfYWN0aW9uOiBpbnNwZWN0b3JfY2FwYWNpdHlfZGVjcmVhc2VfYWN0aW9uLFxuXHRcdFx0ZGVjcmVhc2VfaGVhZGluZzogZ2V0X2NhcGFjaXR5X2NvdW50X2xhYmVsKCBjb25maWcsIGRlbGV0ZV9hY3Rpb24gPyAnY2hvb3NlX2RlbGV0ZV91bml0JyA6ICdjaG9vc2VfZGV0YWNoX3VuaXQnLCBkZWxldGVfYWN0aW9uID8gJ2Nob29zZV9kZWxldGVfdW5pdHMnIDogJ2Nob29zZV9kZXRhY2hfdW5pdHMnLCBkZWNyZWFzZV9jb3VudCApLFxuXHRcdFx0ZGVjcmVhc2VfaGVscDogZGVsZXRlX2FjdGlvbiA/IGNvbmZpZy5pMThuLmRlbGV0ZV91bml0c19oZWxwIHx8ICcnIDogY29uZmlnLmkxOG4uc2VsZWN0X2RldGFjaF9oZWxwIHx8ICcnLFxuXHRcdFx0ZGVjcmVhc2Vfb3V0Y29tZV9sYWJlbDogZGVsZXRlX2FjdGlvbiA/IGNvbmZpZy5pMThuLndpbGxfYmVfZGVsZXRlZCB8fCAnJyA6IGNvbmZpZy5pMThuLm1ha2VfaW5kZXBlbmRlbnQgfHwgJycsXG5cdFx0XHRkZXNjcmlwdGlvbjogY29uZmlnLmkxOG4uY2FwYWNpdHlfZGVzY3JpcHRpb24gfHwgJycsXG5cdFx0XHRjcmVhdGVfbGFiZWw6IGdldF9jYXBhY2l0eV9jb3VudF9sYWJlbCggY29uZmlnLCAnY3JlYXRlX25ld191bml0JywgJ2NyZWF0ZV9uZXdfdW5pdHMnLCBjcmVhdGVfY291bnQgKSxcblx0XHRcdGtlZXBfbGFiZWw6IGdldF9jYXBhY2l0eV9jb3VudF9sYWJlbCggY29uZmlnLCAna2VlcF9leGlzdGluZ191bml0JywgJ2tlZXBfZXhpc3RpbmdfdW5pdHMnLCBrZWVwX2NvdW50ICksXG5cdFx0XHRtYXhpbXVtX2NhcGFjaXR5OiBOdW1iZXIoIGNvbnRleHQubWF4aW11bV9jYXBhY2l0eSApIHx8IGN1cnJlbnRfY2FwYWNpdHksXG5cdFx0XHRtaW5pbXVtX2NhcGFjaXR5OiBOdW1iZXIoIGNvbnRleHQubWluaW11bV9jYXBhY2l0eSApIHx8IDEsXG5cdFx0XHRtb2RlOiAnY2FwYWNpdHknLFxuXHRcdFx0b3BlcmF0aW9uOiBvcGVyYXRpb24sXG5cdFx0XHR0YXJnZXRfY2FwYWNpdHk6IHRhcmdldF9jYXBhY2l0eSxcblx0XHRcdHRpdGxlOiBjb25maWcuaTE4bi5hZGp1c3RfY2FwYWNpdHkgfHwgJydcblx0XHR9O1xuXHR9XG5cblx0LyoqXG5cdCAqIFN5bmNocm9uaXplIHRoZSBsaXZlIGNhcGFjaXR5IHBsYW4gd2l0aG91dCByZXBsYWNpbmcgZm9jdXNlZCBjb250cm9scy5cblx0ICpcblx0ICogQHBhcmFtIHtPYmplY3R9IGNvbmZpZyBDYXRhbG9nIGNvbmZpZ3VyYXRpb24uXG5cdCAqIEByZXR1cm4ge3ZvaWR9XG5cdCAqL1xuXHRmdW5jdGlvbiBzeW5jaHJvbml6ZV9jYXBhY2l0eV9lZGl0b3IoIGNvbmZpZyApIHtcblx0XHR2YXIgZm9ybSA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLWNhdGFsb2ctcmVzb3VyY2UtY2FwYWNpdHktZm9ybV1bZGF0YS1tb2RlPVwiY2FwYWNpdHlcIl0nICk7XG5cdFx0dmFyIHNhdmVfYnV0dG9uID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtdWktY2F0YWxvZy1pbnNwZWN0b3Itc2F2ZV0nICk7XG5cdFx0dmFyIGNvbnRleHQgPSBpbnNwZWN0b3JfY2FwYWNpdHlfY29udGV4dCB8fCB7fTtcblx0XHR2YXIgY3VycmVudF9jYXBhY2l0eSA9IE51bWJlciggY29udGV4dC5jdXJyZW50X2NhcGFjaXR5ICkgfHwgMTtcblx0XHR2YXIgdGFyZ2V0X2NhcGFjaXR5ID0gaW5zcGVjdG9yX2NhcGFjaXR5X3RhcmdldCB8fCBjdXJyZW50X2NhcGFjaXR5O1xuXHRcdHZhciBvcGVyYXRpb24gPSB0YXJnZXRfY2FwYWNpdHkgPiBjdXJyZW50X2NhcGFjaXR5ID8gJ2luY3JlYXNlJyA6ICggdGFyZ2V0X2NhcGFjaXR5IDwgY3VycmVudF9jYXBhY2l0eSA/ICdkZWNyZWFzZScgOiAndW5jaGFuZ2VkJyApO1xuXHRcdHZhciByZXF1aXJlZF9kZXRhY2hfY291bnQgPSBNYXRoLm1heCggMCwgY3VycmVudF9jYXBhY2l0eSAtIHRhcmdldF9jYXBhY2l0eSApO1xuXHRcdHZhciB0YXJnZXRfbnVtYmVyID0gZm9ybSA/IGZvcm0ucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtY2F0YWxvZy1jYXBhY2l0eS10YXJnZXRdJyApIDogbnVsbDtcblx0XHR2YXIgdGFyZ2V0X3JhbmdlID0gZm9ybSA/IGZvcm0ucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtY2F0YWxvZy1jYXBhY2l0eS1yYW5nZV0nICkgOiBudWxsO1xuXG5cdFx0aWYgKCAhIGZvcm0gKSB7XG5cdFx0XHRyZXR1cm47XG5cdFx0fVxuXHRcdGlmICggJ2RlY3JlYXNlJyAhPT0gb3BlcmF0aW9uICkge1xuXHRcdFx0aW5zcGVjdG9yX2NhcGFjaXR5X2RldGFjaF9pZHMgPSBbXTtcblx0XHRcdGluc3BlY3Rvcl9jYXBhY2l0eV9kZWNyZWFzZV9hY3Rpb24gPSAnZGV0YWNoJztcblx0XHR9IGVsc2UgaWYgKCBpbnNwZWN0b3JfY2FwYWNpdHlfZGV0YWNoX2lkcy5sZW5ndGggPiByZXF1aXJlZF9kZXRhY2hfY291bnQgKSB7XG5cdFx0XHRpbnNwZWN0b3JfY2FwYWNpdHlfZGV0YWNoX2lkcyA9IGluc3BlY3Rvcl9jYXBhY2l0eV9kZXRhY2hfaWRzLnNsaWNlKCAwLCByZXF1aXJlZF9kZXRhY2hfY291bnQgKTtcblx0XHR9XG5cdFx0aWYgKCB0YXJnZXRfbnVtYmVyICkge1xuXHRcdFx0dGFyZ2V0X251bWJlci52YWx1ZSA9IFN0cmluZyggdGFyZ2V0X2NhcGFjaXR5ICk7XG5cdFx0fVxuXHRcdGlmICggdGFyZ2V0X3JhbmdlICkge1xuXHRcdFx0dGFyZ2V0X3JhbmdlLnZhbHVlID0gU3RyaW5nKCB0YXJnZXRfY2FwYWNpdHkgKTtcblx0XHR9XG5cdFx0dmFyIGFmdGVyX3ZhbHVlID0gZm9ybS5xdWVyeVNlbGVjdG9yKCAnW2RhdGEtd3BiYy1jYXRhbG9nLWNhcGFjaXR5LWFmdGVyXScgKTtcblx0XHR2YXIga2VlcF9sYWJlbCA9IGZvcm0ucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtY2F0YWxvZy1jYXBhY2l0eS1rZWVwLWxhYmVsXScgKTtcblx0XHR2YXIgY3JlYXRlX2xhYmVsID0gZm9ybS5xdWVyeVNlbGVjdG9yKCAnW2RhdGEtd3BiYy1jYXRhbG9nLWNhcGFjaXR5LWNyZWF0ZS1sYWJlbF0nICk7XG5cdFx0dmFyIGluY3JlYXNlX3JvdyA9IGZvcm0ucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtY2F0YWxvZy1jYXBhY2l0eS1pbmNyZWFzZS1yb3ddJyApO1xuXHRcdHZhciBkZWNyZWFzZV9wYW5lbCA9IGZvcm0ucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtY2F0YWxvZy1jYXBhY2l0eS1kZWNyZWFzZV0nICk7XG5cdFx0dmFyIGRlY3JlYXNlX2hlYWRpbmcgPSBmb3JtLnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLWNhdGFsb2ctY2FwYWNpdHktZGVjcmVhc2UtaGVhZGluZ10nICk7XG5cdFx0dmFyIGRlY3JlYXNlX2hlbHAgPSBmb3JtLnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLWNhdGFsb2ctY2FwYWNpdHktZGVjcmVhc2UtaGVscF0nICk7XG5cdFx0dmFyIGRlbGV0ZV9hY3Rpb24gPSAnZGVjcmVhc2UnID09PSBvcGVyYXRpb24gJiYgJ2RlbGV0ZScgPT09IGluc3BlY3Rvcl9jYXBhY2l0eV9kZWNyZWFzZV9hY3Rpb247XG5cdFx0aWYgKCBhZnRlcl92YWx1ZSApIHtcblx0XHRcdGFmdGVyX3ZhbHVlLnRleHRDb250ZW50ID0gU3RyaW5nKCB0YXJnZXRfY2FwYWNpdHkgKTtcblx0XHR9XG5cdFx0aWYgKCBrZWVwX2xhYmVsICkge1xuXHRcdFx0a2VlcF9sYWJlbC50ZXh0Q29udGVudCA9IGdldF9jYXBhY2l0eV9jb3VudF9sYWJlbCggY29uZmlnLCAna2VlcF9leGlzdGluZ191bml0JywgJ2tlZXBfZXhpc3RpbmdfdW5pdHMnLCAnZGVjcmVhc2UnID09PSBvcGVyYXRpb24gPyB0YXJnZXRfY2FwYWNpdHkgOiBjdXJyZW50X2NhcGFjaXR5ICk7XG5cdFx0fVxuXHRcdGlmICggY3JlYXRlX2xhYmVsICkge1xuXHRcdFx0Y3JlYXRlX2xhYmVsLnRleHRDb250ZW50ID0gZ2V0X2NhcGFjaXR5X2NvdW50X2xhYmVsKCBjb25maWcsICdjcmVhdGVfbmV3X3VuaXQnLCAnY3JlYXRlX25ld191bml0cycsIE1hdGgubWF4KCAwLCB0YXJnZXRfY2FwYWNpdHkgLSBjdXJyZW50X2NhcGFjaXR5ICkgKTtcblx0XHR9XG5cdFx0aWYgKCBpbmNyZWFzZV9yb3cgKSB7XG5cdFx0XHRpbmNyZWFzZV9yb3cuaGlkZGVuID0gJ2luY3JlYXNlJyAhPT0gb3BlcmF0aW9uO1xuXHRcdH1cblx0XHRpZiAoIGRlY3JlYXNlX3BhbmVsICkge1xuXHRcdFx0ZGVjcmVhc2VfcGFuZWwuaGlkZGVuID0gJ2RlY3JlYXNlJyAhPT0gb3BlcmF0aW9uO1xuXHRcdH1cblx0XHRpZiAoIGRlY3JlYXNlX2hlYWRpbmcgKSB7XG5cdFx0XHRkZWNyZWFzZV9oZWFkaW5nLnRleHRDb250ZW50ID0gZ2V0X2NhcGFjaXR5X2NvdW50X2xhYmVsKCBjb25maWcsIGRlbGV0ZV9hY3Rpb24gPyAnY2hvb3NlX2RlbGV0ZV91bml0JyA6ICdjaG9vc2VfZGV0YWNoX3VuaXQnLCBkZWxldGVfYWN0aW9uID8gJ2Nob29zZV9kZWxldGVfdW5pdHMnIDogJ2Nob29zZV9kZXRhY2hfdW5pdHMnLCByZXF1aXJlZF9kZXRhY2hfY291bnQgKTtcblx0XHR9XG5cdFx0aWYgKCBkZWNyZWFzZV9oZWxwICkge1xuXHRcdFx0ZGVjcmVhc2VfaGVscC50ZXh0Q29udGVudCA9IGRlbGV0ZV9hY3Rpb24gPyBjb25maWcuaTE4bi5kZWxldGVfdW5pdHNfaGVscCB8fCAnJyA6IGNvbmZpZy5pMThuLnNlbGVjdF9kZXRhY2hfaGVscCB8fCAnJztcblx0XHR9XG5cdFx0Zm9ybS5xdWVyeVNlbGVjdG9yQWxsKCAnW2RhdGEtd3BiYy1jYXRhbG9nLWNhcGFjaXR5LWRlY3JlYXNlLWFjdGlvbl0nICkuZm9yRWFjaCggZnVuY3Rpb24gKCBhY3Rpb25fY29udHJvbCApIHtcblx0XHRcdHZhciBhY3Rpb25fc2VsZWN0ZWQgPSBhY3Rpb25fY29udHJvbC52YWx1ZSA9PT0gaW5zcGVjdG9yX2NhcGFjaXR5X2RlY3JlYXNlX2FjdGlvbjtcblx0XHRcdGFjdGlvbl9jb250cm9sLmNoZWNrZWQgPSBhY3Rpb25fc2VsZWN0ZWQ7XG5cdFx0XHRpZiAoIGFjdGlvbl9jb250cm9sLmNsb3Nlc3QoICdsYWJlbCcgKSApIHtcblx0XHRcdFx0YWN0aW9uX2NvbnRyb2wuY2xvc2VzdCggJ2xhYmVsJyApLmNsYXNzTGlzdC50b2dnbGUoICdpcy1zZWxlY3RlZCcsIGFjdGlvbl9zZWxlY3RlZCApO1xuXHRcdFx0fVxuXHRcdH0gKTtcblx0XHRmb3JtLnF1ZXJ5U2VsZWN0b3JBbGwoICdbZGF0YS13cGJjLWNhdGFsb2ctY2FwYWNpdHktZGV0YWNoXScgKS5mb3JFYWNoKCBmdW5jdGlvbiAoIGNoZWNrYm94ICkge1xuXHRcdFx0dmFyIHNlbGVjdGVkID0gLTEgIT09IGluc3BlY3Rvcl9jYXBhY2l0eV9kZXRhY2hfaWRzLmluZGV4T2YoIE51bWJlciggY2hlY2tib3gudmFsdWUgKSApO1xuXHRcdFx0dmFyIHVuaXQgPSBjaGVja2JveC5jbG9zZXN0KCAnLndwYmNfYm9va2luZ19yZXNvdXJjZXNfX2NhcGFjaXR5X3VuaXQnICk7XG5cdFx0XHR2YXIgb3V0Y29tZSA9IHVuaXQgPyB1bml0LnF1ZXJ5U2VsZWN0b3IoICcud3BiY19ib29raW5nX3Jlc291cmNlc19fY2FwYWNpdHlfdW5pdF9vdXRjb21lJyApIDogbnVsbDtcblx0XHRcdGNoZWNrYm94LmNoZWNrZWQgPSBzZWxlY3RlZDtcblx0XHRcdGNoZWNrYm94LmRpc2FibGVkID0gISBzZWxlY3RlZCAmJiBpbnNwZWN0b3JfY2FwYWNpdHlfZGV0YWNoX2lkcy5sZW5ndGggPj0gcmVxdWlyZWRfZGV0YWNoX2NvdW50O1xuXHRcdFx0aWYgKCB1bml0ICkge1xuXHRcdFx0XHR1bml0LmNsYXNzTGlzdC50b2dnbGUoICdpcy1zZWxlY3RlZCcsIHNlbGVjdGVkICk7XG5cdFx0XHR9XG5cdFx0XHRpZiAoIG91dGNvbWUgKSB7XG5cdFx0XHRcdG91dGNvbWUuaGlkZGVuID0gISBzZWxlY3RlZDtcblx0XHRcdFx0b3V0Y29tZS50ZXh0Q29udGVudCA9IGRlbGV0ZV9hY3Rpb24gPyBjb25maWcuaTE4bi53aWxsX2JlX2RlbGV0ZWQgfHwgJycgOiBjb25maWcuaTE4bi5tYWtlX2luZGVwZW5kZW50IHx8ICcnO1xuXHRcdFx0XHRvdXRjb21lLmNsYXNzTGlzdC50b2dnbGUoICdpcy1kZXN0cnVjdGl2ZScsIGRlbGV0ZV9hY3Rpb24gKTtcblx0XHRcdH1cblx0XHR9ICk7XG5cdFx0aW5zcGVjdG9yX2RpcnR5ID0gdGFyZ2V0X2NhcGFjaXR5ICE9PSBjdXJyZW50X2NhcGFjaXR5O1xuXHRcdGlmICggc2F2ZV9idXR0b24gKSB7XG5cdFx0XHRzYXZlX2J1dHRvbi5kaXNhYmxlZCA9ICd1bmNoYW5nZWQnID09PSBvcGVyYXRpb24gfHwgKCAnZGVjcmVhc2UnID09PSBvcGVyYXRpb24gJiYgaW5zcGVjdG9yX2NhcGFjaXR5X2RldGFjaF9pZHMubGVuZ3RoICE9PSByZXF1aXJlZF9kZXRhY2hfY291bnQgKTtcblx0XHR9XG5cdH1cblxuXHQvKipcblx0ICogUmVuZGVyIGFuIGF1dGhvcml6ZWQgY2FwYWNpdHkgZWRpdG9yIGNvbnRleHQuXG5cdCAqXG5cdCAqIEBwYXJhbSB7T2JqZWN0fSBjb25maWcgIENhdGFsb2cgY29uZmlndXJhdGlvbi5cblx0ICogQHBhcmFtIHtPYmplY3R9IGNvbnRleHQgU2VydmVyIGNhcGFjaXR5IGNvbnRleHQuXG5cdCAqIEByZXR1cm4ge2Jvb2xlYW59IFRydWUgd2hlbiB0aGUgdGVtcGxhdGUgcmVuZGVyZWQuXG5cdCAqL1xuXHRmdW5jdGlvbiByZW5kZXJfY2FwYWNpdHlfZWRpdG9yKCBjb25maWcsIGNvbnRleHQgKSB7XG5cdFx0dmFyIGhvc3QgPSBnZXRfaW5zcGVjdG9yX2hvc3QoKTtcblx0XHR2YXIgdGFyZ2V0ID0gaG9zdCA/IGhvc3QucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtdWktY2F0YWxvZy1pbnNwZWN0b3ItZm9ybV0nICkgOiBudWxsO1xuXG5cdFx0aWYgKCAhIHRhcmdldCApIHtcblx0XHRcdHJldHVybiBmYWxzZTtcblx0XHR9XG5cdFx0aW5zcGVjdG9yX2NhcGFjaXR5X2NvbnRleHQgPSBjb250ZXh0O1xuXHRcdGluc3BlY3Rvcl9jYXBhY2l0eV90YXJnZXQgPSBOdW1iZXIoIGNvbnRleHQuY3VycmVudF9jYXBhY2l0eSApIHx8IDE7XG5cdFx0aW5zcGVjdG9yX2NhcGFjaXR5X2RldGFjaF9pZHMgPSBbXTtcblx0XHRpbnNwZWN0b3JfY2FwYWNpdHlfZGVjcmVhc2VfYWN0aW9uID0gJ2RldGFjaCc7XG5cdFx0dGFyZ2V0LmlubmVySFRNTCA9IHJlbmRlcl9jb21wb25lbnQoIGNvbmZpZywgJ2luc3BlY3Rvcl9jYXBhY2l0eScsIHtcblx0XHRcdGkxOG46IGNvbmZpZy5pMThuIHx8IHt9LFxuXHRcdFx0dmlldzogZ2V0X2NhcGFjaXR5X2VkaXRvcl92aWV3KCBjb25maWcsIGNvbnRleHQgKVxuXHRcdH0gKTtcblx0XHRpZiAoICEgdGFyZ2V0LnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLWNhdGFsb2ctcmVzb3VyY2UtY2FwYWNpdHktZm9ybV0nICkgKSB7XG5cdFx0XHRyZXR1cm4gZmFsc2U7XG5cdFx0fVxuXHRcdGluc3BlY3Rvcl9tb2RlID0gJ2NhcGFjaXR5Jztcblx0XHRpbnNwZWN0b3JfcmVzb3VyY2VfaWQgPSBOdW1iZXIoIGNvbnRleHQucmVzb3VyY2VfaWQgKSB8fCAwO1xuXHRcdGluc3BlY3Rvcl9yZXZpZXdfdG9rZW4gPSAnJztcblx0XHRpbnNwZWN0b3JfZGlydHkgPSBmYWxzZTtcblx0XHRzZXRfaW5zcGVjdG9yX3N0YXRlKCAnZm9ybScsICcnICk7XG5cdFx0Y29uZmlndXJlX2luc3BlY3Rvcl9mb290ZXIoICd3cGJjX2NhdGFsb2dfYm9va2luZ19yZXNvdXJjZV9jYXBhY2l0eV9mb3JtJywgY29uZmlnLmkxOG4ucmV2aWV3X2NhcGFjaXR5X2NoYW5nZSB8fCAnJywgZmFsc2UsIHRydWUgKTtcblx0XHRtYXJrX2luc3BlY3Rvcl9yZXNvdXJjZV9yb3coIGluc3BlY3Rvcl9yZXNvdXJjZV9pZCApO1xuXHRcdGZvY3VzX2luc3BlY3Rvcl9oZWFkaW5nKCB0YXJnZXQucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtY2F0YWxvZy1yZXNvdXJjZS1jYXBhY2l0eS1mb3JtXScgKSApO1xuXG5cdFx0cmV0dXJuIHRydWU7XG5cdH1cblxuXHQvKipcblx0ICogT3BlbiBjYXBhY2l0eSBjb250ZXh0IGZyb20gZWl0aGVyIHJvdyBhY3Rpb24gZW50cnkgcG9pbnQuXG5cdCAqXG5cdCAqIEBwYXJhbSB7T2JqZWN0fSAgICAgIGNvbmZpZyAgICAgICBDYXRhbG9nIGNvbmZpZ3VyYXRpb24uXG5cdCAqIEBwYXJhbSB7bnVtYmVyfSAgICAgIHJlc291cmNlX2lkICBSb290IG9yIGNoaWxkIFJlc291cmNlIElELlxuXHQgKiBAcGFyYW0ge0hUTUxFbGVtZW50fSBmb2N1c190YXJnZXQgSW5pdGlhdGluZyBjb250cm9sLlxuXHQgKiBAcmV0dXJuIHt2b2lkfVxuXHQgKi9cblx0ZnVuY3Rpb24gb3Blbl9jYXBhY2l0eV9lZGl0b3IoIGNvbmZpZywgcmVzb3VyY2VfaWQsIGZvY3VzX3RhcmdldCApIHtcblx0XHR2YXIgcmVxdWVzdF9zZXF1ZW5jZTtcblxuXHRcdGlmICggISByZXNvdXJjZV9pZCB8fCAhIGNhbl9kaXNjYXJkX2luc3BlY3RvciggY29uZmlnICkgfHwgISBtb3VudF9pbnNwZWN0b3Jfc2hlbGwoIGNvbmZpZyApICkge1xuXHRcdFx0cmV0dXJuO1xuXHRcdH1cblx0XHRjbG9zZV9kZXRhaWxzX3JvdyggZmFsc2UgKTtcblx0XHRpbnNwZWN0b3JfZm9jdXNfdGFyZ2V0ID0gZm9jdXNfdGFyZ2V0IHx8IGRvY3VtZW50LmFjdGl2ZUVsZW1lbnQ7XG5cdFx0cmVxdWVzdF9zZXF1ZW5jZSA9ICsraW5zcGVjdG9yX3JlcXVlc3Rfc2VxdWVuY2U7XG5cdFx0aW5zcGVjdG9yX21vZGUgPSAnY2FwYWNpdHknO1xuXHRcdGluc3BlY3Rvcl9yZXNvdXJjZV9pZCA9IHJlc291cmNlX2lkO1xuXHRcdGluc3BlY3Rvcl9kaXJ0eSA9IGZhbHNlO1xuXHRcdGluc3BlY3Rvcl90cmFja3Nfc2VsZWN0aW9uID0gZmFsc2U7XG5cdFx0c3luY2hyb25pemVfaW5zcGVjdG9yX3dpZHRoKCk7XG5cdFx0c2V0X2luc3BlY3Rvcl9zdGF0ZSggJ2xvYWRpbmcnLCAnJyApO1xuXHRcdG1hcmtfaW5zcGVjdG9yX3Jlc291cmNlX3JvdyggcmVzb3VyY2VfaWQgKTtcblx0XHRleHBhbmRfaW5zcGVjdG9yX3NpZGViYXIoKTtcblxuXHRcdHJlcXVlc3RfaW5zcGVjdG9yKCBjb25maWcsIGNvbmZpZy5jYXBhY2l0eV9jb250ZXh0X2FjdGlvbiwgeyByZXNvdXJjZV9pZDogcmVzb3VyY2VfaWQgfSApLnRoZW4oIGZ1bmN0aW9uICggcmVzcG9uc2UgKSB7XG5cdFx0XHRpZiAoIHJlcXVlc3Rfc2VxdWVuY2UgIT09IGluc3BlY3Rvcl9yZXF1ZXN0X3NlcXVlbmNlICkge1xuXHRcdFx0XHRyZXR1cm47XG5cdFx0XHR9XG5cdFx0XHRpZiAoICEgcmVzcG9uc2UgfHwgdHJ1ZSAhPT0gcmVzcG9uc2Uuc3VjY2VzcyB8fCAhIHJlc3BvbnNlLmRhdGEgfHwgISByZXNwb25zZS5kYXRhLmNvbnRleHQgfHwgISByZW5kZXJfY2FwYWNpdHlfZWRpdG9yKCBjb25maWcsIHJlc3BvbnNlLmRhdGEuY29udGV4dCApICkge1xuXHRcdFx0XHRzZXRfaW5zcGVjdG9yX3N0YXRlKCAnZXJyb3InLCBnZXRfaW5zcGVjdG9yX3Jlc3BvbnNlX21lc3NhZ2UoIHJlc3BvbnNlLCBjb25maWcuaTE4bi5jYXBhY2l0eV9sb2FkX2ZhaWxlZCApICk7XG5cdFx0XHR9XG5cdFx0fSApLmNhdGNoKCBmdW5jdGlvbiAoKSB7XG5cdFx0XHRpZiAoIHJlcXVlc3Rfc2VxdWVuY2UgPT09IGluc3BlY3Rvcl9yZXF1ZXN0X3NlcXVlbmNlICkge1xuXHRcdFx0XHRzZXRfaW5zcGVjdG9yX3N0YXRlKCAnZXJyb3InLCBjb25maWcuaTE4bi5jYXBhY2l0eV9sb2FkX2ZhaWxlZCB8fCAnJyApO1xuXHRcdFx0fVxuXHRcdH0gKTtcblx0fVxuXG5cdC8qKlxuXHQgKiBSZW5kZXIgYSBzaWduZWQgY2FwYWNpdHkgcmV2aWV3IHJldHVybmVkIGJ5IHRoZSBkb21haW4gc2VydmljZS5cblx0ICpcblx0ICogQHBhcmFtIHtPYmplY3R9IGNvbmZpZyAgQ2F0YWxvZyBjb25maWd1cmF0aW9uLlxuXHQgKiBAcGFyYW0ge09iamVjdH0gcHJldmlldyBTaWduZWQgcHJldmlldy5cblx0ICogQHJldHVybiB7Ym9vbGVhbn0gVHJ1ZSB3aGVuIHJlbmRlcmVkLlxuXHQgKi9cblx0ZnVuY3Rpb24gcmVuZGVyX2NhcGFjaXR5X3JldmlldyggY29uZmlnLCBwcmV2aWV3ICkge1xuXHRcdHZhciBob3N0ID0gZ2V0X2luc3BlY3Rvcl9ob3N0KCk7XG5cdFx0dmFyIHRhcmdldCA9IGhvc3QgPyBob3N0LnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLXVpLWNhdGFsb2ctaW5zcGVjdG9yLWZvcm1dJyApIDogbnVsbDtcblx0XHR2YXIgaW5jcmVhc2UgPSAnaW5jcmVhc2UnID09PSBwcmV2aWV3Lm9wZXJhdGlvbjtcblx0XHR2YXIgZGVsZXRlX2FjdGlvbiA9ICdkZWxldGUnID09PSBwcmV2aWV3LmRlY3JlYXNlX2FjdGlvbjtcblx0XHR2YXIgdmlldztcblxuXHRcdGlmICggISB0YXJnZXQgKSB7XG5cdFx0XHRyZXR1cm4gZmFsc2U7XG5cdFx0fVxuXHRcdHZpZXcgPSB7XG5cdFx0XHRjb250ZXh0X2xhYmVsOiAoIGNvbmZpZy5pMThuLnJlc291cmNlX2lkIHx8ICdJRCcgKSArICc6ICcgKyBTdHJpbmcoIHByZXZpZXcucmVzb3VyY2VfaWQgKSxcblx0XHRcdGN1cnJlbnRfY2FwYWNpdHk6IE51bWJlciggcHJldmlldy5jdXJyZW50X2NhcGFjaXR5ICksXG5cdFx0XHRkZWNyZWFzZV9hY3Rpb246IHByZXZpZXcuZGVjcmVhc2VfYWN0aW9uIHx8ICdkZXRhY2gnLFxuXHRcdFx0ZGVsZXRlX2hhc19ib29raW5nczogdHJ1ZSA9PT0gcHJldmlldy5kZWxldGVfaGFzX2Jvb2tpbmdzLFxuXHRcdFx0ZGVzY3JpcHRpb246IGNvbmZpZy5pMThuLnJldmlld19jYXBhY2l0eV9oZWxwIHx8ICcnLFxuXHRcdFx0bW9kZTogJ2NhcGFjaXR5X3JldmlldycsXG5cdFx0XHRvcGVyYXRpb246IHByZXZpZXcub3BlcmF0aW9uLFxuXHRcdFx0b3BlcmF0aW9uX2hlbHA6IGluY3JlYXNlID8gY29uZmlnLmkxOG4uY3JlYXRlX3VuaXRzX2hlbHAgfHwgJycgOiAoIGRlbGV0ZV9hY3Rpb24gPyBjb25maWcuaTE4bi5kZWxldGVfdW5pdHNfaGVscCB8fCAnJyA6IGNvbmZpZy5pMThuLnNlbGVjdF9kZXRhY2hfaGVscCB8fCAnJyApLFxuXHRcdFx0b3BlcmF0aW9uX2xhYmVsOiBpbmNyZWFzZVxuXHRcdFx0XHQ/IGdldF9jYXBhY2l0eV9jb3VudF9sYWJlbCggY29uZmlnLCAnY3JlYXRlX25ld191bml0JywgJ2NyZWF0ZV9uZXdfdW5pdHMnLCBOdW1iZXIoIHByZXZpZXcuY3JlYXRlX2NvdW50ICkgKVxuXHRcdFx0XHQ6IGdldF9jYXBhY2l0eV9jb3VudF9sYWJlbCggY29uZmlnLCAna2VlcF9leGlzdGluZ191bml0JywgJ2tlZXBfZXhpc3RpbmdfdW5pdHMnLCBOdW1iZXIoIHByZXZpZXcudGFyZ2V0X2NhcGFjaXR5ICkgKSxcblx0XHRcdHJlc291cmNlczogaW5jcmVhc2UgPyBwcmV2aWV3LmNyZWF0ZV9yZXNvdXJjZXMgfHwgW10gOiBwcmV2aWV3LmRldGFjaF9yZXNvdXJjZXMgfHwgW10sXG5cdFx0XHR0YXJnZXRfY2FwYWNpdHk6IE51bWJlciggcHJldmlldy50YXJnZXRfY2FwYWNpdHkgKSxcblx0XHRcdHRpdGxlOiBjb25maWcuaTE4bi5yZXZpZXdfY2FwYWNpdHlfdGl0bGUgfHwgJydcblx0XHR9O1xuXHRcdHRhcmdldC5pbm5lckhUTUwgPSByZW5kZXJfY29tcG9uZW50KCBjb25maWcsICdpbnNwZWN0b3JfY2FwYWNpdHknLCB7IGkxOG46IGNvbmZpZy5pMThuIHx8IHt9LCB2aWV3OiB2aWV3IH0gKTtcblx0XHRpZiAoICEgdGFyZ2V0LnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLWNhdGFsb2ctcmVzb3VyY2UtY2FwYWNpdHktZm9ybV0nICkgKSB7XG5cdFx0XHRyZXR1cm4gZmFsc2U7XG5cdFx0fVxuXHRcdGluc3BlY3Rvcl9tb2RlID0gJ2NhcGFjaXR5X3Jldmlldyc7XG5cdFx0aW5zcGVjdG9yX3Jlc291cmNlX2lkID0gTnVtYmVyKCBwcmV2aWV3LnJlc291cmNlX2lkICkgfHwgMDtcblx0XHRpbnNwZWN0b3JfcmV2aWV3X3Rva2VuID0gU3RyaW5nKCBwcmV2aWV3LnJldmlld190b2tlbiB8fCAnJyApO1xuXHRcdGluc3BlY3Rvcl9jYXBhY2l0eV9kZWNyZWFzZV9hY3Rpb24gPSBwcmV2aWV3LmRlY3JlYXNlX2FjdGlvbiB8fCAnZGV0YWNoJztcblx0XHRpbnNwZWN0b3JfZGlydHkgPSB0cnVlO1xuXHRcdHNldF9pbnNwZWN0b3Jfc3RhdGUoICdmb3JtJywgJycgKTtcblx0XHRjb25maWd1cmVfaW5zcGVjdG9yX2Zvb3RlciggJ3dwYmNfY2F0YWxvZ19ib29raW5nX3Jlc291cmNlX2NhcGFjaXR5X2Zvcm0nLCBjb25maWcuaTE4bi5hcHBseV9jYXBhY2l0eV9jaGFuZ2UgfHwgJycsIGRlbGV0ZV9hY3Rpb24sIGRlbGV0ZV9hY3Rpb24gKTtcblx0XHRpZiAoIGRlbGV0ZV9hY3Rpb24gKSB7XG5cdFx0XHR2YXIgYWNrbm93bGVkZ2VtZW50ID0gdGFyZ2V0LnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLWNhdGFsb2ctY2FwYWNpdHktZGVsZXRlLWFja25vd2xlZGdlbWVudF0nICk7XG5cdFx0XHRwdWxzZV9kZWxldGVfYWNrbm93bGVkZ2VtZW50KCBhY2tub3dsZWRnZW1lbnQgPyBhY2tub3dsZWRnZW1lbnQuY2xvc2VzdCggJy53cGJjX2Jvb2tpbmdfcmVzb3VyY2VzX19kZWxldGVfYWNrbm93bGVkZ2VtZW50JyApIDogbnVsbCApO1xuXHRcdH1cblx0XHR2YXIgY2FuY2VsX2J1dHRvbiA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLXVpLWNhdGFsb2ctaW5zcGVjdG9yLWNhbmNlbF0nICk7XG5cdFx0aWYgKCBjYW5jZWxfYnV0dG9uICkge1xuXHRcdFx0Y2FuY2VsX2J1dHRvbi50ZXh0Q29udGVudCA9IGNvbmZpZy5pMThuLmJhY2sgfHwgJyc7XG5cdFx0fVxuXHRcdGZvY3VzX2luc3BlY3Rvcl9oZWFkaW5nKCB0YXJnZXQucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtY2F0YWxvZy1yZXNvdXJjZS1jYXBhY2l0eS1mb3JtXScgKSApO1xuXG5cdFx0cmV0dXJuIHRydWU7XG5cdH1cblxuXHQvKipcblx0ICogU3luY2hyb25pemUgdGhlIFJlc291cmNlIGltYWdlIHByZXZpZXcgYWZ0ZXIgTWVkaWEgTGlicmFyeSBjaGFuZ2VzLlxuXHQgKlxuXHQgKiBAcGFyYW0ge0hUTUxFbGVtZW50fSBmaWVsZCBQaWN0dXJlIFVSTCBmaWVsZC5cblx0ICogQHJldHVybiB7dm9pZH1cblx0ICovXG5cdGZ1bmN0aW9uIHN5bmNocm9uaXplX2luc3BlY3Rvcl9pbWFnZSggZmllbGQgKSB7XG5cdFx0dmFyIGZpZWxkX3dyYXAgPSBmaWVsZCA/IGZpZWxkLmNsb3Nlc3QoICdbZGF0YS13cGJjLWNhdGFsb2ctcmVzb3VyY2UtZmllbGQtd3JhcF0nICkgOiBudWxsO1xuXHRcdHZhciBwcmV2aWV3ID0gZmllbGRfd3JhcCA/IGZpZWxkX3dyYXAucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtY2F0YWxvZy1yZXNvdXJjZS1pbWFnZS1wcmV2aWV3XScgKSA6IG51bGw7XG5cdFx0dmFyIHBsYWNlaG9sZGVyID0gZmllbGRfd3JhcCA/IGZpZWxkX3dyYXAucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtY2F0YWxvZy1yZXNvdXJjZS1pbWFnZS1wbGFjZWhvbGRlcl0nICkgOiBudWxsO1xuXHRcdHZhciByZW1vdmVfYnV0dG9uID0gZmllbGRfd3JhcCA/IGZpZWxkX3dyYXAucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtY2F0YWxvZy1yZXNvdXJjZS1yZW1vdmUtaW1hZ2VdJyApIDogbnVsbDtcblx0XHR2YXIgcGljdHVyZV91cmwgPSBmaWVsZCA/IFN0cmluZyggZmllbGQudmFsdWUgfHwgJycgKS50cmltKCkgOiAnJztcblxuXHRcdGlmICggcHJldmlldyApIHtcblx0XHRcdHByZXZpZXcuc3JjID0gcGljdHVyZV91cmw7XG5cdFx0XHRwcmV2aWV3LmhpZGRlbiA9ICEgcGljdHVyZV91cmw7XG5cdFx0fVxuXHRcdGlmICggcGxhY2Vob2xkZXIgKSB7XG5cdFx0XHRwbGFjZWhvbGRlci5oaWRkZW4gPSAhISBwaWN0dXJlX3VybDtcblx0XHR9XG5cdFx0aWYgKCByZW1vdmVfYnV0dG9uICkge1xuXHRcdFx0cmVtb3ZlX2J1dHRvbi5kaXNhYmxlZCA9ICEgcGljdHVyZV91cmw7XG5cdFx0fVxuXHR9XG5cblx0LyoqXG5cdCAqIFN5bmNocm9uaXplIG9uZSBudW1lcmljIHNsaWRlciB3aXRoIGl0cyBwcmVjaXNlIG51bWJlciBmaWVsZC5cblx0ICpcblx0ICogU3VnZ2VzdGVkIHNsaWRlciBib3VuZHMgcmVtYWluIGNvbnZlbmllbnQgZm9yIG9yZGluYXJ5IHZhbHVlcy4gUHJpY2Uga2VlcHNcblx0ICogaXRzIHByb2R1Y3QtZGVmaW5lZCAwLTEwMDAgc2xpZGVyIHdoaWxlIHRoZSBhdXRob3JpdGF0aXZlIG51bWJlciBmaWVsZCBjYW5cblx0ICogc3RpbGwgcHJlc2VydmUgYSBsZWdhY3kgcHJpY2UgYWJvdmUgMTAwMC4gT3RoZXIgbnVtZXJpYyBjb250cm9scyBjYW4gZXhwYW5kXG5cdCAqIHRvIHJlcHJlc2VudCBzdG9yZWQgdmFsdWVzIG91dHNpZGUgdGhlaXIgc3VnZ2VzdGVkIHJhbmdlLlxuXHQgKlxuXHQgKiBAcGFyYW0ge3N0cmluZ30gZmllbGRfa2V5IE51bWVyaWMgaW5zcGVjdG9yIGZpZWxkIGtleS5cblx0ICogQHJldHVybiB7dm9pZH1cblx0ICovXG5cdGZ1bmN0aW9uIHN5bmNocm9uaXplX2luc3BlY3Rvcl9udW1lcmljX3JhbmdlKCBmaWVsZF9rZXkgKSB7XG5cdFx0dmFyIG51bWJlcl9maWVsZCA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLWNhdGFsb2ctcmVzb3VyY2UtZmllbGQ9XCInICsgZmllbGRfa2V5ICsgJ1wiXVt0eXBlPVwibnVtYmVyXCJdJyApO1xuXHRcdHZhciByYW5nZSA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLWNhdGFsb2ctcmVzb3VyY2UtcmFuZ2U9XCInICsgZmllbGRfa2V5ICsgJ1wiXScgKTtcblx0XHR2YXIgbnVtYmVyX3ZhbHVlO1xuXHRcdHZhciBkZWZhdWx0X21pbjtcblx0XHR2YXIgZGVmYXVsdF9tYXg7XG5cdFx0dmFyIGhhcmRfbWluO1xuXHRcdHZhciBoYXJkX21heDtcblx0XHR2YXIgcmFuZ2VfbWluO1xuXHRcdHZhciByYW5nZV9tYXg7XG5cblx0XHRpZiAoICEgbnVtYmVyX2ZpZWxkIHx8ICEgcmFuZ2UgKSB7XG5cdFx0XHRyZXR1cm47XG5cdFx0fVxuXG5cdFx0bnVtYmVyX3ZhbHVlID0gTnVtYmVyKCBudW1iZXJfZmllbGQudmFsdWUgKTtcblx0XHRpZiAoICEgaXNGaW5pdGUoIG51bWJlcl92YWx1ZSApICkge1xuXHRcdFx0cmV0dXJuO1xuXHRcdH1cblxuXHRcdGRlZmF1bHRfbWluID0gTnVtYmVyKCByYW5nZS5nZXRBdHRyaWJ1dGUoICdkYXRhLXdwYmMtY2F0YWxvZy1yZXNvdXJjZS1yYW5nZS1kZWZhdWx0LW1pbicgKSApO1xuXHRcdGRlZmF1bHRfbWF4ID0gTnVtYmVyKCByYW5nZS5nZXRBdHRyaWJ1dGUoICdkYXRhLXdwYmMtY2F0YWxvZy1yZXNvdXJjZS1yYW5nZS1kZWZhdWx0LW1heCcgKSApO1xuXHRcdGhhcmRfbWluID0gJycgPT09IFN0cmluZyggbnVtYmVyX2ZpZWxkLmdldEF0dHJpYnV0ZSggJ21pbicgKSB8fCAnJyApID8gbnVsbCA6IE51bWJlciggbnVtYmVyX2ZpZWxkLmdldEF0dHJpYnV0ZSggJ21pbicgKSApO1xuXHRcdGhhcmRfbWF4ID0gJycgPT09IFN0cmluZyggbnVtYmVyX2ZpZWxkLmdldEF0dHJpYnV0ZSggJ21heCcgKSB8fCAnJyApID8gbnVsbCA6IE51bWJlciggbnVtYmVyX2ZpZWxkLmdldEF0dHJpYnV0ZSggJ21heCcgKSApO1xuXHRcdGlmICggJ2Jhc2VfY29zdCcgPT09IGZpZWxkX2tleSApIHtcblx0XHRcdHJhbmdlX21pbiA9IGlzRmluaXRlKCBkZWZhdWx0X21pbiApID8gZGVmYXVsdF9taW4gOiAwO1xuXHRcdFx0cmFuZ2VfbWF4ID0gaXNGaW5pdGUoIGRlZmF1bHRfbWF4ICkgPyBkZWZhdWx0X21heCA6IDEwMDA7XG5cdFx0fSBlbHNlIHtcblx0XHRcdHJhbmdlX21pbiA9IG51bGwgIT09IGhhcmRfbWluICYmIGlzRmluaXRlKCBoYXJkX21pbiApXG5cdFx0XHRcdD8gaGFyZF9taW5cblx0XHRcdFx0OiBNYXRoLm1pbiggaXNGaW5pdGUoIGRlZmF1bHRfbWluICkgPyBkZWZhdWx0X21pbiA6IG51bWJlcl92YWx1ZSwgbnVtYmVyX3ZhbHVlICk7XG5cdFx0XHRyYW5nZV9tYXggPSBudWxsICE9PSBoYXJkX21heCAmJiBpc0Zpbml0ZSggaGFyZF9tYXggKVxuXHRcdFx0XHQ/IGhhcmRfbWF4XG5cdFx0XHRcdDogTWF0aC5tYXgoIGlzRmluaXRlKCBkZWZhdWx0X21heCApID8gZGVmYXVsdF9tYXggOiBudW1iZXJfdmFsdWUsIG51bWJlcl92YWx1ZSApO1xuXHRcdH1cblxuXHRcdHJhbmdlLm1pbiA9IFN0cmluZyggcmFuZ2VfbWluICk7XG5cdFx0cmFuZ2UubWF4ID0gU3RyaW5nKCByYW5nZV9tYXggKTtcblx0XHRyYW5nZS52YWx1ZSA9IFN0cmluZyggbnVtYmVyX3ZhbHVlICk7XG5cdH1cblxuXHQvKipcblx0ICogU3luY2hyb25pemUgZXZlcnkgcmVuZGVyZWQgaW5zcGVjdG9yIG51bWVyaWMgc2xpZGVyLlxuXHQgKlxuXHQgKiBAcmV0dXJuIHt2b2lkfVxuXHQgKi9cblx0ZnVuY3Rpb24gc3luY2hyb25pemVfYWxsX2luc3BlY3Rvcl9udW1lcmljX3JhbmdlcygpIHtcblx0XHRkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKCAnW2RhdGEtd3BiYy1jYXRhbG9nLXJlc291cmNlLXJhbmdlXScgKS5mb3JFYWNoKCBmdW5jdGlvbiAoIHJhbmdlICkge1xuXHRcdFx0c3luY2hyb25pemVfaW5zcGVjdG9yX251bWVyaWNfcmFuZ2UoIHJhbmdlLmdldEF0dHJpYnV0ZSggJ2RhdGEtd3BiYy1jYXRhbG9nLXJlc291cmNlLXJhbmdlJyApIHx8ICcnICk7XG5cdFx0fSApO1xuXHR9XG5cblx0LyoqXG5cdCAqIENvcHkgYSBzbGlkZXIgdmFsdWUgaW50byBpdHMgYXV0aG9yaXRhdGl2ZSBudW1iZXIgZmllbGQuXG5cdCAqXG5cdCAqIFRoZSBudW1iZXIgZmllbGQgcmVtYWlucyB0aGUgb25seSBzZXJpYWxpemVkIGNvbnRyb2wgYW5kIGVtaXRzIG9uZSBuYXRpdmVcblx0ICogaW5wdXQgZXZlbnQgc28gdmFsaWRhdGlvbiBhbmQgZGlydHktc3RhdGUgYmVoYXZpb3Igc3RheSBjZW50cmFsaXplZC5cblx0ICpcblx0ICogQHBhcmFtIHtIVE1MRWxlbWVudH0gcmFuZ2UgTnVtZXJpYyByYW5nZSBjb250cm9sLlxuXHQgKiBAcmV0dXJuIHt2b2lkfVxuXHQgKi9cblx0ZnVuY3Rpb24gc3luY2hyb25pemVfaW5zcGVjdG9yX251bWJlcl9mcm9tX3JhbmdlKCByYW5nZSApIHtcblx0XHR2YXIgZmllbGRfa2V5ID0gcmFuZ2UgPyByYW5nZS5nZXRBdHRyaWJ1dGUoICdkYXRhLXdwYmMtY2F0YWxvZy1yZXNvdXJjZS1yYW5nZScgKSB8fCAnJyA6ICcnO1xuXHRcdHZhciBudW1iZXJfZmllbGQgPSBmaWVsZF9rZXkgPyBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCAnW2RhdGEtd3BiYy1jYXRhbG9nLXJlc291cmNlLWZpZWxkPVwiJyArIGZpZWxkX2tleSArICdcIl1bdHlwZT1cIm51bWJlclwiXScgKSA6IG51bGw7XG5cblx0XHRpZiAoICEgbnVtYmVyX2ZpZWxkICkge1xuXHRcdFx0cmV0dXJuO1xuXHRcdH1cblx0XHRudW1iZXJfZmllbGQudmFsdWUgPSByYW5nZS52YWx1ZTtcblx0XHRudW1iZXJfZmllbGQuZGlzcGF0Y2hFdmVudCggbmV3IEV2ZW50KCAnaW5wdXQnLCB7IGJ1YmJsZXM6IHRydWUgfSApICk7XG5cdH1cblxuXHQvKipcblx0ICogQ29uZmlybSB0aGF0IGEgZm9ybSBzdWJtaXQgb3JpZ2luYXRlZCBmcm9tIHRoZSBpbnNwZWN0b3Igc2F2ZSBhY3Rpb24uXG5cdCAqXG5cdCAqIFdvcmRQcmVzcyBNZWRpYSBMaWJyYXJ5IGNvbnRyb2xzIG1heSB0ZW1wb3JhcmlseSBjb2V4aXN0IHdpdGggdGhlIHNpZGViYXJcblx0ICogZm9ybS4gUmVqZWN0aW5nIHRoZWlyIHN1Ym1pdHRlcnMgcHJldmVudHMgYW4gaW1hZ2UgaW5zZXJ0aW9uIGZyb20gc3RhcnRpbmdcblx0ICogYSBSZXNvdXJjZSBtdXRhdGlvbi4gVGhlIGFjdGl2ZSBpbnNwZWN0b3IgZmllbGQgZmFsbGJhY2sgcHJlc2VydmVzIG5hdGl2ZVxuXHQgKiBFbnRlci1rZXkgc3VibWlzc2lvbiBpbiBicm93c2VycyB3aXRob3V0IFN1Ym1pdEV2ZW50LnN1Ym1pdHRlciBzdXBwb3J0LlxuXHQgKlxuXHQgKiBAcGFyYW0ge1N1Ym1pdEV2ZW50fSBldmVudCBGb3JtIHN1Ym1pc3Npb24uXG5cdCAqIEBwYXJhbSB7SFRNTEVsZW1lbnR9IGZvcm0gIEFjdGl2ZSBpbnNwZWN0b3IgZm9ybS5cblx0ICogQHJldHVybiB7Ym9vbGVhbn0gVHJ1ZSBvbmx5IGZvciBhbiBpbnRlbnRpb25hbCBpbnNwZWN0b3Igc3VibWlzc2lvbi5cblx0ICovXG5cdGZ1bmN0aW9uIGlzX2V4cGVjdGVkX2luc3BlY3Rvcl9zdWJtaXQoIGV2ZW50LCBmb3JtICkge1xuXHRcdHZhciBzdWJtaXR0ZXIgPSBldmVudC5zdWJtaXR0ZXIgfHwgZG9jdW1lbnQuYWN0aXZlRWxlbWVudDtcblxuXHRcdGlmICggc3VibWl0dGVyICYmIHN1Ym1pdHRlci5tYXRjaGVzICYmIHN1Ym1pdHRlci5tYXRjaGVzKCAnW2RhdGEtd3BiYy11aS1jYXRhbG9nLWluc3BlY3Rvci1zYXZlXScgKSApIHtcblx0XHRcdHJldHVybiB0cnVlO1xuXHRcdH1cblxuXHRcdHJldHVybiAhIGV2ZW50LnN1Ym1pdHRlclxuXHRcdFx0JiYgc3VibWl0dGVyXG5cdFx0XHQmJiBmb3JtLmNvbnRhaW5zKCBzdWJtaXR0ZXIgKVxuXHRcdFx0JiYgc3VibWl0dGVyLm1hdGNoZXNcblx0XHRcdCYmIHN1Ym1pdHRlci5tYXRjaGVzKCAnaW5wdXQ6bm90KFt0eXBlPVwiYnV0dG9uXCJdKTpub3QoW3R5cGU9XCJzdWJtaXRcIl0pLCBzZWxlY3QnICk7XG5cdH1cblxuXHQvKipcblx0ICogUmVwb3J0IGluc3BlY3RvciB2YWxpZGl0eSB3aXRob3V0IHJlamVjdGluZyBhbiBleGlzdGluZyBkZWNpbWFsIHByaWNlLlxuXHQgKlxuXHQgKiBQcmljZSBjb250cm9scyBpbnRlbnRpb25hbGx5IHVzZSBhIG9uZS11bml0IHNwaW5uZXIgc3RlcC4gRXhpc3RpbmcgcHJpY2VzXG5cdCAqIG1heSBzdGlsbCBjb250YWluIGRlY2ltYWxzLCBzbyB0aGVpciBzdGVwIGNvbnN0cmFpbnQgaXMgcmVsYXhlZCBvbmx5IHdoaWxlXG5cdCAqIG5hdGl2ZSBmb3JtIHZhbGlkaXR5IGlzIGV2YWx1YXRlZC4gU2VydmVyLXNpZGUgcHJpY2UgdmFsaWRhdGlvbiByZW1haW5zIHRoZVxuXHQgKiBhdXRob3JpdGF0aXZlIGJvdW5kYXJ5LlxuXHQgKlxuXHQgKiBAcGFyYW0ge0hUTUxGb3JtRWxlbWVudH0gZm9ybSBJbnNwZWN0b3IgZm9ybS5cblx0ICogQHJldHVybiB7Ym9vbGVhbn0gVHJ1ZSB3aGVuIHRoZSBmb3JtIHBhc3NlcyBuYXRpdmUgdmFsaWRpdHkgY2hlY2tzLlxuXHQgKi9cblx0ZnVuY3Rpb24gcmVwb3J0X2luc3BlY3Rvcl92YWxpZGl0eSggZm9ybSApIHtcblx0XHR2YXIgcHJpY2VfZmllbGRzID0gZm9ybS5xdWVyeVNlbGVjdG9yQWxsKCAnW2RhdGEtd3BiYy1jYXRhbG9nLXJlc291cmNlLWZpZWxkPVwiYmFzZV9jb3N0XCJdLCBbZGF0YS13cGJjLWNhdGFsb2ctcmVzb3VyY2UtYnVsay12YWx1ZT1cImJhc2VfY29zdFwiXScgKTtcblx0XHR2YXIgcHJpY2Vfc3RlcHMgPSBbXTtcblx0XHR2YXIgaXNfdmFsaWQ7XG5cblx0XHRwcmljZV9maWVsZHMuZm9yRWFjaCggZnVuY3Rpb24gKCBwcmljZV9maWVsZCApIHtcblx0XHRcdHByaWNlX3N0ZXBzLnB1c2goIHtcblx0XHRcdFx0ZmllbGQ6IHByaWNlX2ZpZWxkLFxuXHRcdFx0XHRzdGVwOiBwcmljZV9maWVsZC5nZXRBdHRyaWJ1dGUoICdzdGVwJyApXG5cdFx0XHR9ICk7XG5cdFx0XHRwcmljZV9maWVsZC5zZXRBdHRyaWJ1dGUoICdzdGVwJywgJ2FueScgKTtcblx0XHR9ICk7XG5cblx0XHRpc192YWxpZCA9IGZvcm0ucmVwb3J0VmFsaWRpdHkoKTtcblx0XHRwcmljZV9zdGVwcy5mb3JFYWNoKCBmdW5jdGlvbiAoIHByaWNlX3N0ZXAgKSB7XG5cdFx0XHRpZiAoIG51bGwgPT09IHByaWNlX3N0ZXAuc3RlcCApIHtcblx0XHRcdFx0cHJpY2Vfc3RlcC5maWVsZC5yZW1vdmVBdHRyaWJ1dGUoICdzdGVwJyApO1xuXHRcdFx0fSBlbHNlIHtcblx0XHRcdFx0cHJpY2Vfc3RlcC5maWVsZC5zZXRBdHRyaWJ1dGUoICdzdGVwJywgcHJpY2Vfc3RlcC5zdGVwICk7XG5cdFx0XHR9XG5cdFx0fSApO1xuXG5cdFx0cmV0dXJuIGlzX3ZhbGlkO1xuXHR9XG5cblx0LyoqXG5cdCAqIFNhdmUgdGhlIGFjdGl2ZSBpbnNwZWN0b3IgdGhyb3VnaCBpdHMgaW5kZXBlbmRlbnQgbXV0YXRpb24gZW5kcG9pbnQuXG5cdCAqXG5cdCAqIEBwYXJhbSB7U3VibWl0RXZlbnR9IGV2ZW50IEZvcm0gc3VibWlzc2lvbi5cblx0ICogQHBhcmFtIHtPYmplY3R9ICAgICAgY29uZmlnIENhdGFsb2cgY29uZmlndXJhdGlvbi5cblx0ICogQHJldHVybiB7dm9pZH1cblx0ICovXG5cdGZ1bmN0aW9uIHN1Ym1pdF9pbnNwZWN0b3IoIGV2ZW50LCBjb25maWcgKSB7XG5cdFx0dmFyIGZvcm0gPSBldmVudC50YXJnZXQ7XG5cdFx0dmFyIHNhdmVfYnV0dG9uID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtdWktY2F0YWxvZy1pbnNwZWN0b3Itc2F2ZV0nICk7XG5cdFx0dmFyIGNhbmNlbF9idXR0b24gPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCAnW2RhdGEtd3BiYy11aS1jYXRhbG9nLWluc3BlY3Rvci1jYW5jZWxdJyApO1xuXHRcdHZhciBtdXRhdGlvbl9yZXF1ZXN0X3NlcXVlbmNlO1xuXHRcdHZhciBmaWVsZHM7XG5cdFx0dmFyIGFjdGlvbjtcblx0XHR2YXIgc3VibWl0dGVkX21vZGU7XG5cdFx0dmFyIHJlcXVlc3RfdmFsdWVzO1xuXHRcdHZhciBjb250cm9sX2Rpc2FibGVkX3N0YXRlcyA9IFtdO1xuXHRcdHZhciBzdWNjZXNzX21lc3NhZ2U7XG5cdFx0dmFyIHN1Y2Nlc3NfbWVzc2FnZV9pc19nbG9iYWw7XG5cdFx0dmFyIHN1Ym1pdHRlZF9mb3JtX2lzX2FjdGl2ZTtcblxuXHRcdGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XG5cdFx0aWYgKCAhIGlzX2V4cGVjdGVkX2luc3BlY3Rvcl9zdWJtaXQoIGV2ZW50LCBmb3JtICkgfHwgKCBzYXZlX2J1dHRvbiAmJiBzYXZlX2J1dHRvbi5jbGFzc0xpc3QuY29udGFpbnMoICdpcy1idXN5JyApICkgfHwgISByZXBvcnRfaW5zcGVjdG9yX3ZhbGlkaXR5KCBmb3JtICkgKSB7XG5cdFx0XHRyZXR1cm47XG5cdFx0fVxuXHRcdG11dGF0aW9uX3JlcXVlc3Rfc2VxdWVuY2UgPSArK2luc3BlY3Rvcl9tdXRhdGlvbl9yZXF1ZXN0X3NlcXVlbmNlO1xuXHRcdGluc3BlY3Rvcl9tdXRhdGlvbl9pbl9wcm9ncmVzcyA9IHRydWU7XG5cdFx0ZmllbGRzID0gSlNPTi5wYXJzZSggc2VyaWFsaXplX2luc3BlY3Rvcl9maWVsZHMoKSB8fCAne30nICk7XG5cdFx0YWN0aW9uID0gJ2NyZWF0ZScgPT09IGluc3BlY3Rvcl9tb2RlID8gY29uZmlnLmluc3BlY3Rvcl9jcmVhdGVfYWN0aW9uIDogY29uZmlnLmluc3BlY3Rvcl91cGRhdGVfYWN0aW9uO1xuXHRcdHN1Ym1pdHRlZF9tb2RlID0gaW5zcGVjdG9yX21vZGU7XG5cdFx0cmVxdWVzdF92YWx1ZXMgPSB7IGZpZWxkczogSlNPTi5zdHJpbmdpZnkoIGZpZWxkcyApIH07XG5cdFx0aWYgKCAnZWRpdCcgPT09IGluc3BlY3Rvcl9tb2RlICkge1xuXHRcdFx0cmVxdWVzdF92YWx1ZXMucmVzb3VyY2VfaWQgPSBpbnNwZWN0b3JfcmVzb3VyY2VfaWQ7XG5cdFx0fVxuXHRcdGlmICggc2F2ZV9idXR0b24gKSB7XG5cdFx0XHRzYXZlX2J1dHRvbi5kaXNhYmxlZCA9IHRydWU7XG5cdFx0XHRzYXZlX2J1dHRvbi5jbGFzc0xpc3QuYWRkKCAnaXMtYnVzeScgKTtcblx0XHR9XG5cdFx0aWYgKCBjYW5jZWxfYnV0dG9uICkge1xuXHRcdFx0Y2FuY2VsX2J1dHRvbi5kaXNhYmxlZCA9IHRydWU7XG5cdFx0fVxuXHRcdGZvcm0uY2xhc3NMaXN0LmFkZCggJ2lzLXNhdmluZycgKTtcblx0XHRmb3JtLnNldEF0dHJpYnV0ZSggJ2FyaWEtYnVzeScsICd0cnVlJyApO1xuXHRcdGZvcm0ucXVlcnlTZWxlY3RvckFsbCggJ2lucHV0LCBzZWxlY3QsIHRleHRhcmVhLCBidXR0b24nICkuZm9yRWFjaCggZnVuY3Rpb24gKCBjb250cm9sICkge1xuXHRcdFx0Y29udHJvbF9kaXNhYmxlZF9zdGF0ZXMucHVzaCggeyBjb250cm9sOiBjb250cm9sLCBkaXNhYmxlZDogY29udHJvbC5kaXNhYmxlZCB9ICk7XG5cdFx0XHRjb250cm9sLmRpc2FibGVkID0gdHJ1ZTtcblx0XHR9ICk7XG5cblx0XHRyZXF1ZXN0X2luc3BlY3RvciggY29uZmlnLCBhY3Rpb24sIHJlcXVlc3RfdmFsdWVzICkudGhlbiggZnVuY3Rpb24gKCByZXNwb25zZSApIHtcblx0XHRcdGlmICggbXV0YXRpb25fcmVxdWVzdF9zZXF1ZW5jZSAhPT0gaW5zcGVjdG9yX211dGF0aW9uX3JlcXVlc3Rfc2VxdWVuY2UgKSB7XG5cdFx0XHRcdHJldHVybjtcblx0XHRcdH1cblx0XHRcdGlmICggISByZXNwb25zZSB8fCB0cnVlICE9PSByZXNwb25zZS5zdWNjZXNzIHx8ICEgcmVzcG9uc2UuZGF0YSApIHtcblx0XHRcdFx0dGhyb3cgbmV3IEVycm9yKCBnZXRfaW5zcGVjdG9yX3Jlc3BvbnNlX21lc3NhZ2UoIHJlc3BvbnNlLCBjb25maWcuaTE4bi5pbnNwZWN0b3Jfc2F2ZV9mYWlsZWQgKSApO1xuXHRcdFx0fVxuXHRcdFx0cGVuZGluZ19oaWdobGlnaHRfaWRzID0gQXJyYXkuaXNBcnJheSggcmVzcG9uc2UuZGF0YS5yZXNvdXJjZV9pZHMgKSA/IHJlc3BvbnNlLmRhdGEucmVzb3VyY2VfaWRzLm1hcCggU3RyaW5nICkgOiBbXTtcblx0XHRcdGluc3BlY3Rvcl9kaXJ0eSA9IGZhbHNlO1xuXHRcdFx0c3VjY2Vzc19tZXNzYWdlID0gZ2V0X2luc3BlY3Rvcl9yZXNwb25zZV9tZXNzYWdlKCByZXNwb25zZSwgJycgKTtcblx0XHRcdHN1Y2Nlc3NfbWVzc2FnZV9pc19nbG9iYWwgPSBzaG93X2FkbWluX21lc3NhZ2UoIHN1Y2Nlc3NfbWVzc2FnZSwgJ3N1Y2Nlc3MnLCAzMDAwICk7XG5cdFx0XHRzdWJtaXR0ZWRfZm9ybV9pc19hY3RpdmUgPSBkb2N1bWVudC5kb2N1bWVudEVsZW1lbnQuY29udGFpbnMoIGZvcm0gKTtcblx0XHRcdGlmICggJ2NyZWF0ZScgPT09IHN1Ym1pdHRlZF9tb2RlICkge1xuXHRcdFx0XHRpbnNwZWN0b3JfbXV0YXRpb25faW5fcHJvZ3Jlc3MgPSBmYWxzZTtcblx0XHRcdFx0aWYgKCBzdWJtaXR0ZWRfZm9ybV9pc19hY3RpdmUgKSB7XG5cdFx0XHRcdFx0Y2xvc2VfaW5zcGVjdG9yKCBjb25maWcsIGZhbHNlICk7XG5cdFx0XHRcdH1cblx0XHRcdFx0aWYgKCBjYXRhbG9nX2NvbnRyb2xsZXIgKSB7XG5cdFx0XHRcdFx0Y2F0YWxvZ19jb250cm9sbGVyLmxvYWQoIHsgcGFnZV9udW1iZXI6IDEgfSApO1xuXHRcdFx0XHR9XG5cdFx0XHRcdHJldHVybjtcblx0XHRcdH1cblx0XHRcdGlmICggISBzdWJtaXR0ZWRfZm9ybV9pc19hY3RpdmUgKSB7XG5cdFx0XHRcdGlmICggY2F0YWxvZ19jb250cm9sbGVyICkge1xuXHRcdFx0XHRcdGNhdGFsb2dfY29udHJvbGxlci5sb2FkKCk7XG5cdFx0XHRcdH1cblx0XHRcdFx0aW5zcGVjdG9yX211dGF0aW9uX2luX3Byb2dyZXNzID0gZmFsc2U7XG5cdFx0XHRcdHJldHVybjtcblx0XHRcdH1cblx0XHRcdGlmICggcmVzcG9uc2UuZGF0YS5zY2hlbWEgJiYgcmVuZGVyX2luc3BlY3Rvcl9zY2hlbWEoIGNvbmZpZywgcmVzcG9uc2UuZGF0YS5zY2hlbWEsIGZhbHNlICkgKSB7XG5cdFx0XHRcdGZvcm0gPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCAnW2RhdGEtd3BiYy1jYXRhbG9nLXJlc291cmNlLWluc3BlY3Rvci1mb3JtXScgKTtcblx0XHRcdFx0c2hvd19pbnNwZWN0b3JfbWVzc2FnZSggZm9ybSwgc3VjY2Vzc19tZXNzYWdlX2lzX2dsb2JhbCA/ICcnIDogc3VjY2Vzc19tZXNzYWdlLCBmYWxzZSApO1xuXHRcdFx0fSBlbHNlIHtcblx0XHRcdFx0Zm9ybS5jbGFzc0xpc3QucmVtb3ZlKCAnaXMtc2F2aW5nJyApO1xuXHRcdFx0XHRmb3JtLnJlbW92ZUF0dHJpYnV0ZSggJ2FyaWEtYnVzeScgKTtcblx0XHRcdFx0Y29udHJvbF9kaXNhYmxlZF9zdGF0ZXMuZm9yRWFjaCggZnVuY3Rpb24gKCBjb250cm9sX3N0YXRlICkge1xuXHRcdFx0XHRcdGlmICggZG9jdW1lbnQuZG9jdW1lbnRFbGVtZW50LmNvbnRhaW5zKCBjb250cm9sX3N0YXRlLmNvbnRyb2wgKSApIHtcblx0XHRcdFx0XHRcdGNvbnRyb2xfc3RhdGUuY29udHJvbC5kaXNhYmxlZCA9IGNvbnRyb2xfc3RhdGUuZGlzYWJsZWQ7XG5cdFx0XHRcdFx0fVxuXHRcdFx0XHR9ICk7XG5cdFx0XHRcdGlmICggc2F2ZV9idXR0b24gKSB7XG5cdFx0XHRcdFx0c2F2ZV9idXR0b24uY2xhc3NMaXN0LnJlbW92ZSggJ2lzLWJ1c3knICk7XG5cdFx0XHRcdH1cblx0XHRcdFx0aWYgKCBjYW5jZWxfYnV0dG9uICkge1xuXHRcdFx0XHRcdGNhbmNlbF9idXR0b24uZGlzYWJsZWQgPSBmYWxzZTtcblx0XHRcdFx0fVxuXHRcdFx0XHRpbnNwZWN0b3Jfb3JpZ2luYWxfZmllbGRzID0gc2VyaWFsaXplX2luc3BlY3Rvcl9maWVsZHMoKTtcblx0XHRcdFx0c3luY2hyb25pemVfaW5zcGVjdG9yX2RpcnR5X3N0YXRlKCk7XG5cdFx0XHRcdHNob3dfaW5zcGVjdG9yX21lc3NhZ2UoIGZvcm0sIHN1Y2Nlc3NfbWVzc2FnZV9pc19nbG9iYWwgPyAnJyA6IHN1Y2Nlc3NfbWVzc2FnZSwgZmFsc2UgKTtcblx0XHRcdH1cblx0XHRcdGlmICggY2F0YWxvZ19jb250cm9sbGVyICkge1xuXHRcdFx0XHRjYXRhbG9nX2NvbnRyb2xsZXIubG9hZCgpO1xuXHRcdFx0fVxuXHRcdFx0aW5zcGVjdG9yX211dGF0aW9uX2luX3Byb2dyZXNzID0gZmFsc2U7XG5cdFx0fSApLmNhdGNoKCBmdW5jdGlvbiAoIGVycm9yICkge1xuXHRcdFx0aWYgKCBtdXRhdGlvbl9yZXF1ZXN0X3NlcXVlbmNlICE9PSBpbnNwZWN0b3JfbXV0YXRpb25fcmVxdWVzdF9zZXF1ZW5jZSApIHtcblx0XHRcdFx0cmV0dXJuO1xuXHRcdFx0fVxuXHRcdFx0aW5zcGVjdG9yX211dGF0aW9uX2luX3Byb2dyZXNzID0gZmFsc2U7XG5cdFx0XHR2YXIgbWVzc2FnZSA9IGVycm9yICYmIGVycm9yLm1lc3NhZ2UgPyBlcnJvci5tZXNzYWdlIDogY29uZmlnLmkxOG4uaW5zcGVjdG9yX3NhdmVfZmFpbGVkIHx8ICcnO1xuXHRcdFx0aWYgKCAhIGRvY3VtZW50LmRvY3VtZW50RWxlbWVudC5jb250YWlucyggZm9ybSApICkge1xuXHRcdFx0XHRzaG93X2FkbWluX21lc3NhZ2UoIG1lc3NhZ2UsICdlcnJvcicsIDUwMDAgKTtcblx0XHRcdFx0cmV0dXJuO1xuXHRcdFx0fVxuXHRcdFx0Zm9ybS5jbGFzc0xpc3QucmVtb3ZlKCAnaXMtc2F2aW5nJyApO1xuXHRcdFx0Zm9ybS5yZW1vdmVBdHRyaWJ1dGUoICdhcmlhLWJ1c3knICk7XG5cdFx0XHRzaG93X2luc3BlY3Rvcl9tZXNzYWdlKCBmb3JtLCBtZXNzYWdlLCB0cnVlICk7XG5cdFx0XHRjb250cm9sX2Rpc2FibGVkX3N0YXRlcy5mb3JFYWNoKCBmdW5jdGlvbiAoIGNvbnRyb2xfc3RhdGUgKSB7XG5cdFx0XHRcdGlmICggZG9jdW1lbnQuZG9jdW1lbnRFbGVtZW50LmNvbnRhaW5zKCBjb250cm9sX3N0YXRlLmNvbnRyb2wgKSApIHtcblx0XHRcdFx0XHRjb250cm9sX3N0YXRlLmNvbnRyb2wuZGlzYWJsZWQgPSBjb250cm9sX3N0YXRlLmRpc2FibGVkO1xuXHRcdFx0XHR9XG5cdFx0XHR9ICk7XG5cdFx0XHRpZiAoIHNhdmVfYnV0dG9uICkge1xuXHRcdFx0XHRzYXZlX2J1dHRvbi5jbGFzc0xpc3QucmVtb3ZlKCAnaXMtYnVzeScgKTtcblx0XHRcdH1cblx0XHRcdGlmICggY2FuY2VsX2J1dHRvbiApIHtcblx0XHRcdFx0Y2FuY2VsX2J1dHRvbi5kaXNhYmxlZCA9IGZhbHNlO1xuXHRcdFx0fVxuXHRcdFx0c3luY2hyb25pemVfaW5zcGVjdG9yX2RpcnR5X3N0YXRlKCk7XG5cdFx0fSApO1xuXHR9XG5cblx0LyoqXG5cdCAqIFN1Ym1pdCBidWxrLCBwZXJtYW5lbnQtZGVsZXRlLCBhbmQgY2FwYWNpdHkgcmV2aWV3IGluc3BlY3RvciBzdGF0ZXMuXG5cdCAqXG5cdCAqIE11dGF0aW9ucyByZW1haW4gaW1wb3NzaWJsZSBmcm9tIHNlbGVjdGlvbiBhbG9uZTogYnVsayBlZGl0aW5nIHJlcXVpcmVzIGFcblx0ICogc2lnbmVkIHByZXZpZXcsIGFuZCBkZWxldGlvbiBhZGRpdGlvbmFsbHkgcmVxdWlyZXMgZXhwbGljaXQgYWNrbm93bGVkZ2VtZW50LlxuXHQgKlxuXHQgKiBAcGFyYW0ge1N1Ym1pdEV2ZW50fSBldmVudCAgSW5zcGVjdG9yIGZvcm0gc3VibWlzc2lvbi5cblx0ICogQHBhcmFtIHtPYmplY3R9ICAgICAgY29uZmlnIENhdGFsb2cgY29uZmlndXJhdGlvbi5cblx0ICogQHJldHVybiB7dm9pZH1cblx0ICovXG5cdGZ1bmN0aW9uIHN1Ym1pdF9yZXZpZXdlZF9pbnNwZWN0b3IoIGV2ZW50LCBjb25maWcgKSB7XG5cdFx0dmFyIGZvcm0gPSBldmVudC50YXJnZXQ7XG5cdFx0dmFyIHNhdmVfYnV0dG9uID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtdWktY2F0YWxvZy1pbnNwZWN0b3Itc2F2ZV0nICk7XG5cdFx0dmFyIGNhbmNlbF9idXR0b24gPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCAnW2RhdGEtd3BiYy11aS1jYXRhbG9nLWluc3BlY3Rvci1jYW5jZWxdJyApO1xuXHRcdHZhciByZXF1ZXN0X3NlcXVlbmNlO1xuXHRcdHZhciBhY3Rpb247XG5cdFx0dmFyIHZhbHVlcztcblx0XHR2YXIgZmFsbGJhY2s7XG5cdFx0dmFyIGlzX211dGF0aW9uO1xuXHRcdHZhciBzdWJtaXR0ZWRfbW9kZTtcblx0XHR2YXIgc3VibWl0dGVkX3Jlc291cmNlX2lkcztcblx0XHR2YXIgc3VibWl0dGVkX3RyYWNrc19zZWxlY3Rpb247XG5cblx0XHRldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xuXHRcdGlmICggaW5zcGVjdG9yX3NlbGVjdGlvbl9zdGFsZSB8fCAhIGlzX2V4cGVjdGVkX2luc3BlY3Rvcl9zdWJtaXQoIGV2ZW50LCBmb3JtICkgfHwgKCBzYXZlX2J1dHRvbiAmJiAoIHNhdmVfYnV0dG9uLmRpc2FibGVkIHx8IHNhdmVfYnV0dG9uLmNsYXNzTGlzdC5jb250YWlucyggJ2lzLWJ1c3knICkgKSApIHx8ICEgcmVwb3J0X2luc3BlY3Rvcl92YWxpZGl0eSggZm9ybSApICkge1xuXHRcdFx0cmV0dXJuO1xuXHRcdH1cblx0XHRzdWJtaXR0ZWRfbW9kZSA9IGluc3BlY3Rvcl9tb2RlO1xuXHRcdHN1Ym1pdHRlZF9yZXNvdXJjZV9pZHMgPSBpbnNwZWN0b3JfcmVzb3VyY2VfaWRzLnNsaWNlKCk7XG5cdFx0c3VibWl0dGVkX3RyYWNrc19zZWxlY3Rpb24gPSBpbnNwZWN0b3JfdHJhY2tzX3NlbGVjdGlvbjtcblx0XHRpZiAoICdidWxrX2VkaXQnID09PSBzdWJtaXR0ZWRfbW9kZSApIHtcblx0XHRcdGluc3BlY3Rvcl9idWxrX29wZXJhdGlvbnMgPSBjb2xsZWN0X2J1bGtfb3BlcmF0aW9ucygpO1xuXHRcdFx0YWN0aW9uID0gY29uZmlnLmJ1bGtfcHJldmlld19hY3Rpb247XG5cdFx0XHR2YWx1ZXMgPSB7IHJlc291cmNlX2lkczogSlNPTi5zdHJpbmdpZnkoIGluc3BlY3Rvcl9yZXNvdXJjZV9pZHMgKSwgb3BlcmF0aW9uczogSlNPTi5zdHJpbmdpZnkoIGluc3BlY3Rvcl9idWxrX29wZXJhdGlvbnMgKSB9O1xuXHRcdFx0ZmFsbGJhY2sgPSBjb25maWcuaTE4bi5idWxrX3Jldmlld19mYWlsZWQ7XG5cdFx0fSBlbHNlIGlmICggJ2J1bGtfcmV2aWV3JyA9PT0gc3VibWl0dGVkX21vZGUgKSB7XG5cdFx0XHRhY3Rpb24gPSBjb25maWcuYnVsa19hcHBseV9hY3Rpb247XG5cdFx0XHR2YWx1ZXMgPSB7IHJlc291cmNlX2lkczogSlNPTi5zdHJpbmdpZnkoIGluc3BlY3Rvcl9yZXNvdXJjZV9pZHMgKSwgb3BlcmF0aW9uczogSlNPTi5zdHJpbmdpZnkoIGluc3BlY3Rvcl9idWxrX29wZXJhdGlvbnMgKSwgcmV2aWV3X3Rva2VuOiBpbnNwZWN0b3JfcmV2aWV3X3Rva2VuIH07XG5cdFx0XHRmYWxsYmFjayA9IGNvbmZpZy5pMThuLmJ1bGtfYXBwbHlfZmFpbGVkO1xuXHRcdH0gZWxzZSBpZiAoICdkZWxldGVfcmV2aWV3JyA9PT0gc3VibWl0dGVkX21vZGUgKSB7XG5cdFx0XHRhY3Rpb24gPSBjb25maWcuZGVsZXRlX2FwcGx5X2FjdGlvbjtcblx0XHRcdHZhbHVlcyA9IHsgYWNrbm93bGVkZ2VkOiAnMScsIHJlc291cmNlX2lkczogSlNPTi5zdHJpbmdpZnkoIGluc3BlY3Rvcl9yZXNvdXJjZV9pZHMgKSwgcmV2aWV3X3Rva2VuOiBpbnNwZWN0b3JfcmV2aWV3X3Rva2VuIH07XG5cdFx0XHRmYWxsYmFjayA9IGNvbmZpZy5pMThuLmRlbGV0ZV9hcHBseV9mYWlsZWQ7XG5cdFx0fSBlbHNlIGlmICggJ2NhcGFjaXR5JyA9PT0gc3VibWl0dGVkX21vZGUgKSB7XG5cdFx0XHRhY3Rpb24gPSBjb25maWcuY2FwYWNpdHlfcHJldmlld19hY3Rpb247XG5cdFx0XHR2YWx1ZXMgPSB7IHJlc291cmNlX2lkOiBpbnNwZWN0b3JfcmVzb3VyY2VfaWQsIHRhcmdldF9jYXBhY2l0eTogaW5zcGVjdG9yX2NhcGFjaXR5X3RhcmdldCwgZGV0YWNoX3Jlc291cmNlX2lkczogSlNPTi5zdHJpbmdpZnkoIGluc3BlY3Rvcl9jYXBhY2l0eV9kZXRhY2hfaWRzICksIGRlY3JlYXNlX2FjdGlvbjogaW5zcGVjdG9yX2NhcGFjaXR5X2RlY3JlYXNlX2FjdGlvbiB9O1xuXHRcdFx0ZmFsbGJhY2sgPSBjb25maWcuaTE4bi5jYXBhY2l0eV9yZXZpZXdfZmFpbGVkO1xuXHRcdH0gZWxzZSBpZiAoICdjYXBhY2l0eV9yZXZpZXcnID09PSBzdWJtaXR0ZWRfbW9kZSApIHtcblx0XHRcdGFjdGlvbiA9IGNvbmZpZy5jYXBhY2l0eV9hcHBseV9hY3Rpb247XG5cdFx0XHR2YXIgY2FwYWNpdHlfYWNrbm93bGVkZ2VtZW50ID0gZm9ybS5xdWVyeVNlbGVjdG9yKCAnW2RhdGEtd3BiYy1jYXRhbG9nLWNhcGFjaXR5LWRlbGV0ZS1hY2tub3dsZWRnZW1lbnRdJyApO1xuXHRcdFx0dmFsdWVzID0geyByZXNvdXJjZV9pZDogaW5zcGVjdG9yX3Jlc291cmNlX2lkLCB0YXJnZXRfY2FwYWNpdHk6IGluc3BlY3Rvcl9jYXBhY2l0eV90YXJnZXQsIGRldGFjaF9yZXNvdXJjZV9pZHM6IEpTT04uc3RyaW5naWZ5KCBpbnNwZWN0b3JfY2FwYWNpdHlfZGV0YWNoX2lkcyApLCBkZWNyZWFzZV9hY3Rpb246IGluc3BlY3Rvcl9jYXBhY2l0eV9kZWNyZWFzZV9hY3Rpb24sIGFja25vd2xlZGdlZDogY2FwYWNpdHlfYWNrbm93bGVkZ2VtZW50ICYmIGNhcGFjaXR5X2Fja25vd2xlZGdlbWVudC5jaGVja2VkID8gJzEnIDogJzAnLCByZXZpZXdfdG9rZW46IGluc3BlY3Rvcl9yZXZpZXdfdG9rZW4gfTtcblx0XHRcdGZhbGxiYWNrID0gY29uZmlnLmkxOG4uY2FwYWNpdHlfYXBwbHlfZmFpbGVkO1xuXHRcdH0gZWxzZSB7XG5cdFx0XHRyZXR1cm47XG5cdFx0fVxuXG5cdFx0aXNfbXV0YXRpb24gPSAnYnVsa19yZXZpZXcnID09PSBzdWJtaXR0ZWRfbW9kZSB8fCAnZGVsZXRlX3JldmlldycgPT09IHN1Ym1pdHRlZF9tb2RlIHx8ICdjYXBhY2l0eV9yZXZpZXcnID09PSBzdWJtaXR0ZWRfbW9kZTtcblx0XHRyZXF1ZXN0X3NlcXVlbmNlID0gaXNfbXV0YXRpb24gPyArK2luc3BlY3Rvcl9tdXRhdGlvbl9yZXF1ZXN0X3NlcXVlbmNlIDogKytpbnNwZWN0b3JfcmVxdWVzdF9zZXF1ZW5jZTtcblx0XHRpZiAoIGlzX211dGF0aW9uICkge1xuXHRcdFx0aW5zcGVjdG9yX211dGF0aW9uX2luX3Byb2dyZXNzID0gdHJ1ZTtcblx0XHR9XG5cdFx0aWYgKCAnYnVsa19yZXZpZXcnID09PSBzdWJtaXR0ZWRfbW9kZSAmJiBnZXRfaW5saW5lX3Jldmlld193b3JrZmxvdygpICkge1xuXHRcdFx0Z2V0X2lubGluZV9yZXZpZXdfd29ya2Zsb3coKS5zeW5jaHJvbml6ZSggeyBidXN5OiB0cnVlLCBjYW5fYXBwbHk6IHRydWUgfSApO1xuXHRcdH1cblx0XHRpZiAoICdkZWxldGVfcmV2aWV3JyA9PT0gc3VibWl0dGVkX21vZGUgJiYgZ2V0X2RlbGV0ZV9yZXZpZXdfd29ya2Zsb3coKSApIHtcblx0XHRcdGdldF9kZWxldGVfcmV2aWV3X3dvcmtmbG93KCkuc3luY2hyb25pemUoIHsgYnVzeTogdHJ1ZSwgY2FuX2FwcGx5OiB0cnVlIH0gKTtcblx0XHR9XG5cdFx0aWYgKCBzYXZlX2J1dHRvbiApIHtcblx0XHRcdHNhdmVfYnV0dG9uLmRpc2FibGVkID0gdHJ1ZTtcblx0XHRcdHNhdmVfYnV0dG9uLmNsYXNzTGlzdC5hZGQoICdpcy1idXN5JyApO1xuXHRcdH1cblx0XHRpZiAoIGNhbmNlbF9idXR0b24gKSB7XG5cdFx0XHRjYW5jZWxfYnV0dG9uLmRpc2FibGVkID0gdHJ1ZTtcblx0XHR9XG5cdFx0Zm9ybS5jbGFzc0xpc3QuYWRkKCAnaXMtc2F2aW5nJyApO1xuXHRcdGZvcm0uc2V0QXR0cmlidXRlKCAnYXJpYS1idXN5JywgJ3RydWUnICk7XG5cblx0XHRyZXF1ZXN0X2luc3BlY3RvciggY29uZmlnLCBhY3Rpb24sIHZhbHVlcyApLnRoZW4oIGZ1bmN0aW9uICggcmVzcG9uc2UgKSB7XG5cdFx0XHRpZiAoIHJlcXVlc3Rfc2VxdWVuY2UgIT09ICggaXNfbXV0YXRpb24gPyBpbnNwZWN0b3JfbXV0YXRpb25fcmVxdWVzdF9zZXF1ZW5jZSA6IGluc3BlY3Rvcl9yZXF1ZXN0X3NlcXVlbmNlICkgKSB7XG5cdFx0XHRcdHJldHVybjtcblx0XHRcdH1cblx0XHRcdGlmICggISByZXNwb25zZSB8fCB0cnVlICE9PSByZXNwb25zZS5zdWNjZXNzIHx8ICEgcmVzcG9uc2UuZGF0YSApIHtcblx0XHRcdFx0dGhyb3cgbmV3IEVycm9yKCBnZXRfaW5zcGVjdG9yX3Jlc3BvbnNlX21lc3NhZ2UoIHJlc3BvbnNlLCBmYWxsYmFjayApICk7XG5cdFx0XHR9XG5cdFx0XHRpZiAoICdidWxrX2VkaXQnID09PSBzdWJtaXR0ZWRfbW9kZSApIHtcblx0XHRcdFx0aWYgKCAhIHJlc3BvbnNlLmRhdGEucHJldmlldyB8fCAhIHJlbmRlcl9idWxrX3JldmlldyggY29uZmlnLCByZXNwb25zZS5kYXRhLnByZXZpZXcgKSApIHtcblx0XHRcdFx0XHR0aHJvdyBuZXcgRXJyb3IoIGZhbGxiYWNrIHx8ICcnICk7XG5cdFx0XHRcdH1cblx0XHRcdFx0cmV0dXJuO1xuXHRcdFx0fVxuXHRcdFx0aWYgKCAnY2FwYWNpdHknID09PSBzdWJtaXR0ZWRfbW9kZSApIHtcblx0XHRcdFx0aWYgKCAhIHJlc3BvbnNlLmRhdGEucHJldmlldyB8fCAhIHJlbmRlcl9jYXBhY2l0eV9yZXZpZXcoIGNvbmZpZywgcmVzcG9uc2UuZGF0YS5wcmV2aWV3ICkgKSB7XG5cdFx0XHRcdFx0dGhyb3cgbmV3IEVycm9yKCBmYWxsYmFjayB8fCAnJyApO1xuXHRcdFx0XHR9XG5cdFx0XHRcdHJldHVybjtcblx0XHRcdH1cblx0XHRcdGlmICggJ2J1bGtfcmV2aWV3JyA9PT0gc3VibWl0dGVkX21vZGUgKSB7XG5cdFx0XHRcdHBlbmRpbmdfaGlnaGxpZ2h0X2lkcyA9IEFycmF5LmlzQXJyYXkoIHJlc3BvbnNlLmRhdGEudXBkYXRlZF9pZHMgKSA/IHJlc3BvbnNlLmRhdGEudXBkYXRlZF9pZHMubWFwKCBTdHJpbmcgKSA6IFtdO1xuXHRcdFx0fSBlbHNlIGlmICggJ2NhcGFjaXR5X3JldmlldycgPT09IHN1Ym1pdHRlZF9tb2RlICkge1xuXHRcdFx0XHRwZW5kaW5nX2hpZ2hsaWdodF9pZHMgPSBBcnJheS5pc0FycmF5KCByZXNwb25zZS5kYXRhLmFmZmVjdGVkX2lkcyApID8gcmVzcG9uc2UuZGF0YS5hZmZlY3RlZF9pZHMubWFwKCBTdHJpbmcgKSA6IFtdO1xuXHRcdFx0fSBlbHNlIHtcblx0XHRcdFx0dmFyIG1vdW50ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoIGNvbmZpZy5tb3VudF9pZCApO1xuXHRcdFx0XHR2YXIgc2VsZWN0aW9uID0gbW91bnQgJiYgbW91bnQuX3dwYmNfdWlfY2F0YWxvZ19zZWxlY3Rpb25fY29udHJvbGxlcjtcblx0XHRcdFx0dmFyIHNlbGVjdGVkX3Jlc291cmNlX2lkcyA9IGdldF9zZWxlY3RlZF9yZXNvdXJjZV9pZHMoIGNvbmZpZyApO1xuXHRcdFx0XHR2YXIgZGVsZXRlZF9zZWxlY3RlZF9yZXNvdXJjZSA9IHN1Ym1pdHRlZF9yZXNvdXJjZV9pZHMuc29tZSggZnVuY3Rpb24gKCByZXNvdXJjZV9pZCApIHtcblx0XHRcdFx0XHRyZXR1cm4gLTEgIT09IHNlbGVjdGVkX3Jlc291cmNlX2lkcy5pbmRleE9mKCBOdW1iZXIoIHJlc291cmNlX2lkICkgKTtcblx0XHRcdFx0fSApO1xuXHRcdFx0XHRpZiAoIHNlbGVjdGlvbiAmJiAnZnVuY3Rpb24nID09PSB0eXBlb2Ygc2VsZWN0aW9uLmNsZWFyICYmICggc3VibWl0dGVkX3RyYWNrc19zZWxlY3Rpb24gfHwgZGVsZXRlZF9zZWxlY3RlZF9yZXNvdXJjZSApICkge1xuXHRcdFx0XHRcdHNlbGVjdGlvbi5jbGVhcigpO1xuXHRcdFx0XHR9XG5cdFx0XHR9XG5cdFx0XHRzaG93X2FkbWluX21lc3NhZ2UoIGdldF9pbnNwZWN0b3JfcmVzcG9uc2VfbWVzc2FnZSggcmVzcG9uc2UsICcnICksICdzdWNjZXNzJywgNDAwMCApO1xuXHRcdFx0aW5zcGVjdG9yX2RpcnR5ID0gZmFsc2U7XG5cdFx0XHRpbnNwZWN0b3JfbXV0YXRpb25faW5fcHJvZ3Jlc3MgPSBmYWxzZTtcblx0XHRcdGlmICggZG9jdW1lbnQuZG9jdW1lbnRFbGVtZW50LmNvbnRhaW5zKCBmb3JtICkgKSB7XG5cdFx0XHRcdGNsb3NlX2luc3BlY3RvciggY29uZmlnLCBmYWxzZSApO1xuXHRcdFx0fVxuXHRcdFx0aWYgKCBjYXRhbG9nX2NvbnRyb2xsZXIgKSB7XG5cdFx0XHRcdGNhdGFsb2dfY29udHJvbGxlci5sb2FkKCk7XG5cdFx0XHR9XG5cdFx0fSApLmNhdGNoKCBmdW5jdGlvbiAoIGVycm9yICkge1xuXHRcdFx0aWYgKCByZXF1ZXN0X3NlcXVlbmNlICE9PSAoIGlzX211dGF0aW9uID8gaW5zcGVjdG9yX211dGF0aW9uX3JlcXVlc3Rfc2VxdWVuY2UgOiBpbnNwZWN0b3JfcmVxdWVzdF9zZXF1ZW5jZSApICkge1xuXHRcdFx0XHRyZXR1cm47XG5cdFx0XHR9XG5cdFx0XHRpZiAoIGlzX211dGF0aW9uICkge1xuXHRcdFx0XHRpbnNwZWN0b3JfbXV0YXRpb25faW5fcHJvZ3Jlc3MgPSBmYWxzZTtcblx0XHRcdH1cblx0XHRcdGlmICggISBkb2N1bWVudC5kb2N1bWVudEVsZW1lbnQuY29udGFpbnMoIGZvcm0gKSApIHtcblx0XHRcdFx0c2hvd19hZG1pbl9tZXNzYWdlKCBlcnJvciAmJiBlcnJvci5tZXNzYWdlID8gZXJyb3IubWVzc2FnZSA6IGZhbGxiYWNrIHx8ICcnLCAnZXJyb3InLCA1MDAwICk7XG5cdFx0XHRcdHJldHVybjtcblx0XHRcdH1cblx0XHRcdGZvcm0uY2xhc3NMaXN0LnJlbW92ZSggJ2lzLXNhdmluZycgKTtcblx0XHRcdGZvcm0ucmVtb3ZlQXR0cmlidXRlKCAnYXJpYS1idXN5JyApO1xuXHRcdFx0c2hvd19pbnNwZWN0b3JfbWVzc2FnZSggZm9ybSwgZXJyb3IgJiYgZXJyb3IubWVzc2FnZSA/IGVycm9yLm1lc3NhZ2UgOiBmYWxsYmFjayB8fCAnJywgdHJ1ZSApO1xuXHRcdFx0aWYgKCBzYXZlX2J1dHRvbiApIHtcblx0XHRcdFx0c2F2ZV9idXR0b24uY2xhc3NMaXN0LnJlbW92ZSggJ2lzLWJ1c3knICk7XG5cdFx0XHRcdHNhdmVfYnV0dG9uLmRpc2FibGVkID0gaW5zcGVjdG9yX3NlbGVjdGlvbl9zdGFsZSB8fCAoICdkZWxldGVfcmV2aWV3JyA9PT0gaW5zcGVjdG9yX21vZGUgJiYgISBmb3JtLnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLWNhdGFsb2ctcmVzb3VyY2UtZGVsZXRlLWFja25vd2xlZGdlbWVudF06Y2hlY2tlZCcgKSApO1xuXHRcdFx0fVxuXHRcdFx0aWYgKCBjYW5jZWxfYnV0dG9uICkge1xuXHRcdFx0XHRjYW5jZWxfYnV0dG9uLmRpc2FibGVkID0gZmFsc2U7XG5cdFx0XHR9XG5cdFx0XHRpZiAoICdidWxrX3JldmlldycgPT09IHN1Ym1pdHRlZF9tb2RlICYmIGdldF9pbmxpbmVfcmV2aWV3X3dvcmtmbG93KCkgKSB7XG5cdFx0XHRcdGdldF9pbmxpbmVfcmV2aWV3X3dvcmtmbG93KCkuc3luY2hyb25pemUoIHsgYnVzeTogZmFsc2UsIGNhbl9hcHBseTogISBpbnNwZWN0b3Jfc2VsZWN0aW9uX3N0YWxlIH0gKTtcblx0XHRcdH1cblx0XHRcdGlmICggJ2RlbGV0ZV9yZXZpZXcnID09PSBzdWJtaXR0ZWRfbW9kZSAmJiBnZXRfZGVsZXRlX3Jldmlld193b3JrZmxvdygpICkge1xuXHRcdFx0XHRnZXRfZGVsZXRlX3Jldmlld193b3JrZmxvdygpLnN5bmNocm9uaXplKCB7IGJ1c3k6IGZhbHNlLCBjYW5fYXBwbHk6ICEgaW5zcGVjdG9yX3NlbGVjdGlvbl9zdGFsZSB9ICk7XG5cdFx0XHR9XG5cdFx0fSApO1xuXHR9XG5cblx0LyoqXG5cdCAqIEludmFsaWRhdGUgYW4gb3BlbiBzZWxlY3Rpb24tb3duZWQgaW5zcGVjdG9yIHdoZW4gaXRzIHNlbGVjdGlvbiBjaGFuZ2VzLlxuXHQgKlxuXHQgKiBAcGFyYW0ge0N1c3RvbUV2ZW50fSBldmVudCAgU2hhcmVkIHNlbGVjdGlvbiBsaWZlY3ljbGUgZXZlbnQuXG5cdCAqIEBwYXJhbSB7T2JqZWN0fSAgICAgIGNvbmZpZyBDYXRhbG9nIGNvbmZpZ3VyYXRpb24uXG5cdCAqIEByZXR1cm4ge3ZvaWR9XG5cdCAqL1xuXHRmdW5jdGlvbiBoYW5kbGVfaW5zcGVjdG9yX3NlbGVjdGlvbl9jaGFuZ2UoIGV2ZW50LCBjb25maWcgKSB7XG5cdFx0dmFyIHNlbGVjdGVkX2lkcyA9IGV2ZW50ICYmIGV2ZW50LmRldGFpbCAmJiBBcnJheS5pc0FycmF5KCBldmVudC5kZXRhaWwuc2VsZWN0ZWRfaWRzICkgPyBldmVudC5kZXRhaWwuc2VsZWN0ZWRfaWRzIDogZ2V0X3NlbGVjdGVkX3Jlc291cmNlX2lkcyggY29uZmlnICk7XG5cdFx0dmFyIGZvcm07XG5cdFx0dmFyIHNhdmVfYnV0dG9uO1xuXG5cdFx0aWYgKCAhIGluc3BlY3Rvcl90cmFja3Nfc2VsZWN0aW9uIHx8IC0xID09PSBbICdidWxrX2VkaXQnLCAnYnVsa19yZXZpZXcnLCAnZGVsZXRlX3JldmlldycgXS5pbmRleE9mKCBpbnNwZWN0b3JfbW9kZSApICkge1xuXHRcdFx0cmV0dXJuO1xuXHRcdH1cblx0XHRmb3JtID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtY2F0YWxvZy1yZXNvdXJjZS1idWxrLWZvcm1dLCBbZGF0YS13cGJjLWNhdGFsb2ctcmVzb3VyY2UtYnVsay1yZXZpZXctZm9ybV0sIFtkYXRhLXdwYmMtY2F0YWxvZy1yZXNvdXJjZS1kZWxldGUtZm9ybV0nICk7XG5cdFx0c2F2ZV9idXR0b24gPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCAnW2RhdGEtd3BiYy11aS1jYXRhbG9nLWluc3BlY3Rvci1zYXZlXScgKTtcblx0XHRpZiAoIHJlc291cmNlX2lkX2xpc3RzX21hdGNoKCBpbnNwZWN0b3JfcmVzb3VyY2VfaWRzLCBzZWxlY3RlZF9pZHMgKSApIHtcblx0XHRcdGlmICggISBpbnNwZWN0b3Jfc2VsZWN0aW9uX3N0YWxlICkge1xuXHRcdFx0XHRyZXR1cm47XG5cdFx0XHR9XG5cdFx0XHRpbnNwZWN0b3Jfc2VsZWN0aW9uX3N0YWxlID0gZmFsc2U7XG5cdFx0XHRzaG93X2luc3BlY3Rvcl9tZXNzYWdlKCBmb3JtLCAnJywgZmFsc2UgKTtcblx0XHRcdGlmICggJ2RlbGV0ZV9yZXZpZXcnID09PSBpbnNwZWN0b3JfbW9kZSAmJiBnZXRfZGVsZXRlX3Jldmlld193b3JrZmxvdygpICkge1xuXHRcdFx0XHRnZXRfZGVsZXRlX3Jldmlld193b3JrZmxvdygpLnN5bmNocm9uaXplKCB7IGJ1c3k6IGZhbHNlLCBjYW5fYXBwbHk6IHRydWUgfSApO1xuXHRcdFx0fVxuXHRcdFx0aWYgKCAnYnVsa19lZGl0JyA9PT0gaW5zcGVjdG9yX21vZGUgKSB7XG5cdFx0XHRcdHN5bmNocm9uaXplX2J1bGtfZWRpdG9yKCBudWxsICk7XG5cdFx0XHR9IGVsc2UgaWYgKCBzYXZlX2J1dHRvbiApIHtcblx0XHRcdFx0dmFyIGFja25vd2xlZGdlbWVudCA9IGZvcm0gPyBmb3JtLnF1ZXJ5U2VsZWN0b3IoICdbZGF0YS13cGJjLWNhdGFsb2ctcmVzb3VyY2UtZGVsZXRlLWFja25vd2xlZGdlbWVudF0nICkgOiBudWxsO1xuXHRcdFx0XHRzYXZlX2J1dHRvbi5kaXNhYmxlZCA9ICdkZWxldGVfcmV2aWV3JyA9PT0gaW5zcGVjdG9yX21vZGUgJiYgKCAhIGFja25vd2xlZGdlbWVudCB8fCAhIGFja25vd2xlZGdlbWVudC5jaGVja2VkICk7XG5cdFx0XHR9XG5cdFx0XHRyZXR1cm47XG5cdFx0fVxuXHRcdGluc3BlY3Rvcl9zZWxlY3Rpb25fc3RhbGUgPSB0cnVlO1xuXHRcdHNob3dfaW5zcGVjdG9yX21lc3NhZ2UoIGZvcm0sIGNvbmZpZy5pMThuLnNlbGVjdGlvbl9jaGFuZ2VkIHx8ICcnLCB0cnVlICk7XG5cdFx0aWYgKCAnZGVsZXRlX3JldmlldycgPT09IGluc3BlY3Rvcl9tb2RlICYmIGdldF9kZWxldGVfcmV2aWV3X3dvcmtmbG93KCkgKSB7XG5cdFx0XHRnZXRfZGVsZXRlX3Jldmlld193b3JrZmxvdygpLnN5bmNocm9uaXplKCB7IGJ1c3k6IGZhbHNlLCBjYW5fYXBwbHk6IGZhbHNlIH0gKTtcblx0XHR9XG5cdFx0aWYgKCBzYXZlX2J1dHRvbiApIHtcblx0XHRcdHNhdmVfYnV0dG9uLmRpc2FibGVkID0gdHJ1ZTtcblx0XHR9XG5cdH1cblxuXHQvKipcblx0ICogQXBwbHkgdGhlIHBvc3QtbXV0YXRpb24gaGlnaGxpZ2h0IGFmdGVyIGFuIEFKQVggY2F0YWxvZyByZWZyZXNoLlxuXHQgKlxuXHQgKiBAcmV0dXJuIHt2b2lkfVxuXHQgKi9cblx0ZnVuY3Rpb24gYXBwbHlfcGVuZGluZ19oaWdobGlnaHRzKCkge1xuXHRcdHZhciBmaXJzdF9yb3cgPSBudWxsO1xuXG5cdFx0cGVuZGluZ19oaWdobGlnaHRfaWRzLmZvckVhY2goIGZ1bmN0aW9uICggcmVzb3VyY2VfaWQgKSB7XG5cdFx0XHR2YXIgcm93ID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtYm9va2luZy1yZXNvdXJjZS1pZD1cIicgKyByZXNvdXJjZV9pZCArICdcIl0nICk7XG5cdFx0XHRpZiAoIHJvdyApIHtcblx0XHRcdFx0cm93LmNsYXNzTGlzdC5hZGQoICdpcy1yZWNlbnRseS1zYXZlZCcgKTtcblx0XHRcdFx0Zmlyc3Rfcm93ID0gZmlyc3Rfcm93IHx8IHJvdztcblx0XHRcdH1cblx0XHR9ICk7XG5cdFx0aWYgKCBmaXJzdF9yb3cgKSB7XG5cdFx0XHRmaXJzdF9yb3cuc2Nyb2xsSW50b1ZpZXcoIHsgYmxvY2s6ICduZWFyZXN0JywgYmVoYXZpb3I6ICdzbW9vdGgnIH0gKTtcblx0XHR9XG5cdFx0d2luZG93LnNldFRpbWVvdXQoIGZ1bmN0aW9uICgpIHtcblx0XHRcdGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoICcud3BiY19ib29raW5nX3Jlc291cmNlc19faXRlbS5pcy1yZWNlbnRseS1zYXZlZCcgKS5mb3JFYWNoKCBmdW5jdGlvbiAoIHJvdyApIHtcblx0XHRcdFx0cm93LmNsYXNzTGlzdC5yZW1vdmUoICdpcy1yZWNlbnRseS1zYXZlZCcgKTtcblx0XHRcdH0gKTtcblx0XHR9LCA1MDAwICk7XG5cdFx0cGVuZGluZ19oaWdobGlnaHRfaWRzID0gW107XG5cdH1cblxuXHQvKipcblx0ICogUmVtb3ZlIGEgY29uc3VtZWQgcmVhZC1vbmx5IGxhdW5jaCBwYXJhbWV0ZXIgd2l0aG91dCBjaGFuZ2luZyBuYXZpZ2F0aW9uLlxuXHQgKlxuXHQgKiBUaGlzIHByZXZlbnRzIGEgbm9ybWFsIHJlbG9hZCBmcm9tIHJlb3BlbmluZyB0aGUgZ3VpZGVkIGFjdGlvbiBtZW51LiBUaGUgY2F0YWxvZydzXG5cdCAqIG90aGVyIHJlcXVlc3QtbG9jYWwgZGlzcGxheSBhcmd1bWVudHMgcmVtYWluIGluIHBsYWNlIGFuZCBubyBwcmVmZXJlbmNlXG5cdCAqIHNhdmUgYWN0aW9uIGlzIGludHJvZHVjZWQuXG5cdCAqXG5cdCAqIEBwYXJhbSB7T2JqZWN0fSBjb25maWcgQ2F0YWxvZyBjb25maWd1cmF0aW9uLlxuXHQgKiBAcmV0dXJuIHt2b2lkfVxuXHQgKi9cblx0ZnVuY3Rpb24gY29uc3VtZV9pbml0aWFsX2xhdW5jaF9wYXJhbWV0ZXIoIGNvbmZpZyApIHtcblx0XHR2YXIgbGF1bmNoX2ludGVudCA9IGNvbmZpZyAmJiBjb25maWcubGF1bmNoX2ludGVudCA/IGNvbmZpZy5sYXVuY2hfaW50ZW50IDoge307XG5cdFx0dmFyIHF1ZXJ5X3BhcmFtZXRlciA9IFN0cmluZyggbGF1bmNoX2ludGVudC5xdWVyeV9wYXJhbWV0ZXIgfHwgJycgKTtcblx0XHR2YXIgY3VycmVudF91cmw7XG5cblx0XHRpZiAoICEgcXVlcnlfcGFyYW1ldGVyIHx8ICEgd2luZG93Lmhpc3RvcnkgfHwgJ2Z1bmN0aW9uJyAhPT0gdHlwZW9mIHdpbmRvdy5oaXN0b3J5LnJlcGxhY2VTdGF0ZSB8fCAnZnVuY3Rpb24nICE9PSB0eXBlb2Ygd2luZG93LlVSTCApIHtcblx0XHRcdHJldHVybjtcblx0XHR9XG5cblx0XHR0cnkge1xuXHRcdFx0Y3VycmVudF91cmwgPSBuZXcgd2luZG93LlVSTCggd2luZG93LmxvY2F0aW9uLmhyZWYgKTtcblx0XHRcdGN1cnJlbnRfdXJsLnNlYXJjaFBhcmFtcy5kZWxldGUoIHF1ZXJ5X3BhcmFtZXRlciApO1xuXHRcdFx0d2luZG93Lmhpc3RvcnkucmVwbGFjZVN0YXRlKCB3aW5kb3cuaGlzdG9yeS5zdGF0ZSwgJycsIGN1cnJlbnRfdXJsLnRvU3RyaW5nKCkgKTtcblx0XHR9IGNhdGNoICggZXJyb3IgKSB7XG5cdFx0XHQvLyBBIG1hbGZvcm1lZCBhZG1pbmlzdHJhdGlvbiBVUkwgbXVzdCBub3QgcHJldmVudCBub3JtYWwgY2F0YWxvZyB1c2UuXG5cdFx0fVxuXHR9XG5cblx0LyoqXG5cdCAqIERldGVybWluZSB3aGV0aGVyIG9uZSBhdXRob3JpemVkIFJlc291cmNlIERUTyBleHBvc2VzIGEgbmFtZWQgYWN0aW9uLlxuXHQgKlxuXHQgKiBAcGFyYW0ge09iamVjdH0gcmVzb3VyY2UgIEF1dGhvcml6ZWQgUmVzb3VyY2UgbGlzdCBEVE8uXG5cdCAqIEBwYXJhbSB7c3RyaW5nfSBhY3Rpb25faWQgU3RhYmxlIGRvbWFpbiBhY3Rpb24gaWRlbnRpZmllci5cblx0ICogQHJldHVybiB7Ym9vbGVhbn0gVHJ1ZSB3aGVuIHRoZSBzZXJ2ZXItYXV0aG9yaXplZCBhY3Rpb24gaXMgcHJlc2VudC5cblx0ICovXG5cdGZ1bmN0aW9uIHJlc291cmNlX2V4cG9zZXNfYWN0aW9uKCByZXNvdXJjZSwgYWN0aW9uX2lkICkge1xuXHRcdHZhciBhY3Rpb25faXRlbXMgPSByZXNvdXJjZSAmJiBBcnJheS5pc0FycmF5KCByZXNvdXJjZS5hY3Rpb25faXRlbXMgKSA/IHJlc291cmNlLmFjdGlvbl9pdGVtcyA6IFtdO1xuXHRcdHZhciBhY3Rpb25faW5kZXg7XG5cblx0XHRmb3IgKCBhY3Rpb25faW5kZXggPSAwOyBhY3Rpb25faW5kZXggPCBhY3Rpb25faXRlbXMubGVuZ3RoOyBhY3Rpb25faW5kZXggKz0gMSApIHtcblx0XHRcdGlmICggYWN0aW9uX2lkID09PSBTdHJpbmcoIGFjdGlvbl9pdGVtc1sgYWN0aW9uX2luZGV4IF0uaWQgfHwgJycgKSApIHtcblx0XHRcdFx0cmV0dXJuIHRydWU7XG5cdFx0XHR9XG5cdFx0fVxuXG5cdFx0cmV0dXJuIGZhbHNlO1xuXHR9XG5cblx0LyoqXG5cdCAqIE9wZW4gb25lIHJlbmRlcmVkIFJlc291cmNlIGFjdGlvbiBtZW51IGFuZCBmb2N1cyBhIG5hbWVkIGFjdGlvbi5cblx0ICpcblx0ICogVGhlIHNoYXJlZCBjb250cm9sbGVyIG93bnMgbWVudSBtZWNoYW5pY3MuIFRoaXMgZG9tYWluIGFkYXB0ZXIgc2VsZWN0c1xuXHQgKiBvbmx5IGEgUmVzb3VyY2UgYW5kIGFjdGlvbiBhbHJlYWR5IGF1dGhvcml6ZWQgaW4gdGhlIHNlcnZlciByZXNwb25zZS5cblx0ICpcblx0ICogQHBhcmFtIHtPYmplY3R9IGNvbmZpZyAgICAgIFJlZ2lzdGVyZWQgY2F0YWxvZyBjb25maWd1cmF0aW9uLlxuXHQgKiBAcGFyYW0ge251bWJlcn0gcmVzb3VyY2VfaWQgQXV0aG9yaXplZCBCb29raW5nIFJlc291cmNlIElELlxuXHQgKiBAcGFyYW0ge3N0cmluZ30gYWN0aW9uX2lkICAgQXV0aG9yaXplZCBkb21haW4gYWN0aW9uIGlkZW50aWZpZXIuXG5cdCAqIEByZXR1cm4ge2Jvb2xlYW59IFRydWUgd2hlbiB0aGUgbWVudSBvcGVuZWQgYW5kIHRoZSBhY3Rpb24gcmVjZWl2ZWQgZm9jdXMuXG5cdCAqL1xuXHRmdW5jdGlvbiBvcGVuX3Jlc291cmNlX2FjdGlvbl9tZW51KCBjb25maWcsIHJlc291cmNlX2lkLCBhY3Rpb25faWQgKSB7XG5cdFx0dmFyIG1vdW50X2VsZW1lbnQgPSBjb25maWcgJiYgY29uZmlnLm1vdW50X2lkID8gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoIGNvbmZpZy5tb3VudF9pZCApIDogbnVsbDtcblx0XHR2YXIgYWN0aW9uc19jb250cm9sbGVyID0gbW91bnRfZWxlbWVudCAmJiBtb3VudF9lbGVtZW50Ll93cGJjX3VpX2NhdGFsb2dfYWN0aW9uc19jb250cm9sbGVyXG5cdFx0XHQ/IG1vdW50X2VsZW1lbnQuX3dwYmNfdWlfY2F0YWxvZ19hY3Rpb25zX2NvbnRyb2xsZXJcblx0XHRcdDogbnVsbDtcblxuXHRcdGlmICggISBhY3Rpb25zX2NvbnRyb2xsZXIgfHwgJ2Z1bmN0aW9uJyAhPT0gdHlwZW9mIGFjdGlvbnNfY29udHJvbGxlci5vcGVuX2l0ZW0gKSB7XG5cdFx0XHRyZXR1cm4gZmFsc2U7XG5cdFx0fVxuXG5cdFx0cmV0dXJuIGFjdGlvbnNfY29udHJvbGxlci5vcGVuX2l0ZW0oIFN0cmluZyggcmVzb3VyY2VfaWQgKSwgU3RyaW5nKCBhY3Rpb25faWQgfHwgJycgKSApO1xuXHR9XG5cblx0LyoqXG5cdCAqIE9wZW4gdGhlIGFjdGlvbiBtZW51IGZvciB0aGUgZmlyc3Qgc2VydmVyLWF1dGhvcml6ZWQgUmVzb3VyY2UgRFRPLlxuXHQgKlxuXHQgKiBUaGUgVVJMIGNhcnJpZXMgcHJlc2VudGF0aW9uIGludGVudCBvbmx5LiBJdCBuZXZlciBzdXBwbGllcyBhIHRydXN0ZWRcblx0ICogUmVzb3VyY2UgSUQuIEFjdGl2YXRpbmcgdGhlIGZvY3VzZWQgUHVibGlzaCBhY3Rpb24gZm9sbG93cyB0aGUgbm9ybWFsXG5cdCAqIGRvbWFpbiBoYW5kbGVyLCB3aGljaCByZXBlYXRzIGNhcGFiaWxpdHkgYW5kIG93bmVyc2hpcCBjaGVja3MgYmVmb3JlXG5cdCAqIG9wZW5pbmcgcHVibGlzaGluZyB0b29scy5cblx0ICpcblx0ICogQHBhcmFtIHtPYmplY3R9IGNvbmZpZyAgIENhdGFsb2cgY29uZmlndXJhdGlvbi5cblx0ICogQHBhcmFtIHtPYmplY3R9IHJlc3BvbnNlIE5vcm1hbGl6ZWQgYXV0aG9yaXplZCBjYXRhbG9nIHJlc3BvbnNlLlxuXHQgKiBAcmV0dXJuIHt2b2lkfVxuXHQgKi9cblx0ZnVuY3Rpb24gb3Blbl9pbml0aWFsX2xhdW5jaF9pbnRlbnQoIGNvbmZpZywgcmVzcG9uc2UgKSB7XG5cdFx0dmFyIGFjdGlvbl9pZCA9IGNvbmZpZyAmJiBjb25maWcubGF1bmNoX2ludGVudCA/IFN0cmluZyggY29uZmlnLmxhdW5jaF9pbnRlbnQuYWN0aW9uIHx8ICcnICkgOiAnJztcblx0XHR2YXIgcmVzb3VyY2U7XG5cdFx0dmFyIHJlc291cmNlX2luZGV4O1xuXG5cdFx0aWYgKCBsYXVuY2hfaW50ZW50X2hhbmRsZWQgfHwgJ3B1Ymxpc2hfcmVzb3VyY2UnICE9PSBhY3Rpb25faWQgfHwgISByZXNwb25zZSB8fCAhIEFycmF5LmlzQXJyYXkoIHJlc3BvbnNlLml0ZW1zICkgKSB7XG5cdFx0XHRyZXR1cm47XG5cdFx0fVxuXG5cdFx0bGF1bmNoX2ludGVudF9oYW5kbGVkID0gdHJ1ZTtcblx0XHRjb25zdW1lX2luaXRpYWxfbGF1bmNoX3BhcmFtZXRlciggY29uZmlnICk7XG5cblx0XHRmb3IgKCByZXNvdXJjZV9pbmRleCA9IDA7IHJlc291cmNlX2luZGV4IDwgcmVzcG9uc2UuaXRlbXMubGVuZ3RoOyByZXNvdXJjZV9pbmRleCArPSAxICkge1xuXHRcdFx0aWYgKCByZXNvdXJjZV9leHBvc2VzX2FjdGlvbiggcmVzcG9uc2UuaXRlbXNbIHJlc291cmNlX2luZGV4IF0sIGFjdGlvbl9pZCApICkge1xuXHRcdFx0XHRyZXNvdXJjZSA9IHJlc3BvbnNlLml0ZW1zWyByZXNvdXJjZV9pbmRleCBdO1xuXHRcdFx0XHRicmVhaztcblx0XHRcdH1cblx0XHR9XG5cblx0XHRpZiAoICEgcmVzb3VyY2UgfHwgISBOdW1iZXIoIHJlc291cmNlLmlkICkgKSB7XG5cdFx0XHRzaG93X2FkbWluX21lc3NhZ2UoIGNvbmZpZyAmJiBjb25maWcuaTE4biA/IGNvbmZpZy5pMThuLnB1Ymxpc2hpbmdfbGF1bmNoX3VuYXZhaWxhYmxlIHx8ICcnIDogJycsICdlcnJvcicsIDcwMDAgKTtcblx0XHRcdHJldHVybjtcblx0XHR9XG5cblx0XHR3aW5kb3cuc2V0VGltZW91dCggZnVuY3Rpb24gKCkge1xuXHRcdFx0aWYgKCAhIG9wZW5fcmVzb3VyY2VfYWN0aW9uX21lbnUoIGNvbmZpZywgTnVtYmVyKCByZXNvdXJjZS5pZCApLCBhY3Rpb25faWQgKSApIHtcblx0XHRcdFx0c2hvd19hZG1pbl9tZXNzYWdlKCBjb25maWcgJiYgY29uZmlnLmkxOG4gPyBjb25maWcuaTE4bi5wdWJsaXNoaW5nX2xhdW5jaF91bmF2YWlsYWJsZSB8fCAnJyA6ICcnLCAnZXJyb3InLCA3MDAwICk7XG5cdFx0XHR9XG5cdFx0fSwgMCApO1xuXHR9XG5cblx0LyoqXG5cdCAqIEhhbmRsZSBjb21wbGV0ZWQgc2hhcmVkIHJlbmRlcnMgZm9yIHRoaXMgUmVzb3VyY2UgY2F0YWxvZyBvbmx5LlxuXHQgKlxuXHQgKiBAcGFyYW0ge0N1c3RvbUV2ZW50fSBldmVudCBTaGFyZWQgY2F0YWxvZyBsaWZlY3ljbGUgZXZlbnQuXG5cdCAqIEByZXR1cm4ge3ZvaWR9XG5cdCAqL1xuXHRmdW5jdGlvbiBoYW5kbGVfY2F0YWxvZ19yZW5kZXJlZCggZXZlbnQgKSB7XG5cdFx0dmFyIGNvbmZpZyA9IHdpbmRvdy53cGJjX2NhdGFsb2dfYm9va2luZ19yZXNvdXJjZXNfY29uZmlnO1xuXHRcdHZhciBldmVudF9kZXRhaWwgPSBldmVudCAmJiBldmVudC5kZXRhaWwgPyBldmVudC5kZXRhaWwgOiB7fTtcblxuXHRcdGlmICggISBjb25maWcgfHwgZXZlbnRfZGV0YWlsLmNhdGFsb2dfaWQgIT09IGNvbmZpZy5pZCB8fCAhIGV2ZW50X2RldGFpbC5yZXNwb25zZSApIHtcblx0XHRcdHJldHVybjtcblx0XHR9XG5cblx0XHRjYXRhbG9nX3Jlc3BvbnNlID0gZXZlbnRfZGV0YWlsLnJlc3BvbnNlO1xuXHRcdHN5bmNocm9uaXplX2Jvb2tpbmdfcmVzb3VyY2VzX3Rvb2xiYXIoIGNvbmZpZywgY2F0YWxvZ19yZXNwb25zZSApO1xuXHRcdHJlbmRlcl9ib29raW5nX3Jlc291cmNlc19yZXNwb25zZSggY29uZmlnLCBjYXRhbG9nX3Jlc3BvbnNlICk7XG5cdFx0cmVuZGVyX2lubGluZV9iYXIoIGNvbmZpZyApO1xuXHRcdHN5bmNocm9uaXplX2lubGluZV9jb250cm9scyggY29uZmlnICk7XG5cdFx0b3Blbl9pbml0aWFsX2xhdW5jaF9pbnRlbnQoIGNvbmZpZywgY2F0YWxvZ19yZXNwb25zZSApO1xuXHRcdGlmICggaW5zcGVjdG9yX3Jlc291cmNlX2lkICkge1xuXHRcdFx0bWFya19pbnNwZWN0b3JfcmVzb3VyY2Vfcm93KCBpbnNwZWN0b3JfcmVzb3VyY2VfaWQgKTtcblx0XHR9XG5cdFx0YXBwbHlfcGVuZGluZ19oaWdobGlnaHRzKCk7XG5cdH1cblxuXHQvKipcblx0ICogUmVxdWVzdCBhIHNlbGVjdGVkIHBhZ2luYXRpb24gcGFnZSB0aHJvdWdoIHRoZSBzaGFyZWQgY29udHJvbGxlci5cblx0ICpcblx0ICogQHBhcmFtIHtNb3VzZUV2ZW50fSBldmVudCBDYXRhbG9nIGNsaWNrIGV2ZW50LlxuXHQgKiBAcmV0dXJuIHt2b2lkfVxuXHQgKi9cblx0ZnVuY3Rpb24gaGFuZGxlX2NhdGFsb2dfY2xpY2soIGV2ZW50ICkge1xuXHRcdHZhciBhY3Rpb25fYnV0dG9uID0gZXZlbnQudGFyZ2V0LmNsb3Nlc3QoICdbZGF0YS13cGJjLWJvb2tpbmctcmVzb3VyY2UtYWN0aW9uXScgKTtcblx0XHR2YXIgYWN0aW9uX2RldGFpbHM7XG5cdFx0dmFyIGNhdGFsb2dfbW91bnQ7XG5cdFx0dmFyIGNvbmZpZyA9IHdpbmRvdy53cGJjX2NhdGFsb2dfYm9va2luZ19yZXNvdXJjZXNfY29uZmlnO1xuXHRcdHZhciBwYWdlX2J1dHRvbiA9IGV2ZW50LnRhcmdldC5jbG9zZXN0KCAnW2RhdGEtd3BiYy11aS1jYXRhbG9nLXBhZ2VdJyApO1xuXHRcdHZhciByZXNvdXJjZV9hY3Rpb25fZXZlbnQ7XG5cblx0XHRpZiAoIGlubGluZV9zdGF0ZS5hY3RpdmUgJiYgYWN0aW9uX2J1dHRvbiApIHtcblx0XHRcdGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XG5cdFx0XHRyZXR1cm47XG5cdFx0fVxuXG5cdFx0aWYgKCBwYWdlX2J1dHRvbiAmJiAhIHBhZ2VfYnV0dG9uLmRpc2FibGVkICkge1xuXHRcdFx0cGVuZGluZ19mb2N1c19kaXJlY3Rpb24gPSBwYWdlX2J1dHRvbi5nZXRBdHRyaWJ1dGUoICdkYXRhLXdwYmMtdWktY2F0YWxvZy1wYWdlLWRpcmVjdGlvbicgKSB8fCAncGFnZSc7XG5cdFx0fVxuXG5cdFx0aWYgKCAhIGFjdGlvbl9idXR0b24gfHwgISBjb25maWcgKSB7XG5cdFx0XHRyZXR1cm47XG5cdFx0fVxuXHRcdHZhciBhY3Rpb25faWQgPSBhY3Rpb25fYnV0dG9uLmdldEF0dHJpYnV0ZSggJ2RhdGEtd3BiYy1ib29raW5nLXJlc291cmNlLWFjdGlvbicgKSB8fCAnJztcblx0XHRpZiAoICd0b2dnbGVfZGV0YWlscycgPT09IGFjdGlvbl9pZCApIHtcblx0XHRcdHZhciByZXNvdXJjZV9pZCA9IE51bWJlciggYWN0aW9uX2J1dHRvbi5nZXRBdHRyaWJ1dGUoICdkYXRhLXdwYmMtYm9va2luZy1yZXNvdXJjZS1pZCcgKSB8fCAwICk7XG5cdFx0XHR2YXIgcmVzb3VyY2Vfcm93ID0gZ2V0X3Jlc291cmNlX2l0ZW1fY29udGFpbmVyKCBhY3Rpb25fYnV0dG9uICk7XG5cdFx0XHRldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xuXHRcdFx0aWYgKCByZXNvdXJjZV9pZCAmJiByZXNvdXJjZV9yb3cgJiYgY29uZmlnLmRldGFpbHNfYWN0aW9uICkge1xuXHRcdFx0XHRpZiAoIHJlc291cmNlX2lkID09PSBkZXRhaWxzX3Jlc291cmNlX2lkICkge1xuXHRcdFx0XHRcdGNsb3NlX2RldGFpbHNfcm93KCB0cnVlICk7XG5cdFx0XHRcdH0gZWxzZSB7XG5cdFx0XHRcdFx0b3Blbl9kZXRhaWxzX3JvdyggY29uZmlnLCBhY3Rpb25fYnV0dG9uLCByZXNvdXJjZV9yb3csIHJlc291cmNlX2lkICk7XG5cdFx0XHRcdH1cblx0XHRcdH1cblx0XHRcdHJldHVybjtcblx0XHR9XG5cdFx0aWYgKCAnY29weV9kZXRhaWxzX3ZhbHVlJyA9PT0gYWN0aW9uX2lkICkge1xuXHRcdFx0ZXZlbnQucHJldmVudERlZmF1bHQoKTtcblx0XHRcdGNvcHlfZGV0YWlsc192YWx1ZSggYWN0aW9uX2J1dHRvbi5nZXRBdHRyaWJ1dGUoICdkYXRhLXdwYmMtYm9va2luZy1yZXNvdXJjZS1jb3B5LXZhbHVlJyApIHx8ICcnLCBhY3Rpb25fYnV0dG9uLCBjb25maWcgKTtcblx0XHRcdHJldHVybjtcblx0XHR9XG5cdFx0YWN0aW9uX2RldGFpbHMgPSBhY3Rpb25fYnV0dG9uLmNsb3Nlc3QoICdkZXRhaWxzJyApO1xuXHRcdGlmICggYWN0aW9uX2RldGFpbHMgKSB7XG5cdFx0XHRhY3Rpb25fZGV0YWlscy5yZW1vdmVBdHRyaWJ1dGUoICdvcGVuJyApO1xuXHRcdH1cblxuXHRcdGNhdGFsb2dfbW91bnQgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCggY29uZmlnLm1vdW50X2lkICk7XG5cdFx0aWYgKCBjYXRhbG9nX21vdW50ICkge1xuXHRcdFx0aWYgKCAnZnVuY3Rpb24nID09PSB0eXBlb2Ygd2luZG93LkN1c3RvbUV2ZW50ICkge1xuXHRcdFx0XHRyZXNvdXJjZV9hY3Rpb25fZXZlbnQgPSBuZXcgd2luZG93LkN1c3RvbUV2ZW50KCAnd3BiYzpib29raW5nLXJlc291cmNlLWFjdGlvbicsIHtcblx0XHRcdFx0XHRidWJibGVzOiB0cnVlLFxuXHRcdFx0XHRcdGRldGFpbDoge1xuXHRcdFx0XHRcdFx0YWN0aW9uOiBhY3Rpb25fYnV0dG9uLmdldEF0dHJpYnV0ZSggJ2RhdGEtd3BiYy1ib29raW5nLXJlc291cmNlLWFjdGlvbicgKSB8fCAnJyxcblx0XHRcdFx0XHRcdHJlc291cmNlX2lkOiBOdW1iZXIoIGFjdGlvbl9idXR0b24uZ2V0QXR0cmlidXRlKCAnZGF0YS13cGJjLWJvb2tpbmctcmVzb3VyY2UtaWQnICkgfHwgMCApXG5cdFx0XHRcdFx0fVxuXHRcdFx0XHR9ICk7XG5cdFx0XHR9IGVsc2Uge1xuXHRcdFx0XHRyZXNvdXJjZV9hY3Rpb25fZXZlbnQgPSBkb2N1bWVudC5jcmVhdGVFdmVudCggJ0N1c3RvbUV2ZW50JyApO1xuXHRcdFx0XHRyZXNvdXJjZV9hY3Rpb25fZXZlbnQuaW5pdEN1c3RvbUV2ZW50KCAnd3BiYzpib29raW5nLXJlc291cmNlLWFjdGlvbicsIHRydWUsIGZhbHNlLCB7XG5cdFx0XHRcdFx0YWN0aW9uOiBhY3Rpb25fYnV0dG9uLmdldEF0dHJpYnV0ZSggJ2RhdGEtd3BiYy1ib29raW5nLXJlc291cmNlLWFjdGlvbicgKSB8fCAnJyxcblx0XHRcdFx0XHRyZXNvdXJjZV9pZDogTnVtYmVyKCBhY3Rpb25fYnV0dG9uLmdldEF0dHJpYnV0ZSggJ2RhdGEtd3BiYy1ib29raW5nLXJlc291cmNlLWlkJyApIHx8IDAgKVxuXHRcdFx0XHR9ICk7XG5cdFx0XHR9XG5cdFx0XHRjYXRhbG9nX21vdW50LmRpc3BhdGNoRXZlbnQoIHJlc291cmNlX2FjdGlvbl9ldmVudCApO1xuXHRcdH1cblx0fVxuXG5cdC8qKlxuXHQgKiBDbG9zZSBleHBhbmRlZCBkZXRhaWxzIHdpdGggRXNjYXBlIGFuZCByZXN0b3JlIGRpc2Nsb3N1cmUgZm9jdXMuXG5cdCAqXG5cdCAqIEBwYXJhbSB7S2V5Ym9hcmRFdmVudH0gZXZlbnQgQ2F0YWxvZyBrZXlib2FyZCBldmVudC5cblx0ICogQHJldHVybiB7dm9pZH1cblx0ICovXG5cdGZ1bmN0aW9uIGhhbmRsZV9jYXRhbG9nX2tleWRvd24oIGV2ZW50ICkge1xuXHRcdHZhciBjb25maWcgPSB3aW5kb3cud3BiY19jYXRhbG9nX2Jvb2tpbmdfcmVzb3VyY2VzX2NvbmZpZztcblxuXHRcdGlmICggJ0VzY2FwZScgPT09IGV2ZW50LmtleSAmJiBpbmxpbmVfc3RhdGUuYWN0aXZlICYmICdpbmxpbmVfcmV2aWV3JyAhPT0gaW5zcGVjdG9yX21vZGUgKSB7XG5cdFx0XHRzeW5jaHJvbml6ZV9pbmxpbmVfZHJhZnRzKCBjb25maWcgKTtcblx0XHRcdGlmICggISBpbmxpbmVfc3RhdGUuY2hhbmdlZF9yb3dzLmxlbmd0aCB8fCB3aW5kb3cuY29uZmlybSggY29uZmlnLmkxOG4uaW5saW5lX2Rpc2NhcmQgfHwgJycgKSApIHtcblx0XHRcdFx0ZXZlbnQucHJldmVudERlZmF1bHQoKTtcblx0XHRcdFx0bGVhdmVfaW5saW5lX21vZGUoIGNvbmZpZywgdHJ1ZSwgJycgKTtcblx0XHRcdH1cblx0XHRcdHJldHVybjtcblx0XHR9XG5cdFx0aWYgKCAnRXNjYXBlJyA9PT0gZXZlbnQua2V5ICYmIGV2ZW50LnRhcmdldC5jbG9zZXN0KCAnW2RhdGEtd3BiYy1ib29raW5nLXJlc291cmNlLWRldGFpbHMtcm93XScgKSApIHtcblx0XHRcdGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XG5cdFx0XHRjbG9zZV9kZXRhaWxzX3JvdyggdHJ1ZSApO1xuXHRcdH1cblx0fVxuXG5cdC8qKlxuXHQgKiBCbG9jayBCb29raW5nIFJlc291cmNlIGltYWdlIGNoYW5nZXMgaW4gcHVibGljIGRlbW8gaW5zdGFsbGF0aW9ucy5cblx0ICpcblx0ICogVGhpcyBjYXB0dXJlLXBoYXNlIGd1YXJkIHJ1bnMgYmVmb3JlIHRoZSBzaGFyZWQgZGVsZWdhdGVkIG1lZGlhLXVwbG9hZGVyXG5cdCAqIGhhbmRsZXIsIHByZXZlbnRpbmcgdGhlIFdvcmRQcmVzcyBtZWRpYSBtb2RhbCBmcm9tIG9wZW5pbmcuIFNlcnZlci1zaWRlXG5cdCAqIGNyZWF0ZSB2YWxpZGF0aW9uIGluZGVwZW5kZW50bHkgcmVqZWN0cyBhIGZvcmdlZCBwaWN0dXJlIFVSTC5cblx0ICpcblx0ICogQHBhcmFtIHtNb3VzZUV2ZW50fSBldmVudCAgQnJvd3NlciBjbGljayBldmVudC5cblx0ICogQHBhcmFtIHtPYmplY3R9ICAgICBjb25maWcgQ2F0YWxvZyBjb25maWd1cmF0aW9uLlxuXHQgKiBAcmV0dXJuIHt2b2lkfVxuXHQgKi9cblx0ZnVuY3Rpb24gcHJvdGVjdF9kZW1vX3Jlc291cmNlX2ltYWdlX2NoYW5nZSggZXZlbnQsIGNvbmZpZyApIHtcblx0XHR2YXIgbWVkaWFfYnV0dG9uO1xuXHRcdHZhciBpbnNwZWN0b3JfZm9ybTtcblx0XHR2YXIgbWVzc2FnZTtcblx0XHR2YXIgbWVzc2FnZV90aXRsZTtcblxuXHRcdGlmICggISBjb25maWcgfHwgISBpc190cnVlX2ZsYWcoIGNvbmZpZy5pc19kZW1vICkgfHwgISBldmVudC50YXJnZXQgfHwgJ2Z1bmN0aW9uJyAhPT0gdHlwZW9mIGV2ZW50LnRhcmdldC5jbG9zZXN0ICkge1xuXHRcdFx0cmV0dXJuO1xuXHRcdH1cblxuXHRcdG1lZGlhX2J1dHRvbiA9IGV2ZW50LnRhcmdldC5jbG9zZXN0KCAnLndwYmNfbWVkaWFfdXBsb2FkX2J1dHRvbiwgW2RhdGEtd3BiYy1jYXRhbG9nLXJlc291cmNlLXJlbW92ZS1pbWFnZV0nICk7XG5cdFx0aW5zcGVjdG9yX2Zvcm0gPSBtZWRpYV9idXR0b24gPyBtZWRpYV9idXR0b24uY2xvc2VzdCggJ1tkYXRhLXdwYmMtY2F0YWxvZy1yZXNvdXJjZS1pbnNwZWN0b3ItZm9ybV0nICkgOiBudWxsO1xuXHRcdGlmICggISBtZWRpYV9idXR0b24gfHwgISBpbnNwZWN0b3JfZm9ybSApIHtcblx0XHRcdHJldHVybjtcblx0XHR9XG5cblx0XHRldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xuXHRcdGV2ZW50LnN0b3BQcm9wYWdhdGlvbigpO1xuXHRcdGlmICggJ2Z1bmN0aW9uJyA9PT0gdHlwZW9mIGV2ZW50LnN0b3BJbW1lZGlhdGVQcm9wYWdhdGlvbiApIHtcblx0XHRcdGV2ZW50LnN0b3BJbW1lZGlhdGVQcm9wYWdhdGlvbigpO1xuXHRcdH1cblx0XHRtZXNzYWdlID0gY29uZmlnLmkxOG4gJiYgY29uZmlnLmkxOG4uZGVtb19pbWFnZV9jaGFuZ2VfdW5hdmFpbGFibGUgPyBjb25maWcuaTE4bi5kZW1vX2ltYWdlX2NoYW5nZV91bmF2YWlsYWJsZSA6ICcnO1xuXHRcdG1lc3NhZ2VfdGl0bGUgPSBjb25maWcuaTE4biAmJiBjb25maWcuaTE4bi5kZW1vX2ltYWdlX2NoYW5nZV91bmF2YWlsYWJsZV90aXRsZSA/IGNvbmZpZy5pMThuLmRlbW9faW1hZ2VfY2hhbmdlX3VuYXZhaWxhYmxlX3RpdGxlIDogJyc7XG5cdFx0b3Blbl9ib29raW5nX3Jlc291cmNlX21lc3NhZ2VfZGlhbG9nKCBtZXNzYWdlLCBtZXNzYWdlX3RpdGxlLCBtZWRpYV9idXR0b24gKTtcblx0fVxuXG5cdC8qKlxuXHQgKiBNb3VudCB0aGUgbG9jYWxpemVkIGNhdGFsb2cgY29uZmlndXJhdGlvbiBhZnRlciB0aGUgZG9jdW1lbnQgaXMgcmVhZHkuXG5cdCAqXG5cdCAqIEByZXR1cm4ge3ZvaWR9XG5cdCAqL1xuXHRmdW5jdGlvbiBtb3VudF9ib29raW5nX3Jlc291cmNlc19jYXRhbG9nKCkge1xuXHRcdHZhciBjb25maWcgPSB3aW5kb3cud3BiY19jYXRhbG9nX2Jvb2tpbmdfcmVzb3VyY2VzX2NvbmZpZztcblx0XHR2YXIgbW91bnRfZWxlbWVudDtcblxuXHRcdGlmICggISBjb25maWcgfHwgISB3aW5kb3cud3BiY191aV9jYXRhbG9nIHx8ICdmdW5jdGlvbicgIT09IHR5cGVvZiB3aW5kb3cud3BiY191aV9jYXRhbG9nLm1vdW50ICkge1xuXHRcdFx0cmV0dXJuO1xuXHRcdH1cblxuXHRcdG1vdW50X2VsZW1lbnQgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCggY29uZmlnLm1vdW50X2lkICk7XG5cdFx0aWYgKCAhIG1vdW50X2VsZW1lbnQgKSB7XG5cdFx0XHRyZXR1cm47XG5cdFx0fVxuXG5cdFx0bW91bnRfZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKCAnd3BiYzp1aS1jYXRhbG9nLXJlbmRlcmVkJywgaGFuZGxlX2NhdGFsb2dfcmVuZGVyZWQgKTtcblx0XHRtb3VudF9lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoICd3cGJjOnVpLWNhdGFsb2ctYmVmb3JlLXJlbmRlcicsIGZ1bmN0aW9uICgpIHtcblx0XHRcdGNsb3NlX2RldGFpbHNfcm93KCBmYWxzZSApO1xuXHRcdH0gKTtcblx0XHRtb3VudF9lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoICd3cGJjOnVpLWNhdGFsb2ctaGllcmFyY2h5LWNoYW5nZScsIGZ1bmN0aW9uICgpIHtcblx0XHRcdGNsb3NlX2RldGFpbHNfcm93KCBmYWxzZSApO1xuXHRcdFx0c3luY2hyb25pemVfY2FyZF9ncm91cF9wYW5lbHMoIG1vdW50X2VsZW1lbnQgKTtcblx0XHR9ICk7XG5cdFx0bW91bnRfZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKCAnd3BiYzp1aS1jYXRhbG9nLXNlbGVjdGlvbi1jaGFuZ2UnLCBmdW5jdGlvbiAoIGV2ZW50ICkge1xuXHRcdFx0aGFuZGxlX2luc3BlY3Rvcl9zZWxlY3Rpb25fY2hhbmdlKCBldmVudCwgY29uZmlnICk7XG5cdFx0fSApO1xuXHRcdG1vdW50X2VsZW1lbnQuYWRkRXZlbnRMaXN0ZW5lciggJ3dwYmM6dWktY2F0YWxvZy1zZWxlY3Rpb24tcmVzdG9yZWQnLCBmdW5jdGlvbiAoIGV2ZW50ICkge1xuXHRcdFx0aGFuZGxlX2luc3BlY3Rvcl9zZWxlY3Rpb25fY2hhbmdlKCBldmVudCwgY29uZmlnICk7XG5cdFx0fSApO1xuXHRcdG1vdW50X2VsZW1lbnQuYWRkRXZlbnRMaXN0ZW5lciggJ2NsaWNrJywgcHJvdGVjdF9pbmxpbmVfZHJhZnRzX2Zyb21fY2F0YWxvZ19jb250cm9scywgdHJ1ZSApO1xuXHRcdG1vdW50X2VsZW1lbnQuYWRkRXZlbnRMaXN0ZW5lciggJ2NoYW5nZScsIHByb3RlY3RfaW5saW5lX2RyYWZ0c19mcm9tX2NhdGFsb2dfY29udHJvbHMsIHRydWUgKTtcblx0XHRtb3VudF9lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoICdpbnB1dCcsIHByb3RlY3RfaW5saW5lX2RyYWZ0c19mcm9tX2NhdGFsb2dfY29udHJvbHMsIHRydWUgKTtcblx0XHRtb3VudF9lbGVtZW50LmFkZEV2ZW50TGlzdGVuZXIoICdjbGljaycsIGhhbmRsZV9jYXRhbG9nX2NsaWNrICk7XG5cdFx0bW91bnRfZWxlbWVudC5hZGRFdmVudExpc3RlbmVyKCAna2V5ZG93bicsIGhhbmRsZV9jYXRhbG9nX2tleWRvd24gKTtcblx0XHR3aW5kb3cuYWRkRXZlbnRMaXN0ZW5lciggJ3Jlc2l6ZScsIGZ1bmN0aW9uICgpIHtcblx0XHRcdHN5bmNocm9uaXplX292ZXJmbG93X3Rvb2x0aXBzKCBtb3VudF9lbGVtZW50ICk7XG5cdFx0fSApO1xuXHRcdGNhdGFsb2dfY29udHJvbGxlciA9IHdpbmRvdy53cGJjX3VpX2NhdGFsb2cubW91bnQoIGNvbmZpZyApO1xuXHRcdGlmICggY2F0YWxvZ19jb250cm9sbGVyICkge1xuXHRcdFx0aWYgKCAnZnVuY3Rpb24nID09PSB0eXBlb2Ygd2luZG93LndwYmNfdWlfY2F0YWxvZy5jcmVhdGVfaW5saW5lX2VkaXRpbmdfd29ya2Zsb3cgKSB7XG5cdFx0XHRcdGlubGluZV93b3JrZmxvd19jb250cm9sbGVyID0gd2luZG93LndwYmNfdWlfY2F0YWxvZy5jcmVhdGVfaW5saW5lX2VkaXRpbmdfd29ya2Zsb3coIG1vdW50X2VsZW1lbnQsIHtcblx0XHRcdFx0XHRiYXJfc2VsZWN0b3I6ICdbZGF0YS13cGJjLWNhdGFsb2ctaW5saW5lLWJhcl0nLFxuXHRcdFx0XHRcdGNhbmNlbF9zZWxlY3RvcjogJ1tkYXRhLXdwYmMtY2F0YWxvZy1pbmxpbmUtY2FuY2VsXScsXG5cdFx0XHRcdFx0Y29udHJvbHNfcm9vdDogZG9jdW1lbnQsXG5cdFx0XHRcdFx0Y291bnRfc2VsZWN0b3I6ICdbZGF0YS13cGJjLWNhdGFsb2ctaW5saW5lLWNvdW50XScsXG5cdFx0XHRcdFx0cGFnZV9lbGVtZW50OiBtb3VudF9lbGVtZW50Lm1hdGNoZXMoICcud3BiY19ib29raW5nX3Jlc291cmNlc19wYWdlJyApID8gbW91bnRfZWxlbWVudCA6IG1vdW50X2VsZW1lbnQucXVlcnlTZWxlY3RvciggJy53cGJjX2Jvb2tpbmdfcmVzb3VyY2VzX3BhZ2UnICksXG5cdFx0XHRcdFx0cHJvdGVjdGVkX3NlbGVjdG9yOiAnW2RhdGEtd3BiYy1jYXRhbG9nLWJvb2tpbmctcmVzb3VyY2UtY3JlYXRlXScsXG5cdFx0XHRcdFx0cmV2aWV3X3NlbGVjdG9yOiAnW2RhdGEtd3BiYy1jYXRhbG9nLWlubGluZS1yZXZpZXddJyxcblx0XHRcdFx0XHR0b2dnbGVfbGFiZWxfc2VsZWN0b3I6ICdbZGF0YS13cGJjLWNhdGFsb2ctaW5saW5lLXRvZ2dsZS1sYWJlbF0nLFxuXHRcdFx0XHRcdHRvZ2dsZV9zZWxlY3RvcjogJ1tkYXRhLXdwYmMtY2F0YWxvZy1pbmxpbmUtdG9nZ2xlXSdcblx0XHRcdFx0fSApO1xuXHRcdFx0fVxuXHRcdFx0cmVuZGVyX2Jvb2tpbmdfcmVzb3VyY2VzX2ZpbHRlcnMoIGNvbmZpZyApO1xuXHRcdFx0cmVuZGVyX2Jvb2tpbmdfcmVzb3VyY2VzX3Rvb2xiYXIoIGNvbmZpZyApO1xuXHRcdFx0cmVuZGVyX2lubGluZV9iYXIoIGNvbmZpZyApO1xuXHRcdFx0c3luY2hyb25pemVfaW5saW5lX2NvbnRyb2xzKCBjb25maWcgKTtcblx0XHRcdG1vdW50X2luc3BlY3Rvcl9zaGVsbCggY29uZmlnICk7XG5cdFx0fVxuXHRcdGlmICggJ2Z1bmN0aW9uJyA9PT0gdHlwZW9mIHdpbmRvdy53cGJjX2RlZmluZV90aXBweV90b29sdGlwcyApIHtcblx0XHRcdHdpbmRvdy53cGJjX2RlZmluZV90aXBweV90b29sdGlwcyggJ1tkYXRhLXdwYmMtY2F0YWxvZy1ib29raW5nLXJlc291cmNlLXVwZ3JhZGVdJyApO1xuXHRcdH1cblxuXHRcdGRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoICd3cGJjOmJvb2tpbmctcmVzb3VyY2UtYWN0aW9uJywgZnVuY3Rpb24gKCBldmVudCApIHtcblx0XHRcdHZhciBkZXRhaWwgPSBldmVudCAmJiBldmVudC5kZXRhaWwgPyBldmVudC5kZXRhaWwgOiB7fTtcblx0XHRcdGlmICggJ2VkaXRfcmVzb3VyY2UnID09PSBkZXRhaWwuYWN0aW9uICkge1xuXHRcdFx0XHRvcGVuX2luc3BlY3RvciggY29uZmlnLCAnZWRpdCcsIE51bWJlciggZGV0YWlsLnJlc291cmNlX2lkICkgfHwgMCwgZG9jdW1lbnQuYWN0aXZlRWxlbWVudCApO1xuXHRcdFx0fSBlbHNlIGlmICggJ3B1Ymxpc2hfcmVzb3VyY2UnID09PSBkZXRhaWwuYWN0aW9uICkge1xuXHRcdFx0XHRvcGVuX2luc3BlY3RvciggY29uZmlnLCAnZWRpdCcsIE51bWJlciggZGV0YWlsLnJlc291cmNlX2lkICkgfHwgMCwgZG9jdW1lbnQuYWN0aXZlRWxlbWVudCwgJ3Nob3J0Y29kZV9wdWJsaXNoaW5nJyApO1xuXHRcdFx0fSBlbHNlIGlmICggJ2FkanVzdF9jYXBhY2l0eScgPT09IGRldGFpbC5hY3Rpb24gKSB7XG5cdFx0XHRcdG9wZW5fY2FwYWNpdHlfZWRpdG9yKCBjb25maWcsIE51bWJlciggZGV0YWlsLnJlc291cmNlX2lkICkgfHwgMCwgZG9jdW1lbnQuYWN0aXZlRWxlbWVudCApO1xuXHRcdFx0fSBlbHNlIGlmICggJ2RlbGV0ZV9yZXNvdXJjZScgPT09IGRldGFpbC5hY3Rpb24gKSB7XG5cdFx0XHRcdG9wZW5fZGVsZXRlX3JldmlldyggY29uZmlnLCBbIE51bWJlciggZGV0YWlsLnJlc291cmNlX2lkICkgfHwgMCBdLCBkb2N1bWVudC5hY3RpdmVFbGVtZW50LCBmYWxzZSApO1xuXHRcdFx0fVxuXHRcdH0gKTtcblx0XHRkb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKCAnY2xpY2snLCBmdW5jdGlvbiAoIGV2ZW50ICkge1xuXHRcdFx0cHJvdGVjdF9kZW1vX3Jlc291cmNlX2ltYWdlX2NoYW5nZSggZXZlbnQsIGNvbmZpZyApO1xuXHRcdH0sIHRydWUgKTtcblx0XHRkb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKCAnY2xpY2snLCBmdW5jdGlvbiAoIGV2ZW50ICkge1xuXHRcdFx0dmFyIGlubGluZV90b2dnbGUgPSBldmVudC50YXJnZXQuY2xvc2VzdCggJ1tkYXRhLXdwYmMtY2F0YWxvZy1pbmxpbmUtdG9nZ2xlXScgKTtcblx0XHRcdHZhciBpbmxpbmVfY2FuY2VsID0gZXZlbnQudGFyZ2V0LmNsb3Nlc3QoICdbZGF0YS13cGJjLWNhdGFsb2ctaW5saW5lLWNhbmNlbF0nICk7XG5cdFx0XHR2YXIgaW5saW5lX3JldmlldyA9IGV2ZW50LnRhcmdldC5jbG9zZXN0KCAnW2RhdGEtd3BiYy1jYXRhbG9nLWlubGluZS1yZXZpZXddJyApO1xuXHRcdFx0dmFyIGNyZWF0ZV9idXR0b24gPSBldmVudC50YXJnZXQuY2xvc2VzdCggJ1tkYXRhLXdwYmMtY2F0YWxvZy1ib29raW5nLXJlc291cmNlLWNyZWF0ZV0nICk7XG5cdFx0XHR2YXIgdXBncmFkZV9idXR0b24gPSBldmVudC50YXJnZXQuY2xvc2VzdCggJ1tkYXRhLXdwYmMtY2F0YWxvZy1ib29raW5nLXJlc291cmNlLXVwZ3JhZGVdJyApO1xuXHRcdFx0dmFyIGNhbmNlbF9idXR0b24gPSBldmVudC50YXJnZXQuY2xvc2VzdCggJ1tkYXRhLXdwYmMtdWktY2F0YWxvZy1pbnNwZWN0b3ItY2FuY2VsXScgKTtcblx0XHRcdHZhciByZW1vdmVfaW1hZ2VfYnV0dG9uID0gZXZlbnQudGFyZ2V0LmNsb3Nlc3QoICdbZGF0YS13cGJjLWNhdGFsb2ctcmVzb3VyY2UtcmVtb3ZlLWltYWdlXScgKTtcblx0XHRcdHZhciBzaG9ydGNvZGVfYnV0dG9uID0gZXZlbnQudGFyZ2V0LmNsb3Nlc3QoICdbZGF0YS13cGJjLWJvb2tpbmctcmVzb3VyY2Utc2hvcnRjb2RlLWNvbW1hbmRdJyApO1xuXHRcdFx0dmFyIHJlc291cmNlX3JvdyA9IGdldF9yZXNvdXJjZV9pdGVtX2NvbnRhaW5lciggZXZlbnQudGFyZ2V0ICk7XG5cdFx0XHR2YXIgc2VsZWN0aW9uX2FjdGlvbiA9IGV2ZW50LnRhcmdldC5jbG9zZXN0KCAnW2RhdGEtd3BiYy1jYXRhbG9nLXNlbGVjdGlvbi1hY3Rpb25dJyApO1xuXHRcdFx0aWYgKCBpbmxpbmVfdG9nZ2xlICkge1xuXHRcdFx0XHRldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xuXHRcdFx0XHRzdGFydF9pbmxpbmVfbW9kZSggY29uZmlnICk7XG5cdFx0XHRcdHJldHVybjtcblx0XHRcdH1cblx0XHRcdGlmICggaW5saW5lX2NhbmNlbCApIHtcblx0XHRcdFx0ZXZlbnQucHJldmVudERlZmF1bHQoKTtcblx0XHRcdFx0c3luY2hyb25pemVfaW5saW5lX2RyYWZ0cyggY29uZmlnICk7XG5cdFx0XHRcdGlmICggISBpbmxpbmVfc3RhdGUuY2hhbmdlZF9yb3dzLmxlbmd0aCB8fCB3aW5kb3cuY29uZmlybSggY29uZmlnLmkxOG4uaW5saW5lX2Rpc2NhcmQgfHwgJycgKSApIHtcblx0XHRcdFx0XHRsZWF2ZV9pbmxpbmVfbW9kZSggY29uZmlnLCB0cnVlLCAnJyApO1xuXHRcdFx0XHR9XG5cdFx0XHRcdHJldHVybjtcblx0XHRcdH1cblx0XHRcdGlmICggaW5saW5lX3JldmlldyApIHtcblx0XHRcdFx0ZXZlbnQucHJldmVudERlZmF1bHQoKTtcblx0XHRcdFx0cHJldmlld19pbmxpbmVfY2hhbmdlcyggY29uZmlnLCBpbmxpbmVfcmV2aWV3ICk7XG5cdFx0XHRcdHJldHVybjtcblx0XHRcdH1cblx0XHRcdGlmICggc2VsZWN0aW9uX2FjdGlvbiApIHtcblx0XHRcdFx0ZXZlbnQucHJldmVudERlZmF1bHQoKTtcblx0XHRcdFx0aWYgKCAnYnVsa19lZGl0JyA9PT0gc2VsZWN0aW9uX2FjdGlvbi5nZXRBdHRyaWJ1dGUoICdkYXRhLXdwYmMtY2F0YWxvZy1zZWxlY3Rpb24tYWN0aW9uJyApICkge1xuXHRcdFx0XHRcdG9wZW5fYnVsa19lZGl0b3IoIGNvbmZpZywgc2VsZWN0aW9uX2FjdGlvbiApO1xuXHRcdFx0XHR9IGVsc2Uge1xuXHRcdFx0XHRcdG9wZW5fZGVsZXRlX3JldmlldyggY29uZmlnLCBnZXRfc2VsZWN0ZWRfcmVzb3VyY2VfaWRzKCBjb25maWcgKSwgc2VsZWN0aW9uX2FjdGlvbiwgdHJ1ZSApO1xuXHRcdFx0XHR9XG5cdFx0XHRcdHJldHVybjtcblx0XHRcdH1cblx0XHRcdGlmICggc2hvcnRjb2RlX2J1dHRvbiApIHtcblx0XHRcdFx0ZXZlbnQucHJldmVudERlZmF1bHQoKTtcblx0XHRcdFx0dmFyIHNob3J0Y29kZV9yZXNvdXJjZV9pZCA9IE51bWJlciggc2hvcnRjb2RlX2J1dHRvbi5nZXRBdHRyaWJ1dGUoICdkYXRhLXdwYmMtYm9va2luZy1yZXNvdXJjZS1pZCcgKSB8fCAwICk7XG5cdFx0XHRcdHZhciBzaG9ydGNvZGVfY29tbWFuZCA9IHNob3J0Y29kZV9idXR0b24uZ2V0QXR0cmlidXRlKCAnZGF0YS13cGJjLWJvb2tpbmctcmVzb3VyY2Utc2hvcnRjb2RlLWNvbW1hbmQnICkgfHwgJyc7XG5cdFx0XHRcdHZhciBzaG9ydGNvZGVfdmFsdWUgPSBnZXRfYm9va2luZ19yZXNvdXJjZV9zaG9ydGNvZGUoIHNob3J0Y29kZV9yZXNvdXJjZV9pZCwgc2hvcnRjb2RlX2J1dHRvbiApO1xuXHRcdFx0XHRpZiAoICdjb3B5JyA9PT0gc2hvcnRjb2RlX2NvbW1hbmQgKSB7XG5cdFx0XHRcdFx0Y29weV9kZXRhaWxzX3ZhbHVlKCBzaG9ydGNvZGVfdmFsdWUsIHNob3J0Y29kZV9idXR0b24sIGNvbmZpZyApO1xuXHRcdFx0XHR9IGVsc2UgaWYgKCAnY3VzdG9taXplJyA9PT0gc2hvcnRjb2RlX2NvbW1hbmQgKSB7XG5cdFx0XHRcdFx0Y3VzdG9taXplX2Jvb2tpbmdfcmVzb3VyY2Vfc2hvcnRjb2RlKCBzaG9ydGNvZGVfcmVzb3VyY2VfaWQsIHNob3J0Y29kZV92YWx1ZSApO1xuXHRcdFx0XHR9IGVsc2UgaWYgKCAncHVibGlzaCcgPT09IHNob3J0Y29kZV9jb21tYW5kICkge1xuXHRcdFx0XHRcdHB1Ymxpc2hfYm9va2luZ19yZXNvdXJjZV9zaG9ydGNvZGUoIHNob3J0Y29kZV9yZXNvdXJjZV9pZCwgc2hvcnRjb2RlX3ZhbHVlLCBzaG9ydGNvZGVfYnV0dG9uICk7XG5cdFx0XHRcdH1cblx0XHRcdFx0cmV0dXJuO1xuXHRcdFx0fVxuXHRcdFx0aWYgKCBjcmVhdGVfYnV0dG9uICkge1xuXHRcdFx0XHRldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xuXHRcdFx0XHRvcGVuX2luc3BlY3RvciggY29uZmlnLCAnY3JlYXRlJywgMCwgY3JlYXRlX2J1dHRvbiApO1xuXHRcdFx0XHRyZXR1cm47XG5cdFx0XHR9XG5cdFx0XHRpZiAoIHVwZ3JhZGVfYnV0dG9uICkge1xuXHRcdFx0XHRldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xuXHRcdFx0XHRvcGVuX2Jvb2tpbmdfcmVzb3VyY2VfdXBncmFkZV9kaWFsb2coIHVwZ3JhZGVfYnV0dG9uICk7XG5cdFx0XHRcdHJldHVybjtcblx0XHRcdH1cblx0XHRcdGlmICggY2FuY2VsX2J1dHRvbiApIHtcblx0XHRcdFx0ZXZlbnQucHJldmVudERlZmF1bHQoKTtcblx0XHRcdFx0aWYgKCAnaW5saW5lX3JldmlldycgPT09IGluc3BlY3Rvcl9tb2RlICkge1xuXHRcdFx0XHRcdGluc3BlY3Rvcl9kaXJ0eSA9IGZhbHNlO1xuXHRcdFx0XHRcdGNsb3NlX2luc3BlY3RvciggY29uZmlnLCBmYWxzZSApO1xuXHRcdFx0XHRcdGlubGluZV9zdGF0ZS5yZXZpZXdfdG9rZW4gPSAnJztcblx0XHRcdFx0XHRzeW5jaHJvbml6ZV9pbmxpbmVfY29udHJvbHMoIGNvbmZpZyApO1xuXHRcdFx0XHR9IGVsc2UgaWYgKCAnY2FwYWNpdHlfcmV2aWV3JyA9PT0gaW5zcGVjdG9yX21vZGUgJiYgaW5zcGVjdG9yX2NhcGFjaXR5X2NvbnRleHQgKSB7XG5cdFx0XHRcdFx0dmFyIHJldmlld2VkX3RhcmdldF9jYXBhY2l0eSA9IGluc3BlY3Rvcl9jYXBhY2l0eV90YXJnZXQ7XG5cdFx0XHRcdFx0dmFyIHJldmlld2VkX2RldGFjaF9pZHMgPSBpbnNwZWN0b3JfY2FwYWNpdHlfZGV0YWNoX2lkcy5zbGljZSgpO1xuXHRcdFx0XHRcdHZhciByZXZpZXdlZF9kZWNyZWFzZV9hY3Rpb24gPSBpbnNwZWN0b3JfY2FwYWNpdHlfZGVjcmVhc2VfYWN0aW9uO1xuXHRcdFx0XHRcdGluc3BlY3Rvcl9kaXJ0eSA9IGZhbHNlO1xuXHRcdFx0XHRcdHJlbmRlcl9jYXBhY2l0eV9lZGl0b3IoIGNvbmZpZywgaW5zcGVjdG9yX2NhcGFjaXR5X2NvbnRleHQgKTtcblx0XHRcdFx0XHRpbnNwZWN0b3JfY2FwYWNpdHlfdGFyZ2V0ID0gcmV2aWV3ZWRfdGFyZ2V0X2NhcGFjaXR5O1xuXHRcdFx0XHRcdGluc3BlY3Rvcl9jYXBhY2l0eV9kZXRhY2hfaWRzID0gcmV2aWV3ZWRfZGV0YWNoX2lkcztcblx0XHRcdFx0XHRpbnNwZWN0b3JfY2FwYWNpdHlfZGVjcmVhc2VfYWN0aW9uID0gcmV2aWV3ZWRfZGVjcmVhc2VfYWN0aW9uO1xuXHRcdFx0XHRcdHN5bmNocm9uaXplX2NhcGFjaXR5X2VkaXRvciggY29uZmlnICk7XG5cdFx0XHRcdH0gZWxzZSB7XG5cdFx0XHRcdFx0Y2xvc2VfaW5zcGVjdG9yKCBjb25maWcsIHRydWUgKTtcblx0XHRcdFx0fVxuXHRcdFx0XHRyZXR1cm47XG5cdFx0XHR9XG5cdFx0XHRpZiAoIHJlbW92ZV9pbWFnZV9idXR0b24gKSB7XG5cdFx0XHRcdGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XG5cdFx0XHRcdHZhciBpbWFnZV9maWVsZCA9IHJlbW92ZV9pbWFnZV9idXR0b24uY2xvc2VzdCggJ1tkYXRhLXdwYmMtY2F0YWxvZy1yZXNvdXJjZS1maWVsZC13cmFwXScgKS5xdWVyeVNlbGVjdG9yKCAnW2RhdGEtd3BiYy1jYXRhbG9nLXJlc291cmNlLWZpZWxkPVwicGljdHVyZV91cmxcIl0nICk7XG5cdFx0XHRcdGlmICggaW1hZ2VfZmllbGQgKSB7XG5cdFx0XHRcdFx0aW1hZ2VfZmllbGQudmFsdWUgPSAnJztcblx0XHRcdFx0XHRzeW5jaHJvbml6ZV9pbnNwZWN0b3JfaW1hZ2UoIGltYWdlX2ZpZWxkICk7XG5cdFx0XHRcdFx0c3luY2hyb25pemVfaW5zcGVjdG9yX2RpcnR5X3N0YXRlKCk7XG5cdFx0XHRcdH1cblx0XHRcdFx0cmV0dXJuO1xuXHRcdFx0fVxuXHRcdFx0aWYgKCAhIGlubGluZV9zdGF0ZS5hY3RpdmUgJiYgcmVzb3VyY2Vfcm93ICYmICEgZXZlbnQudGFyZ2V0LmNsb3Nlc3QoICdhLCBidXR0b24sIGlucHV0LCBzZWxlY3QsIHRleHRhcmVhLCBzdW1tYXJ5LCBkZXRhaWxzLCBsYWJlbCcgKSApIHtcblx0XHRcdFx0b3Blbl9pbnNwZWN0b3IoIGNvbmZpZywgJ2VkaXQnLCBOdW1iZXIoIHJlc291cmNlX3Jvdy5nZXRBdHRyaWJ1dGUoICdkYXRhLXdwYmMtYm9va2luZy1yZXNvdXJjZS1pZCcgKSApIHx8IDAsIHJlc291cmNlX3JvdyApO1xuXHRcdFx0fVxuXHRcdH0gKTtcblx0XHRkb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKCAnaW5wdXQnLCBmdW5jdGlvbiAoIGV2ZW50ICkge1xuXHRcdFx0aWYgKCBldmVudC50YXJnZXQubWF0Y2hlcyggJ1tkYXRhLXdwYmMtY2F0YWxvZy1jYXBhY2l0eS10YXJnZXRdLCBbZGF0YS13cGJjLWNhdGFsb2ctY2FwYWNpdHktcmFuZ2VdJyApICkge1xuXHRcdFx0XHR2YXIgY29udGV4dCA9IGluc3BlY3Rvcl9jYXBhY2l0eV9jb250ZXh0IHx8IHt9O1xuXHRcdFx0XHR2YXIgbWluaW11bSA9IE51bWJlciggY29udGV4dC5taW5pbXVtX2NhcGFjaXR5ICkgfHwgMTtcblx0XHRcdFx0dmFyIG1heGltdW0gPSBOdW1iZXIoIGNvbnRleHQubWF4aW11bV9jYXBhY2l0eSApIHx8IG1pbmltdW07XG5cdFx0XHRcdHZhciByZXF1ZXN0ZWRfY2FwYWNpdHkgPSBNYXRoLnJvdW5kKCBOdW1iZXIoIGV2ZW50LnRhcmdldC52YWx1ZSApIHx8IG1pbmltdW0gKTtcblxuXHRcdFx0XHRpbnNwZWN0b3JfY2FwYWNpdHlfdGFyZ2V0ID0gTWF0aC5tYXgoIG1pbmltdW0sIE1hdGgubWluKCBtYXhpbXVtLCByZXF1ZXN0ZWRfY2FwYWNpdHkgKSApO1xuXHRcdFx0XHRzeW5jaHJvbml6ZV9jYXBhY2l0eV9lZGl0b3IoIGNvbmZpZyApO1xuXHRcdFx0XHRyZXR1cm47XG5cdFx0XHR9XG5cdFx0XHRpZiAoIGV2ZW50LnRhcmdldC5tYXRjaGVzKCAnW2RhdGEtd3BiYy1jYXRhbG9nLWlubGluZS1maWVsZF0nICkgKSB7XG5cdFx0XHRcdHN5bmNocm9uaXplX2lubGluZV9kcmFmdHMoIGNvbmZpZyApO1xuXHRcdFx0XHRyZXR1cm47XG5cdFx0XHR9XG5cdFx0XHRpZiAoIGV2ZW50LnRhcmdldC5tYXRjaGVzKCAnW2RhdGEtd3BiYy1jYXRhbG9nLXJlc291cmNlLWJ1bGstdmFsdWVdLCBbZGF0YS13cGJjLWNhdGFsb2ctcmVzb3VyY2UtYnVsay1yYW5nZV0nICkgKSB7XG5cdFx0XHRcdHN5bmNocm9uaXplX2J1bGtfZWRpdG9yKCBldmVudC50YXJnZXQgKTtcblx0XHRcdFx0cmV0dXJuO1xuXHRcdFx0fVxuXHRcdFx0aWYgKCBldmVudC50YXJnZXQubWF0Y2hlcyggJ1tkYXRhLXdwYmMtY2F0YWxvZy1yZXNvdXJjZS1yYW5nZV0nICkgKSB7XG5cdFx0XHRcdHN5bmNocm9uaXplX2luc3BlY3Rvcl9udW1iZXJfZnJvbV9yYW5nZSggZXZlbnQudGFyZ2V0ICk7XG5cdFx0XHRcdHJldHVybjtcblx0XHRcdH1cblx0XHRcdGlmICggZXZlbnQudGFyZ2V0Lm1hdGNoZXMoICdbZGF0YS13cGJjLWNhdGFsb2ctcmVzb3VyY2UtaW5zcGVjdG9yLWZvcm1dIFtkYXRhLXdwYmMtY2F0YWxvZy1yZXNvdXJjZS1maWVsZF0nICkgKSB7XG5cdFx0XHRcdG1hcmtfY2hhbmdlb3Zlcl9wb2xpY3lfdG91Y2hlZCggZXZlbnQudGFyZ2V0ICk7XG5cdFx0XHRcdGlmICggJ3BpY3R1cmVfdXJsJyA9PT0gZXZlbnQudGFyZ2V0LmdldEF0dHJpYnV0ZSggJ2RhdGEtd3BiYy1jYXRhbG9nLXJlc291cmNlLWZpZWxkJyApICkge1xuXHRcdFx0XHRcdHN5bmNocm9uaXplX2luc3BlY3Rvcl9pbWFnZSggZXZlbnQudGFyZ2V0ICk7XG5cdFx0XHRcdH1cblx0XHRcdFx0aWYgKCAnbnVtYmVyJyA9PT0gZXZlbnQudGFyZ2V0LnR5cGUgKSB7XG5cdFx0XHRcdFx0c3luY2hyb25pemVfaW5zcGVjdG9yX251bWVyaWNfcmFuZ2UoIGV2ZW50LnRhcmdldC5nZXRBdHRyaWJ1dGUoICdkYXRhLXdwYmMtY2F0YWxvZy1yZXNvdXJjZS1maWVsZCcgKSB8fCAnJyApO1xuXHRcdFx0XHR9XG5cdFx0XHRcdHN5bmNocm9uaXplX2luc3BlY3Rvcl9kaXJ0eV9zdGF0ZSgpO1xuXHRcdFx0fVxuXHRcdH0gKTtcblx0XHRkb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKCAnY2hhbmdlJywgZnVuY3Rpb24gKCBldmVudCApIHtcblx0XHRcdGlmICggZ2V0X2RlbGV0ZV9yZXZpZXdfd29ya2Zsb3coKSAmJiBnZXRfZGVsZXRlX3Jldmlld193b3JrZmxvdygpLmhhbmRsZV9jaGFuZ2UoIGV2ZW50ICkgKSB7XG5cdFx0XHRcdHJldHVybjtcblx0XHRcdH1cblx0XHRcdGlmICggZXZlbnQudGFyZ2V0Lm1hdGNoZXMoICdbZGF0YS13cGJjLWNhdGFsb2ctY2FwYWNpdHktZGVjcmVhc2UtYWN0aW9uXScgKSApIHtcblx0XHRcdFx0aW5zcGVjdG9yX2NhcGFjaXR5X2RlY3JlYXNlX2FjdGlvbiA9ICdkZWxldGUnID09PSBldmVudC50YXJnZXQudmFsdWUgPyAnZGVsZXRlJyA6ICdkZXRhY2gnO1xuXHRcdFx0XHRzeW5jaHJvbml6ZV9jYXBhY2l0eV9lZGl0b3IoIGNvbmZpZyApO1xuXHRcdFx0XHRyZXR1cm47XG5cdFx0XHR9XG5cdFx0XHRpZiAoIGV2ZW50LnRhcmdldC5tYXRjaGVzKCAnW2RhdGEtd3BiYy1jYXRhbG9nLWNhcGFjaXR5LWRldGFjaF0nICkgKSB7XG5cdFx0XHRcdHZhciBkZXRhY2hfaWQgPSBOdW1iZXIoIGV2ZW50LnRhcmdldC52YWx1ZSApIHx8IDA7XG5cdFx0XHRcdGlmICggZXZlbnQudGFyZ2V0LmNoZWNrZWQgKSB7XG5cdFx0XHRcdFx0aWYgKCAtMSA9PT0gaW5zcGVjdG9yX2NhcGFjaXR5X2RldGFjaF9pZHMuaW5kZXhPZiggZGV0YWNoX2lkICkgKSB7XG5cdFx0XHRcdFx0XHRpbnNwZWN0b3JfY2FwYWNpdHlfZGV0YWNoX2lkcy5wdXNoKCBkZXRhY2hfaWQgKTtcblx0XHRcdFx0XHR9XG5cdFx0XHRcdH0gZWxzZSB7XG5cdFx0XHRcdFx0aW5zcGVjdG9yX2NhcGFjaXR5X2RldGFjaF9pZHMgPSBpbnNwZWN0b3JfY2FwYWNpdHlfZGV0YWNoX2lkcy5maWx0ZXIoIGZ1bmN0aW9uICggcmVzb3VyY2VfaWQgKSB7IHJldHVybiByZXNvdXJjZV9pZCAhPT0gZGV0YWNoX2lkOyB9ICk7XG5cdFx0XHRcdH1cblx0XHRcdFx0c3luY2hyb25pemVfY2FwYWNpdHlfZWRpdG9yKCBjb25maWcgKTtcblx0XHRcdFx0cmV0dXJuO1xuXHRcdFx0fVxuXHRcdFx0aWYgKCBldmVudC50YXJnZXQubWF0Y2hlcyggJ1tkYXRhLXdwYmMtY2F0YWxvZy1pbmxpbmUtZmllbGRdJyApICkge1xuXHRcdFx0XHRzeW5jaHJvbml6ZV9pbmxpbmVfZHJhZnRzKCBjb25maWcgKTtcblx0XHRcdFx0cmV0dXJuO1xuXHRcdFx0fVxuXHRcdFx0aWYgKCBldmVudC50YXJnZXQubWF0Y2hlcyggJ1tkYXRhLXdwYmMtY2F0YWxvZy1yZXNvdXJjZS1idWxrLWVuYWJsZV0sIFtkYXRhLXdwYmMtY2F0YWxvZy1yZXNvdXJjZS1idWxrLW9wZXJhdGlvbl0sIFtkYXRhLXdwYmMtY2F0YWxvZy1yZXNvdXJjZS1idWxrLXZhbHVlXSwgW2RhdGEtd3BiYy1jYXRhbG9nLXJlc291cmNlLWJ1bGstcmFuZ2VdJyApICkge1xuXHRcdFx0XHRzeW5jaHJvbml6ZV9idWxrX2VkaXRvciggZXZlbnQudGFyZ2V0ICk7XG5cdFx0XHRcdHJldHVybjtcblx0XHRcdH1cblx0XHRcdGlmICggZXZlbnQudGFyZ2V0Lm1hdGNoZXMoICdbZGF0YS13cGJjLWNhdGFsb2ctY2FwYWNpdHktZGVsZXRlLWFja25vd2xlZGdlbWVudF0nICkgKSB7XG5cdFx0XHRcdHZhciBjYXBhY2l0eV9kZWxldGVfYnV0dG9uID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtdWktY2F0YWxvZy1pbnNwZWN0b3Itc2F2ZV0nICk7XG5cdFx0XHRcdHZhciBjYXBhY2l0eV9hY2tub3dsZWRnZW1lbnQgPSBldmVudC50YXJnZXQuY2xvc2VzdCggJy53cGJjX2Jvb2tpbmdfcmVzb3VyY2VzX19kZWxldGVfYWNrbm93bGVkZ2VtZW50JyApO1xuXG5cdFx0XHRcdGlmICggZXZlbnQudGFyZ2V0LmNoZWNrZWQgJiYgY2FwYWNpdHlfYWNrbm93bGVkZ2VtZW50ICkge1xuXHRcdFx0XHRcdGNhcGFjaXR5X2Fja25vd2xlZGdlbWVudC5jbGFzc0xpc3QucmVtb3ZlKCAnd3BiY19ib29raW5nX3Jlc291cmNlc19fZGVsZXRlX2Fja25vd2xlZGdlbWVudC0tYXR0ZW50aW9uJyApO1xuXHRcdFx0XHR9IGVsc2Uge1xuXHRcdFx0XHRcdHB1bHNlX2RlbGV0ZV9hY2tub3dsZWRnZW1lbnQoIGNhcGFjaXR5X2Fja25vd2xlZGdlbWVudCApO1xuXHRcdFx0XHR9XG5cdFx0XHRcdGlmICggY2FwYWNpdHlfZGVsZXRlX2J1dHRvbiApIHtcblx0XHRcdFx0XHRjYXBhY2l0eV9kZWxldGVfYnV0dG9uLmRpc2FibGVkID0gISBldmVudC50YXJnZXQuY2hlY2tlZDtcblx0XHRcdFx0fVxuXHRcdFx0XHRyZXR1cm47XG5cdFx0XHR9XG5cdFx0XHRpZiAoIGV2ZW50LnRhcmdldC5tYXRjaGVzKCAnW2RhdGEtd3BiYy1jYXRhbG9nLXJlc291cmNlLXJhbmdlXScgKSApIHtcblx0XHRcdFx0c3luY2hyb25pemVfaW5zcGVjdG9yX251bWJlcl9mcm9tX3JhbmdlKCBldmVudC50YXJnZXQgKTtcblx0XHRcdFx0cmV0dXJuO1xuXHRcdFx0fVxuXHRcdFx0aWYgKCBldmVudC50YXJnZXQubWF0Y2hlcyggJ1tkYXRhLXdwYmMtY2F0YWxvZy1yZXNvdXJjZS1yYWRpby1maWVsZD1cImNyZWF0aW9uX21vZGVcIl0nICkgKSB7XG5cdFx0XHRcdHN5bmNocm9uaXplX2NyZWF0ZV9pbnNwZWN0b3JfY29udHJvbHMoKTtcblx0XHRcdFx0c3luY2hyb25pemVfaW5zcGVjdG9yX2RpcnR5X3N0YXRlKCk7XG5cdFx0XHRcdHJldHVybjtcblx0XHRcdH1cblx0XHRcdGlmICggZXZlbnQudGFyZ2V0Lm1hdGNoZXMoICdbZGF0YS13cGJjLWNhdGFsb2ctcmVzb3VyY2UtaW5zcGVjdG9yLWZvcm1dIFtkYXRhLXdwYmMtY2F0YWxvZy1yZXNvdXJjZS1maWVsZF0nICkgKSB7XG5cdFx0XHRcdG1hcmtfY2hhbmdlb3Zlcl9wb2xpY3lfdG91Y2hlZCggZXZlbnQudGFyZ2V0ICk7XG5cdFx0XHRcdGlmICggJ2NyZWF0ZScgPT09IGluc3BlY3Rvcl9tb2RlICkge1xuXHRcdFx0XHRcdHN5bmNocm9uaXplX2NyZWF0ZV9pbnNwZWN0b3JfY29udHJvbHMoKTtcblx0XHRcdFx0fVxuXHRcdFx0XHRzeW5jaHJvbml6ZV9jaGFuZ2VvdmVyX3BvbGljeV9jb250cm9scygpO1xuXHRcdFx0XHRzeW5jaHJvbml6ZV9pbnNwZWN0b3JfZGlydHlfc3RhdGUoKTtcblx0XHRcdH1cblx0XHR9ICk7XG5cdFx0aWYgKCB3aW5kb3cualF1ZXJ5ICkge1xuXHRcdFx0d2luZG93LmpRdWVyeSggJy53cGJjX3NldHRpbmdzX3BhZ2Vfd3JhcHBlcicgKS5vbiggJ3dwYmM6cmlnaHQtc2lkZWJhci1iZWZvcmUtY29udGVudC1jb2xsYXBzZS53cGJjQ2F0YWxvZ0Jvb2tpbmdSZXNvdXJjZXMnLCBmdW5jdGlvbiAoIGV2ZW50ICkge1xuXHRcdFx0XHR2YXIgY2xvc2luZ19tb2RlID0gaW5zcGVjdG9yX21vZGU7XG5cblx0XHRcdFx0aWYgKCBpbnNwZWN0b3JfbW9kZSAmJiAhIGNsb3NlX2luc3BlY3RvciggY29uZmlnLCB0cnVlICkgKSB7XG5cdFx0XHRcdFx0ZXZlbnQucHJldmVudERlZmF1bHQoKTtcblx0XHRcdFx0XHRyZXR1cm47XG5cdFx0XHRcdH1cblx0XHRcdFx0aWYgKCAnaW5saW5lX3JldmlldycgPT09IGNsb3NpbmdfbW9kZSApIHtcblx0XHRcdFx0XHRpbmxpbmVfc3RhdGUucmV2aWV3X3Rva2VuID0gJyc7XG5cdFx0XHRcdFx0c3luY2hyb25pemVfaW5saW5lX2NvbnRyb2xzKCBjb25maWcgKTtcblx0XHRcdFx0fVxuXHRcdFx0fSApO1xuXHRcdFx0d2luZG93LmpRdWVyeSggZG9jdW1lbnQgKS5vbiggJ3dwYmNfbWVkaWFfdXBsb2FkX3VybF9zZXQnLCAnW2RhdGEtd3BiYy1jYXRhbG9nLXJlc291cmNlLWZpZWxkPVwicGljdHVyZV91cmxcIl0nLCBmdW5jdGlvbiAoKSB7XG5cdFx0XHRcdHN5bmNocm9uaXplX2luc3BlY3Rvcl9pbWFnZSggdGhpcyApO1xuXHRcdFx0XHRzeW5jaHJvbml6ZV9pbnNwZWN0b3JfZGlydHlfc3RhdGUoKTtcblx0XHRcdH0gKTtcblx0XHRcdHdpbmRvdy5qUXVlcnkoIGRvY3VtZW50ICkub24oICd3cGJjOnJlc291cmNlLXNob3J0Y29kZS1zZWxlY3RlZCcsIGZ1bmN0aW9uICggZXZlbnQsIHNlbGVjdGlvbiApIHtcblx0XHRcdFx0dmFyIHNlbGVjdGVkX3Jlc291cmNlX2lkID0gTnVtYmVyKCBzZWxlY3Rpb24gJiYgc2VsZWN0aW9uLnJlc291cmNlX2lkID8gc2VsZWN0aW9uLnJlc291cmNlX2lkIDogMCApO1xuXHRcdFx0XHR2YXIgc2VsZWN0ZWRfc2hvcnRjb2RlID0gU3RyaW5nKCBzZWxlY3Rpb24gJiYgc2VsZWN0aW9uLnNob3J0Y29kZSA/IHNlbGVjdGlvbi5zaG9ydGNvZGUgOiAnJyApO1xuXHRcdFx0XHR2YXIgaW5zcGVjdG9yX3Nob3J0Y29kZTtcblxuXHRcdFx0XHRpZiAoICEgc2VsZWN0ZWRfcmVzb3VyY2VfaWQgKSB7XG5cdFx0XHRcdFx0cmV0dXJuO1xuXHRcdFx0XHR9XG5cdFx0XHRcdHN5bmNocm9uaXplX2Jvb2tpbmdfcmVzb3VyY2Vfc2hvcnRjb2RlX2lucHV0KCBzZWxlY3RlZF9yZXNvdXJjZV9pZCwgc2VsZWN0ZWRfc2hvcnRjb2RlICk7XG5cdFx0XHRcdGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoICdbZGF0YS13cGJjLWJvb2tpbmctcmVzb3VyY2UtaWQ9XCInICsgU3RyaW5nKCBzZWxlY3RlZF9yZXNvdXJjZV9pZCApICsgJ1wiXVtkYXRhLXdwYmMtYm9va2luZy1yZXNvdXJjZS1zaG9ydGNvZGUtY29tbWFuZF0nICkuZm9yRWFjaCggZnVuY3Rpb24gKCBhY3Rpb25fYnV0dG9uICkge1xuXHRcdFx0XHRcdGFjdGlvbl9idXR0b24uc2V0QXR0cmlidXRlKCAnZGF0YS13cGJjLWJvb2tpbmctcmVzb3VyY2Utc2hvcnRjb2RlJywgc2VsZWN0ZWRfc2hvcnRjb2RlICk7XG5cdFx0XHRcdH0gKTtcblx0XHRcdFx0dmFyIGRldGFpbHNfcm93ID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtYm9va2luZy1yZXNvdXJjZS1kZXRhaWxzLXJvdz1cIicgKyBTdHJpbmcoIHNlbGVjdGVkX3Jlc291cmNlX2lkICkgKyAnXCJdJyApO1xuXHRcdFx0XHR2YXIgZGV0YWlsc19jb2RlID0gZGV0YWlsc19yb3cgPyBkZXRhaWxzX3Jvdy5xdWVyeVNlbGVjdG9yKCAnW2RhdGEtd3BiYy1ib29raW5nLXJlc291cmNlLWRldGFpbHMtc2VjdGlvbj1cImJvb2tpbmdfcGFnZVwiXSBjb2RlJyApIDogbnVsbDtcblx0XHRcdFx0aWYgKCBkZXRhaWxzX2NvZGUgKSB7XG5cdFx0XHRcdFx0ZGV0YWlsc19jb2RlLnRleHRDb250ZW50ID0gc2VsZWN0ZWRfc2hvcnRjb2RlO1xuXHRcdFx0XHRcdGRldGFpbHNfY29kZS5zZXRBdHRyaWJ1dGUoICdkYXRhLXdwYmMtdWktY2F0YWxvZy1vdmVyZmxvdy10b29sdGlwJywgc2VsZWN0ZWRfc2hvcnRjb2RlICk7XG5cdFx0XHRcdFx0c3luY2hyb25pemVfb3ZlcmZsb3dfdG9vbHRpcHMoIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCBjb25maWcubW91bnRfaWQgKSApO1xuXHRcdFx0XHR9XG5cdFx0XHRcdGlmICggc2VsZWN0ZWRfcmVzb3VyY2VfaWQgPT09IGluc3BlY3Rvcl9yZXNvdXJjZV9pZCApIHtcblx0XHRcdFx0XHRpbnNwZWN0b3Jfc2hvcnRjb2RlID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvciggJ1tkYXRhLXdwYmMtY2F0YWxvZy1yZXNvdXJjZS1pbnNwZWN0b3ItZm9ybV0gLndwYmNfY2F0YWxvZ19ib29raW5nX3Jlc291cmNlc19fZWRpdG9yX2NvZGUnICk7XG5cdFx0XHRcdFx0aWYgKCBpbnNwZWN0b3Jfc2hvcnRjb2RlICkge1xuXHRcdFx0XHRcdFx0aW5zcGVjdG9yX3Nob3J0Y29kZS52YWx1ZSA9IHNlbGVjdGVkX3Nob3J0Y29kZTtcblx0XHRcdFx0XHRcdHN5bmNocm9uaXplX2luc3BlY3Rvcl9kaXJ0eV9zdGF0ZSgpO1xuXHRcdFx0XHRcdH1cblx0XHRcdFx0fVxuXHRcdFx0fSApO1xuXHRcdH1cblx0XHRkb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKCAnc3VibWl0JywgZnVuY3Rpb24gKCBldmVudCApIHtcblx0XHRcdGlmICggZXZlbnQudGFyZ2V0Lm1hdGNoZXMoICdbZGF0YS13cGJjLWNhdGFsb2ctaW5saW5lLXJldmlldy1mb3JtXScgKSApIHtcblx0XHRcdFx0YXBwbHlfaW5saW5lX2NoYW5nZXMoIGV2ZW50LCBjb25maWcgKTtcblx0XHRcdH0gZWxzZSBpZiAoIGV2ZW50LnRhcmdldC5tYXRjaGVzKCAnW2RhdGEtd3BiYy1jYXRhbG9nLXJlc291cmNlLWluc3BlY3Rvci1mb3JtXScgKSApIHtcblx0XHRcdFx0c3VibWl0X2luc3BlY3RvciggZXZlbnQsIGNvbmZpZyApO1xuXHRcdFx0fSBlbHNlIGlmICggZXZlbnQudGFyZ2V0Lm1hdGNoZXMoICdbZGF0YS13cGJjLWNhdGFsb2ctcmVzb3VyY2UtYnVsay1mb3JtXSwgW2RhdGEtd3BiYy1jYXRhbG9nLXJlc291cmNlLWJ1bGstcmV2aWV3LWZvcm1dLCBbZGF0YS13cGJjLWNhdGFsb2ctcmVzb3VyY2UtZGVsZXRlLWZvcm1dLCBbZGF0YS13cGJjLWNhdGFsb2ctcmVzb3VyY2UtY2FwYWNpdHktZm9ybV0nICkgKSB7XG5cdFx0XHRcdHN1Ym1pdF9yZXZpZXdlZF9pbnNwZWN0b3IoIGV2ZW50LCBjb25maWcgKTtcblx0XHRcdH1cblx0XHR9ICk7XG5cdFx0d2luZG93LmFkZEV2ZW50TGlzdGVuZXIoICdiZWZvcmV1bmxvYWQnLCBmdW5jdGlvbiAoIGV2ZW50ICkge1xuXHRcdFx0aWYgKCBpbnNwZWN0b3JfZGlydHkgfHwgaW5zcGVjdG9yX211dGF0aW9uX2luX3Byb2dyZXNzIHx8ICggaW5saW5lX3N0YXRlLmFjdGl2ZSAmJiBpbmxpbmVfc3RhdGUuY2hhbmdlZF9yb3dzLmxlbmd0aCApICkge1xuXHRcdFx0XHRldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xuXHRcdFx0XHRldmVudC5yZXR1cm5WYWx1ZSA9ICcnO1xuXHRcdFx0fVxuXHRcdH0gKTtcblx0fVxuXG5cdGlmICggJ2xvYWRpbmcnID09PSBkb2N1bWVudC5yZWFkeVN0YXRlICkge1xuXHRcdGRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoICdET01Db250ZW50TG9hZGVkJywgbW91bnRfYm9va2luZ19yZXNvdXJjZXNfY2F0YWxvZyApO1xuXHR9IGVsc2Uge1xuXHRcdG1vdW50X2Jvb2tpbmdfcmVzb3VyY2VzX2NhdGFsb2coKTtcblx0fVxufSggd2luZG93LCBkb2N1bWVudCApICk7XG4iXSwibWFwcGluZ3MiOiI7O0FBQUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNFLFdBQVdBLE1BQU0sRUFBRUMsUUFBUSxFQUFHO0VBQy9CLFlBQVk7O0VBRVosSUFBSUMsa0JBQWtCLEdBQUcsSUFBSTtFQUM3QixJQUFJQywwQkFBMEIsR0FBRyxJQUFJO0VBQ3JDLElBQUlDLGlDQUFpQyxHQUFHLElBQUk7RUFDNUMsSUFBSUMsaUNBQWlDLEdBQUcsSUFBSTtFQUM1QyxJQUFJQyw2QkFBNkIsR0FBRyxJQUFJO0VBQ3hDLElBQUlDLGdCQUFnQixHQUFHLElBQUk7RUFDM0IsSUFBSUMsd0JBQXdCLEdBQUcsSUFBSTtFQUNuQyxJQUFJQyx3QkFBd0IsR0FBRyxDQUFDO0VBQ2hDLElBQUlDLG1CQUFtQixHQUFHLENBQUM7RUFDM0IsSUFBSUMscUJBQXFCLEdBQUcsSUFBSTtFQUNoQyxJQUFJQyx1QkFBdUIsR0FBRyxFQUFFO0VBQ2hDLElBQUlDLGVBQWUsR0FBRyxLQUFLO0VBQzNCLElBQUlDLHNCQUFzQixHQUFHLElBQUk7RUFDakMsSUFBSUMsY0FBYyxHQUFHLEVB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
