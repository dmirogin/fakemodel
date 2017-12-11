<?php

namespace tests\builder;

use dmirogin\fakeme\ModelBuilder;
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
}
