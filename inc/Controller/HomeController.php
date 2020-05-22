<?php

namespace Controller {

/**
 * Home page
 */
class HomeController extends Controller {
    
    public static $route = '/^$/';
    
    public function Get( $args ) {
        
        $this->AddCSS( 'home.css' );
        
        $this->RenderView( 'Home.html.php', [
            'page_name' => 'Home',
        ]);
    }
}

}
