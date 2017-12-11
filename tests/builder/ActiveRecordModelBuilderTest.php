<?php

namespace tests\builder;

use dmirogin\fakeme\ModelBuilder;
use Faker\Generator;
use tests\data\ActiveRecordModel;
use tests\DBTestCase;

class ActiveRecordModelBuilderTest extends DBTestCase
{
    public function testCreate()
    {
        $builder = new ModelBuilder();
        $builder->setClassName(ActiveRecordModel::class);

        /** @var ActiveRecordModel $model */
        $model = $builder->create();

        $this->assertInstanceOf(ActiveRecordModel::class, $model);
    }

    public function testCreateWithData()
    {
        $builder = new ModelBuilder();
        $builder->setClassName(ActiveRecordModel::class);

        /** @var ActiveRecordModel $model */
        $model = $builder->create(['title' => 'string']);
        $model->refresh();

        $this->assertSame('string', $model->title);
    }

    public function testCreateWithDefinesAsFunction()
    {
        $builder = new ModelBuilder();
        $builder->setClassName(ActiveRecordModel::class);
        $builder->setDefines([
            ActiveRecordModel::class => function (Generator $faker) {
                return [
                    'title' => $faker->word,
                    'text' => $faker->text
                ];
            }
        ]);

        /** @var ActiveRecordModel $model */
        $model = $builder->create();
        $model->refresh();

        $this->assertNotEmpty($model->title);
        $this->assertNotEmpty($model->text);
    }

    public function testCreateWithDefinesAsArray()
    {
        $builder = new ModelBuilder();
        $builder->setClassName(ActiveRecordModel::class);
        $builder->setDefines([
            ActiveRecordModel::class => [
                'title' => 'title'
            ]
        ]);

        /** @var ActiveRecordModel $model */
        $model = $builder->create();
        $model->refresh();

        $this->assertSame('title', $model->title);
    }

    public function testCreateWithDefinesAsArrayAndRelations()
    {
        $builder = new ModelBuilder();
        $builder->setClassName(ActiveRecordModel::class);
        $builder->setDefines([
            ActiveRecordModel::class => [
                'title' => function () {
                    return 'title';
                },
            ]
        ]);

        /** @var ActiveRecordModel $model */
        $model = $builder->create();
        $model->refresh();

        $this->assertSame('title', $model->title);
    }

    public function testCreateWithDefinesAsFunctionAndRelations()
    {
        $builder = new ModelBuilder();
        $builder->setClassName(ActiveRecordModel::class);
        $builder->setDefines([
            ActiveRecordModel::class => function ($faker) {
                return [
                    'title' => function () {
                        return 'title';
                    }
                ];
            }
        ]);

        /** @var ActiveRecordModel $model */
        $model = $builder->create();
        $model->refresh();

        $this->assertSame('title', $model->title);
    }
}
