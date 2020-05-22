<?php

namespace Controller;

/**
 * Displays errors
 */
class ErrorController extends Controller {
    
    public static $route = null;
    
    public function Get( $args ) {
        
        $this->AddCSS( 'error.css' );
        
        $this->RenderView( 'Error.html.php', [
            'page_name' => 'Error',
        ]);
    }
    
}
