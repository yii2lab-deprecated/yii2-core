Установка
===

Устанавливаем зависимость:

```
composer require yii2lab/yii2-core
```

Объявляем домен:

```php
return [
	'components' => [
		// ...
		'core' => [
			'class' => 'yii2lab\domain\Domain',
			'path' => 'yii2lab\core',
			'repositories' => [
				'client' => Driver::REST,
			],
			'services' => [
				'client',
			],
		],
		// ...
	],
];
```
