<?php

namespace Module;

/**
 * Abstract class to be inherited by all modules
 */
class Module {
    
    // shortcut for easier access
    protected $kernel = null;
    
    public function __construct( $kernel ) {
        $this->kernel = $kernel;
    }
    
}
