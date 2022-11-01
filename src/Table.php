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

    public $created_at_key = 'created_at';

    public $updated_at_key = 'updated_at';

	public function __construct( $name, $columns, $definition ) {
		$this->name       = $name;
		$this->definition = $definition;

		foreach ( $columns as $column ) {
			$this->columns[ $column->name ] = $column;
		}
	}

	public function get_name() {
		return $this->name;
	}

	public function get_full_name() {
		return $this->database->get_prefix() . $this->name;
	}

	private function get_column_format( $name ) {
		return $this->columns[ $name ]->format;
	}

    public function get_data_format( $data ) {
        $format = [];

        foreach ( $data as $column => $value ) {
            $format[] = $this->get_column_format( $column );
        }

        return $format;
    }

	private function first_query( $select, $condition ) {
		$where_condition = [];

		foreach ( $condition as $name => $value ) {
			$where_condition[] = $name . ' = ' . $this->get_column_format( $name );
		}

		return $this->database->prepare(
			sprintf(
				'SELECT %s FROM %s WHERE %s LIMIT 1;',
				$select,
				$this->get_full_name(),
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

	public function update( $data, $id ) {
		if ( null !== $this->updated_at_key ) {
			$data[ $this->updated_at_key ] = \current_time( 'mysql', true );
		}

		$this->database->update(
			$this->get_full_name(),
			$data,
			[
				$this->primary_key => $id,
			],
		);

		return $id;
	}

	public function insert( $data ) {
		if ( null !== $this->created_at_key ) {
			$data[ $this->created_at_key ] = \current_time( 'mysql', true );
		}

		if ( null !== $this->updated_at_key ) {
			$data[ $this->updated_at_key ] = \current_time( 'mysql', true );
		}

		return $this->database->insert(
			$this->get_full_name(),
			$data,
			$this->get_data_format( $data )
		);
	}
}
