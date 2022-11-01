<?php
/**
 * Entity
 *
 * @package Pronamic/WordPress/Database
 */

namespace Pronamic\WordPress\Database;

/**
 * Entity class
 */
class Entity {
    public $created_at_key = 'created_at';

    public $updated_at_key = 'updated_at';

    public function __construct( $table, $primary_key, $format ) {
        $this->table       = $table;
        $this->primary_key = $primary_key;
        $this->format      = $format;
    }

    public function get_column_format( $column ) {
        return \array_key_exists( $column, $this->format ) ? $this->format[ $column ] : '%s';
    }

    public function get_data_format( $data ) {
        $format = [];

        foreach ( $data as $column => $value ) {
            $format[] = $this->get_column_format( $column );
        }

        return $format;
    }
}
