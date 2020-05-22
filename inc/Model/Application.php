<?php

namespace Model;

/**
 * Applications of users
 */
class Application extends Model {
    
    public static $table = 'applications';
    public static $fields = [ 'email', 'amount' ]; // fields to read/write
    
}
