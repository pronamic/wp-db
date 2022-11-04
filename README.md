# Object–relational mapping library for WordPress

## Examples

```php
<?php

$database = new Database();

$payments_table = new Table(
	'mollie_payments',
	[
		new Column( 'id', 'BIGINT(16) UNSIGNED NOT NULL AUTO_INCREMENT', '%d' ),
		new Column( 'created', 'TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP', '%s' ),
		new Column( 'account_post_id', 'BIGINT(16) UNSIGNED DEFAULT NULL', '%s' ),
		new Column( 'mollie_id', 'VARCHAR(20) DEFAULT NULL', '%s' ),
		new Column( 'mode', 'VARCHAR(20) DEFAULT NULL', '%s' ),
		new Column( 'created_at', 'DATETIME DEFAULT NULL', '%s' ),
		new Column( 'status', 'VARCHAR(20) DEFAULT NULL', '%s' ),
		new Column( 'is_cancelable', 'BOOL DEFAULT NULL', '%d' ),
		new Column( 'paid_at', 'DATETIME DEFAULT NULL', '%s' ),
		new Column( 'amount', 'NUMERIC(15,2) DEFAULT NULL', '%s' ),
		new Column( 'description', 'VARCHAR(200) DEFAULT NULL', '%s' ),
		new Column( 'method', 'VARCHAR(20) DEFAULT NULL', '%s' ),
		new Column( 'json', 'TEXT DEFAULT NULL', '%s' ),
	],
	'
	PRIMARY KEY  (id),
	UNIQUE KEY mollie (mollie_id)
	'
);

$payments_table->primary_key    = 'id';
$payments_table->updated_at_key = null;
$payments_table->created_at_key = null;

$database->register_table( $payments_table );

$database->install();
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
