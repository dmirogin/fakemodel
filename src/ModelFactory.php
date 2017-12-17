<?php

namespace dmirogin\fakemodel;

use dmirogin\fakemodel\resolvers\Resolver;
use Yii;
use yii\base\BaseObject;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Help to make and store objects.
 * This factory is another way to work with fixtures.
 *
 * ```
 * Yii::$app->factory->setModel(Model::class)->make();
 * ```
 */
class ModelFactory extends BaseObject
{
    /**
     * @var string
     */
    protected $model;

    /**
     * @var array
     */
    protected $states = [];

    /**
     * @var int Amount of models that will be created
     */
    protected $amount = 1;

    /**
     * @var Resolver[]
     */
    protected $resolvers = [];

    /**
     * Create the model with attributes
     *
     * @param array|null $attributes
     * @return Model|Model[]
     */
    public function make(array $attributes = [])
    {
        $models = [];

        for ($i = 0; $i < $this->amount; $i++) {
            $models[] = $this->createModel($attributes);
        }

        return $this->getOneOrArray($models);
    }

    /**
     * Create and persist in db the model with attributes
     *
     * @param array|null $attributes
     * @return ActiveRecord|ActiveRecord[]
     * @throws \InvalidArgumentException
     */
    public function create(array $attributes = [])
    {
        $this->abortIfNotActiveRecord();

        $models = [];

        for ($i = 0; $i < $this->amount; $i++) {
            /** @var ActiveRecord $model */
            $model = $this->createModel($attributes);
            $model->save(false);

            $models[] = $model;
        }

        return $this->getOneOrArray($models);
    }

    /**
     * Create new model instance
     *
     * @param array $attributes
     * @return Model
     */
    protected function createModel(array $attributes = []): Model
    {
        $this->abortIfNotYiiModel();

        /** @var Model $model */
        $model = new $this->model;

        $attributes = ArrayHelper::merge($this->getAttributesFromResolvers(), $attributes);
        $model->setAttributes($attributes, false);

        return $model;
    }

    /**
     * Get attributes from resolvers
     *
     * @return array
     */
    protected function getAttributesFromResolvers(): array
    {
        $attributes = [];

        foreach ($this->resolvers as $resolver) {
            $attributes = ArrayHelper::merge($attributes, $resolver->resolve($this->model, $this->states));
        }

        return $attributes;
    }

    /**
     * Get one item or array of items
     *
     * @param $models
     * @return mixed
     */
    private function getOneOrArray($models)
    {
        return $this->amount === 1 ? $models[0] : $models;
    }

    /**
     * Set states
     *
     * @param array $states
     * @return ModelFactory
     */
    public function states(array $states): self
    {
        $this->states = $states;

        return $this;
    }

    /**
     * Throw an error if model is not activeRecord
     *
     * @throws \InvalidArgumentException
     */
    private function abortIfNotActiveRecord(): void
    {
        if (!is_subclass_of($this->model, ActiveRecord::class)) {
            throw new \InvalidArgumentException('Model must be yii\db\ActiveRecord');
        }
    }

    /**
     * Throw an error if model is not yii model
     *
     * @throws \InvalidArgumentException
     */
    private function abortIfNotYiiModel()
    {
        if (!is_subclass_of($this->model, Model::class)) {
            throw new \InvalidArgumentException('Model must be yii\base\Model');
        }
    }

    /**
     * Set amount
     *
     * @param int $amount
     * @return ModelFactory
     * @throws \InvalidArgumentException
     */
    public function setAmount(int $amount): self
    {
        if ($amount < 1) {
            throw new \InvalidArgumentException('Amount must higher than 1');
        }

        $this->amount = $amount;

        return $this;
    }

    /**
     * @param string $model
     * @return ModelFactory
     */
    public function setModel(string $model): ModelFactory
    {
        $this->model = $model;

        return $this;
    }


    /**
     * @param Resolver[] $resolvers
     * @return ModelFactory
     * @throws \yii\base\InvalidConfigException
     */
    public function setResolvers(array $resolvers): ModelFactory
    {
        foreach ($resolvers as $resolver) {
            $this->resolvers[] = Yii::createObject($resolver);
        }

        return $this;
    }
}
