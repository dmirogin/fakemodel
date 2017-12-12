<?php

namespace dmirogin\fakeme;

use dmirogin\fakeme\resolvers\DefinitionResolver;
use dmirogin\fakeme\resolvers\Resolver;
use dmirogin\fakeme\resolvers\StatesResolver;
use yii\base\BaseObject;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class ModelBuilder extends BaseObject
{
    /**
     * @var string
     */
    private $className;

    /**
     * @var array
     */
    private $states = [];

    /**
     * @var int Amount of models that will be created
     */
    private $amount = 1;

    /**
     * @var Resolver
     */
    private $statesDefinitionsResolver;

    /**
     * @var Resolver
     */
    private $definitionResolver;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->definitionResolver = new DefinitionResolver();
        $this->statesDefinitionsResolver = new StatesResolver();
    }

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
        /** @var Model $model */
        $model = new $this->className;

        $fakeAttributes = $this->definitionResolver->resolve($this->className);
        $statesAttributes = $this->statesDefinitionsResolver->resolve($this->className, $this->states);

        $model->setAttributes(ArrayHelper::merge($fakeAttributes, $statesAttributes, $attributes), false);

        return $model;
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
     * @return ModelBuilder
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
        if (!is_subclass_of($this->className, ActiveRecord::class)) {
            throw new \InvalidArgumentException('Model must be ActiveRecord');
        }
    }

    /**
     * Get class name
     *
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * Set class name
     *
     * @param string $className
     * @return ModelBuilder
     */
    public function setClassName($className): self
    {
        $this->className = $className;

        return $this;
    }

    /**
     * Get model definitions
     *
     * @return array
     */
    public function getModelDefinitions(): array
    {
        return $this->definitionResolver->getDefinitions();
    }

    /**
     * Set model definitions
     *
     * @param array $defines
     * @return ModelBuilder
     */
    public function setModelDefinitions(array $defines): self
    {
        $this->definitionResolver->setDefinitions($defines);

        return $this;
    }

    /**
     * Get states definitions
     *
     * @return array
     */
    public function getStatesDefinitions(): array
    {
        return $this->statesDefinitionsResolver->getDefinitions();
    }

    /**
     * Set states definitions
     *
     * @param array $defines
     * @return ModelBuilder
     */
    public function setStatesDefinitions(array $defines): self
    {
        $this->statesDefinitionsResolver->setDefinitions($defines);

        return $this;
    }

    /**
     * Set amount
     *
     * @param int $amount
     * @return ModelBuilder
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
     * Get amount
     *
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }
}
