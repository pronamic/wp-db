<?php
/**
 * Entity manager
 *
 * @package Pronamic/WordPress/ORM
 */

namespace Pronamic\WordPress\ORM;

/**
 * Entity manager class
 */
class EntityManager {
	private $entities = [];

	public function __construct( $wpdb ) {
		$this->wpdb = $wpdb;
	}

	public function register_entity( $class, $entity ) {
		$this->entities[ $class ] = $entity;
	}

	/**
	 * 
	 * Like `getManagerForClass`.
	 */
	public function get_entity( $object ) {
		$class = get_class( $object );

		if ( ! array_key_exists( $class, $this->entities ) ) {
			throw new \Exception( \sprintf( 'Unknow entity: %s', $class ) );
		}

		return $this->entities[ $class ];
	}

	public function first( $object, $condition ) {
		$entity = $this->get_entity( $object );

		$where_condition = [];

		foreach ( $condition as $key => $value ) {
			$where_condition[] = $key . ' = ' . $entity->get_column_format( $key );
		}

		$query = $this->wpdb->prepare(
			sprintf(
				'SELECT %s FROM %s WHERE %s LIMIT 1;',
				$entity->primary_key,
				$entity->table,
				implode( ' AND ', $where_condition )
			),
			$condition
		);

		$id = $this->wpdb->get_var( $query );

		return $id;
	}

	private function insert( $entity, $data ) {
		if ( null !== $entity->created_at_key ) {
			$data[ $entity->created_at_key ] = \current_time( 'mysql', true );
		}

		if ( null !== $entity->updated_at_key ) {
			$data[ $entity->updated_at_key ] = \current_time( 'mysql', true );
		}

		$result = $this->wpdb->insert(
			$entity->table,
			$data,
			$entity->get_data_format( $data )
		);

		if ( false === $result ) {
			throw new \Exception( \sprintf( 'Insert error: %s, data: %s.', $this->wpdb->last_error, \wp_json_encode( $data, \JSON_PRETTY_PRINT ) ) );
		}

		$id = $this->wpdb->insert_id;

		return $id;
	}

	private function update( $entity, $data, $id ) {
		if ( null !== $entity->updated_at_key ) {
			$data[ $entity->updated_at_key ] = \current_time( 'mysql', true );
		}

		$result = $this->wpdb->update(
			$entity->table,
			$data,
			[
				$entity->primary_key => $id,
			],
		);

		if ( false === $result ) {
			throw new \Exception( \sprintf( 'Update error: %s', $this->wpdb->last_error ) );
		}

		return $id;
	}

	public function first_or_create( $object, $condition, $values ) {
		global $wpdb;
		
		$entity = $this->get_entity( $object );

		$id = $this->first( $object, $condition );

		if ( null === $id ) {
			$data = array_merge( $condition, $values );

			$id = $this->insert( $entity, $data );
		}

		return $id;
	}

	public function update_or_create( $object, $condition, $values ) {
		global $wpdb;

		$entity = $this->get_entity( $object );

		$id = $this->first( $object, $condition );

		if ( null !== $id ) {
			$id = $this->update( $entity, $values, $id );
		}

		if ( null === $id ) {
			$data = array_merge( $condition, $values );

			$id = $this->insert( $entity, $data );
		}

		return $id;
	}
}
