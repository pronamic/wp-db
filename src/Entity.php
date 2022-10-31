<?php
/**
 * Entity
 *
 * @package Pronamic/WordPress/Twinfield
 * @link    https://libreworks.github.io/xyster/documentation/guide/xyster.orm.setup.html
 * @link    https://redbeanphp.com/
 * @link    https://www.doctrine-project.org/projects/doctrine-orm/en/2.11/reference/php-mapping.html
 * @link    https://symfony.com/doc/current/doctrine.html#creating-an-entity-class
 * @link    http://propelorm.org/documentation/reference/active-record.html
 * @link    https://www.baeldung.com/hibernate-entitymanager
 */

namespace Pronamic\WordPress\ORM;

/**
 * Entity class
 */
class Entity {
	public function __construct( $table, $primary_key, $format ) {
		$this->table       = $table;
		$this->primary_key = $primary_key;
		$this->format      = $format;

		$this->format['created_at'] = '%s';
		$this->format['updated_at'] = '%s';
	}
}
