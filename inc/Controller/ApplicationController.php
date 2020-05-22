<?php

namespace Controller {

use \Model\Application;
use \Model\Deal;

/**
 * Application submission
 */
class ApplicationController extends Controller {
    
    public static $route = '/^apply$/';
    
    public function Get( $args ) {
        
        $this->AddCSS( 'apply.css' );
        
        $this->RenderView( 'Apply.html.php', [
            'page_name' => 'Submit application',
        ]);
    }
    
    public function Post( $args ) {
        if (
            !isset( $args[ 'csrf' ], $args[ 'email' ], $args[ 'amount' ] ) ||
            !$this->IsCSRFValid( $args[ 'csrf' ] )
        ) {
            $this->SetFlash([
                'error' => 'Invalid request or invalid CSRF token',
            ]);
            $this->Redirect( '/apply' );
        }
        
        try {
            // create application
            $application = new Application();
            $application->email = $args[ 'email' ];
            $application->amount = $args[ 'amount' ];
            $application->Save();

            // create deal
            $deal = new Deal();
            $deal->application = $application;
            if ( $application->amount > 5000 )
                $deal->partner = 'A';
            else
                $deal->partner = 'B';
            $deal->application_id = $application->id;
            $deal->status = 'ask';
            $deal->Save();
            
        } catch ( Exception $e ) {
            $this->SetFlash([
                'error' => 'DB error',
            ]);
            $this->Redirect( '/apply' );
        }
        
        // all saved
        if ( !$deal->SendMail() ) {
            // couldn't send mail, so need to revert everything
            $deal->Delete();
            $application->Delete();
            $this->SetFlash([
                'error' => 'Mail error',
            ]);
            $this->Redirect( '/apply' );
        }
        
        $this->Redirect( '/success' );
        
    }
}

}
