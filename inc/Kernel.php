<?php

/**
 * Site core
 */
class Kernel {
    
    // configure kernel
    
    public static $modules = [
        'mailer' => Module\Mailer::class,
    ];
    
    public static $controllers = [
        Controller\HomeController::class,
        Controller\ApplicationController::class,
        Controller\SuccessController::class,
        Controller\AdminController::class,
    ];
    
    public static $error_handlers = [
        ErrorHandler\NotFoundErrorHandler::class,
        ErrorHandler\GenericErrorHandler::class,
    ];
    
    public static $options = [
        'debug_mode' => false, // display debug info?
    ];
    
    // runtime data
    public $db = null;
    
    /**
     * process request, return response, handle errors if any
     */
    public function Run() {
        
        try {
        
            // global shortcut
            $GLOBALS[ 'kernel' ] = $this;
            
            // initialize session
            session_start();
            
            // initialize and assign modules
            foreach( $this::$modules as $property => $module_class )
                $this->$property = new $module_class( $this );
            
            // initialize db
            $this->db = @ new mysqli( 'localhost', 'alterotest', 'alterotestpassword123', 'alterotest' );
            if ( $this->db->connect_error )
                throw new \Exception( 'Unable to connect to database: ' . $this->db->connect_error );
            
            // find matching controller for route
            $route = substr( strtok( $_SERVER[ 'REQUEST_URI' ], '?&' ), 1 );
            $args = strtok( '' );

            $selected_controller_class = null;
            foreach( $this::$controllers as $controller_class ) {
                if ( preg_match( $controller_class::$route, $route ) ) {
                    $selected_controller_class = $controller_class;
                    break;
                }
            }

            if ( !$selected_controller_class ) {
                throw new \Exception( 'Page not found', 404 );
            }
            
            $controller = new $selected_controller_class();
            
            switch ( $_SERVER[ 'REQUEST_METHOD' ] ) {
                case 'GET':
                    $controller->Get( $_GET );
                    break;
                case 'POST':
                    $controller->Post( $_POST );
                    break;
                default:
                    throw new \Exception( 'Page not found', 404 );
            }

        } catch ( \Exception $e ) {
            
            // find suitable error handler
            $selected_error_handler_class = null;
            foreach( $this::$error_handlers as $error_handler_class ) {
                if ( $error_handler_class::$code == $e->getCode() ) {
                    $error_handler = new $error_handler_class();
                    
                    if ( $error_handler->Handle( $e ) )
                        break; // error handled successfully
                }
            }
            
        }
        
    }
    
}
