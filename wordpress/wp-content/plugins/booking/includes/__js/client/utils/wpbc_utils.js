/**
 * =====================================================================================================================
 * JavaScript Util Functions		../includes/__js/utils/wpbc_utils.js
 * =====================================================================================================================
 */

/**
 * Trim  strings and array joined with  (,)
 *
 * @param string_to_trim   string / array
 * @returns string
 */
function wpbc_trim(string_to_trim) {

	if ( Array.isArray( string_to_trim ) ) {
		string_to_trim = string_to_trim.join( ',' );
	}

	if ( 'string' == typeof (string_to_trim) ) {
		string_to_trim = string_to_trim.trim();
	}

	return string_to_trim;
}

/**
 * Check if element in array
 *
 * @param array_here		array
 * @param p_val				element to  check
 * @returns {boolean}
 */
function wpbc_in_array(array_here, p_val) {
	for ( var i = 0, l = array_here.length; i < l; i++ ) {
		if ( array_here[i] == p_val ) {
			return true;
		}
	}
	return false;
}

/**
 * Keep Booking Calendar action links in their current browsing context.
 *
 * WordPress Playground may define a document-wide default target. Its legacy
 * compatibility fallback therefore adds an explicit target before click
 * dispatch, including when Booking Calendar inserts links dynamically.
 */
( function () {
	'use strict';

	var wpbc_link_scope_selector = [
		'[data-wpbc-action-link]',
		'[data-wp-no-blank]',
		'[data-toggle^="wpbc_"]',
		'[id^="wpbc_"]',
		'[class*="wpbc_"]',
		'[id^="booking_form"]',
		'.booking_form',
		'.booking_form_div',
		'.datepick'
	].join( ',' );

	/**
	 * Determine whether the current document is the official WordPress Playground.
	 *
	 * The broad legacy-link fallback is intentionally limited to Playground. Other
	 * environments rely on the explicit targets emitted by Booking Calendar.
	 *
	 * @return {boolean} Whether the current origin is WordPress Playground.
	 */
	function is_playground_origin() {
		return 'https://playground.wordpress.net' === window.location.origin;
	}

	/**
	 * Determine whether an anchor is an in-page action rather than navigation.
	 *
	 * @param {HTMLAnchorElement|null} link Anchor to classify.
	 * @return {boolean} Whether the href is empty, a fragment, or JavaScript.
	 */
	function is_pseudo_link( link ) {
		if ( ! link || ! link.getAttribute ) {
			return false;
		}

		var href = ( link.getAttribute( 'href' ) || '' ).trim().toLowerCase();

		return ! href || 0 === href.indexOf( '#' ) || 0 === href.indexOf( 'javascript:' );
	}

	/**
	 * Determine whether an anchor belongs to Booking Calendar user interface.
	 *
	 * @param {HTMLAnchorElement|null} link Anchor to inspect.
	 * @return {boolean} Whether the link or an ancestor has a WPBC UI marker.
	 */
	function is_booking_calendar_link( link ) {
		if ( ! link || ! link.matches ) {
			return false;
		}

		return link.matches( wpbc_link_scope_selector )
			|| ( link.closest && null !== link.closest( wpbc_link_scope_selector ) );
	}

	/**
	 * Apply the same-context target to one eligible action link.
	 *
	 * The legacy data attribute remains an explicit opt-in even outside a WPBC
	 * container. Real HTTP, email, telephone, payment, and visitor links remain
	 * unchanged.
	 *
	 * @param {HTMLAnchorElement|null} link Anchor to normalize.
	 * @return {void}
	 */
	function fix_target( link ) {
		if ( ! link || ! link.hasAttribute ) {
			return;
		}

		if ( link.hasAttribute( 'data-wp-no-blank' ) ) {
			link.setAttribute( 'target', '_self' );
			return;
		}

		var explicit_target = ( link.getAttribute( 'target' ) || '' ).trim();
		if ( explicit_target && '_self' !== explicit_target.toLowerCase() ) {
			return;
		}

		if ( is_booking_calendar_link( link ) && is_pseudo_link( link ) ) {
			link.setAttribute( 'target', '_self' );
		}
	}

	/**
	 * Normalize eligible anchors contained by a document or inserted subtree.
	 *
	 * @param {Document|DocumentFragment|Element|null} root Root node to inspect.
	 * @return {void}
	 */
	function fix_links( root ) {
		if ( ! root ) {
			return;
		}

		if ( root.matches && root.matches( 'a[href]' ) ) {
			fix_target( root );
		}

		if ( ! root.querySelectorAll ) {
			return;
		}

		var links = root.querySelectorAll( 'a[href]' );
		for ( var link_index = 0; link_index < links.length; link_index++ ) {
			fix_target( links[ link_index ] );
		}
	}

	/**
	 * Normalize links added or updated after initial page rendering.
	 *
	 * @param {MutationRecord[]} mutation_records Observed DOM changes.
	 * @return {void}
	 */
	function handle_mutations( mutation_records ) {
		for ( var record_index = 0; record_index < mutation_records.length; record_index++ ) {
			var mutation_record = mutation_records[ record_index ];

			if ( 'attributes' === mutation_record.type ) {
				fix_target( mutation_record.target );
				continue;
			}

			for ( var node_index = 0; node_index < mutation_record.addedNodes.length; node_index++ ) {
				fix_links( mutation_record.addedNodes[ node_index ] );
			}
		}
	}

	/**
	 * Resolve and normalize the closest link for an interaction event.
	 *
	 * Pointer and focus events happen before click dispatch, providing a fallback
	 * when another script inserts and activates a link in an unusual sequence.
	 *
	 * @param {Event} event Browser interaction event.
	 * @return {void}
	 */
	function fix_event_link( event ) {
		var link = event.target && event.target.closest ? event.target.closest( 'a[href]' ) : null;
		fix_target( link );
	}

	/**
	 * Initialize immediate and dynamic same-context protection.
	 *
	 * @return {void}
	 */
	function init_fix() {
		// Protect links already present before observers and capture listeners start.
		fix_links( document );

		if ( 'function' === typeof window.MutationObserver && document.documentElement ) {
			var link_observer = new window.MutationObserver( handle_mutations );
			link_observer.observe(
				document.documentElement,
				{
					attributes     : true,
					attributeFilter: [ 'href' ],
					childList      : true,
					subtree        : true
				}
			);
		}

		document.addEventListener( 'pointerdown', fix_event_link, true );
		document.addEventListener( 'mousedown', fix_event_link, true );
		document.addEventListener( 'focusin', fix_event_link, true );
		document.addEventListener( 'click', fix_event_link, true );
	}

	if ( ! is_playground_origin() ) {
		return;
	}

	if ( document.documentElement ) {
		init_fix();
	} else {
		document.addEventListener( 'DOMContentLoaded', init_fix, { once: true } );
	}
} )();
