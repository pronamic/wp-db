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

	public $columns;

	public $definition;

	public $primary_key;

	public function __construct( $name, $columns, $definition ) {
		$this->name       = $name;
		$this->columns    = $columns;
		$this->definition = $definition;
	}

	public function get_name() {
		return $this->name;
	}
}
