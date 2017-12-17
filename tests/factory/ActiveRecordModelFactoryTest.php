<?php

namespace tests\factory;

use dmirogin\fakemodel\ModelFactory;
use tests\data\ActiveRecordModel;
use tests\DBTestCase;

class ActiveRecordModelFactoryTest extends DBTestCase
{
    public function testCreate()
    {
        $builder = new ModelFactory();
        $builder->setModel(ActiveRecordModel::class);

        /** @var ActiveRecordModel $model */
        $model = $builder->create();

        $this->assertInstanceOf(ActiveRecordModel::class, $model);
    }

    public function testCreateWithData()
    {
        $builder = new ModelFactory();
        $builder->setModel(ActiveRecordModel::class);

        /** @var ActiveRecordModel $model */
        $model = $builder->create(['title' => 'string']);
        $model->refresh();

        $this->assertSame('string', $model->title);
    }
}
