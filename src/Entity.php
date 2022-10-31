<?php
/**
 * Entity
 *
 * @package Pronamic/WordPress/ORM
 */

namespace Pronamic\WordPress\ORM;

/**
 * Entity class
 */
class Entity {
    private $created_at_key = 'created_at';

    private $updated_at_key = 'updated_at';

	public function __construct( $table, $primary_key, $format ) {
		$this->table       = $table;
		$this->primary_key = $primary_key;
		$this->format      = $format;

		$this->format['created_at'] = '%s';
		$this->format['updated_at'] = '%s';
	}
}
