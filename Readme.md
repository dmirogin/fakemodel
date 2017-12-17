
<p align="center">
    <a href="http://www.yiiframework.com/" target="_blank">
        <img src="http://dmirogin.ru/assets/images/fakemodel.png" width="400" alt="Yii Framework" />
    </a>
</p>

[![Build Status](https://travis-ci.org/dmirogin/fakemodel.svg?branch=master)](https://travis-ci.org/dmirogin/fakemodel)
[![GitHub license](https://img.shields.io/github/license/dmirogin/fakemodel.svg)](https://github.com/dmirogin/fakemodel/blob/master/LICENSE)

This package helps you to manage faked models.
Make, create and store in database.
This factory is another way to work with fixtures and inspired by factories in laravel.

### Requirements
- PHP 7.1 +

### Instalation
```php
composer require dmirogin/yii2-js-urlmanager
```

### How to use

1. Add component to your application configuration
    ```php
    'factory' => [
        'class' => \dmirogin\fakemodel\ModelFactory::class,
        'resolvers' => [
            [
                'class' => \dmirogin\fakemodel\resolvers\FakerResolver::class,
                'definitions' => [
                    \app\models\MyModel::class => function (\Faker\Generator $faker) {
                        return [
                            'id' => $faker->numberBetween(1, 100),
                            'username' => $faker->userName,
                            'password' => $faker->password
                        ];
                    }
                ]
            ]
        ]
    ],
    ```
    
2. Now you can do:
```php
Yii::$app->factory->setModel(\app\models\MyModel::class)->make();
```

### Function in base TestCase

In your base TestCase class you can create simple function:
```php
/**
 * Create model factory
 *
 * @param string $model
 * @param int $amount
 * @return \dmirogin\fakemodel\ModelFactory
 */
protected function factory(string $model, int $amount = 1): \dmirogin\fakemodel\ModelFactory
{
    /** @var \dmirogin\fakemodel\ModelFactory $factory */
    $factory = Yii::$app->factory;
    return $factory->setModel(\app\models\MyModel::class)->setAmount(1);
}
```

and call just by:
```php
$this->factory(\app\models\MyModel::class)->make();
```

### Enhanced example

```php
'factory' => [
    'class' => \dmirogin\fakemodel\ModelFactory::class,
    'resolvers' => [
        [
            'class' => \dmirogin\fakemodel\resolvers\FakerResolver::class,
            'definitions' => [
                \app\models\MyModel::class => function (\Faker\Generator $faker) {
                    return [
                        'id' => $faker->numberBetween(1, 100),
                        'username' => $faker->userName,
                        'password' => $faker->password
                    ];
                }
            ]
        ],
        [
            'class' => \dmirogin\fakemodel\resolvers\StatesResolver::class,
            'definitions' => [
                \app\models\MyModel::class => [
                    'admin' => [
                        'id' => 1
                    ]
                ]
            ]
        ]
    ]
],

Yii::$app->factory->setModel(\app\models\MyModel::class)->states(['admin'])->setAmount(5)->make();
```
See more in [WIKI](https://github.com/dmirogin/fakemodel/wiki).
