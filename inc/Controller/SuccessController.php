<?php

namespace Controller {

/**
 * Application submission success
 */
class SuccessController extends Controller {
    
    public static $route = '/^success$/';
    
    public function Get( $args ) {
        
        $this->AddCSS( 'apply.css' );
        
        $this->RenderView( 'Success.html.php', [
            'page_name' => 'Submit application',
        ]);
    }
    
}

}
