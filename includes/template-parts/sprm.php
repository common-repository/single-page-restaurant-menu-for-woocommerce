<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

get_header( 'sprm' );

sprm_get_template_part('content');

get_footer( 'sprm' );