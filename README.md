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

## Resources

- https://libreworks.github.io/xyster/documentation/guide/xyster.orm.setup.html
- https://redbeanphp.com/
- https://www.doctrine-project.org/projects/doctrine-orm/en/2.11/reference/php-mapping.html
- https://symfony.com/doc/current/doctrine.html#creating-an-entity-class
- http://propelorm.org/documentation/reference/active-record.html
- https://www.baeldung.com/hibernate-entitymanager
- https://yoast.com/developer-blog/yoast-seo-14-0-x/
- https://github.com/Yoast/wordpress-seo/blob/88f4a39f6f285de342e2b9d9ed6c207a517b23d1/lib/orm.php
- https://laravel-news.com/firstornew-firstorcreate-firstor-updateorcreate
