<?php
/**
 * Table
 *
 * @package Pronamic/WordPress/Database
 */

namespace Pronamic\WordPress\Database;

/**
 * Table class
 */
class Table {
	private $name;

	private $columns;

	public function __construct( $name, $columns ) {
		$this->name    = $name;
		$this->columns = $columns;
	}
}
