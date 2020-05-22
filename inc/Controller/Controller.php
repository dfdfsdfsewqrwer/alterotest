<?php

namespace Controller {

/**
 * Abstract class to be inherited by all controllers
 */
abstract class Controller {
    
    // controller configuration
    
    public static $route = null; // URI mask ( regexp ) to call controller for, if null will be skipped during lookup
    
    // controller parameters ( can be modified in runtime )
    
    protected $header = 'inc/Header.html.php';
    protected $footer = 'inc/Footer.html.php';
    protected $css = [
        'normalize.css',
        'layout.css',
    ];
    protected $js = [
    ];
    
    // handlers to be overridden
    
    public function Get( $args ) { // handle GET request
        throw new \Exception( 'Page not found', 404 );
    }
    public function Post( $args ) { // handle POST request
        throw new \Exception( 'Page not found', 404 );
    }
 
    // runtime data
    
    protected $data = [];
    protected $flash = [];
    
    // shortcut
    protected $kernel = null;
    
    public function __construct() {
        // set shortcut
        $this->kernel = $GLOBALS[ 'kernel' ];
        
        // get and clear data set from previous request
        if ( isset( $_SESSION[ 'flash' ] ) ) {
            $this->flash = $_SESSION[ 'flash' ];
            unset( $_SESSION[ 'flash' ] );
        }
    }
    
    // public callables
    
    /**
     * Set some variables inside controller
     * 
     * @param array $data - key-value data to set
     */
    public function SetData( $data ) {
        $this->data = array_merge( $this->data, $data );
    }
    
    // internal callables
    
    /**
     * Adds css to page
     * 
     * @param type $css - name of css file
     */
    protected function AddCSS( $css ) {
        $this->css []= $css;
    }
    
    /**
     * Adds js to page
     * 
     * @param type $js - name of js file
     */
    protected function AddJS( $js ) {
        $this->js []= $js;
    }
    
    /**
     * Renders specified view with specified data
     * 
     * @param string $view - name of view to load
     * @param array $data - ( optional ) pass key-value data to view
     */
    protected function RenderView( $view, $data ) {
        
        // add runtime data from controller
        $data = array_merge( $data, $this->data );
 
        // put variables into clean context, to be used in views
        // use temporary $_vars to keep old context, restore it later
        $_vars = get_defined_vars();
        foreach( $_vars as $k => $v )
            unset( $$k );
        if ( $_vars[ 'data' ] )
            foreach( $_vars[ 'data' ] as $k => $v )
                $$k = $v;
        
        // execute views
        require( './inc/View/' . $this->header );
        require( './inc/View/' . $_vars[ 'view' ] );
        require( './inc/View/' . $this->footer );
        
        // restore old context
        if ( $_vars[ 'data' ] )
            foreach( $_vars[ 'data' ] as $k => $v )
                unset( $$k );
        foreach( $_vars as $k => $v )
            $$k = $v;
        unset( $_vars );
        
    }
    
    /**
     * Generate CSRF token for future validation, to be called in Get()
     */
    protected function MakeCSRF() {
        $lastcsrf = isset( $_SESSION[ 'csrf' ] ) ? $_SESSION[ 'csrf' ] : null;
        $csrf = md5( rand( 0, 99999999 ) );
        $_SESSION[ 'csrf' ] = $csrf;
        return $csrf;
    }
    
    /**
     * Checks if provided CSRF is valid
     * 
     * @param string $csrf - CSRF
     */
    protected function IsCSRFValid( $csrf ) {
        $result = isset( $_SESSION[ 'csrf' ] ) && $_SESSION[ 'csrf' ] === $csrf;
        unset( $_SESSION[ 'csrf' ] );
        return $result;
    }

    /**
     * Store some data for next page load
     * 
     * @param array $data
     */
    protected function SetFlash( $data ) {
        $_SESSION[ 'flash' ] = $data;
    }
    
    /**
     * Redirects to URL
     * 
     * @param string $url
     */
    protected function Redirect( $url ) {
        header( 'Location: ' . $url );
        die(); // stop further execution
    }
}

}
