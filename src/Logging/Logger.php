<?php

namespace WooSearch\Logging;

class Logger {

    private function __construct()
    {
        
    }
    
    public static function debugLog( $message, $context = "" ) {
        if ( defined("WP_DEBUG") && defined("WP_DEBUG_LOG") ) {
            error_log( $message . " - " . $context );
        }
    }
}