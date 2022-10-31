# Object–relational mapping library for WordPress

## Examples

```php
<?php

$orm = new EntityManager( $wpdb );

$orm->register_entity(
	\Pronamic\WordPress\Twinfield\Organisations\Organisation::class,
	new Entity(
		$wpdb->prefix . 'twinfield_organisations',
		'id',
		[
			'code' => '%s',
		]
	)
);
```
