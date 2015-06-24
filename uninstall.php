<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

// uninstall code

$options = array(

);

foreach ( $options as $option ) {
	delete_option( $option );
}