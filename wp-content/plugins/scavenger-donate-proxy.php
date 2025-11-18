<?php
/**
 * Plugin Name: Scavenger Donate Proxy
 * Description: Proxies donate_to calls from the frontend to the Midnight Scavenger API to avoid CORS.
 */

add_action('rest_api_init', function () {
    register_rest_route(
        'scavenger/v1',
        '/donate',
        [
            'methods'             => 'POST',
            'callback'            => 'scavenger_handle_donate',
            'permission_callback' => '__return_true', // you can tighten this later if you want
        ]
    );
});

function scavenger_handle_donate( WP_REST_Request $request ) {
    $params    = $request->get_json_params();
    $dest      = isset( $params['dest'] ) ? trim( $params['dest'] ) : null;
    $orig      = isset( $params['orig'] ) ? trim( $params['orig'] ) : null;
    $signature = isset( $params['signature'] ) ? trim( $params['signature'] ) : null;

    if ( empty( $dest ) || empty( $orig ) || empty( $signature ) ) {
        return new WP_REST_Response(
            [ 'message' => 'Missing dest, orig, or signature in request body' ],
            400
        );
    }

    $base = 'https://scavenger.prod.gd.midnighttge.io';
    $url  = $base . '/donate_to/' .
        rawurlencode( $dest ) . '/' .
        rawurlencode( $orig ) . '/' .
        $signature;

    // Log for debugging if you want:
    // error_log('[Scavenger] Forwarding to: ' . $url);

    $response = wp_remote_post(
        $url,
        [
            'headers' => [ 'Content-Type' => 'application/json' ],
            'body'    => '{}',
            'timeout' => 20,
        ]
    );

    if ( is_wp_error( $response ) ) {
        return new WP_REST_Response(
            [
                'message' => 'Upstream error calling Scavenger /donate_to',
                'error'   => $response->get_error_message(),
            ],
            502
        );
    }

    $status = wp_remote_retrieve_response_code( $response );
    $body   = wp_remote_retrieve_body( $response );

    $decoded = json_decode( $body, true );
    if ( json_last_error() !== JSON_ERROR_NONE ) {
        // Not JSON, just wrap the raw body
        $decoded = [ 'raw' => $body ];
    }

    return new WP_REST_Response( $decoded, $status );
}
