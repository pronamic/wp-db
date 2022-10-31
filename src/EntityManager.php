<?php
/**
 * Entity manager
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
			$where_condition[] = $key . ' = ' . $entity->format[ $key ];
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
		$data['created_at'] = \current_time( 'mysql', true );
		$data['updated_at'] = \current_time( 'mysql', true );

		$result = $this->wpdb->insert(
			$entity->table,
			$data,
			array_intersect_key( $entity->format, $data )
		);

		if ( false === $result ) {
			throw new \Exception( \sprintf( 'Insert error: %s, data: %s.', $this->wpdb->last_error, \wp_json_encode( $data, \JSON_PRETTY_PRINT ) ) );
		}

		$id = $this->wpdb->insert_id;

		return $id;
	}

	private function update( $entity, $data, $id ) {
		$data['updated_at'] = \current_time( 'mysql', true );

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