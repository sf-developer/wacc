<?php

// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

/* <------------------------------- Drop tables -------------------------------> */
global $wpdb;
$table_prefix = $wpdb->prefix; // Get tables prefix

$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . 'wacc_agents' );

/* <------------------------------- Delete plugin options -------------------------------> */
delete_option('wacc-settings');