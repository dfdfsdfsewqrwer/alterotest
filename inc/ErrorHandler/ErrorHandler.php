<?php

namespace ErrorHandler;

/**
 * Abstract class to be inherited by all error handlers
 */
abstract class ErrorHandler {
    
    // error handler configuration
    
    public static $code = null; // error code to execute for, if null execute for any
    
    // handlers to be overridden
    
    public function Handle( $e ) {
        throw new \Exception( 'Invalid error handler ( Run() not implemented )', 500 );
    }
    
}
