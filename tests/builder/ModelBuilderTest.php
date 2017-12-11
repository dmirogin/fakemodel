<?php

namespace tests\builder;

use dmirogin\fakeme\ModelBuilder;
use tests\data\BaseModel;

class ModelBuilderTest extends \PHPUnit\Framework\TestCase
{
    public function testMakeNewModel()
    {
        $builder = new ModelBuilder();
        $builder->setClassName(BaseModel::class);

        /** @var BaseModel $model */
        $model = $builder->make();

        $this->assertInstanceOf(BaseModel::class, $model);
    }

    public function testMakeNewModelWithData()
    {
        $builder = new ModelBuilder();
        $builder->setClassName(BaseModel::class);

        /** @var BaseModel $model */
        $model = $builder->make(['field' => 'string']);

        $this->assertSame('string', $model->field);
    }
}
