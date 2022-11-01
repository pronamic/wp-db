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

	public function register_table( Table $table ) {
		$name = $table->get_name();

		$table->database = $this;

		$this->tables[ $name ] = $table;

		$this->wpdb->$name = $this->wpdb->prefix . $name;
	}

	public function install() {
		foreach ( $this->tables as $table ) {
			$this->install_table( $table );
		}
	}

	public function get_table( $name ) {
		return $this->tables[ $name ];
	}

	public function get_prefix() {
		return $this->wpdb->prefix;
	}

	private function install_table( $table ) {
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$full_table_name = $table->get_full_name();

		$charset_collate = '';

		if ( $this->wpdb->has_cap( 'collation' ) ) {
			if ( ! empty( $this->wpdb->charset ) ) {
				$charset_collate .= "DEFAULT CHARACTER SET $wpdb->charset";
			}

			if ( ! empty( $this->wpdb->collate ) ) {
				$charset_collate .= " COLLATE $wpdb->collate";
			}
		}

		$table_options = $charset_collate;

		$create_definitions = [];

		foreach ( $table->columns as $column ) {
			$create_definitions[] = $column->name . ' ' . $column->definition;
		}

		$create_definition = \implode( ',' . PHP_EOL, $create_definitions ) . ', ' . $table->definition;

		$query = "CREATE TABLE $full_table_name ( $create_definition ) $table_options";

		\dbDelta( $query );

		\maybe_convert_table_to_utf8mb4( $full_table_name );
	}

	public function get_var( $query ) {
		$var = $this->wpdb->get_var( $query );

		return $var;
	}

	public function get_row( $query ) {
		$row = $this->wpdb->get_row( $query );

		return $row;
	}

	public function prepare( $query, $args ) {
		return $this->wpdb->prepare( $query, $args );
	}

	/**
	 * Update.
	 * 
	 * @link https://developer.wordpress.org/reference/classes/wpdb/update/
	 */
	public function update( $table, $data, $where, $format = null, $where_format = null ) {
		$result = $this->wpdb->update( $table, $data, $where, $format, $where_format );

		if ( false === $result ) {
			throw new \Exception( \sprintf( 'Update error: %s', $this->wpdb->last_error ) );
		}

		return $result;
	}

	/**
	 * Insert.
	 * 
	 * @link https://developer.wordpress.org/reference/classes/wpdb/insert/
	 */
	public function insert( $table, $data, $format = null ) {
		$result = $this->wpdb->insert( $table, $data, $format );

		if ( false === $result ) {
			throw new \Exception( \sprintf( 'Insert error: %s, data: %s.', $this->wpdb->last_error, \wp_json_encode( $data, \JSON_PRETTY_PRINT ) ) );
		}

		$id = $this->wpdb->insert_id;

		return $id;
	}
}
