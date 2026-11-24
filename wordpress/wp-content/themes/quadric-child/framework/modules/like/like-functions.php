<?php

if ( ! function_exists('edgtf_quadric_like') ) {
	/**
	 * Returns EdgefQuadricLike instance
	 *
	 * @return EdgefQuadricLike
	 */
	function edgtf_quadric_like() {
		return EdgefQuadricLike::get_instance();
	}

}

function edgtf_quadric_get_like() {

	echo wp_kses(edgtf_quadric_like()->add_like(), array(
		'span' => array(
			'class' => true,
			'aria-hidden' => true,
			'style' => true,
			'id' => true
		),
		'i' => array(
			'class' => true,
			'style' => true,
			'id' => true
		),
		'a' => array(
			'href' => true,
			'class' => true,
			'id' => true,
			'title' => true,
			'style' => true
		)
	));
}

if ( ! function_exists('edgtf_quadric_like_latest_posts') ) {
	/**
	 * Add like to latest post
	 *
	 * @return string
	 */
	function edgtf_quadric_like_latest_posts() {
		return edgtf_quadric_like()->add_like();
	}

}

if ( ! function_exists('edgtf_quadric_like_portfolio_list') ) {
	/**
	 * Add like to portfolio project
	 *
	 * @param $portfolio_project_id
	 * @return string
	 */
	function edgtf_quadric_like_portfolio_list($portfolio_project_id) {
		return edgtf_quadric_like()->add_like_portfolio_list($portfolio_project_id);
	}

}