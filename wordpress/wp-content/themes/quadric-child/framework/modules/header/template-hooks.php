<?php

//top header bar
add_action('edgtf_quadric_before_page_header', 'edgtf_quadric_get_header_top');

//mobile header
add_action('edgtf_quadric_after_page_header', 'edgtf_quadric_get_mobile_header');