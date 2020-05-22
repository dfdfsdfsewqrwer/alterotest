<?php

namespace Model;

/**
 * Abstract class to be inherited by all models
 */
abstract class Model {
    
    // model configuration
    
    public static $table = null; // table in database, must be defined
    public static $fields = []; // fields to read/write
    public static $primary_key = 'id'; // should stay default for most cases
    
    public function __construct() {
        // shortcut
        $this->db = $GLOBALS[ 'kernel' ]->db;
    }
    
    // internal callables
    
    /**
     * Find all models that match criteria
     * 
     * @param array $criteria - array of key-value pairs to search for
     */
    static function Find( $criteria = [] ) {
        $db = $GLOBALS[ 'kernel' ]->db;
        
        // prepare query
        $query = 'SELECT `' . static::$primary_key . '`';
        foreach( static::$fields as $field )
            $query .= ', `' . $field . '`';
        $query .= ' FROM `' . static::$table . '`';
        $args = [];
        foreach( $criteria as $field => $value ) {
            if ( count( $args ) == 0 )
                $query .= ' WHERE ';
            else
                $query .= ' AND ';
            $query .= '`' . $field . '` = ?';
            $args []= &$value;
        }
        //print_r( $query ); echo '<br />'; print_r( $args ); die();
        
        // execute
        $stmt = $db->prepare( $query );
        if ( count( $args ) > 0 ) {
            if ( !\call_user_func_array( array( $stmt, 'bind_param' ), array_merge( [ str_repeat( 's', count( $args ) ) ], $args ) ) )
                throw new \Exception( 'DB bind_param failed: ' . $stmt->error );
        }
        
        if ( !$stmt->execute() )
            throw new \Exception( 'DB execute failed: ' . $stmt->error );
        
        $models = [];
        
        $stmt_res = $stmt->get_result();
        while ( $row = $stmt_res->fetch_assoc() ) {
            $model = new static();
            foreach( $row as $k => $v )
                $model->$k = $v;
            $models []= $model;
        }
        
        return $models;
    }
    
    /**
     * Save model to database, will use insert or update based on presense of primary key
     * Model will always have primary key set after successful saving
     */
    public function Save() {
        $is_insert = !isset( $this->{$this::$primary_key} );
        
        $query = '';
        $args = [];

        if ( $is_insert ) {
            // make INSERT query
            $q_fields = '';
            $q_values = '';
            foreach( $this::$fields as $field ) {
                if ( !isset( $this->$field ) )
                    continue; // skip non-existent data for query generation
                if ( count( $args ) > 0 ) {
                    $q_fields .= ', ';
                    $q_values .= ', ';
                }
                $q_fields .= '`' . $field . '`';
                $q_values .= '?';
                $args []= &$this->$field;
            }
            $query = 'INSERT INTO `' . $this::$table . '` ( ' . $q_fields . ' ) VALUES ( ' . $q_values . ' )';
        }
        else {
            // make UPDATE query
            $query = 'UPDATE `' . $this::$table . '` SET ';
            foreach( $this::$fields as $field ) {
                if ( !isset( $this->$field ) )
                    continue; // skip non-existent data for query generation
                if ( count( $args ) > 0 )
                    $query .= ', ';
                $query .= '`' . $field . '` = ?';
                $args []= &$this->$field;
            }
            $query .= ' WHERE `' . $this::$primary_key . '` = ?';
            $args []= &$this->{$this::$primary_key};
        }
        
        // execute
        $stmt = $this->db->prepare( $query );
        if ( !\call_user_func_array( array( $stmt, 'bind_param' ), array_merge( [ str_repeat( 's', count( $args ) ) ], $args ) ) )
            throw new \Exception( 'DB bind_param failed: ' . $stmt->error );
        
        if ( !$stmt->execute() )
            throw new \Exception( 'DB execute failed: ' . $stmt->error );
        
        // model will get id after inserted
        if ( $is_insert )
            $this->{$this::$primary_key} = $stmt->insert_id;
        
    }
    
    /**
     * Delete model from database
     */
    public function Delete() {
        if ( isset( $this->{$this::$primary_key} ) ) {
            $stmt = $this->db->prepare( 'DELETE FROM `' . $this::$table . '` WHERE `' . $this::$primary_key . '` = ?' );
            $stmt->bind_param( 's', $this->{$this::$primary_key} );
            if ( !$stmt->execute() )
                throw new \Exception( 'DB execute failed: ' . $stmt->error );
            unset( $this->{$this::$primary_key} );
        }
    }
    
}
