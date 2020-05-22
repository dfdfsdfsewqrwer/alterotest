<?php

namespace Model;

/**
 * Deals sent to partners
 */
class Deal extends Model {
    
    public static $table = 'deals';
    public static $fields = [ 'partner', 'application_id', 'status' ]; // fields to read/write
    
    /**
     * Tries to send mail to partner
     * 
     * @return boolean - true on success, false otherwise
     */
    public function SendMail() {
        return $GLOBALS[ 'kernel' ]->mailer->Mail( 'Sponsor' . $this->sponsor . '@sponsors.com', 'New Deal', $this->application->email . ' ' . $this->application->amount );
    }
}
