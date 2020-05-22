<?php

namespace Module;

/**
 * Abstract class to be inherited by all modules
 */
class Mailer extends Module {
    
    // public callables
    public function Mail( $to, $subject, $html ) {
        
        // not needed in scope of test
        
        return true; // success
    }
    
}
