<?php
/**
 * Column
 *
 * @package Pronamic/WordPress/Database
 */

namespace Pronamic\WordPress\Database;

/**
 * Column class
 */
class Column {
	public $name;

	public $definition;

	public $format;

	public function __construct( $name, $definition, $format ) {
		$this->name       = $name;
		$this->definition = $definition;
		$this->format     = $format;
	}
}
