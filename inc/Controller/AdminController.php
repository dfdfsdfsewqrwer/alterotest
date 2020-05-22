<?php

namespace Controller {

use \Model\Deal;
use \Model\Application;
    
/**
 * Application submission success
 */
class AdminController extends Controller {
    
    public static $route = '/^admin$/';
    
    public function Get( $args ) {
        
        $this->AddCSS( 'admin.css' );
        
        $deals = Deal::Find();
        foreach( $deals as $deal )
            $deal->application = Application::Find([ // this can be done automatically inside Model, but for purposes of test task should suffice here
                'id' => $deal->application_id,
            ])[ 0 ];
        
        $this->RenderView( 'Admin.html.php', [
            'page_name' => 'Admin page',
            'deals' => $deals,
        ]);
    }
    
    public function Post( $args ) {

        if (
            !isset( $args[ 'csrf' ], $args[ 'deals' ] ) ||
            !is_array( $args[ 'deals' ] ) ||
            !$this->IsCSRFValid( $args[ 'csrf' ] )
        ) {
            $this->SetFlash([
                'error' => 'Invalid request or invalid CSRF token',
            ]);
            $this->Redirect( '/admin' );
        }
        
        $errors = [];
        $mails_sent = 0;
        foreach( $args[ 'deals' ] as $deal_id => $new_status ) {
            $deals = Deal::Find([
                'id' => $deal_id,
            ]);
            if ( count( $deals ) == 0 ) {
                $errors []= 'Deal #' . $deal_id . ' not found';
                continue;
            }
            $deal = $deals[ 0 ];
            $deal->application = Application::Find([ // same as above - this logic can be moved to Model for real project
                'id' => $deal->application_id,
            ])[ 0 ];
            if ( $deal->status != $new_status ) { // status changed
                if ( $new_status == 'offer' ) {
                    if ( !$deal->SendMail() ) {
                        $errors []= 'Mail error';
                        continue;
                    }
                    $mails_sent++;
                }
                $deal->status = $new_status;
                $deal->Save();
            }
        }
        
        if ( count( $errors ) > 0 )
            $this->SetFlash([
                'error' => implode( '<br/>', $errors ),
            ]);
        else
            $this->SetFlash([
                'success' => 'Deals updated! Mails sent: ' . $mails_sent,
            ]);
        
        //print_r( $args );
        //die();
        $this->Redirect( '/admin' );
    }
    
}

}
