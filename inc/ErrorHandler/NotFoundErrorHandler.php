<?php

namespace ErrorHandler;

/**
 * Handles 404 page
 */
class NotFoundErrorHandler extends ErrorHandler {
    
    public static $code = 404;
    
    public function Handle( $e ) {
        $controller = new \Controller\ErrorController();
        $controller->SetData([
            'message' => 'This page does not exist!',
        ]);
        $controller->Get( $e );
    }
}
