<?php

namespace tests\factory;

use dmirogin\fakeme\ModelFactory;
use tests\data\BaseModel;
use tests\data\SecondTestResolver;
use tests\data\TestResolver;

class ModelFactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testMakeNewModel()
    {
        $builder = new ModelFactory();
        $builder->setModel(BaseModel::class);

        /** @var BaseModel $model */
        $model = $builder->make();

        $this->assertInstanceOf(BaseModel::class, $model);
    }

    public function testMakeNewModelWithData()
    {
        $builder = new ModelFactory();
        $builder->setModel(BaseModel::class);

        /** @var BaseModel $model */
        $model = $builder->make(['field' => 'string']);

        $this->assertSame('string', $model->field);
    }

    public function testMakeManyModels()
    {
        $builder = new ModelFactory();
        $builder->setModel(BaseModel::class);
        $builder->setAmount(5);

        $models = $builder->make();

        $this->assertCount(5, $models);
        $this->assertInstanceOf(BaseModel::class, $models[0]);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function testMakeWithResolver()
    {
        $builder = new ModelFactory();
        $builder->setModel(BaseModel::class);
        $builder->setResolvers([TestResolver::class]);

        /** @var BaseModel $model */
        $model = $builder->make(['field' => 'string']);

        $this->assertSame('string', $model->field);
        $this->assertSame('title', $model->title);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function testMakeWithBunchResolver()
    {
        $builder = new ModelFactory();
        $builder->setModel(BaseModel::class);
        $builder->setResolvers([
            TestResolver::class,
            SecondTestResolver::class,
        ]);

        /** @var BaseModel $model */
        $model = $builder->make();

        $this->assertSame(42, $model->field);
        $this->assertSame('inherited', $model->title);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function testMakeWithStatesResolver()
    {
        $builder = new ModelFactory();
        $builder->setModel(BaseModel::class);
        $builder->setResolvers(['class' => TestResolver::class]);

        /** @var BaseModel $model */
        $model = $builder->states(['default'])->make(['field' => 'string']);

        $this->assertSame('string', $model->field);
        $this->assertSame('states', $model->title);
    }

    public function testStoreModel()
    {
        $builder = new ModelFactory();
        $builder->setModel(BaseModel::class);

        $this->expectException(\InvalidArgumentException::class);
        /** @var BaseModel $model */
        $model = $builder->create();
    }

    public function testSetUncountableAmount()
    {
        $builder = new ModelFactory();
        $builder->setModel(BaseModel::class);

        $this->expectException(\InvalidArgumentException::class);
        /** @var BaseModel $model */
        $model = $builder->setAmount(0);
    }
}
