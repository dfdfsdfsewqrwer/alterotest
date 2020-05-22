<?php

namespace ErrorHandler;

/**
 * Handles 404 page
 */
class GenericErrorHandler extends ErrorHandler {
    
    public static $code = null;
    
    public function Handle( $e ) {
        $controller = new \Controller\ErrorController();
        $controller->SetData([
            'message' => $e->GetMessage(),
        ]);
        $controller->Get( $e );
    }
}
