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

	private function register_table( Table $table ) {
		$name = $table->get_name();

		$this->tables[ $name ] = $table;

		$this->wpdb->$name = $this->wpdb->prefix . $name;
	}
}
