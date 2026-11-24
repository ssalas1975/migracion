<?php

//top header bar
add_action('any_edge_before_page_header', 'any_edge_get_header_top');

//mobile header
add_action('any_edge_after_page_header', 'any_edge_get_mobile_header');