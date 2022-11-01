<?php
/**
 * Database
 *
 * @package Pronamic/WordPress/Database
 */

namespace Pronamic\WordPress\Database;

/**
 * Database class
 */
class Database {
	private $wpdb;

	private $tables;

	public function __construct() {
		global $wpdb;

		$this->wpdb   = $wpdb;
		$this->tables = [];
	}

	private function register_table( $name ) {
		$this->wpdb->$name = $this->wpdb->prefix . $name;
	}
}
