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

    public function testMakeWithStates()
    {
        $builder = new ModelBuilder();
        $builder->setClassName(BaseModel::class)->setStatesDefinitions([
            BaseModel::class => [
                'active' => [
                    'field' => 'active'
                ]
            ]
        ]);

        /** @var BaseModel $model */
        $model = $builder->states(['active'])->make();

        $this->assertSame('active', $model->field);
    }

    public function testMakeWithStatesAsCallable()
    {
        $builder = new ModelBuilder();
        $builder->setClassName(BaseModel::class)->setStatesDefinitions([
            BaseModel::class => [
                'active' => [
                    'field' => function () {
                        return 'active';
                    }
                ]
            ]
        ]);

        /** @var BaseModel $model */
        $model = $builder->states(['active'])->make();

        $this->assertSame('active', $model->field);
    }

    public function testMakeManyModels()
    {
        $builder = new ModelBuilder();
        $builder->setClassName(BaseModel::class);
        $builder->setAmount(5);

        $models = $builder->make();

        $this->assertCount(5, $models);
        $this->assertInstanceOf(BaseModel::class, $models[0]);
    }
}
