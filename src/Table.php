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

	public $columns = [];

	public $definition;

	public $primary_key;

	public function __construct( $name, $columns, $definition ) {
		$this->name       = $name;
		$this->definition = $definition;

		foreach ( $columns as $column ) {
			$this->columns[ $column->get_name() ] = $column;
		}
	}

	public function get_name() {
		return $this->name;
	}

	private function get_column_format( $name ) {
		return $this->columns[ $name ]->format;
	}

	private function first_query( $select, $condition ) {
		$where_condition = [];

		foreach ( $condition as $name => $value ) {
			$where_condition[] = $name . ' = ' . $this->get_column_format( $name );
		}

		return $this->wpdb->prepare(
			sprintf(
				'SELECT %s FROM %s WHERE %s LIMIT 1;',
				$select,
				$this->name,
				implode( ' AND ', $where_condition )
			),
			$condition
		);
	}

	public function first_key( $condition ) {
		$query = $this->first_query( $this->primary_key, $condition );

		return $this->database->get_var( $query );
	}

	public function first_row( $condition ) {
		$query = $this->first_query( '*', $condition );

		return $this->database->get_row( $query );
	}
}
